<?
session_start();

define('FRONTEND_SELF', pathinfo(__FILE__, PATHINFO_BASENAME));

define('FRONTEND_FCPATH', str_replace('logic/'.FRONTEND_SELF, '', __FILE__));

if($_GET['eid']){
	insertNews();
}

if($_GET['adid'] != ""){
	setAd($_GET['adid']);
}


if(strpos($_SERVER['HTTP_REFERER'], "youtube") == true ){
	setAd(262);
} elseif(strpos($_SERVER['HTTP_REFERER'], "facebook") == true ){
	setAd(722);
}

function conn(){
	// $host = "localhost";
	// $uname="mjtren5_user2";
	// $pass="XLgf;E0uWz_#";
	// $database="mjtren5_mjtrends";

	$host = "localhost";
	$uname="root";
	$pass="";
	$database="mjtren5_mjtrends";
	
	$connection = mysql_connect($host, $uname, $pass);
	$result =mysql_select_db($database);	
}

function mysqliConn(){
	// $host = "localhost";
	// $uname="mjtren5_user2";
	// $pass="XLgf;E0uWz_#";
	// $database="mjtren5_mjtrends";

	$host = "localhost";
	$uname="root";
	$pass="";
	$database="mjtren5_mjtrends";
	return new mysqli($host, $uname, $pass, $database);
}

class DB extends MySQLi {
	private static $instance = null;

	// private static $_host = "localhost";
	// private static $_uname = "mjtren5_user2";
	// private static $_pass = "XLgf;E0uWz_#";
	// private static $_database = "mjtren5_mjtrends";

	private static $_host = "localhost";
	private static $_uname = "root";
	private static $_pass = "";
	private static $_database = "mjtren5_mjtrends";

	private function __construct($host, $user, $password, $database){
		parent::__construct($host, $user, $password, $database);
	}

	public static function getInstance(){
		if (self::$instance == null){
			self::$instance = new self(self::$_host, self::$_uname, self::$_pass, self::$_database);
		}
		return self::$instance;
	}
}

/*
//for local

function conn(){
	$host = "localhost";
	$uname="root";
	$pass="bond007?";
	$database="mjtrends_mjtrends";
	$connection = mysql_connect($host, $uname, $pass);
	$result =mysql_select_db($database);	
}

function forumConn(){
	$host = "localhost";
	$uname="root";
	$pass="bond007?";
	$database="mjt_forums";
	$connection = mysql_connect($host, $uname, $pass);
	$result =mysql_select_db($database);	
}
*/
function cache($cachefile, $dfile){
global $cacheRes;
	$cachetime = 604800; // 1 week
	if (file_exists($cachefile) && (time() - $cachetime< filemtime($cachefile))){
	// the page has been cached from an earlier request
	// output the contents of the cache file
	$cacheRes = "$cachefile"; 
	}else{
	$cacheRes = "$dfile";
	}
}

function checkPOB($country, $address){
	if(strcasecmp($country,"US") == 0 OR strcasecmp($country,"United States") == 0){
		$address = str_replace(".", "", $address);
		$address = explode(" ",$address);
		if(strcasecmp($address[0], "POB") == 0){
			return true;
		} elseif(strcasecmp($address[0]." ".$address[1], "PO BOX") == 0){
			return true;
		} else {
			return false;
		}
	} else {
		return false;
	}
}

function setAddress(){
	global $billArr, $shipArr;

	if(!isset($_POST['shipPh3'])){
		$bphone = $_POST['shipPh'];
		$sphone = $_POST['shipPh'];
	} else {
		($_POST['billPh4'] == '') ? $bphone = "(".$_POST['billPh1'].") ".$_POST['billPh2']."-".$_POST['billPh3'] : $bphone = "(".$_POST['billPh1'].") ".$_POST['billPh2']."-".$_POST['billPh3']." x".$_POST['billPh4'];
		($_POST['shipPh4'] == '') ? $sphone = "(".$_POST['shipPh1'].") ".$_POST['shipPh2']."-".$_POST['shipPh3'] : $sphone = "(".$_POST['shipPh1'].") ".$_POST['shipPh2']."-".$_POST['shipPh3']." x".$_POST['shipPh4'];
	}
	
	$billArr = array($_POST['billfirst']." ".$_POST['billlast'], $_POST['billComp'], $_POST['billAd1'], $_POST['billAd2'], $_POST['billCity']." ".$_POST['billState'].", ".$_POST['billZip'], $_POST['billCo'], $bphone);
	$shipArr = array($_POST['shipfirst']." ".$_POST['shiplast'], $_POST['shipComp'], $_POST['shipAd1'], $_POST['shipAd2'], $_POST['shipCity']." ".$_POST['shipState'].", ".$_POST['shipZip'], $_POST['fedCo'], $sphone, $_POST['email']);
}

function insertNews(){
	$host = "localhost";
	$uname="mjtrends_hedon";
	$pass="mikeyry";
	$database="mjtrends_mjtrends";
	$connection = mysql_connect($host, $uname, $pass);
	$result =mysql_select_db($database);
	
	$nid  = mysql_real_escape_string($_GET['nid']); 
	$eid  = mysql_real_escape_string($_GET['eid']);
	$page = $_SERVER['REQUEST_URI'];
	
	$query  = "INSERT INTO newsletter_stats(nid, email, type, page, date, time) VALUES('$nid', '$eid', 'click', '$page', CURDATE(), CURTIME())";
	$result = mysql_query($query);
	$_SESSION['convo'] = mysql_insert_id();
	
	mysql_close($connection);
}

 function truncate($string, $limit, $break=" ", $pad="...") {  // return with no change if string is shorter than $limit  
	if(strlen($string) <= $limit) return $string; 
	$string = substr($string, 0, $limit); 
	if(false !== ($breakpoint = strrpos($string, $break))) { 
		$string = substr($string, 0, $breakpoint); 
	} 
	return $string . $pad; 
}

function setAd($ad){
	conn();

	$ad = mysql_real_escape_string($ad);
	$query = "INSERT INTO ad_stats(adid, date) VALUES($ad, NOW())";
	$result = mysql_query($query);
	$id 	= mysql_insert_id();
	
	setcookie("adCookie", $id, time()+3628800); /* Expires in 6 weeks */ 
}

function RandomString($length) {

    $keys = array_merge(range(0,9), range('a', 'z'));

    for($i=0; $i < $length; $i++) {

        $key .= $keys[array_rand($keys)];

    }

    return $key;

}

function catTest(){
	if(isset($_COOKIE['catTest']) ){
		$cookieArray = explode("+", $_COOKIE['catTest']); 
		$variation = $cookieArray[0];

		if($variation == 61){
			header("Location: http://www.MJTrends.com/category2.php?cat=".$_GET['cat']."", true, 307);
   			die();
		}
	} else {
		include_once('mvt.php');
		$variation = rand(60,61);//51 for new page, 50 for old
		setcookie("catTest", $variation."+0", time()+2592000 );// 30 days
		conn();
		$mvt = new MVT;
		$mvt->saveImpression($variation, 'catTest');

		if($variation == 61){
			header("Location: http://www.MJTrends.com/category2.php?cat=".$_GET['cat']."", true, 307);
   			die();
		}
	}

}

function setBounce(){
	if($_SERVER['HTTP_REFERER'] == 'http://www.mjtrends.com/' && isset($_COOKIE['bounce'])){
		$cookieArray = explode("+", $_COOKIE['newsTest']); 
		$variation = $cookieArray[0];

		setcookie ("bounce", "", time() - 3600);//delete cookie

		include_once('mvt.php');
		conn();
		$mvt = new MVT;
		$mvt->saveClick($variation);
	}
}

function newsTest(){
	if(!isset($_COOKIE['usr']) && !isset($_SESSION['showMod']) ){

		$_SESSION['showMod'] = true;
		
		include('mvt.php');
		conn();
		$mvt = new MVT;

		if(isset($_COOKIE['newsTest']) ){
			$cookieArray = explode("+", $_COOKIE['newsTest']); 
			$variation = $cookieArray[0];
			$signedup = $cookieArray[1];

			if($variation == 71 && $signedup == '0'){
				$mvt->saveImpression($variation, 'newsTest');
				setcookie("bounce", '1', time()+3600 );// 1 hour
				return true;
			} else {
				if($signedup == '0'){
					$mvt->saveImpression($variation, 'newsTest');
					setcookie("bounce", '1', time()+3600 );// 1 hour
				}
				return false;
			}
		} else {
			$variation = rand(70,71);//71 for modal, 70 for no modal
			setcookie("newsTest", $variation."+0", time()+2592000 );// 30 days
			setcookie("bounce", '1', time()+3600 );// 1 hour
			$mvt->saveImpression($variation, 'newsTest');

			if($variation == 71){
				return true;
			} else {
				return false;
			}
		}

	}

}

function ordinal($number){
	$ends = array('th','st','nd','rd','th','th','th','th','th','th');
	
	if (($number %100) >= 11 && ($number%100) <= 13){
	   $abbreviation = $number. 'th';
	} else {
 	   $abbreviation = $number. $ends[$number % 10];
	}

	return $abbreviation;
}

function resizeImage($width, $height, $target_width, $target_height, $suffix, $target, $filename, $savePath, $imgType){

	$target_ratio  = $target_width / $target_height;
	$img_ratio = $width / $height;

	if ($target_ratio > $img_ratio) {
		$new_height = $target_height;
		$new_width = $img_ratio * $target_height;
	} else {
		$new_height = $target_width / $img_ratio;
		$new_width = $target_width;
	}

	if ($new_height > $target_height) {
		$new_height = $target_height;
	}
	if ($new_width > $target_width) {
		$new_height = $target_width;
	}

	if ($imgType == 1) {
        $imgt = "ImageGIF";
        $imgcreatefrom = "ImageCreateFromGIF";
    } elseif($imgType == 2){
        $imgt = "ImageJPEG";
        $imgcreatefrom = "ImageCreateFromJPEG";
    } elseif($imgType == 3){
        $imgt = "ImagePNG";
        $imgcreatefrom = "ImageCreateFromPNG";
    }
	
	$new_img = ImageCreateTrueColor($target_width, $target_height);
	imagefilledrectangle($new_img, 0, 0, $target_width-1, $target_height-1, 0);	// Fill the image black
	
	$image = $imgcreatefrom($target); 
	imagecopyresampled($new_img, $image, ($target_width-$new_width)/2, ($target_height-$new_height)/2, 0, 0, $new_width, $new_height, $width, $height);

    $urlArray = explode(".", $filename);//EG: filename.jpg
    $save_path = $savePath.$urlArray[0].$suffix.".".$urlArray[1];

	if($imgType == 2 || $imgType == 3){
		$imgt($new_img, $save_path, 100); 
	} else {
		$imgt($new_img, $save_path); 
	}
}

function pagination($link, $pageNum, $totalRecords, $pageLimit){
	$pagNav = '';
	$num_of_pages = ceil($totalRecords / $pageLimit)-1; 
	if($num_of_pages > 1){
		$upperLimit = (($num_of_pages - $pageNum) > $pageLimit) ? $pageNum + $pageLimit : $num_of_pages;
		$lowerLimit = $upperLimit - $pageLimit;
		if($lowerLimit <= 0) $lowerLimit = 1;

		$pagNav .= '<div class="paginWrap"><div class="pagination">There are '.$num_of_pages.' pages.</div>';
		$pagNav .= '<div class="numbers">';

		if($pageNum != 1){ 
			$pagePrev = $pageNum-1; 
			$pagNav .=  '<a href="'.$link.$pagePrev.'">Prev</a>'; 
		} else { 
			$pagNav .= '<span>Prev</span>'; 
		}

		for($i = $lowerLimit; $i <= $upperLimit; $i++ ){
			if($i == $pageNum){ 
				$pagNav .= '<span class="cur">'.$i.'</span>'; 
			} else { 
				$pagNav .=  '<a class="pNum" href="'.$link.$i.'">'.$i.'</a>'; 
			} 
		}

		if($pageNum != $num_of_pages){ 
			$pageNext = $pageNum+1; 
			$pagNav .= '<a href="'.$link.$pageNext.'">Next</a>'; 
		}else{ 
			$pagNav .="<span>Next</span>"; 
		} 

		$pagNav .= '</div></div>';
	}
	return $pagNav;
}

function CopyFileToFTP($fileArray, $remote_dir = '', $create = false){
	include 'global_ftp.php';
	
	if (!$remote_dir) {
		$remote_dir = $ftp_prod_cache_path;
	}

	$conn_id = ftp_connect($ftp_server); // set up basic connection
	$login_result = ftp_login($conn_id, $ftp_user_name, $ftp_user_pass);

	$i = 0;
	ftp_pasv($conn_id, true);
	

	foreach ($fileArray as $src_filename) {
		$remote_filename = pathinfo($src_filename, PATHINFO_BASENAME);

	
		if (!file_exists($src_filename)) {
			return false;
		}
		
		if ($create) {
			@ftp_mkdir($conn_id, $remote_dir);
		}

		$res = ftp_put($conn_id, $remote_dir . $remote_filename, $src_filename, FTP_BINARY);
		
		$i++;
	}

	ftp_close($conn_id);

	return $res !== FALSE;
}

setBounce();
