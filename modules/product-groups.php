<?php
//include('../logic/global_ftp.php');
include('../logic/global.php');
echo "product-groups.php";
//loop through all items that have traits
$mysqli = mysqliConn();
$sql = "SELECT inven_traits.*, cat, type, color FROM inven_traits LEFT JOIN inven_mj ON inven_traits.invid = inven_mj.invid WHERE length IS NOT NULL ORDER BY cat, type, color";
$result = $mysqli->query($sql);

//call product_groups_ajax to build ajax cache files
$fileArray =  array();

$server_url = 'http://localhost:8888/stage';
while ($row = $result->fetch_assoc()) {
	echo $server_url."/modules/product_groups_ajax.php?invid=".$row['invid']."&type=".$row['type']."&color".$row['color']."=&cat=".$row['cat']."<br>";
	file_get_contents($server_url."/modules/product_groups_ajax.php?invid=".$row['invid']."&type=".$row['type']."&color".$row['color']."=&cat=".$row['cat']);
	$fileArray[] = realpath("../cache/prod/ajax/".$row['invid'].".js");
}

//FTP files to CDN
// if ($server_url != 'http://mjtrends.loc') {
// 	CopyFileToFTP($fileArray, $remote_dir = 'www/cache/prod/ajax/');
// }