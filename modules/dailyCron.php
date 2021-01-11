<?php
function conn(){
	$host = "localhost";
	$uname="mjtrends_hedon";
	$pass="mikeyry";
	$database="mjtrends_mjtrends";
	$connection = mysql_connect($host, $uname, $pass);
	$result =mysql_select_db($database);	
}
conn();
cleanCart();

function cleanCart(){
	$query 	= "DELETE FROM tmp_cart WHERE date < CURDATE()";
	$result = mysql_query($query);
}