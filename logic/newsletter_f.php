<?php

function setData(){
	$mysqli = mysqliConn();

	if($_REQUEST['email'] != ""){
		$email = $mysqli->real_escape_string($_REQUEST['email']);
		$date = date('Y-m-d');
		$query = "INSERT INTO email(address, date, active) VALUES('$email', '$date',1)";
		$mysqli->query($query);
		
		$cookieArray = explode("+", $_COOKIE['newsTest']); 
		$variation = $cookieArray[0];

		setcookie("newsTest", $variation."+1", time()+31104000 );// 12 months
		conn();
		include('mvt.php');
		$mvt = new mvt;
		$mvt->saveConversion($variation);
		
		return "true";
	} elseif($_POST['emailUpdate'] != ""){
		$email = $_POST['emailUpdate'];
		$firstName = $_POST['firstName'];
		$other =  ($_POST['other'] == "Write other interests here") ? "" : $_POST['other'];
		
		if(isset($_POST['style'])){
		    foreach($_POST['style'] as $item){
		        $style .= $item." ";
		    }
		}

		$ent1 = $_POST['ent1'];
		$ent2 = $_POST['ent2'];
		$ent3 = $_POST['ent3'];
		$ent4 = $_POST['ent4'];
		$ent5 = $_POST['ent5'];
		$ent6 = $_POST['ent6'];
		$other = $_POST['other'];
		

		$query = "UPDATE email SET firstName = '$firstName', style='$style', ent1='$ent1', ent2='$ent2', ent3='$ent3', ent4='$ent4', ent5='$ent5', ent6='$ent6', other='$other'  WHERE address = '$email' ";
		$mysqli->query($query);
		return "prefSet";
	} else {
		return "new";
	}

}