<?php

function index(){
	$db = DB::getInstance();
	$letter = $db->real_escape_string($_GET['letter']);
	$query  = "SELECT word, entry FROM site_index WHERE letter = '$letter' ORDER BY word ASC";

	$result = $db->query($query);
	
	while ($row = $result->fetch_assoc()) {
		$resultArray[] = $row;
	}

	return $resultArray;	
}

function showIndex(){
	$db = DB::getInstance();
	$query  = "SELECT word, letter FROM site_index order by word ASC";
	$result = $db->query($query);

	while ($row = $result->fetch_assoc()) {
		$resultArray[] = $row;
	}

	return $resultArray;
}