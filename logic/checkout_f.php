<?php
//use a cookie in case coming from PayPal
function setCoupon(){
	if(isset($_POST['couponCode'])){
		$_SESSION['couponCode'] = $_POST['couponCode'];
		setcookie("couponCode", $_POST['couponCode'], time() + 86400 * 1, '/');
	}
	if(isset($_COOKIE['couponCode'])){
		$_SESSION['couponCode'] = $_COOKIE['couponCode'];
	}
}

function setMVTCookies(){
	setcookie("convo", $_POST['conversion'], time()+86400);
	setcookie("adCookie", $_POST['adid'], time()+86400);
	//remove catTest from production
	setcookie("catTest", $_POST['catTest'], time()+31536000 );
	setcookie("newsletter", 'yes', time()+86400);
}

function getShipping($rate, $user, $cart){
	//get rates again if set from cart
	if($_POST['shipMeth'] != ""){
		$address = array("shipco" => $_POST['shippingCo'], "shipzip" => $_POST['shippingZip']);
		$user->setAddress($address);
		$user->getAddress();
		
		$rate->init($cart->items, $address['shipco']);
		#$_SESSION['shipNfo']['code'] = str_replace(' ',' ',strtoupper($_POST['shipMeth']));
		$rate->setServiceFromSession();

		if(!empty($rate->fedexService)) $rate->getFedexRates($user, 'true');   
		if(!empty($rate->uspsService))  $rate->getUSPSRates($user, $cart->subtotal); 
		if($rate->flatRate == true) $rate->setRateOrder();

		$_SESSION['shipNfo'] = $rate->rateArray[0];
	}
}