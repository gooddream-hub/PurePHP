<?php
include ("../logic/global.php");
include ("../logic/poll_f.php");

conn();
$file_id = 0;
if (!empty($_GET['poll_id'])) {
	$file_id = $_GET['poll_id'];
}
$polls = getPoll($file_id);

//echo '<pre>';
//print_r($polls);
//die;
//
ob_start();
if ($polls) {
?>
<div class="poll_block">
<?php
foreach ($polls as $poll_id => $poll) {
?>
	<div class="poll-title"><?php echo $poll['name']; ?></div>
<?php
	foreach ($poll['questions'] as $question_id => $question) {
		$path = 'poll_'.$poll_id.'_'.$question['type'].'.php';
		if (file_exists($path)) {
			include ($path);
		}
		else {
			include ('poll_'.$question['type'].'.php');
		}
	}
}
?>
	<div class="row">
		<input type="button" name="save_poll" value="Save" id="save_poll" class="save-poll">
	</div>
	<input type="hidden" name="poll_id" value="<?php echo $poll_id;?>" id="poll_id">
</div>

<?php
}
$cachefile = "../cache/poll/poll.html";
// open the cache file "cache/home.html" for writing
$fp = fopen($cachefile, 'w');
// save the contents of output buffer to the file
fwrite($fp, ob_get_contents());
// close the file
fclose($fp);
// Send the output to the browser
ob_end_flush();
?>