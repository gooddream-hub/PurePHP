<?php

include ("../logic/global.php");
include ("../logic/global_ftp.php");
include ("../logic/product.class.php");

$product = new Product;
$product->mysqli = mysqliConn();
$product->getItemDetails();
$product->getTutorials();
$product->getPosts();

ob_start();
include_once 'product_page.php';
$cache_content = ob_get_contents();
//ob_end_clean();

$files = array();
$tmp = explode('-', $product->details['color'], 2);
$path = $product->details['cat'].'-'.str_replace(' ', '', $product->details['type']).'-'.str_replace(' ', '', $product->details['color']);
$cachefile = "../cache/prod/ajax/".strtolower($path).".html";

// open the cache file "cache/home.html" for writing
$fp = fopen($cachefile, 'w');
// save the contents of output buffer to the file
fwrite($fp, $cache_content);
// close the file
fclose($fp);

$files[] = realpath($cachefile);

// Send the output to the browser
ob_end_flush();
CopyFileToFTP($files, $ftp_prod_ajax_cache_path);