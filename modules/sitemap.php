<?php
include ("../logic/global.php");
include ("../logic/sitemap_f.php");
conn();
sitemap();

// start the output buffer
ob_start(); 
?>

<div class="links">
	<h2>Fabric and sewing <span class="red">Articles</span></h2>
	<ul class="pds">
		<? foreach($art_arr as $val):?>
			<li><a href="articles,<?=str_replace(" ","-", $val)?>"><?=$val?></a></li>
		<? endforeach; ?>
	</ul>
	<h2>Product <span class="red">Categories</span></h2>	
	<ul>
		<? foreach($cat_arr as $row):?>
			<li><a href="categories-<?php $val = explode(" ",$row);echo $val[0].",".$val[1]?>"><?=str_replace("-"," ",$row)?></a></li>
		<? endforeach; ?>
	</ul>
	<h2><span class="red">Products</span></h2>
	<ul class="pds">
		<? foreach($prod_arr as $row):?>
			<li><a href="products.<?=$row[0].",".$row[1].",".$row[2]?>"><?=str_replace("-"," ",$row[0])?> <?=str_replace("-"," ",$row[1])?> <?=str_replace("-"," ",$row[2])?></a></li>
		<? endforeach; ?>
	</ul>
</div>

<?php
$cachefile = "../cache/sitemap.html";
// open the cache file "cache/home.html" for writing
$fp = fopen($cachefile, 'w');
// save the contents of output buffer to the file
fwrite($fp, ob_get_contents());
// close the file
fclose($fp);
// Send the output to the browser
ob_end_flush();
?>