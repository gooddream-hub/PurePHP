<?php
function getPrevArt(){
	$email = $_COOKIE['user'];
	$img = array();

	$query = "SELECT imgPath FROM applique WHERE custid IN (SELECT custid FROM custinfo where email = '$email') ";
	$result = mysql_query($query); 
	$row = mysql_fetch_assoc($result);

	while ($row = mysql_fetch_assoc($result)){
		$img[] = $row['imgPath'];
	}

	return $img;
}

function getExistingArt(){
	$query = "SELECT * FROM inven_mj where cat = 'Applique' and active = 1";
	$result = mysql_query($query); 

	while ($row = mysql_fetch_assoc($result)){
		$app[] = array('imgPath' => $row['imgPath'], 'title' => $row['title'], 'invid' => $row['invid']);
	}

	return $app;
}