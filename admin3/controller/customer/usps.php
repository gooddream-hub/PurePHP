<?php 
// error_reporting(0);
// ini_set('display_errors', 0);

include('../../../logic/global.php');
require_once('../../libraries/easypost.php');

$mysqli = mysqliConn();
$invoice_id = (int)$_POST['custid'];

$data = array();
$easypost_production = 1;

$invoice = array();

$sql  = "SELECT * FROM `custinfo` WHERE custid = ". $invoice_id;
if($result = $mysqli->query($sql)){
    while($row = $result->fetch_assoc() ){
        $invoice[] = array_map('utf8_encode', $row);
    }  
}

require_once('../../../lib/packer/Boxes.php');
require_once('../../../lib/packer/Packer.php');

\EasyPost\EasyPost::setApiKey('4cu9YuoaAfMP4t6zmopTEg');

$selected_rate = setEasypostShipType($_POST['shipType']);
$options = array('print_custom_1' => 'www.MJTrends.com: We Help Create');
$phone = rtrim($invoice[0]['phone'], "-");

$to_address = \EasyPost\Address::create(
    array(
        'name'    => $invoice[0]['shipfirst'].$invoice[0]['shiplast'],
        'street1' => $invoice[0]['shipadone'],
        'street2' => $invoice[0]['shipadtwo'],
        'city'    => $invoice[0]['shipcity'],
        'state'   => $invoice[0]['shipstate'],
        'zip'     => $invoice[0]['shipzip'],
        'country' => $invoice[0]['shipco'],
        'phone'   => $phone,
        'options' => $options
    )
);

$from_address = \EasyPost\Address::create(
    array(
        'company' => 'MJTrends',
        'street1' => '2611 E. Meredith Dr.',
        'city'    => 'Vienna',
        'state'   => 'VA',
        'zip'     => '22181',
        'phone'	  => '571-643-9918'
    )
);

$value['weight'] = round((float)$_POST['weight']*16,1);
$value['box'] = $_POST['box'];

$items = array();
$total_weight = 0;
$subtotal = 0;

$sql = "SELECT orderdetails.invid, orderdetails.cat, orderdetails.type, orderdetails.color, orderdetails.price, orderdetails.quant, minwidth, minlength, minheight, volume, weight FROM orderdetails LEFT JOIN inven_mj ON orderdetails.invid = inven_mj.invid where custid = $invoice_id";
$result = $mysqli->query($sql);

while($row = $result->fetch_assoc() ){
    $items[] = $row;
    $total_weight += $row['weight']*$row['quant'];
    $subtotal += $row['quant']*$row['price'];
}

$swatch = set_swatch($items);

$parcel_params = setEasyPostParcel($value, $_POST['shipType'], $swatch);

$parcel = \EasyPost\Parcel::create($parcel_params);
		
if($_POST['shipType'] == 'USPS Intl' OR $invoice[0]['shipco'] != "US"){
    $customs_item_params = array(
        "description"      => "Fabric & Sewing Supplies",
        "hs_tariff_number" => 5903,
        "origin_country"   => "US",
        "quantity"         => 1,
        "value"            => $subtotal,
        "weight"           => $parcel['weight']
    );
    
    $customs_item = \EasyPost\CustomsItem::create($customs_item_params);
    $customs_info_params = array(
        "customs_certify"      => true,
        "customs_signer"       => "Michael Harris",
        "contents_type"        => "gift",
        "contents_explanation" => "", // only necessary for contents_type=other
        "eel_pfc"              => "NOEEI 30.37(a)",
        "non_delivery_option"  => "return",
        "restriction_type"     => "none",
        "restriction_comments" => "",
        "customs_items"        => array($customs_item)
    );
    $customs_info = \EasyPost\CustomsInfo::create($customs_info_params);
    // create shipment
    $shipment_params = array(
        "from_address" => $from_address,
        "to_address"   => $to_address,
        "parcel"       => $parcel,
        "customs_info" => $customs_info
    );
} else {
    $shipment_params = array(
        "from_address" => $from_address,
        "to_address"   => $to_address,
        "parcel"       => $parcel
    );
}

$shipment = \EasyPost\Shipment::create($shipment_params);
$shipment_rate = $shipment->lowest_rate('usps');

foreach ($shipment->rates as $rate) {
    if ($selected_rate == $rate->service) {
        $shipment_rate = $rate;
        break;
    }
}
    
$shipment->buy(array('rate' => $shipment_rate));

$tracking = array();
if($invoice[0]['tracking']) {
    $tracking = json_decode($invoice[0]['tracking'], true);
}

if($invoice[0]['packing']) {
    $packings = json_decode($invoice[0]['packing'], true);
}
foreach ($packings as $key => $packing) {
    if ( $packing['tracking'] == $shipment->tracking_code ) {
        $packing['tracking_status'] = 0;
    }
}

$tracking[$shipment->tracking_code] = 0;

$label = array();
if($invoice[0]['label']) {
    $label = json_decode($invoice[0]['label'], true);
}
$label[] = $shipment->postage_label->label_url;

$packing = json_decode($invoice[0]['packing'], true);
$packing[$_POST['box']]['weight'] = $_POST['weight'];
$packing[$_POST['box']]['tracking'] = $shipment->tracking_code;
$packing[$_POST['box']]['tracking_status'] = 0;
$packing[$_POST['box']]['label'] = $shipment->postage_label->label_url;
if($swatch) {
    $packing[$_POST['box']]['isSwatch'] = 1;
}

$query  = "UPDATE custinfo SET tracking = '". json_encode($tracking) ."', label = '". json_encode($label) ."', packing = '". json_encode($packing) ."' WHERE custid = '$invoice_id'";
$result = $mysqli->query($query);
if(!$result){
    echo "Something error on update custinfo";
}

$data['balance'] = 0;
// if ($easypost_production) {
//     $user = \EasyPost\User::retrieve_me();
//     $data['balance'] = $user->balance;
// }

// postEtsyTracking($invoice_id, $shipment->tracking_code);

echo '{"path": "http://admin.mjtrends.com/labels/usps/'.$invoice_id.'.png'.'", "balance" : "'.$data['balance'].'", "label" : "'. $shipment->postage_label->label_url .'"}';
die;

// echo json_encode($invoice);


//////////////////////////////////////////////////////////

function postEtsyTracking($invoice_id, $tracking_num){
    $mysqli = mysqliConn();
	include('../../libraries/etsy/MY_Etsy.php');
	include('../../libraries/etsy/etsy_helper.php');

	$sql = "SELECT receipt_id FROM etsy_transactions where invoice_id = '$invoice_id'";
	$result = $mysqli->query($sql);

	$receipt_id = $result->fetch_array(MYSQLI_ASSOC);
	$receipt_id = $receipt_id['receipt_id'];

	$etsy = new MY_Etsy;

	if($etsy->test_etsy_token() == false){
		$etsy->etsy_get_token();
	}

	$tracking_num = (string)$tracking;
	$etsy->postTracking($receipt_id, $tracking_num);
}

function setEasyPostParcel($box, $shipType, $isSwatch) {
    $all_boxes = Boxes::allBoxes();
    $parcel = array('weight' => $_POST['weight']);
    if($shipType == 'USPS Flat Rate' && $isSwatch == "true"){
        $parcel["predefined_package"] = "FlatRateEnvelope";
    } elseif($shipType == 'USPS Flat Rate'){
        $parcel["predefined_package"] = "MediumFlatRateBox"; 
    } elseif($shipType == 'USPS Regional Rate BoxA'){
        $parcel["predefined_package"] = "RegionalRateBoxA"; 
    } elseif($shipType == 'USPS Regional Rate BoxB'){
        $parcel["predefined_package"] = "RegionalRateBoxB"; 
    } elseif($shipType == 'USPS ParcelSelect'){
        $parcel["predefined_package"] = "Parcel"; 
    } else {
        $parcel['length'] = $_POST['length'];
        $parcel['width'] = $_POST['width'];
        $parcel['height'] = $_POST['height'];
    }
    return $parcel;
}

function set_swatch($items){
    $swatch = 'true';
	foreach($items as $row){
		if($row['cat'] != "Swatches"){
			$swatch = 'false';
			break;
		} else {
			$swatch = 'true';
		}
	}
	return $swatch;
}

function setEasypostShipType($shipType){
    $typebox = array("USPS Priority", "USPS Flat Rate", "USPS ParcelSelect", "USPS Regional Rate BoxA", "USPS Regional Rate BoxB", "USPS Intl", "USPS Express", "USPS First Class");
    $res = $shipType;
    $shipArray = array(
        "USPS Priority"  => "Priority",
        "USPS Flat Rate" => "Priority",
        "USPS ParcelSelect" => "ParcelSelect",
        "USPS Regional Rate BoxA" => "Priority",
        "USPS Regional Rate BoxB" => "Priority",
        "USPS Intl"      => "PriorityMailInternational",
        "USPS Express"   => "Express",
        "USPS First Class" => "First",
    );
    if(in_array($shipType, $typebox)){
        $res = $shipArray[$shipType];
    }
    return $res;
}
?>