<?
include_once 'logic/securimage.php';
$securimage = new Securimage();

if (!$_POST['email']){
	$on = "display:block";
	$off = "display:none";
	$captcha = "display:none";
	
// } elseif($securimage->check($_POST['captcha_code']) == false) {
// 	$on = "display:block";
// 	$off = "display:none";
// 	$captcha = "display:block";
	
} else {
	$on = "display:none";
	$off = "display:block";
	$captcha = "display:none";
	
	$address = mysql_real_escape_string(strip_tags($_POST['email']));
	$ent1 = mysql_real_escape_string(strip_tags($_POST['ent1']));
	$ent2 = mysql_real_escape_string(strip_tags($_POST['ent2']));
	$ent3 = mysql_real_escape_string(strip_tags($_POST['ent3']));
	$ent4 = mysql_real_escape_string(strip_tags($_POST['ent4']));
	$ent5 = mysql_real_escape_string(strip_tags($_POST['ent5']));
	$ent6 = mysql_real_escape_string(strip_tags($_POST['ent6']));
	$other = mysql_real_escape_string(strip_tags($_POST['other']));
	
	$query1 = "Insert INTO email (address, ent1, ent2, ent3, ent4, ent5, ent6, other, date) VALUES ('$address', '$ent1', '$ent2', '$ent3', '$ent4', '$ent5', '$ent6', '$other', CURDATE())"; 
	$result1 = mysql_query($query1);
}

?>