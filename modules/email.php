<?php
include ("../logic/global.php");
conn();
setEmail();

function setEmail(){
	$query = "SELECT email FROM custinfo WHERE newsletter != 'no' AND order_date > CURDATE()-8";
	$result = mysql_query($query)or die(mysql_error());
	
	while($row = mysql_fetch_assoc($result)){
		$email = $row['email'];
		$query2 = "INSERT INTO email(address) VALUES('$email')";
		$result2 = mysql_query($query2);
	}
}
