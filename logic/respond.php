<?
session_start();

if(!isset($_SESSION['user'])){
	$thread = $_GET['thread'];
	header( 'Location: ../authenticate.php?thread='.$thread ) ;
}

include ("../logic/global.php");
include ("../logic/user.class.php");
include ("logic/forum.class.php");
conn();


$user = new User;
$user->getUser();

$thread = (int)$_GET['thread'];
$forum = new Forum;
?>

<!DOCTYPE html>
<head>
	<link rel="stylesheet" type="text/css" href="http://mjtrends.r.worldssl.net/forum/css/forum-min.css" />

	<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
	<!-- fallback to local file -->
	<script> window.jQuery || document.write('<script src="http://mjtrends.r.worldssl.net/jquery/jquery-1.9.1.min.js"><script>')</script> 

	<link rel="stylesheet" href="http://mjtrends.r.worldssl.net/forum/redactor/redactor.min.css" />
	<script type="text/javascript" src="http://mjtrends.r.worldssl.net/forum/redactor/redactor.min.js"></script>
	<script type="text/javascript" src="http://mjtrends.r.worldssl.net/forum/logic/functions-min.js"></script>
</head>

<body>
	<div class="respondMod">
		<?$forum->getThread($thread);?>
		<form method="post" action="save.php">

			<?if($forum->threads[$thread]['thread']['topic'] != ""):?>
				<h4>Topic: <i><?=$forum->threads[$thread]['thread']['topic'];?></i></h4>
				<input type="hidden" value="<?=$forum->threads[$thread]['thread']['topic'];?>" name="topic">
				<input type="hidden" value="<?=$forum->threads[$thread]['thread']['forum_type'];?>" name="forumType">
			<?else:?>
				<div class="rTopic">
					<label for="topic">Topic:</label>
					<input type="text" name="topic" maxlength="65" id="topic">
					<br><br>
					<label for="forumType">Category:</label>
					<select name="forumType" id="forumType">
						<option value="">Select:</option>
						<option value="latex">Latex Sheeting</option>
						<option value="vinyl">Vinyl fabrics</option>
					</select>
				</div>
			<?endif;?>

			<div class="left">
				<img src="http://www.mjtrends.com/images/avatars/<?=$_SESSION['user']['avatar']?>-96x96.jpg" width="96" height="96">
			</div>

			<div class="right">
				<div id="redactor"></div>
			</div>

			<script type="text/javascript">
				$('#redactor').redactor({
					buttons: ['image', '|', 'video', '|', 'link', '|', 'unorderedlist', 'bold', '|', 'underline'],
					imageUpload: 'redactor/image_upload.php'
				});
			</script>
			<input type="hidden" value="<?=$forum->threads[$thread]['thread']['thread_num']?>" name="thread_num">
			<input type="submit" value="Submit Post" class="rButton" id="postSubmit">
		</form>
	</div>
</body>

<script type="text/javascript">
	$(document).ready(function() {
		$("#postSubmit").click(function(){
			return validatePost();
		});
	});

	if(parent.confirm == "true"){
		$('.respondMod').prepend('<div class="error">Thank you for confirming your account.  Please continue with your post :)</div>');
	}
</script>