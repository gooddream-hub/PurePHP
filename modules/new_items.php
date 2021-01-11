<?php
include ("../logic/global.php");
conn();

function newItems(){
	global $image, $link, $prod;
	$query = "SELECT invid, cat, type, color, img FROM inven_mj WHERE invid < 5000 AND active = 1 ORDER BY purch DESC LIMIT 7";
	$result = mysql_query($query); 
	$row = mysql_fetch_assoc($result);
	
	while ($row = mysql_fetch_assoc($result)){
		$imgArray = json_decode($row['img'], true);
		$image[] = $row['invid']."/".$imgArray[0]["path"]."_130x98.jpg";
		$link[] = $row['color'].",".$row['type'].",".$row['cat'];
		$prod[] = str_replace("-"," ",$row['color'])." ".str_replace("-"," ",$row['type'])." ".str_replace("-"," ",$row['cat']);
	}
}

newItems();
// start the output buffer
ob_start(); 
?>

<div class="side_show" itemscope itemtype="http://schema.org/IndividualProduct">
	<div class="container">
		<h3>What's New: Recently Added Items</h3>
		<div class="cont">
			<div class="l_arrow" onclick="rotateSlides(-1);"></div>
			<div id="visible_slides"><? for($i = 0; $i < 6; $i++): ?><div id="slide<?=$i?>" class="<? echo ($i < 3) ? 'visible': 'invisible'; ?>"><a href="/products.<?=$link[$i]?>"><img itemprop="image" src="http://mjtrends.r.worldssl.net/images/product/<?=$image[$i]?>" width="131" height="107" /></a><a itemprop="url" href="/products.<?=$link[$i]?>"><p itemprop="name"><?=$prod[$i]?></p></a></div><? endfor;?></div>
			<div class="r_arrow" onclick="rotateSlides(1);"> </div>
		</div>
	</div>
</div>					

<?php
$cachefile = "../cache/new_items.html";
// open the cache file "cache/home.html" for writing
$fp = fopen($cachefile, 'w');
// save the contents of output buffer to the file
fwrite($fp, ob_get_contents());
// close the file
fclose($fp);
// Send the output to the browser
ob_end_flush();
?>