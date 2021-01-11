<?
include ("../logic/global.php");
include ("../logic/category_f.php");

filter_prods($_POST);
	
ob_start();
?>
	<?=$noprod?>
	<? if($prods){foreach($prods as $row): ?>
		<div class="col <?=$class?>" id="pid-<?=$row['invid']?>" itemscope itemtype="http://schema.org/IndividualProduct">
			<a class="catImg" href="products.<?=$row['color']?>,<?=$row['type']?>,<?=$row['cat']?>"><img itemprop="image" class="catProd" src="http://mjtrends.r.worldssl.net/images/product/<?=$row['invid']?>/<?=$row['img']?>_370x280.jpg" alt="<?=$row['alt']?>" /></a> 
			<a href="products.<?=$row['color']?>,<?=$row['type']?>,<?=$row['cat']?>" itemprop="url"><h4 itemprop="name"><?=str_replace("-"," ",$row['color'])." ".str_replace("-"," ",$row['type'])?></h4></a>
			<span>List Price $<?=$row['retail']?></span>
			<?=$row['sale']?>

			<?if($row['sale'] != ''):?>
				<img class="imgSale" alt="<?=$row['color']?> <?=$row['type']?> currently on sale" src="images/sale-star.png">
			<?endif;?>
		</div>
	<? endforeach;}?>

<?php
$cachefile = "../cache/filters/".strtolower($_GET['cat']).".html";
//$cachefile = "../cache/filters/testfilter.html";
// open the cache file "cache/home.html" for writing
$fp = fopen($cachefile, 'w');
// save the contents of output buffer to the file
fwrite($fp, ob_get_contents());
// close the file
fclose($fp);
// Send the output to the browser
ob_end_flush();
?>
