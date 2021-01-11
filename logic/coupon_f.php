<?php
if($_POST['requestType'] == 'ajax'){
	include('global.php');
	//conn();
	$coupon = new Coupon();
	$coupon->validate();
	
	$arr = array('status' => $coupon->status, 'msg' => $coupon->msg, 'shipping_discount' => $coupon->shipping_discount, 'total_discount' => (float)$coupon->total_discount);
	echo json_encode($arr);
} 

class Coupon{
	var $status;
	var $msg;
	var $shipping_discount;
	var $total_discount;
	var $db;
	
	function __construct() {
		include_once('global.php');
		$this->db = DB::getInstance();
	}

	function validate(){
		(isset($_POST['couponCode'])) ? $coupon = $_POST['couponCode'] : $coupon = $_SESSION['couponCode'];  
		if($coupon != ""){
			$coupon = $this->db->real_escape_string($coupon);
			$query  = "SELECT code, msg, php, active, end_date FROM coupon LEFT JOIN coupon_code ON coupon.ckey = coupon_code.ckey WHERE code = '$coupon' AND start_date <= CURDATE()";
			$result = $this->db->query($query);
			$row 	= $result->fetch_assoc();
		} else {
			$row = array('msg' => '', 'end_date' => '', 'active' => '');
		}
		$this->setStatus($row);
		$this->setMsg($row['msg'], $row['end_date'], $row['active'], $row['code']);
		$this->setDiscount($row['php']);
	}
	
	function setStatus($row){
		if($row['code'] != "" && $row['end_date'] > date('Y-m-d') && $row['active'] == 1){
			$this->status   = "success";
			$_SESSION['couponCode'] = $row['code'];
		} else {
			$this->status = "failure";
			unset($_SESSION['couponCode']);
		}
	}
	
	function setMsg($msg, $end_date, $active, $code){
		if($msg == ""){
			$this->msg = "Sorry, you used an invalid coupon code.";
		} elseif($active == 0){
			$this->msg = "Sorry, your coupon code has been used.";
		} elseif($end_date < date('Y-m-d')){
			$this->msg = "Sorry, the coupon date has expired.";
		} elseif($msg != "") {
			$this->msg = $msg;
		} else {
			$this->msg = "Invalid coupon - error code: 'Not valid'.";
		}
	}

	function setDiscount($php){
		if($php != ""){
			include_once("cart.class.php");
			$cart = new Cart;
			$cart->getCart();
			
			eval($php);			
		} else {
			$this->shipping_discount  = 0;
			$this->total_discount 	= 0;
		}
	}
}
