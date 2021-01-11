<?php
include('../../../logic/global.php');


function delete_invoice(){
	$mysqli = mysqliConn();
	$custid = (int)$_POST['custid'];

	try{
		$sql = "UPDATE custinfo SET ship_date = 'failed' WHERE custid = $custid";
		$result = $mysqli->query($sql);

		echo "success";
	}  catch (Exception $e) {
		echo 'Caught exception: ',  $e->getMessage(), "\n";
	}
}

function set_ship_date(){
	$mysqli = mysqliConn();
	$custid = (int)$_POST['custid'];
	$now = date("Y-m-d");

	try{
		$sql = "UPDATE custinfo SET ship_date = '$now' where custid = $custid";
		$result = $mysqli->query($sql);

		echo "success";
	} catch (Exception $e){
		echo 'Caught exception: ',  $e->getMessage(), "\n";
	}
}


if( isset($_POST['delete']) ){
	delete_invoice();
}

if( isset($_POST['set_ship']) ){
	set_ship_date();
}

if( isset($_POST['service']) ){
	//include_once('../../../lib/packer/Boxes.php');
	include('invoice.php');

	$invoice = get_invoice($_POST['custid']);
	$weight = (float)$_POST['weight'];

	if( stripos($_POST['service'], "fedex") !== false){
		try{
			$box = array();
			$box[0]['box'] = $_POST['box'];
			$box[0]['weight'] = $weight;
			$tr = get_fedex($invoice, $box);
			
			echo "success";
		} catch (Exception $e){
			echo 'Caught exception: ',  $e->getMessage(), "\n";
		}
	}else{
		$tr = get_usps($invoice);
	}

}