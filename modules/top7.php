<?php
include ("../logic/global.php");
conn();

function top7(){
//get top selling products
	global $topLink, $topProd;
	$query = "SELECT cat, type, color FROM inven_mj WHERE cat = 'fabric' OR cat = 'Latex-Sheeting' AND active = 1 ORDER BY RAND() LIMIT 8";
	$result = mysql_query($query);
	$row = mysql_fetch_assoc($result);

	while ($row = mysql_fetch_assoc($result)){
		$topLink[] = $row['color'].",".$row['type'].",".$row['cat'];
		$topProd[] = str_replace("-"," ",$row['color'])." ".str_replace("-"," ",$row['type'])." ".str_replace("-"," ",$row['cat']);
	}
}

top7();
// start the output buffer
ob_start();
?>

<div class="top7">
    <div class="hdr">Top <span>7</span> Searches</div>
    <div class="seven">
		<? for($i = 0; $i < 6; $i++):?>
            <a href="http://www.mjtrends.com/products.<?=$topLink[$i]?>"><span class="number"><?=$i+1?></span> <?=$topProd[$i]?></a>
            <span class="t7brdr"></span>
		<? endfor;?>
            <a href="http://www.mjtrends.com/products.<?=$topLink[6]?>"><span class="number">7</span> <?=$topProd[6]?></a>
    </div>
</div>

<?php
$cachefile = "../cache/top7.html";
// open the cache file "cache/home.html" for writing
$fp = fopen($cachefile, 'w');
// save the contents of output buffer to the file
fwrite($fp, ob_get_contents());
// close the file
fclose($fp);
// Send the output to the browser
ob_end_flush();
?>