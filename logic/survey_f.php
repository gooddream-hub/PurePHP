<?php
include ("global.php");
conn();

$survey = new Survey;
$survey->validate();

if($survey->status == 'success'){
	$survey->save();
	$survey->createCoupon();
	$survey->sendCoupon();
}

$survey->returnMsg();

class Survey{
	public $custid;
	public $status;
	public $msg;
	public $email;
	public $order_date;
	public $coupon_code;

	function validate(){
		$this->custid = (int)$_POST['custid'];

		$sql = "SELECT custid, email, order_date FROM custinfo WHERE custid = $this->custid ";
		$result = mysql_query($sql);
		$row = mysql_fetch_row($result);

		if($row[0] == ''){
			$this->status = 'error';
			$this->msg = 'Incorrect invoice id.';
		} else {
			$this->email = $row[1];
			$this->order_date = $row[2];

			$sql = "SELECT custid FROM coupon WHERE custid = $this->custid ";		
		 	$result = mysql_query($sql);
			$row = mysql_fetch_row($result);

			if($row[0] != ''){
				$this->status = 'error';
				$this->msg = 'Submission has already been received and a coupon emailed to: '.$this->email;
			} else {
				$this->status = "success";
			}
		}
	}

	function save(){
		$q1 = mysql_real_escape_string(strip_tags($_POST['improve']));
		$q2 = mysql_real_escape_string(strip_tags($_POST['products']));
		$q3 = mysql_real_escape_string(strip_tags($_POST['use']));

		$sql = "INSERT INTO survey(custid, answer1, answer2, answer3, date) VALUES($this->custid, '$q1', '$q2', '$q3', CURDATE()) ";
		$result = mysql_query($sql);
	}

	function createCoupon(){
  		$this->coupon_code = "SV_".RandomString(11);
  		$source = mysql_real_escape_string(strip_tags($_POST['source']));

		$sql = "INSERT INTO coupon(code, ckey, start_date, end_date, custid, active, sales_rep, customer) VALUES('$this->coupon_code', 0, CURDATE(), DATE_ADD('$this->order_date',INTERVAL 90 DAY), $this->custid, 1, '$source', '$this->email') ";
		$result = mysql_query($sql);
	}

	function sendCoupon(){
		//send code to email
	   $to = $this->email;

	   // subject
	   $subject = 'MJTrends Coupon for 10% off';

	   $this->msg = "You = awesome. Us = grateful. <br><br> You can't see us, but right now we're doing the happy dance.  But seriously, thank you for taking the time to fill out our survey.  Our goal is to help you create, and your feedback is helping us serve you better.  <br><br>Below is a coupon code for 10% off your next purchase of non-clearance items.  It will expire in 90 days from your previous purchase.  Your coupon code is: <br><br><b><span class='coupon'>".$this->coupon_code."</span></b><br><br>Customer Service<br><a href='mailto:sales@MJTrends.com'>sales@MJTrends.com</a><br><a href='http://www.MJTrends.com'>www.MJTrends.com</a>";;
	   // To send HTML mail, the Content-type header must be set
	   $headers  = 'MIME-Version: 1.0' . "\r\n";
	   $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
	   $headers .= 'From: MJTrends <sales@mjtrends.com>' . "\r\n";
	   $headers .= 'Bcc: Mike <mharris@mjtrends.com>' . "\r\n";

	   // Mail it
	   mail($to, $subject, $this->msg, $headers);

	}

	function returnMsg(){
		echo json_encode(array('status' => $this->status, 'msg' => $this->msg));
	}

}