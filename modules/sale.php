<?php
include ("../logic/global.php");
conn();

function sale(){
//get top selling products
	global $prod;
	$query = "SELECT cat, type, color, descr FROM inven_mj WHERE saleprice > 0 AND invid < 5000 ORDER BY RAND() LIMIT 1";
	$result = mysql_query($query); 
	$row = mysql_fetch_assoc($result);
	$descr = $row['descr'];
	$descr = explode("<li>", $descr);
	
	$prod = array(str_replace("-"," ",$row['color'])." ".str_replace("-"," ",$row['type'])." ".str_replace("-"," ",$row['cat']), "products.".$row['color'].",".$row['type'].",".$row['cat'], truncate($descr[1], 150)); //product name, link, description
}
sale();
// start the output buffer
ob_start(); 
?>
<div class="promo">
	<div class="container">
		<h3>Weekly Sale Item</h3>
		<div class="wrapper" itemscope itemtype="http://schema.org/IndividualProduct">
			<div class="photo">
				<img src="http://mjtrends.r.worldssl.net/images/sale.gif" />
			</div>
			<div class="text">
				<h4 itemprop="name"><a itemprop="url" href="<?=$prod[1]?>"><?=$prod[0];?></a></h4>
				<p itemprop="description"><?=$prod[2];?> On sale for a limited time only.</p>
				<a class="button" href="<?=$prod[1]?>"><span></span>View Details</a>
			</div>
		</div>
	</div>
</div>
<?php
$cachefile = "../cache/sale.html";
// open the cache file "cache/home.html" for writing
$fp = fopen($cachefile, 'w');
// save the contents of output buffer to the file
fwrite($fp, ob_get_contents());
// close the file
fclose($fp);
// Send the output to the browser
ob_end_flush();
?>