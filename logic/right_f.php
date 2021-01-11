<?php
function right(){
	global $minCart_d, $rec_d, $viewed_d;
	$minCart_d="none";
	$rec_d = "none";
	$viewed_d = "none";
	
	if($_SERVER['PHP_SELF']=="/cart.php"){
		if(count($_SESSION['recent'])>4){
			viewed();
		}  else {
			rec();
		}
	} else {
		if(count($_SESSION['recent'])>4){
			viewed();
		} 
		else{
			rec();
		}
	}
}

function rec(){
	$host = "localhost";
	$uname="mjtrends_hedon";
	$pass="mikeyry";
	$database="mjtrends_mjtrends";
	$connection = mysql_connect($host, $uname, $pass);
	$result =mysql_select_db($database);

	global $rec, $rec_d;
	
	$rec_d = "block";
	$rec   = "";
	
	if ($_GET['type']!=""){
		$type = mysql_real_escape_string($_GET['type']);
		$query = "SELECT type, img_url, color, retail, saleprice, cat FROM inven_mj WHERE type = '$type' AND active = 1 order by RAND() LIMIT 4";
		} else {
		$query = "SELECT type, img_url, color, retail, saleprice, cat FROM inven_mj WHERE (cat = 'fabric' or cat = 'latex-sheeting') AND active = 1 order by RAND() LIMIT 4";
	}
	$result = mysql_query($query); 
	
	if($result){
		if( mysql_num_rows($result) < 4 ){
			$query = "SELECT type, img_url, color, retail, saleprice, cat FROM inven_mj WHERE invid IN (3011, 3000, 3002, 3020)";
			$result = mysql_query($query); 
		}
		while($row = mysql_fetch_assoc($result)){
			if ($row['saleprice']!=""  && $row['saleprice'] > 0){
				$price = number_format(($row['saleprice']),2,'.','');
			} else {
				$price = number_format(($row['retail']),2,'.','');
			}
			$rec[] = array($row['color'].",".$row['type'].",".$row['cat'],$row['img_url'],$row['color']." ".$row['type']." ".$row['cat'], $price); 
		} 
	}
}

function minCart(){
	global $minCart_d, $total;
	$minCart_d ="block";
	foreach ($_SESSION['cart'] as $row){
		$total = number_format($row[9]+$total,2,'.','');;
	}
}

function viewed(){
	global $viewed_d, $view_arr;
	$view_arr = array_reverse($_SESSION['recent']);
	$viewed_d = "block";
}
?>
