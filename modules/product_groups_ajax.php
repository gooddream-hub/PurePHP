<?php
include ("../logic/global.php");
include('../logic/product.class.php');

$product = new Product;
$product->mysqli = mysqliConn();
$product->getItemDetails();
$product->getDiffTutorials();
$product->getDiffPosts();
$product->getDiffImages();
$product->getDiffVideos();

$features = "";
foreach($product->details['features'] as $key=>$val){
	$features .= "<li><b>".$key.": </b>".$val."</li>";
}

$tutorials = "";
if($product->tutorials != ""){
	foreach($product->diffTutorials as $row){
		$tutorials .= '<a href="'.$row["vid_url"].'" title="'.$row["title"].'" class="vidPop"><div><img src="http://mjtrends.r.worldssl.net/images/video/'.$row["thumb"].'_115x87.jpg" alt="'.$row["title"].'"><p class="title">'.$row["title"].'</p></div></a>';
	}
}

$posts = "";
if($product->posts != ""){
	foreach($product->posts as $row){
		$posts .= '<h4><a href="/forum/thread/'.$row["thread_num"].'/'.str_replace(' ','-',$row["topic"]).'">'.$row["topic"].'</a></h4><p class="meta">Posted on: '.date("M jS Y g:i:s a",strtotime($row["date_time"])).'</p><p>'.truncate(strip_tags($row["discussion"]), 70).'</p>';
	}
}

$images = "";
if($product->details['img'] != ""){
	foreach($product->details['img'] as $row){
		if($i == 0){
			$images .= '<div id="primaryImg">';
			$images .= 		'<a class="imgPop" href="images/product/'.$product->details["invid"].'/'.$row["path"].'_924x699.jpg" title="'.$row["alt"].'">';
			$images .= 			'<img src="http://mjtrends.r.worldssl.net/images/product/'.$product->details["invid"].'/'.$row["path"].'_370x280.jpg" width="370" height="280" alt="'.$row["alt"].'" itemprop="image">';
			$images .= 		'</a>';
			$images .=	'</div>';								
		};

		$i++;

		$images .= '<a class="imgPop" href="images/product/'.$product->details["invid"].'/'.$row["path"].'_924x699.jpg" title="'.$row["alt"].'">';
		$images .=		'<img class="imgThumb" src="http://mjtrends.r.worldssl.net/images/product/'.$product->details["invid"].'/'.$row["path"].'_115x87.jpg" width="115" height="87" alt="'.$row["alt"].'" thumbnail image.">';
		$images .=	'</a>';
	}
}

$videos = "";
if($product->details['video'] != ""){
	foreach($product->details['video'] as $row){
		$videos .= '<img class="vidThumb" src="http://mjtrends.r.worldssl.net/images/prod/'.$row["thumb"].'_115x87.jpg" width="115" height="87" alt="'.$row["alt"].'">';
	}
}

$json_array = array(
	"type"		=> $product->details['type'],
	"title"		=> $product->details['title'],
	"retail"	=> $product->details['retail'],
	"saleprice" => $product->details['saleprice'],
	"features" 	=> $features,
	"description" => $product->details['descr'],
	"tutorials"	=> $tutorials,
	"posts"		=> $posts,
	"images"	=> $images,
	"videos"	=> $videos
	);
ob_start();
?>

<?=json_encode($json_array);?>

<?
$cachefile = "../cache/prod/ajax/".$product->details['invid'].".js";
$cache_content = str_replace(array("\n", "\t", "\r"), '', ob_get_contents());//remove carriage returns and spaces

// open the cache file "cache/home.html" for writing
$fp = fopen($cachefile, 'w');
// save the contents of output buffer to the file
fwrite($fp, $cache_content);
// close the file
fclose($fp);
?>
