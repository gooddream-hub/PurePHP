<?php
if($_POST['func'] != ''){
	include('global.php');
	$_POST['func']($_POST['dataInput']);
}

function getPatternType($gender){
	$mysqli = mysqliConn();
	$query = "SELECT patID, type FROM pattern_type where gender = '$gender'";
	$result = $mysqli->query($query);
	
	while ($row = $result->fetch_assoc()) {
		$types .= '{"type" : "'.$row['type'].'", "patID" : "'.$row['patID'].'"},';
	}
	$types = rtrim($types, ",");
	
	echo "[".$types."]"; //json notation - [{"type" : "top", "patID" : "432"},{"type" : "bottom", "patID" : "123"}, {"type" : "skirt", "patID" : 245"}]
}

function getPatternStyle(){
	$mysqli = mysqliConn();
	$query = "SELECT styleID, style, gender FROM pattern_style LEFT JOIN pattern_type ON pattern_style.patID = pattern_type.patID ORDER BY gender DESC, styleID";
	$result = $mysqli->query($query);
	
	while ($row = $result->fetch_assoc()) {
		$types[] = $row;
	}
	
	return $types;
}

function getPatternSizing($garmentStyle){
	$mysqli = mysqliConn();
	$query = "SELECT sizing FROM pattern_style where styleID = '$garmentStyle'";
	$result = $mysqli->query($query);
	$row = $result->fetch_row();
	
	echo $row[0];//["waist", "thigh", "inseam"]
	
}

function getFabTypes($styleID){
	$mysqli = mysqliConn();
	$query = "SELECT fabTypes FROM pattern_style WHERE styleID = $styleID";
	$result = $mysqli->query($query);
	
	$row = $result->fetch_row();
	echo $row[0];
}

function savePattern($data){ 
	$mysqli = mysqliConn();
	if ($_SESSION['custid']==""){
		$query1 = "UPDATE getid SET custid = custid +1";
		$result1 = $mysqli->query($query1);
		$query = "SELECT custid from getid";
		$result = $mysqli->query($query);
		$row = $result->fetch_assoc();
		$_SESSION['custid']=$row['custid'];
	}
	
	$data = stripslashes($data);
	$data = explode('*',$data);
	$query = "INSERT INTO pattern_order(custid, styleID, size) VALUES(".$_SESSION['custid'].", $data[0], '$data[1]')";
	$result = $mysqli->query($query);
	$insert_id = $mysqli->insert_id; 
	
	$query = "SELECT gender, style, TRIM(TRAILING 's' FROM pattern_type.type) AS type FROM pattern_type LEFT JOIN pattern_style on pattern_style.patID = pattern_type.patID WHERE pattern_style.styleID = $data[0]";
	$result = $mysqli->query($query);
	$row = $result->fetch_assoc();
	
	$patterns = array('/{/', '/}/', '/"/');
	$replacements = array('','','');
	$sizing = preg_replace($patterns, $replacements, $data[1]);
	
	$arr[] = array("type" => $row['gender'], "color" => $row['style'].' pattern', "quant" => 1, "price" => 12.5, "invid" => $insert_id, "amt" => 10, "sale" => 8.5, "img" => "", "cat" => "Pattern", "weight" => .25, "volume" => 2);
	
	if($_SESSION['cart'][0] == ""){
		$_SESSION['cart']= $arr;
	} else {
		$_SESSION['cart'][] = array("type" => $row['gender'], "color" => $row['style'].' pattern', "quant" => 1, "price" => 12.5, "invid" => $insert_id, "amt" => 10, "sale" => 8.5, "img" => "", "cat" => "Pattern", "weight" => .25, "volume" => 2);
	}
	setcookie("current_custid", $_SESSION['custid'], time()+3600*24*30);
	$cart = str_replace("\"","+",serialize($_SESSION['cart']));
	$query 	= "REPLACE INTO tmp_cart(custid,cart,date) VALUES('{$_SESSION['custid']}','$cart',CURDATE())";
	mysql_query($query);
	echo "success";
}

function getStandardSize(){
	$mysqli = mysqliConn();
	
	$size = $mysqli->real_escape_string($_POST['size']);
	$gender = $mysqli->real_escape_string($_POST['gender']);

	$query = "SELECT measurements FROM pattern_sizing where size = '$size' and gender = '$gender' ";
	$result = $mysqli->query($query);
	$row = $result->fetch_row();

	echo $row[0];
}
