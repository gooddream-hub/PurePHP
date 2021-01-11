<?php
$function = array('removeCartItem' => 'removeCartItem','filter_categories' => 'filter_categories', 'shipping' => 'setShipNfo', 'addToCart' => 'setCart', 'getCart' => 'getCart', 'signin' => 'userSignin', 'recoverPWD' => 'recoverPWD', 'createAcc' => 'createAccount', 'resendConfirmation' => 'resendConfirmation', 'signout' => 'signOut', 'getInvamount' => 'getInvamount', 'setCatClick' => 'setCatClick', 'setBounce'=> 'setBounceNews', 'getKitAmount' => 'getKitAmount', 'getKitGridValue'=>'getKitGridValue', 'getTotalPrice'=> 'setNewTotalPrice', 'savePattern'=>'savePattern', 'emptyCart' => 'resetCart', 'getID' => 'getID');
$function['getPins'] = 'get_pins';
$function['savePoll'] = 'save_poll';
$function[$_POST['function']]();

function setShipNfo(){
	session_start();
	include('user.class.php');
	
	$code = (preg_match('/^[a-zA-Z0-9_\s]+$/',$_POST['code'])) ? $_POST['code'] : '';
	$cost = round((float)$_POST['cost'], 2);

	$user = new user;
	$shipDetails = array('code' => $code, 'cost' => $cost, 'service' => $_POST['service'], 'delDate' => $_POST['delDate']);
	$user->setShipping($shipDetails);
}

function removeCartItem() {
	include('cart.class.php');
	
	$cart = new Cart;
	$delid = $_POST["id"];
	$delid = intval($delid);

	$_GET['update'.$delid] = $delid;
	$_GET['qty'.$delid] = 0;
	$cart->updateCart();

	$msg = array(
			"status" => "success",	
		);
	echo json_encode($msg);
}

function getCart() {
	include('global.php');
	include('cart.class.php');
	
	$cart = new Cart;
	$msg = array(
		"status" => "success",
		"value"  => $cart->getCart(),
	);
	
	echo json_encode($msg);

}

function setCart(){
	include('global.php');
	$mysqli = mysqliConn();
	include('cart.class.php');

	$price = 0;

	$invid = (int)$_POST['invid'];
	$quant = (int)$_POST['quant'];

	$query = "SELECT cat, type, color, retail, saleprice, img, weight, volume, minWidth, minLength, minHeight, invamount FROM inven_mj where invid = $invid";
	$result = $mysqli->query($query);
	$obj = $result->fetch_object();

	$imgArray = json_decode($obj->img, true);
	$img_url = $imgArray[0]['path'];

	$cart = new Cart;
	$cart->setProdClick();
	
	if(isset($_POST['color'])){
		$invid = $mysqli->real_escape_string($_POST['invid']);
		$color = $mysqli->real_escape_string(strip_tags($_POST['color']));
		$type = $obj->color;
		echo $cart->addToCart($obj->cat, $type, $color, $obj->weight, $obj->volume, $obj->retail, $quant, $obj->invamount, $invid, $obj->saleprice, $img_url, $obj->minWidth, $obj->minLength, $obj->minHeight);
	} else {
		if(!empty($obj->length)) $obj->color = $obj->color." ".$obj->length;
		echo $cart->addToCart($obj->cat, $obj->type, $obj->color, $obj->weight, $obj->volume, $obj->retail, $quant, $obj->invamount, $invid, $obj->saleprice, $img_url, $obj->minWidth, $obj->minLength, $obj->minHeight);
	}

	$result->free();
	$mysqli->close();
}


function userSignin(){
	session_start();
	include('global.php');
	include('user.class.php');
	conn();

	$email= filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
	$pwd = strip_tags($_POST['pwd']);

	if(filter_var($email, FILTER_VALIDATE_EMAIL) == true AND $_POST['pwd'] != ''){
		$user = new user;
		echo $user->signIn($email, $pwd);
	} else {
		echo "fail";
	} 
}

function signOut(){
	session_start();
	unset($_SESSION['user']);
	setcookie ("usr", "", time() - 3600, '/');
	echo "signout function called";
}

function recoverPWD(){
	include('global.php');
	include('user.class.php');
	conn();

	$email= filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
	$referrer = $_POST['referrer'];

	if(filter_var($email, FILTER_VALIDATE_EMAIL) == true){
		$user = new user;
		echo $user->recoverPWD($email, $referrer);
	} else {
		echo "fail";
	} 
}

function createAccount(){
	include('global.php');
	include('user.class.php');
	conn();

	$email= filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
	$userName = $_POST['username'];
	$pwd = $_POST['pwd'];
	$thread = $_POST['thread'];

	$user = new user;
	
	if(!$user->validateEmail($email)){
		echo '<div class="error">That email address is already registered. <br> <button id="recoverAcc" class="gButton" onclick="accRecoverPwd();">Recover Password</button></div>';
		die();
	}

	if(!$user->validateUserName($userName)){
		echo '<div class="error">Sorry, that username is already being used.  Try again :) </div>';
		die();
	}

	$user->createAccount($email, $userName, $pwd, $thread);
	echo "success";

}

function resendConfirmation(){
	include('global.php');
	include('user.class.php');
	conn();

	$email= filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
	$thread = $_POST['thread'];

	$user = new user;
	$user->resendConfirmation($email, $thread);	
}

function getInvamount(){
	include ("global.php");
	$mysqli = mysqliConn();

	if(isset($_GET['invid'])){
		$invid = (int)$_GET['invid'];
		$invid = substr($_GET['invid'], 0, 5);
		$query = "SELECT invamount, cat FROM inven_mj WHERE invid = $invid ";
	} else {
		$cat = mysqli_real_escape_string($mysqli, $_GET['cat']);
		$type =  mysqli_real_escape_string($mysqli, $_GET['type']);
		$color =  mysqli_real_escape_string($mysqli, $_GET['color']);
		$query = "SELECT invamount, cat FROM inven_mj WHERE cat = '$cat' AND type = '$type' AND color = '$color' ";
	}

	$result = $mysqli->query($query);
	$obj = $result->fetch_object();

	if($obj->cat == "Fabric" OR $obj->cat == "Latex-Sheeting"){
		$obj->invamount = $obj->invamount." yards";
	}
	echo $obj->invamount;

}

function setCatClick(){
	if(isset($_COOKIE['catTest'])){
		$cookie_array = explode("+",$_COOKIE['catTest']);
		$variation = $cookie_array[0];

		if($cookie_array[1] == 0){	
			include('global.php');
			include('mvt.php');
			conn();
			$mvt = new mvt;
			$mvt->saveClick($variation);

			$variation = $variation."+1";
			setcookie("catTest", $variation, time()+2592000 );// 30 days
		} 
	}
}

function setBounceNews(){
	include_once ("global.php");
	setBounce();
}

function getKitAmount(){
	include ("global.php");
	$mysqli = mysqliConn();
	$kit = $mysqli->real_escape_string(strip_tags($_GET['kit']));

	$query = "SELECT items FROM kit WHERE title = '$kit' ";
	$result = $mysqli->query($query);
	$row = $result->fetch_assoc();

	$invidArray = json_decode($row['items'],true);

	foreach($invidArray as $key=>$val){
		$invids .= $key.",";
	}
	$invids = rtrim($invids, ',');		

	$sql = "SELECT invamount FROM inven_mj where invid IN($invids) ORDER BY invamount ASC LIMIT 1";
	$result = $mysqli->query($sql);
	$obj = $result->fetch_object();

	echo $obj->invamount;
}

function getKitGridValue(){
	include ("global.php");
	include ("product.class.php");

	$product = new Product;
	$product->mysqli = mysqliConn();
	echo $product->getKitGridValue($_POST['kit']);

}

function setNewTotalPrice() {
	include('global.php');
	$mysqli = mysqliConn();
	include('cart.class.php');
	include('shipRate.class.php');
	include('user.class.php');
	
	$cart = new Cart;

	$price = 0;
	$shipRate = '';	
	$shipMeth = '';

	$invid = (int)$_GET['invid'];
	$quant = (int)$_GET['quant'];
	
	$query = "SELECT saleprice, retail FROM inven_mj where invid = '$invid'";
	$result = $mysqli->query($query);
	$obj = $result->fetch_object();

	if($obj->saleprice > 0){
		$price = $obj->saleprice;
	} else {
		$price = $obj->retail;
	}

	$totalPrice = (double)($price * $quant);
	$formatPrice = '$'.number_format(($totalPrice),2,'.',''); //format Price

	$arr = $cart->getCart();
	foreach ($arr as $rownum=>$value){
		if ($arr[$rownum]['invid']==$invid){
			$arr[$rownum]['quant'] = $quant; //set new quantity to $_SESSION['cart'] with corresponding invid 
		}
	}

	$cart->saveCart($_COOKIE['custid'], $arr);
	$cart->getCart();

	$subtotal = $cart->getSubtotal(); //retrieve new subtotal
	
	if(isset($_SESSION['shipNfo'])){
		$rate = new ShipRate;
		$user = new User;
		
		$user->getAddress();
		$rate->init($cart->items, $user->shipco);

		$rate->setServiceFromSession();

		if(!empty($rate->fedexService)) $rate->getFedexRates($user, 'true');  
		if(!empty($rate->uspsService))  $rate->getUSPSRates($user, $subtotal); 
		if($rate->flatRate == true) $rate->setRateOrder();

		$shipRate = number_format($rate->rateArray[0]['cost'],2,'.','');
		$shipMeth = $rate->rateArray[0]['service']."<br>".$rate->rateArray[0]['shipTime'];

		$gtotal = number_format($subtotal + $shipRate,2,'.','');	// grand total will be subtotal + shipping costs.
	} else {
		$gtotal = $subtotal;
	}

	$_SESSION['gtotal'] = $gtotal;

	$output = array("status" => "success", "totalPrice" => $formatPrice, "subTotal" => $subtotal, "grandTotal" => $gtotal, "shipRate" => $shipRate, "shipMeth" => $shipMeth);
	echo json_encode($output);
	$result->free();
	$mysqli->close();
}

function filter_categories() {
	$data = array(
		'message' => 'failed',
		'html'    => 'test'
	);
	
	include_once("global.php");
	include_once("category_f.php");
	
	$html = filter_prods($_POST);
	if ($html) {
		$data['html']    = $html;
		$data['message'] = 'success';
	}
	
	echo json_encode($data);
}

function get_pins(){
	include('global.php');
	include ("pin_f.php");
	include_once 'global_ftp.php';

	conn();
	
	$data = array(
		'status' => 'success',
		'html'   => '',
	);
	
	$rows = getImages((int)$_POST['page']);

	foreach ($rows as $row) {
		$data['html'] .= '<div class="col-md-3 col-sm-4 col-xs-6 img-container block" id="pid-'.$row['invid'].'">
							<div class="img-block">
								<a class="catImg" href="pins.php?name='.$row['name'].'">
									<img src="'.$cdn_url.ltrim($ftp_image_pins_url, '/').$row['thumb_name'].'" alt="'.$row['alt'].'" itemprop="image">
								</a>
							</div>
							<p>
								'.$row['desc'].'
							</p>
						</div>';
	}
	
	echo json_encode($data);
}

function save_poll(){
	include('global.php');
	$mysqli = mysqliConn();
	
	$server_url = 'http://www.mjtrends.com';
	
	$data = array(
		'status' => 'failed'
	);
	if (empty($_POST['answers'])) {
		echo json_encode($data);
		return;
	}
	
	$sql = 'INSERT INTO poll_answers (poll_id, question_id, answer_id, val, other) VALUES ';
	foreach ($_POST['answers'] as $k => $answer) {
		list($q_id, $a_id) = explode('_', $k);
		$other = '';
		if (!$a_id) {
			$other = $answer;
			$answer = '';
		}
		$sql .= '('.((int)$_POST['poll_id']).', '.$q_id.', '.$a_id.', "'.$answer.'", "'.$other.'"),';
	}
	$sql = trim($sql, ',');
	$result = $mysqli->query($sql);
	
	setcookie("poll", 1, time()+3600*24*365, '/');
	
	$data['status'] = 'success';
	$data['html'] = file_get_contents($server_url.'/modules/poll_results.php?poll_id=1');
	echo json_encode($data);
}

function savePattern(){
	include('global.php');
	include('pattern.class.php');
	include('cart.class.php');

	$cart = new Cart;
	$custid = $cart->getID();

	$pattern = new Pattern;
	$pattern->mysqli = mysqliConn();

	$pattern->savePattern($_POST['dataInput'], $custid);

}

function getID(){
	include('cart.class.php');

	$cart = new Cart;
	echo $cart->getID();

}