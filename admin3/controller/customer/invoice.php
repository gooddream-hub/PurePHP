<?php
setlocale(LC_MONETARY,"en_US");


function get_box_info($invoice){
	$mysqli = mysqliConn();
	include('../../../logic/shipRate.class.php');
	unset($_SESSION['cart']);

	$data['total_volume'] = $data['total_weight'] = 0;
	foreach($invoice['items'] as $item) {
		$data['total_volume'] += (float)$item['volume']*$item['quant'];
		$data['total_weight'] += (float)$item['weight']*$item['quant'];
		if($item['cat'] == 'Coupon') {
			// We needn't coupon in cart
		} elseif($item['cat'] == 'Pattern') { //have to set values for weight and size
			$_SESSION['cart'][] = array("volume" => 5, "quant" => $item['quant'], "weight" => 0.2, "minWidth" => 8, "minLength" => 11, "minHeight" => 0.05);
		} else{
			$_SESSION['cart'][] = array("volume" => $item['volume'], "quant" => $item['quant'], "weight" => $item['weight'], "minWidth" => $item['minwidth'], "minLength" => $item['minlength'], "minHeight" => $item['minheight']);
		}
		
		if($item['cat'] == 'Swatches') {	
			$swatchArray[] = $item;
		}
		if(($item['cat'] == 'Fabric' OR $item['cat'] == 'Latex-Sheeting') AND !in_array($item['type'], $catArray)) {
			$catType .= "'".str_replace(' ', '-', $item['type'])."'".' OR type ='; 
			$catArray[] = $item['type'];
		}
	}
	
	$ship = new ShipRate;

	if($data['row']['shipco'] != "US") {
		$data['row']['shipco'] = convertCo($data['row']['shipco']);
	}

	$ship->init($_SESSION['cart'], $invoice['details'][0]['shipco']);
	// if country not USA and we need to know what system selected
	if( strpos($data['row']['ship_type'], 'USPS') !== false || $data['row']['ship_type'] == 'Priority Letter') {
		$boxes = $ship->uspsBoxes;
		#$data['total_weight'] = $ship->uspsPacker->weight;
	} else {
		$boxes = $ship->fedexBoxes;
		#$data['total_weight'] = $ship->fedexPacker->weight;
	}

	$bbs = array();
	foreach($boxes as $box) {
		for($i=0;$i<$box['quantity'];$i++) {
			$bb['box'] = $box['box'];
			$bb['volume'] = $box['volume'];
			$bb['weight'] = $box['weight'];
			$bb['tracking'] = '';
			$bb['label'] = '';
			$bbs[] = $bb;
			unset($bb);
		}
	}
			
	$boxes = $bbs;

	$sql_pack = "UPDATE `custinfo` SET `packing` = '". json_encode($boxes) ."' WHERE `custid` = ". $invoice['details'][0]['custid'];
	$result = $mysqli->query($sql_pack);

	return $boxes;	
}


function get_fedex($invoice, $boxes){
	include('fedex.php');
	$mysqli = mysqliConn();

	$data['standard_boxes'] = Boxes::allBoxes();
	extract($data['standard_boxes'][$boxes[0]['box']]['size']);

	if($invoice['details'][0]['shipco'] == 'CA'){
		$state = convert_canadian_state($row['shipstate']);
	} else {
		$state = $invoice['details'][0]['shipstate'];
	}

	$tr_num = createFedexLabel($invoice['details'][0]['shipfirst']." ".$invoice['details'][0]['shiplast'], $invoice['details'][0]['shipadone'], $invoice['details'][0]['shipadtwo'], $invoice['details'][0]['shipcomp'], $invoice['details'][0]['shipcity'], $invoice['details'][0]['shipstate'], $invoice['details'][0]['shipzip'], $invoice['details'][0]['shipco'], $invoice['details'][0]['phone'], $boxes[0]['weight'], $length, $width, $height, getFedexType($invoice['details'][0]['ship_type']), $invoice['details'][0]['custid']);

	$sql = "UPDATE custinfo SET tracking = concat_ws(',',ifnull(tracking,''), '".$tr_num."') WHERE custid = ".$invoice['details'][0]['custid'];
	$result = $mysqli->query($sql);
}

function get_USPS($invoice){
	include('usps.php');
	
	$box = get_box_info($invoice);
	$weight = $_POST['weight'];
	get_usps_label($invoice, $box[0]['box'], $weight);
}


function get_invoice($custid){
	$mysqli = mysqliConn();
	$details = array();
	$items = array();
	$subtotal = 0;
	$count = 0;
	$total_weight = 0;

	$sql = "SELECT * FROM custinfo LEFT JOIN payment on custinfo.custid = payment.custid WHERE custinfo.custid = $custid";
	$result = $mysqli->query($sql);

	while($row = $result->fetch_assoc() ){
		$details[] = $row;
		$count += 1;
		
		if($row['payType'] == "cc"){
			$payment = "Credit Card: XXXXX-".substr($row['ccnumber'], -4);
		} elseif($row['payType'] == "pp"){
			$payment = "PayPal";
		} else{
			$payment = $row['payType'];
		}
	} 

	$sql = "SELECT orderdetails.invid, orderdetails.cat, orderdetails.type, orderdetails.color, orderdetails.price, orderdetails.quant, minwidth, minlength, minheight, volume, weight FROM orderdetails LEFT JOIN inven_mj ON orderdetails.invid = inven_mj.invid where custid = $custid";
	$result = $mysqli->query($sql);

	while($row = $result->fetch_assoc() ){
		$items[] = $row;
		$total_weight += $row['weight']*$row['quant'];
		$subtotal += $row['quant']*$row['price'];

	}

	return array("items"=>$items, "details"=>$details, "subtotal"=>$subtotal, "payment"=>$payment, "count"=>$count, "total_weight"=>$total_weight);

}


function convert_canadian_state($state){
	if(strlen($state) > 2 ){
		$conversion = array("Alberta" => "AB", "British Columbia" => "BC", "Manitoba" => "MB", "New Brunswick" => "NB", "Newfoundland" => "NL", "Nova Scotia" => "NS", "Nunavut" => "NU", "Ontario" => "ON", "Quebec" => "QC", "Saskatchewan" => "SK", "Yukon" => "YK");
		return($conversion[$state]);
	} else {
		return $state;
	}
}


function convertCo($iso){
		$mysqli = mysqliConn();
		$sql = "SELECT name FROM country WHERE iso_code = '".$iso."'";
		$result = $mysqli->query($sql);

		$row = $result->fetch_array();
		return $row["name"];
}

function get_packing($invoice){
	$packing = array();

	if( stripos($invoice['details'][0]['ship_type'], "fedex") == false){
		$packing = json_decode($invoice['details'][0]['packing'], true);
	}

	return $packing;
}


function get_bx($invoice){
	include_once('../../../lib/packer/Boxes.php');
	
	$boxes = Boxes::allBoxes();
	unset($boxes['FlateRate']);
	unset($boxes['RegionalRateBoxA']);
	unset($boxes['RegionalRateBoxB']);
	
	return $boxes;

}

function get_labels($invoice){
	$files = array();

	if( stripos($invoice['details'][0]['ship_type'], "fedex") !== false){
		$path    = '../../assets/labels/fedex/'.$invoice['details'][0]['custid'];
		$items = scandir($path);
		$items = array_diff(scandir($path), array('.', '..'));
		
		foreach($items as $row){
			$files[] = "../../assets/labels/fedex/".$invoice['details'][0]['custid']."/".$row;
		}
	} else {
		$files = explode(",",$invoice['details'][0]['label']);
	}

	return $files;
}


function init($invoice){
	if($invoice['details'][0]['packing'] == ""){
		$boxes = get_box_info($invoice);
		
		if( stripos($invoice['details'][0]['ship_type'], "fedex") !== false){
			get_fedex($invoice, $boxes);
		}
	}
}
