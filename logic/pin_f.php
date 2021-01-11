<?php
function getDesc(){
	global $title, $desc;
	$db = DB::getInstance();
	$name = $db->real_escape_string(strip_tags($_GET['name']));
	$query = "SELECT image_pins.* "
		. "FROM image_pins "
		. "WHERE name LIKE '$name%' "
		. "LIMIT 1";

	$result = $db->query($query); 
	$row = $result->fetch_assoc();
	
	$img = @json_decode($row['img'], true);
	$row['img'] = array("path" => $row['invid']."/".$img[0]['path'], "alt" => $img[0]['alt']);
	
	$title = $row['title'];
	$desc = $row['desc'];

	return $row;
}

function getImages($page = 0){
	$db = DB::getInstance();
	$limit = 50;
	$start = $page*$limit;
	$query = "SELECT image_pins.* "
		. "FROM image_pins "
		. "ORDER BY id DESC "
		. "LIMIT ".$start.','.$limit;

	$result = $db->query($query);
	
	$rows   = array();
	while ($row = $result->fetch_array()) {
		$parts = pathinfo($row['name']);
		$row['thumb_name'] = $parts['filename'].'_x_150.'.$parts['extension'];
		
		$rows[] = $row;
	}
	
	return $rows;
}

function getImagesCount(){
	$db = DB::getInstance();
	$query = "SELECT * FROM image_pins";
	$result = $db->query($query);

	return $result->num_rows;
}

