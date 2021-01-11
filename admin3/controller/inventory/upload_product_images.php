<?php
$invid= $_GET['invid'];
$session_id= $_GET['session_id'];
$DocumentRoot= $_GET['DocumentRoot'];  // TODO Must be changed for Production Server
include('../../common/constants.php');

// DebToFile('$invid::'.$invid, true);
// DebToFile('$session_id::'.$session_id, false);

// Get a file name
if (isset($_REQUEST["name"])) {
	$fileName = $_REQUEST["name"];
} elseif (!empty($_FILES)) {
	$fileName = $_FILES["file"]["name"];
} else {
	$fileName = uniqid("file_");
}
set_time_limit(500);

if (empty($_FILES) || $_FILES["file"]["error"]) {
	die('{"OK": 0}');
}
$fileName = $_FILES["file"]["name"];

if ( strtolower($invid) == "new" ) {
	if ( !file_exists( $DocumentRoot . '/images/product/temp' ) ) {
		// DebToFile( 'Create::'.$DocumentRoot . '/uploads/temp_products', false);
		mkdir( $DocumentRoot . '/images/product/temp' );
	}

	if ( !file_exists( $DocumentRoot . '/images/product/temp/product-' . $session_id ) ) {
		// DebToFile( 'Create::' . $DocumentRoot . '/uploads/temp_products/product-' . $session_id, false);
		mkdir( $DocumentRoot . '/images/product/temp/product-' . $session_id );
	}
	move_uploaded_file( $_FILES["file"]["tmp_name"], $DocumentRoot . "/images/product/temp/product-" . $session_id. DIRECTORY_SEPARATOR . $fileName );

} else {

	if ( !file_exists( $DocumentRoot . '/images/product' ) ) {
		mkdir( $DocumentRoot . '/images/product' );
	}
	if ( !file_exists( $DocumentRoot . '/images/product/' . $invid ) ) {
		mkdir( $DocumentRoot . '/images/product/' . $invid );
	}

	move_uploaded_file( $_FILES["file"]["tmp_name"], $DocumentRoot . "/images/product/" . $invid. DIRECTORY_SEPARATOR . $fileName );

}


function DebToFile($contents, $IsClearText= true, $FileName= '') {
	try {
		if (empty($FileName))
			$FileName = '/home/mjtrends/public_html/admin/logging_deb.txt';
		$fd = fopen($FileName, ( $IsClearText ? "w+" : "a+"));
		fwrite($fd, $contents . chr(13));
		fclose($fd);
		return true;
	} catch (Exception $lException) {
		return false;
	}
}

die('{"OK": 1}');
?>