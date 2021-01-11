<?

if(isset($_POST['reason'])){
	$reason = mysql_real_escape_string($_POST['reason']); 
	$email = mysql_real_escape_string($_POST['email']);

	$query = "UPDATE email_users SET unsub_reason = '$reason' WHERE email = '$email'";
	$resul = mysql_query($query);	
}

function removeUser(){
	$email = mysql_real_escape_string($_GET['eid']);
	$query = "UPDATE email_users SET unsub = NOW(),status = 'inactive' WHERE email = '$email'";
	$resul = mysql_query($query);
	$rc = mysql_affected_rows();

	if($rc > 0){
		$query = "UPDATE email_stats SET unsubs = unsubs+1,status = 'inactive' WHERE email = '$email'";
		$resul = mysql_query($query);	

		$query = "SELECT first_name FROM email_users WHERE email = '$email' ";
		$resul = mysql_query($query);
		$row = mysql_fetch_row($resul);

		if($row[0] == ""){
			$row[0] = "friend";
		}

		return $row[0];
	} else{
		return "";
	}

}
