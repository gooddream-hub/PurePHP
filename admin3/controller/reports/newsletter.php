<?php
include('../../config/config.php');

function get_newsletter(){
	$mysqli = mysqliConn();
	$newsletter = array();
    
	$sql = "SELECT * FROM email_stats ORDER BY nid desc LIMIT 3";
	$result = $mysqli->query($sql);

	while($row = $result->fetch_assoc() ){
		$newsletter[] = $row;
	} 
	return $newsletter;
}

?>


