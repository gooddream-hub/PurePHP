<?php
function sitemap(){
global $cat_arr, $prod_arr, $art_arr;
	$query1 = "SELECT cat, type, color FROM inven_mj ORDER BY type asc, color asc";
	$query2 = "SELECT title FROM articles ORDER BY title asc LIMIT 5";
	$prodRes = mysql_query($query1); 
	$artRes = mysql_query($query2); 
	
	while ($row = mysql_fetch_assoc($prodRes)){
		$cat_arr[] = $row['type']." ".$row['cat'];
		$prod_arr[] = array($row['color'],$row['type'],$row['cat']);
	}
	$cat_arr = array_unique($cat_arr);
	
	while ($row = mysql_fetch_assoc($artRes)){
		$art_arr[] = $row['title'];
	}
}
?>