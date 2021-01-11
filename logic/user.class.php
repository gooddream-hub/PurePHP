<?php
class User{
	
	public $shipfirst;
	public $shiplast;
	public $shipadOne;
	public $shipadTwo;
	public $shipcity;
	public $shipstate;
	public $shipzip;
	public $shipco;
	public $status;
	public $rank;
	public $posts;
	public $images;
	public $profile;
	public $orders;
	public $db;

	function __construct() {
		include_once('global.php');
		$this->db = DB::getInstance();
	}
	
	function getUser(){
		if(!isset($_SESSION['user']['shipfirst']) ){
			//if user has not submitted and failed payment, AND they have signed in on non-SSL
			if(!empty($_POST['usr']) ){
				$_SESSION['user'] = unserialize($_POST['usr']);	
			} elseif(isset($_COOKIE['usrInfo'])){ //if user has not submitted and failed payment, AND they have checked out before (cookie has been set on SSL domain)
				$_SESSION['user'] = unserialize($_COOKIE['usrInfo']);
			}

			//if user has not submitted and failed payment, AND they have set shipping on cart - only override user zip and country
			if(!isset($_SESSION['user']['shipfirst']) && !empty($_POST['shipMeth']) ){
				$_SESSION['user']['shipzip'] = $_POST['shippingZip'];
				$_SESSION['user']['shipco'] = $_POST['shippingCo'];
			} 
		}
	}

	function setUserFromPayPal(){
		if (isset($_REQUEST['token'])){
			require_once ("paypalfunctions.php");			

			$resArray = GetShippingDetails( $_REQUEST['token'] );
			$ack = strtoupper($resArray["ACK"]);
			
			if( $ack == "SUCCESS" ){

				$email 				= $resArray["EMAIL"]; // ' Email address of payer.
				$payerId 			= $resArray["PAYERID"]; // ' Unique PayPal customer account identification number.
				$payerStatus		= $resArray["PAYERSTATUS"]; // ' Status of payer. Character length and limitations: 10 single-byte alphabetic characters.
				$shipToName			= $resArray["SHIPTONAME"]; // ' Person's name associated with this address.
				$addressStatus 		= $resArray["ADDRESSSTATUS"]; // ' Status of street address on file with PayPal   
				$invoiceNumber		= $resArray["INVNUM"]; // ' Your own invoice or tracking number, as set by you in the element of the same name in SetExpressCheckout request .
				
				$_SESSION['payer_id'] 	= $payerId;
				$_SESSION['token']		= $token;

				$_SESSION['user'] = unserialize($_COOKIE['usrInfo']);
				
				//if the input shipping address is blank, and the billing address doesn't match the paypal address, update the billing address to paypal and set the shipping address to the input billing address
				if($_SESSION['shipadone'] == '' && (strtolower($_SESSION['user']['billadone']) != strtolower($resArray['SHIPTOSTREET']) ) ){
					$address = array("shipfirst" => $_SESSION['user']['billfirst'], "shiplast" => $_SESSION['user']['billlast'], "shipcomp" => $_SESSION['user']['billcomp'], "shipadone" => $_SESSION['user']['billadone'], "shipadtwo" => $_SESSION['user']['billadtwo'], "shipcity" => $_SESSION['user']['billcity'], "shipstate" => $_SESSION['user']['billstate'], "shipzip" => $_SESSION['user']['billzip'], "shipco" => $_SESSION['user']['billco'], "shipPh" => $_SESSION['user']['billphone'], "billfirst" => $resArray["FIRSTNAME"], "billlast" => $resArray["LASTNAME"], "billcomp" => $resArray["BUSINESS"], "billadone" => $resArray["SHIPTOSTREET"], "billadtwo" => $resArray["SHIPTOSTREET2"], "billcity" => $resArray["SHIPTOCITY"], "billstate" => $resArray["SHIPTOSTATE"], "billzip" => $resArray["SHIPTOZIP"], "billco" => $resArray["SHIPTOCOUNTRYCODE"], "billPh" => $_SESSION['user']['billphone'], "payType" => "pp", "email" => $_SESSION['user']['email']);
				} else {
				//set billing address to paypal and set shipping address to existing address if one provided
					$address = array("shipfirst" => $_SESSION['user']['shipfirst'], "shiplast" => $_SESSION['user']['shiplast'], "shipcomp" => $_SESSION['user']['shipcomp'], "shipad1" => $_SESSION['user']['shipadone'], "shipad2" => $_SESSION['user']['shipadtwo'], "shipcity" => $_SESSION['user']['shipcity'], "shipstate" => $_SESSION['user']['shipstate'], "shipzip" => $_SESSION['user']['shipzip'], "shipco" => $_SESSION['user']['shipco'], "shipPh" => $_SESSION['user']['shipphone'], "billfirst" => $resArray["FIRSTNAME"], "billlast" => $resArray["LASTNAME"], "billcomp" => $resArray["BUSINESS"], "billadone" => $resArray["SHIPTOSTREET"], "billadtwo" => $resArray["SHIPTOSTREET2"], "billcity" => $resArray["SHIPTOCITY"], "billstate" => $resArray["SHIPTOSTATE"], "billzip" => $resArray["SHIPTOZIP"], "billco" => $resArray["SHIPTOCOUNTRYCODE"], "billPh" => $_SESSION['user']['billphone'], "payType" => "pp", "email" => $_SESSION['user']['email']);
				}
				$this->setAddress($address);
			} else  {
				//Display a user friendly Error on the page using any of the following error information returned by PayPal
				$_GET['paypal'] = 'cancel';

				$ErrorCode = urldecode($resArray["L_ERRORCODE0"]);
				$ErrorShortMsg = urldecode($resArray["L_SHORTMESSAGE0"]);
				$ErrorLongMsg = urldecode($resArray["L_LONGMESSAGE0"]);
				$ErrorSeverityCode = urldecode($resArray["L_SEVERITYCODE0"]);
				
				$_GET['errResponse'] = "PayPal payment error: ".$ErrorLongMsg;
			}
		}
	}

	function getPublicProfile($uid){
		$sql = "SELECT username, avatar, about, points, website FROM users where uid = $uid";
		$result = $this->db->query($sql);
		
		if( $result->num_rows != 0 ){
			$row = $result->fetch_assoc();
			$this->profile = $row;

			if($this->profile['about'] == ''){
				if($_SESSION['user']['uid'] == $uid){
					$this->profile['about'] = 'Oops - you haven\'t filled out your statement of awesomeness yet!  <br><br><a href="http://www.MJTrends.com/account-forum.php">Fill it out and we\'ll credit your account 5 points.</a><br><br>
					  FYI- points are redeemable for coupons, free shipping, and Skype sessions with out expert sewing and crafting staff.';
				} else {
					$this->profile['about'] = 'Oops - this user hasn\'t filled out their statement of awesomeness yet! ';
				}
			}
		}
	}

	function signIn($email, $pwd){
		$email = $this->db->real_escape_string($email);
		$pwd = $this->db->real_escape_string($pwd);

		$sql = 'SELECT *, MD5(uid) as md5uid FROM users where email = "'.$email.'" AND pwd = "'.$pwd.'" ';
		$result = $this->db->query($sql);

		if($result->num_rows !=0 ){
			while ($row = $result->fetch_assoc()) {
				$resultArray = $row;
			}

			if($resultArray['confirmed'] == 1){
				setcookie("usr", $resultArray['md5uid'], time() + (5 * 365 * 24 * 60 * 60), '/');
				$this->setAddress($resultArray);
				
				$sql = "UPDATE users SET last_signin = CURDATE() WHERE uid = ".$resultArray['uid']." ";
				$result = $this->db->query($sql);
				return "success";				
			} else {
				return "not confirmed";
			}

		} else {
			return "fail";
		}
	}

	function resendConfirmation($email, $thread){
		$email = $this->db->real_escape_string($email);
		$thread = (int)$thread;
		
		$sql = 'SELECT username FROM users where email = "'.$email.'" ';
		$result = $this->db->query($sql);
		$row = $result->fetch_row();
		$username = $row[0];

		$to = $shipfirst." <".$email.">";
		$subject = 'Confirm Account';

		$message = '
			<html>
				<body>
					<h4>We received a request to send you another confirmation link.</h4>
					<p>Please click the following link to confirm your account:</p>
					<br>
					<a style="border: 1px solid #666; -moz-border-radius: 6px 6px 6px 6px; -webkit-border-radius: 6px 6px 6px 6px; border-radius: 6px 6px 6px 6px; background-color: #F27030; float:left; padding:2px 6px; color:#fff; font-size: 1.4em; text-decoration:none;" href="http://www.mjtrends.com/forum/confirm.php?cf='.md5($email).'&u='.$username.'&thread='.$thread.'">Confirm Account</a><br>
					<br><span style="font-weight:bold"><span style="color: #3871AB; font-size:1.5em; position:relative; bottom:-4px;">{</span>We help create!  Check out helpful links below<span style="color: #3871AB; font-size:1.5em; position:relative; bottom:-4px;">}</span></span><br>
					<br><a href="http://www.mjtrends.com/articles.php">Vinyl and latex sheeting video tutorials</a>
					<br><a href="http://www.mjtrends.com/forum/index.php">Community forums with tons of existing content and helpful posters like yourself.</a>
				</body>
			</html>';

		// To send HTML mail, the Content-type header must be set
		$headers  = 'MIME-Version: 1.0' . "\r\n";
		$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";

		// Additional headers
		$headers .= 'From: MJTrends <forums@mjtrends.com>' . "\r\n";

		// Mail it
		mail($to, $subject, $message, $headers);

	}

	function recoverPWD($email, $referrer){
		$email = $this->db->real_escape_string($email);
		$sql = 'SELECT shipfirst, pwd FROM users where email = "'.$email.'" ';
		$result = $this->db->query($sql);

		if( $result->num_rows !=0 ){
			$row = $result->fetch_row();
			$pwd = $row[0];
			$shipfirst = $row[1];

			$to = $shipfirst." <".$email.">";
			$subject = 'Password Recovery';
			$link = $referrer;

			if((stripos($referrer, 'thread')!== false) || (stripos($referrer, 'forum')!== false)){
				$link_text = 'Return to Forums';
			} else {
				$link_text = 'Return to MJTrends';
			}


			$message = '
				<html>
					<body>
						<h4>We received a request to recover your password.</h4>
						<p>Your password is: <br><b>'.$pwd.'</b></p><br>
						<a style="border: 1px solid #666; -moz-border-radius: 6px 6px 6px 6px; -webkit-border-radius: 6px 6px 6px 6px; border-radius: 6px 6px 6px 6px; background-color: #F27030; float:left; padding:2px 6px; color:#fff; font-size: 1.4em; text-decoration:none;" href="'.$link.'">'.$link_text.'</a>
						<br><span style="font-weight:bold"><span style="color: #3871AB; font-size:1.5em; position:relative; bottom:-4px;">{</span>We help create!  Check out helpful links below<span style="color: #3871AB; font-size:1.5em; position:relative; bottom:-4px;">}</span></span>
						<br><a href="http://www.mjtrends.com/articles.php">Vinyl and latex sheeting video tutorials</a>
						<br><a href="http://www.mjtrends.com/forum/index.php">Community forums with tons of existing content and helpful posters like yourself.</a>
					</body>
				</html>';

			// To send HTML mail, the Content-type header must be set
			$headers  = 'MIME-Version: 1.0' . "\r\n";
			$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";

			// Additional headers
			$headers .= 'From: MJTrends <forums@mjtrends.com>' . "\r\n";

			// Mail it
			mail($to, $subject, $message, $headers);

			return "success";
		} else {
			return "fail";
		}
	}

	function setAddress($address){
		if(is_array($address)){
			foreach($address as $key=>$val){
				if($val != "" && $key == 'pwd') {
					$_SESSION['user'][$key]  = md5($val);
				}else{
					$_SESSION['user'][$key]  = $val;
				}
			}
		}
		//for checkout
		$value = serialize($address);
		setcookie("usrInfo", $value, time()+3600);
	}

	function getAddress(){
		if(!empty($_SESSION['user'])){
			foreach($_SESSION['user'] as $key=>$val){
				$this->$key = $val;
			}
		}
	}

	function setShipping($shipDetails){
		if(is_array($shipDetails)){
			foreach($shipDetails as $key=>$val){
				$_SESSION['shipNfo'][$key] = $val;
			}
		}
	}

	function setStatus($points){
		if($points < 20) {
			$this->status = 'newbie';
		} elseif($points >= 20 AND $points < 100){
			$this->status = 'apprentice';
		} elseif($points >= 100 AND $points < 300){
			$this->status = 'master';
		} elseif($points >= 300){
			$this->status = 'icon';
		}
	}

	function setPoints($points){
		$uid = $_SESSION['user']['uid'];
		$sql = "UPDATE users SET points = points+$points WHERE uid = $uid";
		$result = $this->db->query($sql);
	}
	
	/**
	 *
	 * @param int $points
	 * @param int $uid
	 */
	function setUserPoints($points,$uid){
		$sql = "UPDATE users SET points = points+$points WHERE uid = $uid";
		return $this->db->query($sql);
	}
	
	function validateEmail($email){
		$email = $this->db->real_escape_string($email);
		$sql = "SELECT uid FROM users WHERE email = '$email' ";
		$result = $this->db->query($sql);

		if( $result->num_rows != 0 ){
			return false;
		} else {
			return true;
		}

	}

	function validateUserName($username){
		$username = $this->db->real_escape_string($username);
		$sql = "SELECT uid FROM users WHERE username = '$username' ";
		$result = $this->db->query($sql);

		if( $result->num_rows != 0 ){
			return false;
		} else {
			return true;
		}
	}

	function createAccount($email, $username, $pwd, $thread){
		$email = $this->db->real_escape_string($email);
		$username = $this->db->real_escape_string($username);
		$pwd = $this->db->real_escape_string($pwd);
		$thread = (int)$thread;
		$avatar = "default-".rand(1,22);

		$query = "SELECT * FROM custinfo WHERE email = '$email' ORDER BY custid DESC LIMIT 1";
		$result = $this->db->query($query);
		if ( $result->num_rows != 0 ) { 
			$row = $result->fetch_assoc();
			extract($row);
			$sql = "INSERT INTO users(email, username, pwd, avatar, shipfirst, shiplast, shipadone, shipadtwo, shipcomp, shipcity, shipstate, shipzip, shipco, shiphone, billfirst, billlast, billadone, billadtwo, billcomp, billcity, billstate, billzip, billco, billphone) VALUES('$email', '$username', '$pwd', '$avatar', '$shipfirst', '$shiplast', '$shipadone', '$shipadtwo', '$shipcomp', '$shipcity', '$shipstate', '$shipzip', '$shipco', '$shiphone', '$billfirst', '$billlast', '$billadone', '$billadtwo', '$billcomp', '$billcity', '$billstate', '$billzip', '$billco', '$billphone')";
		} else {
			$sql = "INSERT INTO users(email, username, pwd, avatar) VALUES('$email', '$username', '$pwd', '$avatar')";
		}
		$this->db->query($sql);

		$to = $shipfirst." <".$email.">";
		$subject = 'Confirm Account';

		$message = '
			<html>
				<body>
					<h4>Thanks for creating a new account!  You\'ve just joined a creative community of artists whose palette is fabric.</h4>
					<p>Please click the following link to confirm your account:</p>
					<br>
					<a style="border: 1px solid #666; -moz-border-radius: 6px 6px 6px 6px; -webkit-border-radius: 6px 6px 6px 6px; border-radius: 6px 6px 6px 6px; background-color: #F27030; float:left; padding:2px 6px; color:#fff; font-size: 1.4em; text-decoration:none;" href="http://www.mjtrends.com/forum/confirm.php?cf='.md5($email).'&u='.$username.'&thread='.$thread.'">Confirm Account</a><br>
					<br><span style="font-weight:bold"><span style="color: #3871AB; font-size:1.5em; position:relative; bottom:-4px;">{</span>We help create!  Check out helpful links below<span style="color: #3871AB; font-size:1.5em; position:relative; bottom:-4px;">}</span></span><br>
					<br><a href="http://www.mjtrends.com/articles.php">Vinyl and latex sheeting video tutorials</a>
					<br><a href="http://www.mjtrends.com/forum/index.php">Community forums with tons of existing content and helpful posters like yourself.</a>
				</body>
			</html>';

		// To send HTML mail, the Content-type header must be set
		$headers  = 'MIME-Version: 1.0' . "\r\n";
		$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";

		// Additional headers
		$headers .= 'From: MJTrends <forums@mjtrends.com>' . "\r\n";

		// Mail it
		mail($to, $subject, $message, $headers);
	}

	function confirmAccount(){
		$emailHash = $_GET['cf'];
		$username = $_GET['u'];
		$username = $this->db->real_escape_string($username);
		
		$sql = "SELECT *, MD5(uid) as md5uid FROM users WHERE username = '$username' ";
		$result = $this->db->query($sql);
		
		if( $result->num_rows != 0 ){
			while ($row = $result->fetch_assoc()) {
				$resultArray = $row;
			}
			
			if(md5($resultArray['email']) == $emailHash){
				$uid = $resultArray['uid'];
				$sql = "UPDATE users SET confirmed = 1, signup_date = CURDATE() WHERE uid = $uid";
				$result = $this->db->query($sql);

				setcookie("usr", $resultArray['md5uid'], time() + (5 * 365 * 24 * 60 * 60), '/');
				$this->setAddress($resultArray);
				return true;
			} else {
				return false;
			}
		} else {
			return false;
		}
	}

	function getUserRank($points){
		if(!is_numeric($points)) $points = 0;
		$sql = "SELECT COUNT(uid) FROM users WHERE points > $points";
		$result = $this->db->query($sql);
		$row = $result->fetch_row();
		$this->rank = $row[0] + 1;
	}

	function getForumPosts($uid){
		$sql = "SELECT DISTINCT(thread_num) AS thread_num, topic, discussion, date_time FROM forum WHERE uid = $uid AND approved = 1 ORDER BY date_time DESC";
		$result = $this->db->query($sql);
		if( $result->num_rows != 0 ){
			while ($row = $result->fetch_assoc()) {
				$this->posts[] = $row;
			}
		}
	}
	
	function getUploadedImages($uid){
		$sql = "SELECT url FROM forum_media WHERE uid = $uid AND type = 0 ORDER BY mid DESC LIMIT 15";
		$result = $this->db->query($sql);
		if( $result->num_rows != 0 ){
			while ($row = $result->fetch_assoc()) {
				$this->images[] = $row;
			}
		}
	}

	function getPreviousOrders($email){
		$email = $this->db->real_escape_string($email);
		$sql = "SELECT custinfo.custid AS custid, billzip, order_date, orderdetails.cat, orderdetails.color, orderdetails.type, quant, price, invamount, saleprice, retail, orderdetails.invid, weight, volume FROM custinfo LEFT JOIN orderdetails ON custinfo.custid = orderdetails.custid LEFT JOIN inven_mj ON orderdetails.invid = inven_mj.invid WHERE email = '$email' LIMIT 200";
		$result = $this->db->query($sql);

		if( $result->num_rows != 0 ){
			$invoice = 0;
			while ($row = $result->fetch_assoc()) {
				if($invoice != $row['custid']){
					$invoice = $row['custid'];
					$this->orders[] = array('invoice' => '<a href="tracking.php?email='.$email.'&billzip='.$row['billzip'].'&id='.$invoice.'">'.$invoice.'</a>', 'date' => $row['order_date'], 'cat' => '', 'color' => '', 'type' => '', 'quant' => '', 'price' => '', 'invamount' => '', 'saleprice' => '', 'retail' => '', 'invid' => '', 'weight' => '', 'volume' => '');
				}
				$this->orders[] = array('invoice' => '', 'date' => '', 'cat' => $row['cat'], 'color' => $row['color'], 'type' => $row['type'], 'quant' => $row['quant'], 'price' => $row['price'], 'invamount' => $row['invamount'], 'saleprice' => $row['saleprice'], 'retail'=> $row['retail'], 'invid' => $row['invid'], 'weight' => $row['weight'], 'volume'=> $row['volume']);
			}
		}
	}

	function updateAccount($fieldArray, $uid){
		$sql = "UPDATE users SET ";
		foreach($fieldArray as $key=> $val){
			if(($key == "about" || $key == "website") && $val == ""){
				$val = "Not set"; //ensure that fields cannot be empty so as to not allocate points again.
			}

			if($key == "about" && $_SESSION['user']['about'] == ''){
				$this->setPoints(5);
			}

			if($key == 'website' && $_SESSION['user']['website'] == ''){
				$this->setPoints(5);
			}

			$val = $this->db->real_escape_string($val);
			$sql .= "$key = '$val',";
		}
		$sql = rtrim($sql, ",");
		$sql = $sql." WHERE uid = $uid";
		
		$result = $this->db->query($sql);
		$this->setAddress($fieldArray);
	}

	function updateAvatar($imgPath, $uid){
		if(strcmp($_SESSION['user']['avatar'], "default") > 0){
			$this->setPoints(10);
		}

		$sql = "UPDATE users SET avatar = '$imgPath' WHERE uid = $uid ";
		if($result = $this->db->query($sql)) {
			$fieldArray['avatar'] = $imgPath;
			$this->setAddress($fieldArray);
		}
	}
}