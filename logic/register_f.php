<?php
function init(){
	if($_POST['update']){
		update();
	} elseif($_POST['register']){
		register();
	} else {
		signIn();
	}
}

function setUsrCookie(){
	($_POST['email_2'] == "") ? $email = $_POST['email']: $email = $_POST['email_2'];
	setcookie("usr", $email, mktime(). time()+60*60*24*365*10);//set for 10 years
}

function signIn(){
	$email = mysql_real_escape_string($_POST['email_2']);
	$pwd = mysql_real_escape_string($_POST['password']);

	$query = "SELECT * FROM users WHERE email = '$email' and pwd = '$pwd'";
	$result = mysql_query($query);

	$row = mysql_fetch_assoc($result);

	if($row['email'] == ""){
		Header("Location: http://www.mjtrends.com/login.php?signin=err");
	}else{
		setUsrCookie();
		getUser('',$email, $pwd);
		
		$query = "UPDATE users SET last_signin = CURDATE() WHERE email = '$email' and pwd = '$pwd'";
		$result = mysql_query($query);
	}
}

function setPhone($type){
	if($type == "shipping"){
		$phone = mysql_real_escape_string($_POST['shipPh1'])."-".mysql_real_escape_string($_POST['shipPh2'])."-".mysql_real_escape_string($_POST['shipPh3'])." x".mysql_real_escape_string($_POST['shipPh4']);
	} else {
		$phone = mysql_real_escape_string($_POST['billPh1'])."-".mysql_real_escape_string($_POST['billPh2'])."-".mysql_real_escape_string($_POST['billPh3'])." x".mysql_real_escape_string($_POST['billPh4']);
	}
	return $phone;
}

function register(){
	include_once 'logic/securimage.php';
	$securimage = new Securimage();
	
	$email 		= mysql_real_escape_string($_POST['email']);
	$pwd 		= mysql_real_escape_string($_POST['pwd']);
	$t_query 	= "SELECT email FROM users where email = '$email'";
	$result 	= mysql_query($t_query);
	$row 		= mysql_fetch_assoc($result);

	$phone  = setPhone("shipping");
	$bPhone = setPhone("billing");

	if($row['email'] != ""){
		header("Location: http://www.mjtrends.com/login.php?register=taken");
	} elseif($securimage->check($_POST['captcha_code']) == false) {
		header("Location: http://www.mjtrends.com/login.php?captcha=err");
	} else {
		$query = "INSERT INTO users (email, pwd, shipfirst, shiplast, shipadone, shipadtwo, shipcomp, shipcity, shipstate, shipzip, shipco, shiphone, newsletter, billfirst, billlast, billadone, billadtwo, billcomp, billcity, billstate, billzip, billco, billphone, signup_date) VALUES ('".$email."', '".$pwd."', '".mysql_real_escape_string($_POST['shipfirst'])."', '".mysql_real_escape_string($_POST['shiplast'])."', '".mysql_real_escape_string($_POST['shipAd1'])."', '".mysql_real_escape_string($_POST['shipAd2'])."', '".mysql_real_escape_string($_POST['shipComp'])."', '".mysql_real_escape_string($_POST['shipCity'])."', '".mysql_real_escape_string($_POST['shipState'])."', '".mysql_real_escape_string($_POST['shipZip'])."', '".mysql_real_escape_string($_POST['shipCo'])."', '".$phone."', '".mysql_real_escape_string($_POST['newsletter'])."', '".mysql_real_escape_string($_POST['billfirst'])."', '".mysql_real_escape_string($_POST['billlast'])."', '".mysql_real_escape_string($_POST['billAd1'])."', '".mysql_real_escape_string($_POST['billAd2'])."', '".mysql_real_escape_string($_POST['billComp'])."', '".mysql_real_escape_string($_POST['billCity'])."', '".mysql_real_escape_string($_POST['billState'])."', '".mysql_real_escape_string($_POST['billZip'])."', '".mysql_real_escape_string($_POST['billCo'])."', '". $bPhone ."', CURDATE())";
		$result = mysql_query($query) or die(mysql_error());
		setUsrCookie();
		getUser('new', $email, $pwd);
	}
}

function update(){
	$phone  = setPhone("shipping");
	$bPhone = setPhone("billing");

	$query = "UPDATE users SET email = '".mysql_real_escape_string($_POST['email'])."', pwd = '".mysql_real_escape_string($_POST['pwd'])."', shipfirst = '".mysql_real_escape_string($_POST['shipfirst'])."', shiplast = '".mysql_real_escape_string($_POST['shiplast'])."', shipadone = '".mysql_real_escape_string($_POST['shipAd1'])."', shipadtwo = '".mysql_real_escape_string($_POST['shipAd2'])."', shipcomp = '".mysql_real_escape_string($_POST['shipComp'])."', shipcity = '".mysql_real_escape_string($_POST['shipCity'])."', shipstate = '".mysql_real_escape_string($_POST['shipState'])."', shipzip = '".mysql_real_escape_string($_POST['shipZip'])."',  shipco = '".mysql_real_escape_string($_POST['shipCo'])."', shiphone = '".$phone."', newsletter = '".mysql_real_escape_string($_POST['shipState'])."', newsletter = '".mysql_real_escape_string($_POST['newsletter'])."', billfirst = '".mysql_real_escape_string($_POST['billfirst'])."', billlast = '".mysql_real_escape_string($_POST['billlast'])."', billadone = '".mysql_real_escape_string($_POST['billAd1'])."', billadtwo = '".mysql_real_escape_string($_POST['billAd2'])."', billcomp = '".mysql_real_escape_string($_POST['billComp'])."', billcity = '".mysql_real_escape_string($_POST['billCity'])."', billstate = '".mysql_real_escape_string($_POST['billState'])."', billzip = '".mysql_real_escape_string($_POST['billZip'])."', billco = '".mysql_real_escape_string($_POST['billCo'])."', billphone = '".$bPhone."' WHERE email = '". mysql_real_escape_string($_POST['email_orig']) ."' ";
	$result = mysql_query($query) or die(mysql_error());
	setUsrCookie();
	getUser("update");
}

function getUser($type, $email, $pwd){
	global $welcome, $data;

	$query 	= "SELECT * FROM users WHERE email = '". $email ."' AND pwd = '". $pwd ."' ";
	$result = mysql_query($query) or die(mysql_error());
	$data 	= mysql_fetch_assoc($result);

	setWelcome($type);
	getPhone();
}

function setWelcome($type){
	global $welcome;

	if($type == "new"){
		$welcome = "You have successfully created an account!";
	} elseif($type == "update") {
		$welcome = "You have updated your account.";
	} else {
		$welcome = "Thank you for signing in!";
	}
}

function getPhone(){
	global $data, $sphone, $bphone;

	$sphone = explode("-",$data['shiphone']);
	$bphone = explode("-",$data['billphone']);

	$sphone[2] = str_replace("x","",$sphone[2]);
	$bphone[2] = str_replace("x","",$bphone[2]);

	$s_ext 		= explode(" ",$sphone[2]);
	$s_ext 		= $s_ext[1];
	$sphone[3] 	= $s_ext;

	$b_ext 		= explode(" ",$bphone[2]);
	$b_ext 		= $b_ext[1];
	$bphone[3] 	= $b_ext;
}