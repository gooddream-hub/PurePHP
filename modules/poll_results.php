<?
include ("../logic/global.php");
include ("../logic/poll_f.php");

conn();
$file_id = 0;
if (!empty($_GET['poll_id'])) {
	$file_id = $_GET['poll_id'];
}
$polls = getResults($file_id);

//echo '<pre>';
//print_r($polls);
//die;
//
ob_start();
?>
<div class="poll_block">
<?php
foreach ($polls as $poll_id => $poll) {
?>
	<div class="poll-title"><?php echo $poll['name']; ?></div>
	<div class="row results">Result:</div>
<?php
	foreach ($poll['questions'] as $question_id => $question) {
		$path = 'poll_results_'.$question_id.'.php';
		if (file_exists($path)) {
			include ($path);
		}
		else {
			include ('poll_results_question.php');
		}
		if ($poll_id == 1 && $question_id == 1) {
			break;
		}
	}
}
?>
</div>

<?php
$cachefile = "../cache/poll/poll_results.html";
// open the cache file "cache/home.html" for writing
$fp = fopen($cachefile, 'w');
// save the contents of output buffer to the file
fwrite($fp, ob_get_contents());
// close the file
fclose($fp);
// Send the output to the browser
ob_end_flush();
?>
