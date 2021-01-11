<?
function promo(){
	global $top, $new, $fet, $clearance, $markdown;
	
	$query3 = "SELECT link, text, type FROM promo";
	$result3 = mysql_query($query3); 
	$row3 = mysql_fetch_assoc($result3);

	mysql_data_seek($result3,0);
	while ($row3 = mysql_fetch_assoc($result3)){
		switch ($row3['type']){
			case "top":
				$top[] = array($row3['link'],$row3['text']); 
				break;
			case "new":
				$new[] = array($row3['link'],$row3['text']);
				break;
			case "clearance":
				$clearance[] = array($row3['link'],$row3['text']);
				break;
			case "markdown":
				$markdown[] = array($row3['link'],$row3['text']);
				break;
		}
	}
}

function soldout(){
	global $sold;
	$query2 = "SELECT invtype, invcolor, category, invamount FROM inven_master WHERE (category = 'fabric' or category= 'latex-sheeting') AND invamount > 0 ORDER BY invamount asc LIMIT 6";
	$result2 = mysql_query($query2);
	$row2 = mysql_fetch_assoc($result2);
	
	mysql_data_seek($result2,0);
	while ($row2 = mysql_fetch_assoc($result2)){
		$sold[]= array("products.".$row2['invcolor'].",".$row2['invtype'].",".$row2['category'],str_replace("-"," ",$row2['invcolor'])." ".str_replace("-"," ",$row2['invtype'])." ".$row2['category']);
	}
}
?>
