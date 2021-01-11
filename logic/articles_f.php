<?php
function getAll(){
	global $titles;
	$db = DB::getInstance();
	$query = "SELECT title FROM articles ORDER BY date asc";
	$result = $db->query($query); 
	
	while ($row = $result->fetch_assoc()){
		$titles[] = $row['title'];
	}
}

function getTutorial(){
	global $tutorial;
	$db = DB::getInstance();
	$title = $db->real_escape_string(str_replace("-", " ",$_GET['title']));
	$query = "SELECT * FROM articles WHERE title = '". $title ."'";
	$result = $db->query($query); 
	
	while ($row = $result->fetch_assoc()){
		$tutorial = array("title" => $row['title'], "vid_url" => $row['vid_url'], "content" => $row['content'], "date" => $row['date']);
	}
}