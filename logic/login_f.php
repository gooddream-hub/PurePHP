<?php
function init(){
	if($_POST['email']){
		signIn();
	} elseif($_POST['lostemail']){
		sendPWD();
	} elseif($_POST['first']){
		signUp();
	}
	showErrors();
}

function sendPWD(){

	$email 	= mysql_real_escape_string($_POST['lostemail']);
	$query 	= "SELECT email FROM users WHERE email = '$email'";
	$result = mysql_query($query);
	$row 	= mysql_fetch_assoc($result);
	
	if($row['email'] == ""){
		echo "error error error error ";
		echo"<script type=\"text/javascript\">showHideList('lost,email_err2','signin,register');</script>";
	} else{
		//send out email, show signin screen w/ message that email has been sent
		$to = $row['email'];
		$subject = 'MJTrends password request';
		$body = '<html><body>Your requested password for your www.MJTrends.com account is:<br> '.$row['pwd'].'  <p>You may log into your account by visiting:<br><a href="http://www.mjtrends.com/login.php">http://www.mjtrends.com/login.php</a></p></html></body>';
		
		mail($to, 'accounts@mjtrends.com', $subject, $body);
		echo"<script type=\"text/javascript\">showHideList('signin,email_sent','lost');</script>";
	}	
}

function showErrors(){
	if(isset($_GET['register'])) echo "<script type=\"text/javascript\">showHideList('signup,email_taken','signin,register');</script>";
	if(isset($_GET['signin'])) echo "<script type=\"text/javascript\">showHideList('password_err','');</script>";
	if(isset($_GET['captcha'])) echo "<script type=\"text/javascript\">showHideList('signup','signin,register');</script>";
}