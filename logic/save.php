<?php
include ("../logic/global.php");
include ("../logic/user.class.php");
include ("logic/forum.class.php");
include ("../logic/rewards.class.php");
conn();
$user = new User;
$forum = new Forum;
$reward = new Reward;

$forum->savePost();
$forum->extractMedia();
$forum->createThumb();

$points = $_SESSION['user']['points'] + $forum->points;
eval('$rewardArray = array('.$_SESSION["user"]["reward"].');');
$reward->setReward($points, $rewardArray, $_SESSION['user']['username'], $_SESSION['user']['email']);

$user->setPoints($forum->points);
$user->getUser();//reset the session with updated reward / points
if($forum->newThread == false){
	$forum->emailNotification((int)$_POST['thread_num'], $_POST['topic']);		
} else {
	$forum->emailNewPost($_POST['topic'], stripslashes($_POST['redactor']));
}
?>

<link rel="stylesheet" type="text/css" href="http://mjtrends.r.worldssl.net/jquery/colorbox/colorbox.css" />
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
<!-- fallback to local file -->
<script> window.jQuery || document.write('<script src="http://mjtrends.r.worldssl.net/jquery/jquery-1.9.1.min.js"><script>')</script> 
<script type="text/javascript" src="http://mjtrends.r.worldssl.net/jquery/colorbox/jquery.colorbox-min.js"></script>	

<link rel="stylesheet" type="text/css" href="http://mjtrends.r.worldssl.net/forum/css/forum-min.css" />

<script type="text/javascript">
	parent.reload = true;
	
	$(document).ready(function() {
		parent.$.colorbox.resize({
			width: 485,
			height: 315
		});
		
		$("#close").click(function(){
			parent.$.colorbox.close();
		});
	});
</script>

<div class="saved">
	<h3>Thank you for submitting your post.</h3>
	<h4>Your account has been credited: <?=$forum->points?> points.</h4>
	<p>Close this window to view your post.</p>

	<button class="rButton" id="close">Close</button>
</div>