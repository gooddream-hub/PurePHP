<?php
conn();

$invid = mysql_real_escape_string(strip_tags($_POST['invid']));
$name = mysql_real_escape_string(strip_tags($_POST['name']));
$title = mysql_real_escape_string(strip_tags($_POST['title']));
$comm = $_POST['comm'];
$overall = mysql_real_escape_string(strip_tags($_POST['overall']));
$quality = mysql_real_escape_string(strip_tags($_POST['quality']));
$value = mysql_real_escape_string(strip_tags($_POST['value']));
$purch = mysql_real_escape_string(strip_tags($_POST['purch']));

$query = "INSERT INTO review (invid, overall, quality, value, name, title, comm, purch, date1) values ('$invid', '$overall', '$quality','$value','$name','$title','$comm','$purch', DATE_FORMAT(now(),'%Y-%m-%d'))";
$result = mysql_query($query);

$to = 'mharris@mjtrends.com';
$subject = $invid.' reviewed';
$body = 'A review has been submitted for product number '.$invid;

mail($to, $subject, $body);
?>