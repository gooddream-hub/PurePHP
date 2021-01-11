<?php
require_once('../../libraries/easypost.php');
header("Cache-Control: no-cache, must-revalidate");				// HTTP/1.1
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");	

function get_usps_label($invoice, $box_type, $weight){
	$mysqli = mysqliConn();

	\EasyPost\EasyPost::setApiKey('g7qys2B5AMTtcFhsHvnQPg');
	#$weight = number_format($_POST['weight'][$_POST['box']]*16, 1);
	
	$selected_rate = setEasypostShipType($invoice['details'][0]['ship_type']);
	
	$options = array('print_custom_1' => 'www.MJTrends.com: We Help Create');
	$phone = rtrim($invoice['details'][0]['phone'], '-');

	$to_address = \EasyPost\Address::create(
		array(
			'name'    => $invoice['details'][0]['shipfirst']." ".$invoice['details'][0]['shiplast'],
			'street1' => $invoice['details'][0]['shipadone'],
			'street2' => $invoice['details'][0]['shipadtwo'],
			'city'    => $invoice['details'][0]['shipcity'],
			'state'   => $invoice['details'][0]['shipstate'],
			'zip'     => $invoice['details'][0]['shipzip'],
			'country' => $invoice['details'][0]['shipCo'],
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
	
	$value['weight'] = round($weight*16,1);
	$value['box'] = $box_type; //box_type = Box1 etc.  

	$swatch = set_swatch($invoice['items']);

	$parcel_params = setEasyPostParcel($value, $invoice['details'][0]['ship_type'], $swatch);

	$parcel = \EasyPost\Parcel::create($parcel_params);
	
	if($_POST['shipType'] == 'USPS Intl' OR $_POST['shipCo'] != "US"){
		$customs_item_params = array(
			"description"      => "Fabric & Sewing Supplies",
			"hs_tariff_number" => 5903,
			"origin_country"   => "US",
			"quantity"         => 1,
			"value"            => $_POST['subtotal'],
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


	$custid = $invoice['details'][0]['custid'];

	$query  = "UPDATE custinfo SET tracking = concat_ws(',',ifnull(tracking,''), '".$tr_num."'), label = '". $shipment->postage_label->label_url ."' WHERE custid = '$custid'";
	$result = $mysqli->query($sql);

	if($invoice['details'][0]['payType'] == "Etsy"){
		postEtsyTracking($invoice, $shipment->tracking_code);
	}

	echo '{"label" : "'. $shipment->postage_label->label_url .'"}';
}

function postEtsyTracking($custid, $tracking){
	$mysqli = mysqliConn();
	include('../../libraries/etsy/MY_Etsy.php');
	include('../../libraries/etsy/etsy_helper.php');

	$sql = "SELECT receipt_id FROM etsy_transactions where invoice_id = '$custid'";
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
	//echo "<br>\nboxes box is: ";
	//print_r($box['box']);
	$all_boxes = Boxes::allBoxes();
	$parcel = array('weight' => $box['weight']);
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
		$parcel['length'] = $all_boxes[$box['box']]['size']['length'];
		$parcel['width'] = $all_boxes[$box['box']]['size']['width'];
		$parcel['height'] = $all_boxes[$box['box']]['size']['height'];
	}
	return $parcel;
}


function setEasypostShipType($shipType){
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
	return $shipArray[$shipType];
}


function set_swatch($items){
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