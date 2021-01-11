<?php

include ("../logic/global.php");
include ("../logic/global_ftp.php");
include ("../logic/grid_f.php");
include ("../config/config.php");
conn();
getProds();

$thumb_width  = 26;
$thumb_height = 20;

$type     = mysql_real_escape_string(strip_tags($_GET['cat']));

$type = preg_replace( '/[^a-z0-9]+/', '-', strtolower( $type ) );



$cdn_images = [];
$ftp_sprites   = 'images/sprites/';
$local_sprites = '../images/sprites/';



@mkdir($local_sprites);
$sprite_name = $type.'_'.$thumb_width.'x'.$thumb_height.'.jpg';
$sprite_relative_path = $local_sprites . $sprite_name;
$sprite_absolute_path = FRONTEND_FCPATH . 'images/sprites/' . $sprite_name;



$sprite_width  = count($prod)*$thumb_width;
$sprite_height = $thumb_height;




$sprite = imagecreatetruecolor($sprite_width, $sprite_height);
$white = imagecolorallocate($sprite, 255, 255, 255);
imagefilledrectangle($sprite, 0, 0, $sprite_width, $sprite_height, $white);

$sprite_x = 0;
$sprite_y = 0;
foreach ($prod as $product) {
	if (!empty($product['img_name'])) {
		$thumb = imagecreatefromjpeg('http:'.$config->CDN.'images/product/'.$product['invid'].'/'.$product['img_name'].'_'.$thumb_width.'x'.$thumb_height.'.jpg');

		if ($thumb) {
			imagecopyresampled($sprite, $thumb, $sprite_x, $sprite_y, 0, 0, $thumb_width, $thumb_height, $thumb_width, $thumb_height);
		} else {
			create_thumbnail($product, $config);
		}


		
		$sprite_x += $thumb_width;
	}
}


imagejpeg($sprite, $sprite_relative_path);
$cdn_images []= $sprite_absolute_path;
CopyFileToFTP($cdn_images, $ftp_sprites, true);

echo '<img src="'.$sprite_relative_path.'" />';


function create_thumbnail ($product, $config) {//use ($sprite, $sprite_x, $sprite_y, $thumb_width, $thumb_height, $cdn_images) {
	$full_image = imagecreatefromjpeg('http:'.$config->CDN.'images/product/'.$product['invid'].'/'.$product['img_name'].'.jpg');
	if ($full_image) {
		$new_thumb = resize_image($full_image, $thumb_width, $thumb_height);
		imagecopyresampled($sprite, $new_thumb, $sprite_x, $sprite_y, 0, 0, $thumb_width, $thumb_height, $thumb_width, $thumb_height);
		
		$path = 'images/product/'.$product['invid'].'/'.$product['img_name'].'_'.$thumb_width.'x'.$thumb_height.'.jpg';

		imagejpeg($new_thumb, 'http:'.$config->CDN . $path);

		$cdn_images []=  FRONTEND_FCPATH . $path; 
	}
}

function resize_image($original_image, $x, $y) {
	$thumb = imagecreatetruecolor($x, $y);

	$ratio_thumb=$x/$y; // ratio thumb

	list($xx, $yy) = getimagesize($image); // original size
	$ratio_original=$xx/$yy; // ratio original

	if ($ratio_original>=$ratio_thumb) {
	    $yo=$yy; 
	    $xo=ceil(($yo*$x)/$y);
	    $xo_ini=ceil(($xx-$xo)/2);
	    $xy_ini=0;
	} else {
	    $xo=$xx; 
	    $yo=ceil(($xo*$y)/$x);
	    $xy_ini=ceil(($yy-$yo)/2);
	    $xo_ini=0;
	}

	imagecopyresampled($thumb, $source, 0, 0, $xo_ini, $xy_ini, $x, $y, $xo, $yo);
	return $thumb;
}