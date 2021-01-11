<?php
include('constants.php');
class Auth{

	function __construct() {
		// include('/var/www/mjtrends/config/config.php');
		// include('/var/www/mjtrends/logic/global.php');
		// include('/xampp/htdocs/LAMP/config/config.php');
		// include('/xampp/htdocs/LAMP/logic/global.php');
		
		include(SITE_ROOT_PATH.'config/config.php');
		include(SITE_ROOT_PATH.'logic/global.php');
		
		$this->mysqli = mysqliConn();
		$this->config = $config;
	}	

	function get_auth(){
		if($_SESSION['auth'] != "true"){
			header("Location: ".$this->config->domain."admin3/view/login.php");
		}
	}

	function login($user, $pwd) {
		$user = $this->mysqli->real_escape_string($user);
		$sql = "SELECT id, username, userpwd, usergroup from sb_users where username = '".$user."' ";
		$result = $this->mysqli->query($sql);
		$row = $result->fetch_assoc();

		if( password_verify($pwd, $row['userpwd']) ){
			$_SESSION['user_id'] = $row['id'];
			$_SESSION['auth'] = 'true';
			$_SESSION['group'] = $row['usergroup'];
			$_SESSION['username'] = $row['username'];
		} else {
			header("Location: http://".$this->config->domain."admin3/login.php?status=fail");
		}

		return $row['usergroup'];

	}

	function get_admin_access() {

	}

	function get_marketing_access() {
		if( ($_SESSION['group'] != "marketing") && ($_SESSION['group'] != "admin") ){
			header("Location: http://".$this->config->domain."admin3/view/unauthorized.html"); 
		} 
	}

	function get_warehouse_access() {
		if( ($_SESSION['group'] != "warehouse") && ($_SESSION['group'] != "admin") ){
			header("Location: http://".$this->config->domain."admin3/view/unauthorized.php"); 
		}
	}

}

?>