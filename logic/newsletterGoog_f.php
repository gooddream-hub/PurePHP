<?
include ("global.php");
conn();

if (isset($_POST['email'])){

	$email = mysql_real_escape_string(strip_tags($_POST['email']));
	
	if(!validEmail($email)){
		echo json_encode(array("status"=>"fail","error"=>"your email address does not appear to be valid.<br>  <a href=''>Refresh the page to try again.</a>"));
	} else {
		
		$query1 = "Insert INTO email (address, other, date) VALUES ('$email', 'Google', CURDATE())"; 
		$result1 = mysql_query($query1);
		
		if(mysql_affected_rows() > 0){
			echo json_encode(array("status"=>"success"));
			sendCoupon();
		} else {
			echo json_encode(array("status"=>"fail","error"=>"it looks you are already signed up for our newsletter!"));
		}
	}
}

function sendCoupon(){
	//update db with new code
	//make it good for 30 days
   $code = "GG_".RandomString(11);
	$query1 = "Insert INTO coupon(code, ckey, start_date, end_date, active, sales_rep, offer_date) VALUES('$code', 5, CURDATE, DATE_ADD(CURDATE(),INTERVAL 30 DAY), 1, 'Google', CURDATE()) "; 
   $result1 = mysql_query($query1);

	//send code to email
   $to = "<".$_POST['email'].">";

   // subject
   $subject = 'MJTrends Coupon for 20% off';

   $message = "Thanks for signing up for our newsletter.  You\'ve just joined a community of artistic geniuses whose palette is fabric! </b><br><br>Below is a coupon code for 20% off your next purchase of non-clearance items.  It will expire in 30 days.  Your coupon code is: <br>".$code."<br><br>Customer Service<br><a href='mailto:sales@MJTrends.com'>sales@MJTrends.com</a><br><a href='http://www.MJTrends.com'>www.MJTrends.com</a>";

   // To send HTML mail, the Content-type header must be set
   $headers  = 'MIME-Version: 1.0' . "\r\n";
   $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
   $headers .= 'From: MJTrends <sales@mjtrends.com>' . "\r\n";
   $headers .= 'Bcc: Mike <mharris@mjtrends.com>' . "\r\n";

   // Mail it
   mail($to, $subject, $message, $headers);

}

function RandomString($length) {

    $keys = array_merge(range(0,9), range('a', 'z'));

    for($i=0; $i < $length; $i++) {

        $key .= $keys[array_rand($keys)];

    }

    return $key;

}

function validEmail($email)
{
   $isValid = true;
   $atIndex = strrpos($email, "@");
   if (is_bool($atIndex) && !$atIndex)
   {
      $isValid = false;
   }
   else
   {
      $domain = substr($email, $atIndex+1);
      $local = substr($email, 0, $atIndex);
      $localLen = strlen($local);
      $domainLen = strlen($domain);
      if ($localLen < 1 || $localLen > 64)
      {
         // local part length exceeded
         $isValid = false;
      }
      else if ($domainLen < 1 || $domainLen > 255)
      {
         // domain part length exceeded
         $isValid = false;
      }
      else if ($local[0] == '.' || $local[$localLen-1] == '.')
      {
         // local part starts or ends with '.'
         $isValid = false;
      }
      else if (preg_match('/\\.\\./', $local))
      {
         // local part has two consecutive dots
         $isValid = false;
      }
      else if (!preg_match('/^[A-Za-z0-9\\-\\.]+$/', $domain))
      {
         // character not valid in domain part
         $isValid = false;
      }
      else if (preg_match('/\\.\\./', $domain))
      {
         // domain part has two consecutive dots
         $isValid = false;
      }
      else if
(!preg_match('/^(\\\\.|[A-Za-z0-9!#%&`_=\\/$\'*+?^{}|~.-])+$/',
                 str_replace("\\\\","",$local)))
      {
         // character not valid in local part unless 
         // local part is quoted
         if (!preg_match('/^"(\\\\"|[^"])+"$/',
             str_replace("\\\\","",$local)))
         {
            $isValid = false;
         }
      }
      if ($isValid && !(checkdnsrr($domain,"MX") || checkdnsrr($domain,"A")))
      {
         // domain not found in DNS
         $isValid = false;
      }
   }
   return $isValid;
}

?>