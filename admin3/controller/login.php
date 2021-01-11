<?php
include('../common/auth.php');
include('../../config/config.php');

if (session_status() == PHP_SESSION_NONE) {
	session_start();
}

if(isset($_POST['user']) ){
	$auth = new Auth;
	$group = $auth->login($_POST['user'],$_POST['pwd']);

	if($group == 'admin'){
		header("Location: ".$config->domain."admin3/view/dashboard.php");
	} elseif($group == 'warehouse') {
		header("Location: ".$config->domain."admin3/view/customer/invoice-list.php");
	} elseif($group == 'marketing'){
		header("Location: ".$config->domain."admin3/view/marketing/social.php");
	} else{
		echo "group is: ".$group;
	}

}