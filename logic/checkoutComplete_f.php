<?
$tax = 0;
function orderInit(){
	if($_SESSION['submitted'] != $_COOKIE['custid']){
		$grandtotal = grandtotal();
		$payment = processPayment($grandtotal);
		
		if($payment['status'] == true){
			
			if( (strcasecmp($_POST['shipAd1'], "15 Adella Avenue") == 0) || (strcasecmp($_SESSION['user']['shipadone'], "15 Adella Avenue") == 0 || strcasecmp($_POST['shipAd1'], "282 spring street") == 0) || (strcasecmp($_SESSION['user']['shipadone'], "282 spring street") == 0 || strcasecmp($_POST['shipAd1'], "157 old rose street") == 0) || (strcasecmp($_SESSION['user']['shipadone'], "239 pearl street") == 0 ) || (strcasecmp($_SESSION['user']['shipadone'], "239 pearl street") == 0 ) || (strcasecmp($_SESSION['user']['shipadone'], "930 southard street") == 0 ) || (strcasecmp($_SESSION['user']['shipadone'], "930 southard street") == 0 ) || (strcasecmp($_SESSION['user']['shipadone'], "27 oak street") == 0 ) || (strcasecmp($_SESSION['user']['shipadone'], "27 oak street") == 0 ) || (strcasecmp($_SESSION['user']['shipadone'], "134 turpin street") == 0 ) || (strcasecmp($_POST['shipAd1'], "134 turpin street") == 0) ){ //fraud
				header('Location: checkout.php?payment=error&paymentType='.$payment['payment'].'&errResponse=Payment failure');
				exit();
			}

			insertUser();
			insertCart();
			insertPay($grandtotal);
			if(isset($_SESSION['couponCode'])) insertCoupon();
			if(isset($_SESSION['addedShipOnRoll'])) insertShipOnRoll();
			if($_POST['wireFee'] > 0) insertPayAddOn();
			setConvo();
			saveUser();
			deleteTmpCart();
			$_SESSION['submitted'] = $_COOKIE['custid'];

		} else {
			/*
			put user info in session so it's populated on checkout.php
			put shipping info in session so it's populated on checkout.php
			*/
			saveUser();
			$to = "Michael <ryland22@gmail.com>";
			$subject = 'MJTrends Payment Failure';
			$message = 'Payment method: '.$_POST['paymentType'].' <br>Customer email: .'.$_POST['email'].'<br>Custid: '.$_COOKIE['custid'].'<br>Error Reason: '.$payment['error'];
			// To send HTML mail, the Content-type header must be set
			$headers  = 'MIME-Version: 1.0' . "\r\n";
			$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
			$headers .= 'From: MJTrends <orders@mjtrends.com>' . "\r\n";
			mail($to, $subject, $message, $headers);
			
			//redirect user back to checkout_info
			header('Location: checkout.php?payment=error&paymentType='.$payment['payment'].'&errResponse='.$payment['error']);
			exit();
		}

	}
}

function saveuser(){
	include('user.class.php');
	$user = new User;
	$address = array( "billfirst" => $_POST['billfirst'], "shipfirst" => $_POST['shipfirst'], "billlast" => $_POST['billlast'], "shiplast" => $_POST['shiplast'],  "billadone" => $_POST['billAd1'], "shipadone" => $_POST['shipAd1'], "billadtwo" => $_POST['billAd2'], "shipadtwo" => $_POST['shipAd2'], "billcity" => $_POST['billCity'], "shipcity" => $_POST['shipCity'], "billstate" => $_POST['billState'], "shipstate" => $_POST['shipState'], "billzip" =>$_POST['billZip'], "shipzip" => $_POST['shippingZip'], "billco" => $_POST['billCo'], "shipco" => $_POST['shipCo'], "email" => $_POST['email'], "phone" => $_POST['phone']);
	$user->setAddress($address);
}

function deleteTmpCart(){
	$custid = $_COOKIE['custid'];
	$db = DB::getInstance();
	$query = "DELETE FROM tmp_cart where custid = $custid ";
	$result =$db->query($query);
}

function processPayment($grandtotal){
	if($_GET['paypal'] == 'success'){
		$payment = submitPayPal($grandtotal);
	} elseif($_POST['paymentType'] == "cc") {
		$payment = submitAuthorize($grandtotal);
	} else {
		$payment = array('status' => true);
	}
	return $payment;
}

function submitAuthorize($grandtotal){
	include ("authnet.php");

	$payment = Authnet::instance();
	$payment -> setTransaction($_POST['cNum'], sprintf("%02d",$_POST['exp_month']).substr($_POST['exp_year'], 2,4), $grandtotal, $_POST['cvv2'], $_POST['billfirst'], $_POST['billlast'], $_POST['billAd1'], $_POST['billCity'], $_POST['billState'], $_POST['billZip'], $_POST['billCo'], $_COOKIE['custid'], $_POST['email'], $_POST['phone']);
	$payment -> process();

	if($payment->isApproved()){
		return array('status' => true);
	} else {
		return array('status' => false, 'payment' => 'cc', 'error' => $payment->getResponseText());
	} 	
}

function setShippingCost(){
	if(isset($_POST['shipCode'])){

		$shipType = explode(',', $_POST['shipCode']);
		$code = $shipType[0];
		$serviceName = $shipType[1];
		$shipTime = $shipType[2];
		$cost = $shipType[3];
		$delDate = $shipType[4];
		$shipDate = $shipType[5];
		
		$_SESSION['shipNfo'] = array('code' => $code,'serviceName' => $serviceName, 'shipTime' => $shipTime, 'cost' => $cost, 'delDate' => $delDate, 'shipDate' => $shipDate);
	}	
}

function grandTotal(){
	global $tax;
	$total = 0;
	$tax = 0;

	foreach($_SESSION['cart'] as $row){
		$price = ($row['sale'] > 0) ? $price = $row['sale'] : $row['price'];
		$total += $row['quant']*$price;
	}
	
	if(isset($_SESSION['couponCode'])){
		global $coupon;	
		$total -= $coupon->total_discount; 
	}

	if($_SESSION['user']['shipco'] == "US" || $_POST['shipCo'] == "US"){
		$tax = $total * .045;
	}

	$total += $_SESSION['shipNfo']['cost'];
	$total += $_SESSION['ship_on_roll']['cost'] ;
	$total += $_POST['wireFee'];
	$total += $tax;

	return number_format($total,2,'.','');
}

function submitPayPal($grandtotal){
	require_once ("paypalfunctions.php");
	$_SESSION['token']		= $_REQUEST['token'];
	$_SESSION['payer_id'] 	= $_REQUEST['PayerID'];

	//get users shipping info from paypal and set as billing address
	$resArray = GetShippingDetails( $_SESSION['token'] );
	$ack = strtoupper($resArray["ACK"]);

	if( $ack == "SUCCESS" ){
		$_POST['billfirst'] = $resArray["FIRSTNAME"];
		$_POST['billlast'] = $resArray["LASTNAME"];
		$_POST['billComp'] = $resArray["BUSINESS"];
		$_POST['billAd1'] = $resArray["SHIPTOSTREET"];
		$_POST['billAd2'] = $resArray["SHIPTOSTREET2"];
		$_POST['billCity'] = $resArray["SHIPTOCITY"];
		$_POST['billState'] = $resArray["SHIPTOSTATE"];
		$_POST['billZip'] = $resArray["SHIPTOZIP"];
		$_POST['billCo'] = $resArray["SHIPTOCOUNTRYCODE"];
		$_POST['newsletter'] = $_SESSION['newsletter'];
	} 

	//submit the payment to PayPal for completion
	$resArray = ConfirmPayment ( $grandtotal );
	$ack = strtoupper($resArray["ACK"]);
	
	if( $ack == "SUCCESS" ){
		$transactionId		= $resArray["TRANSACTIONID"]; // ' Unique transaction ID of the payment. Note:  If the PaymentAction of the request was Authorization or Order, this value is your AuthorizationID for use with the Authorization & Capture APIs. 
		$transactionType 	= $resArray["TRANSACTIONTYPE"]; //' The type of transaction Possible values: l  cart l  express-checkout 
		$paymentType		= $resArray["PAYMENTTYPE"];  //' Indicates whether the payment is instant or delayed. Possible values: l  none l  echeck l  instant 
		$orderTime 			= $resArray["ORDERTIME"];  //' Time/date stamp of payment
		$amt				= $resArray["AMT"];  //' The final amount charged, including any shipping and taxes from your Merchant Profile.
		$currencyCode		= $resArray["CURRENCYCODE"];  //' A three-character currency code for one of the currencies listed in PayPay-Supported Transactional Currencies. Default: USD. 
		$feeAmt				= $resArray["FEEAMT"];  //' PayPal fee amount charged for the transaction
		$settleAmt			= $resArray["SETTLEAMT"];  //' Amount deposited in your PayPal account after a currency conversion.
		$taxAmt				= $resArray["TAXAMT"];  //' Tax charged on the transaction.
		$exchangeRate		= $resArray["EXCHANGERATE"];  //' Exchange rate if a currency conversion occurred. Relevant only if your are billing in their non-primary currency. If the customer chooses to pay with a currency other than the non-primary currency, the conversion occurs in the customer�s account.
		
		/*
		' Status of the payment: 
				'Completed: The payment has been completed, and the funds have been added successfully to your account balance.
				'Pending: The payment is pending. See the PendingReason element for more information. 
		*/
		
		$paymentStatus	= $resArray["PAYMENTSTATUS"]; 

		/*
		'The reason the payment is pending:
		'  none: No pending reason 
		'  address: The payment is pending because your customer did not include a confirmed shipping address and your Payment Receiving Preferences is set such that you want to manually accept or deny each of these payments. To change your preference, go to the Preferences section of your Profile. 
		'  echeck: The payment is pending because it was made by an eCheck that has not yet cleared. 
		'  intl: The payment is pending because you hold a non-U.S. account and do not have a withdrawal mechanism. You must manually accept or deny this payment from your Account Overview. 		
		'  multi-currency: You do not have a balance in the currency sent, and you do not have your Payment Receiving Preferences set to automatically convert and accept this payment. You must manually accept or deny this payment. 
		'  verify: The payment is pending because you are not yet verified. You must verify your account before you can accept this payment. 
		'  other: The payment is pending for a reason other than those listed above. For more information, contact PayPal customer service. 
		*/
		
		$pendingReason	= $resArray["PENDINGREASON"];  

		/*
		'The reason for a reversal if TransactionType is reversal:
		'  none: No reason code 
		'  chargeback: A reversal has occurred on this transaction due to a chargeback by your customer. 
		'  guarantee: A reversal has occurred on this transaction due to your customer triggering a money-back guarantee. 
		'  buyer-complaint: A reversal has occurred on this transaction due to a complaint about the transaction from your customer. 
		'  refund: A reversal has occurred on this transaction because you have given the customer a refund. 
		'  other: A reversal has occurred on this transaction due to a reason not listed above. 
		*/

		$reasonCode		= $resArray["REASONCODE"];   
		return array('status'=> true);
	}
	else  
	{
		//Display a user friendly Error on the page using any of the following error information returned by PayPal
		$ErrorCode = urldecode($resArray["L_ERRORCODE0"]);
		$ErrorShortMsg = urldecode($resArray["L_SHORTMESSAGE0"]);
		$ErrorLongMsg = urldecode($resArray["L_LONGMESSAGE0"]);
		$ErrorSeverityCode = urldecode($resArray["L_SEVERITYCODE0"]);
		
		/*
		echo "GetExpressCheckoutDetails API call failed. ";
		echo "Detailed Error Message: " . $ErrorLongMsg;
		echo "Short Error Message: " . $ErrorShortMsg;
		echo "Error Code: " . $ErrorCode;
		echo "Error Severity Code: " . $ErrorSeverityCode;
		*/
		if($ErrorSeverityCode == "Warning"){
			return array('status' => true, 'error' => $ErrorLongMsg, 'payment' => 'pp');
		} else {
			return array('status' => false, 'error' => $ErrorLongMsg, 'payment' => 'pp');
		}
	}
}	

function insertUser(){
	$db = DB::getInstance();

	if($_POST['paymentType'] == "cc"){
		$pending = "pending credit";
	} elseif($_POST['paymentType'] == "mail"){
		$pending = "pending mail";
	} else {
		$pending = "pending payPal";
	}

	$newsletter = $_POST['newsletter'];
	if($pending == "pending payPal"){
		$_POST['shipfirst'] = $_SESSION['user']['shipfirst'];
		$_POST['shiplast'] = $_SESSION['user']['shiplast'];
		$_POST['shipComp'] = $_SESSION['user']['shipComp'];
		$_POST['shipAd1'] = $_SESSION['user']['shipadone'];
		$_POST['shipAd2'] = $_SESSION['user']['shipadtwo'];
		$_POST['shipCity'] = $_SESSION['user']['shipcity'];
		$_POST['shipState'] = $_SESSION['user']['shipstate'];
		$_POST['shippingZip'] = $_SESSION['user']['shipzip'];
		$_POST['shipCo'] = $_SESSION['user']['shipco'];	
		$_POST['phone'] = $_SESSION['user']['phone'];
		$_POST['email'] = $_SESSION['user']['email'];
		$newsletter = $_SESSION['user']['newsletter'];
	} elseif($pending == 'pending mail'){//if shipping not different from billing then we need to set the shipping values b/c they will be blank
		$_POST['billfirst'] = $_POST['shipfirst'];
		$_POST['billlast'] = $_POST['shiplast'];
		$_POST['billComp'] = $_POST['shipComp'];
		$_POST['billAd1'] = $_POST['shipAd1'];
		$_POST['billAd2'] = $_POST['shipAd2'];
		$_POST['billCity'] = $_POST['shipCity'];
		$_POST['billState'] = $_POST['shipState'];
		$_POST['billZip'] = $_POST['shipZip'];
		$_POST['billCo'] = $_POST['shipCo'];
	} 



	$query = "INSERT INTO custinfo (custid, shipfirst, shiplast, shipadone, shipadtwo, shipcomp, shipcity, shipstate, shipzip, shipco, email, phone, newsletter, billfirst, billlast, billadone, billadtwo, billcomp, billcity, billstate, billzip, billco, order_date, ship_date, del_date) VALUES ('".$db->real_escape_string($_COOKIE['custid'])."', '".$db->real_escape_string(utf8_decode($_POST['shipfirst']))."', '".$db->real_escape_string(utf8_decode($_POST['shiplast']))."', '".$db->real_escape_string(utf8_decode($_POST['shipAd1']))."', '".$db->real_escape_string(utf8_decode($_POST['shipAd2']))."', '".$db->real_escape_string($_POST['shipComp'])."', '".$db->real_escape_string(utf8_decode($_POST['shipCity']))."', '".$db->real_escape_string(utf8_decode($_POST['shipState']))."', '".$db->real_escape_string($_POST['shippingZip'])."', '".$db->real_escape_string($_POST['shipCo'])."', '".$db->real_escape_string($_POST['email'])."', '".$db->real_escape_string($_POST['phone'])."', '".$newsletter."', '".$db->real_escape_string(utf8_decode($_POST['billfirst']))."', '".$db->real_escape_string(utf8_decode($_POST['billlast']))."', '".$db->real_escape_string(utf8_decode($_POST['billAd1']))."', '".$db->real_escape_string(utf8_decode($_POST['billAd2']))."', '".$db->real_escape_string($_POST['billComp'])."', '".$db->real_escape_string(utf8_decode($_POST['billCity']))."', '".$db->real_escape_string(utf8_decode($_POST['billState']))."', '".$db->real_escape_string($_POST['billZip'])."', '".$db->real_escape_string($_POST['billCo'])."', CURDATE(), '".$pending."', '". $db->real_escape_string($_SESSION['shipNfo']['delDate']) ."' )";
	$result = $db->query($query) or die($db->error());
}

function insertCart(){ //loop through cart and update inventory amounts
	global $patternTutorial;
	$db = DB::getInstance();

	foreach($_SESSION['cart'] as $row){
		/*
		Added By Anish R on 01/11/2009
		*/
		$ship_roll	= 0;
		$price = ($row['sale'] > 0) ? $price = $row['sale'] : $row['price'];
		
		if(count($_SESSION['rws_cart']['selAr'])){
			$tickMark	= in_array($row['invid'], $_SESSION['rws_cart']['selAr'])? "<td><span style='font-size: 14px; text-align: center'>&radic;</span></td>":null;
			if( in_array($row['invid'], $_SESSION['rws_cart']['selAr']) ) {
				$ship_roll	= 1;
				if($_SESSION['rws_cart']['shipSepHidden'] > 0) $ship_roll	= 2;
			}
		}	
		
		$invidArr = explode("-",$row['invid']);
		$invid = $invidArr[0]; //do this for swatches which get arbitrary number appending to the end of them eg: 1409-302

		if($row['cat'] != "Pattern"){
			$insert = "INSERT INTO orderdetails (custid, invid, cat, type, color, price, quant, wholesale, ship_roll) VALUES ('".$db->real_escape_string($_COOKIE['custid'])."', '".$invid."', '".str_replace("-"," ",$row['cat'])."', '".str_replace("-"," ",$row['type'])."', '".str_replace("-"," ",$row['color'])."',(SELECT IF(saleprice > 0,saleprice,retail) FROM inven_mj WHERE invid = ".$invid."), '".$row['quant']."',(SELECT wholesale FROM inven_mj WHERE invid = ".$invid."), '".$ship_roll."')"; 
			$update = "Update inven_mj SET invamount = invamount-".$db->real_escape_string($row['quant'])." WHERE invid = ".$invid.""; 

			$result = $db->query($insert);
			$result = $db->query($update);
		} else {
			$insert = "INSERT INTO orderdetails (custid, invid, cat, type, color, price, quant, wholesale, ship_roll) VALUES ('".$db->real_escape_string($_COOKIE['custid'])."', '".$invid."', '".$row['cat']."', '".$row['type']."', '".$row['color']."', '".$row['price']."', '".$row['quant']."', 4, '".$ship_roll."')"; 
			$result = $db->query($insert);

			$style = str_replace(' pattern', '', $row['color']);
			$query = "SELECT tutorial FROM pattern_style where gender = '".$row['type']."' AND style = '$style' LIMIT 1";
			$result = $db->query($query);
			$row5 = $result->fetch_row();
			$patternTutorial[] = json_decode($row5[0], true);
		}
		
		$k++;
		if($k%3==0 OR $k==1) $gray = "class=\"gray\"";
		$cartloop .= "<tr><td ".$gray.">".$row['invid']."</td><td ".$gray.">".$row['type']." ".$row['color']."</td><td ".$gray." align=\"center\">".$row['quant']."</td><td ".$gray.">$".number_format(($row['price']),2,'.','')."</td><td ".$gray.">$".number_format(($row['price']*$row['quant']),2,'.','')."</td>".$tickMark."</tr>";
		$subtotal += $row['quant']*$price;
	}
	
	sendEmail($cartloop,$subtotal);
	setShipOnRoll($subtotal);
}

function insertCoupon(){
	global $coupon;

	$db = DB::getInstance();
	$msg 	= $coupon->msg; 
	$price  = $coupon->total_discount;
	$query  = "INSERT INTO orderdetails (custid, cat, type, color, price, quant) VALUES ('".$db->real_escape_string($_COOKIE['custid'])."', 'Coupon', '".$_SESSION['couponCode']."', '$msg', '-$price', 1);";
	$result = $db->query($query); 

	$coupArray = array('vinyl_starter','vinyl_advanced', 'vinyl_pro', 'latex_starter', 'latex_advanced', 'latex_pro', 'lingerie', 'french_curve_pro_pack', 'pattern_party', 'zipmeup', 'Reddit_crafter9', 'span_hero');
	
	if(in_array(strtolower($_SESSION['couponCode']), $coupArray) === FALSE){
		$query  = "UPDATE coupon SET active = 0 WHERE code = '".$_SESSION['couponCode']."'";
		$result = $db->query($query);
	}
	
}

function insertShipOnRoll(){
	$db = DB::getInstance();
	$shipOnRoll = $_SESSION['addedShipOnRoll'];
	$query  = "INSERT INTO orderdetails (custid, cat, type, color, price, quant) VALUES ('".$db->real_escape_string($_COOKIE['custid'])."', 'Ship on Roll', '".$_SESSION['couponCode']."', 'Total Cost', '$shipOnRoll', 1);";
	$result = $db->query($query);
}

function insertPayAddOn(){
	$db = DB::getInstance();
	$query  = "INSERT INTO orderdetails (custid, cat, type, color, price, quant) VALUES ('".$db->real_escape_string($_COOKIE['custid'])."', 'Wire Fee', '".$db->real_escape_string($_POST['wireFee'])."', 'Total Cost', '$shipOnRoll', 1);";
	$result = $db->query($query);
}

function insertPay($grandtotal){
	global $tax;
	$db = DB::getInstance();
	(($_POST['cNum']) == '') ? $ccnum = 0 : $ccnum = $db->real_escape_string($_POST['cNum']); 
	$payType = (isset($_POST['paymentType'])) ? $_POST['paymentType'] : 'pp';

	if($ccnum == "") $ccnum = 0;
	if($_POST['exp_month'] == "") $_POST['exp_month'] = 0;
	if($_POST['exp_year'] == "") $_POST['exp_year'] = 0;
	if($_POST['cvv2'] == "") $_POST['cvv2'] = 0;

	$insert = "INSERT INTO payment (custid, payType, ccnumber, ccmonth, ccyear ,cvv2, shipping, ship_type, tax, grandtotal) VALUES ('".$db->real_escape_string($_COOKIE['custid'])."', '".$db->real_escape_string($payType)."', '".$ccnum."', '".$db->real_escape_string($_POST['exp_month'])."', '".$db->real_escape_string($_POST['exp_year'])."', '".$db->real_escape_string($_POST['cvv2'])."', '".$db->real_escape_string($_SESSION['shipNfo']['cost'])."', '".$db->real_escape_string($_SESSION['shipNfo']['service'])."', '".$db->real_escape_string($tax)."','". $grandtotal ."')";
	$result = $db->query($insert);
}

function updateUser(){ //update user table if change in user information
	if($_POST['update'] == true){
		$db = DB::getInstance();
		$update = "UPDATE user SET WHERE id = ".$_COOKIE['usr']."";
		$result = $db->query($update) or die($db->error());
	}
}

function sendEmail($cartloop,$subtotal){
	global $coupon, $tax;

	$gtotal = $subtotal+$tax+$_SESSION['shipNfo']['cost']+$_SESSION['addedShipOnRoll'];
	if(isset($_SESSION['couponCode'])) $gtotal -= $coupon->total_discount;
	
	/*$shipInfoCost	= (float)$_SESSION['shipNfo']['cost'];
	if (1 == $_SESSION['rws_cart']['shipSepHidden'] && 'US' == strtoupper($_SESSION['usrNfo']['fedCo']) ) {
		for ($i = 1; $i<=count($_SESSION['rws_cart']['selAr']); $i++) {
			if(0 != $shipInfoCost) {
				$shipInfoCost +=18;
			}
			$gtotal	+= 18;
		}
	} else {
		for ($i = 1; $i<=count($_SESSION['rws_cart']['selAr']); $i++) {
			if(1 == $i) {
				if(0 != $shipInfoCost)
					$shipInfoCost +=18;
					$gtotal += 18;
				}
				else {
					if(0 != $shipInfoCost)
						$shipInfoCost +=6;
						$gtotal += 6;
					}
				}
	}*/

	$to = $_POST['billfirst']." <".$_POST['email'].">";

	// subject
	$subject = $_POST['billfirst']."'s MJTrends Order#: ".$_COOKIE['custid']."!";
	
	//Added By Anish R//
	$extraColHead	= null;
	$extraCol		= null;
	$extraCol2		= null;
	if(count($_SESSION['rws_cart']['selAr']) && 'US' == strtoupper($_SESSION['user']['shipco'])) {
		$extraColHead	= '<td style="width:78px;"><b>Ship on roll</b></td>';
		$extraCol		= '<td class="gray" style="width:78px;">&nbsp;</td>';
		$extraCol2		= '<td>&nbsp;</td>';
	}
	
	//Added By Anish R ends//
	$couponRow = null;
	if(isset($_SESSION['couponCode'])){
		$couponRow = 
			'<tr>
				<td></td>
				<td></td>
				<td></td>
				<td>Coupon:</td>
				<td>-$'.number_format($coupon->total_discount,2,'.','').'</td>
				<td>&nbsp;</td>
			</tr>';
	}

	$shipOnRollRow = null;
	if(isset($_SESSION['addedShipOnRoll'])){
		$shipOnRollRow = 
			'<tr>
				<td></td>
				<td></td>
				<td></td>
				<td>Ship on Roll:</td>
				<td>$'.number_format($_SESSION['addedShipOnRoll'],2,'.','').'</td>
				<td>&nbsp;</td>
			</tr>';
	}

	// message
	$trackIt = "";
	if($_SESSION['shipNfo']['serviceName'] != "USPS 1st Class") $trackIt = "As soon as your package has shipped, you will receive an email with tracking information.  ";
	
	$message = '
		<html><body>
		<img src="http://www.mjtrends.com/images/email_hdr.gif">
		<STYLE type="text/css">
			table tr td{border-top:4px solid #fff;border-bottom:4px #fff}
			.gray {background: #ccc}
		</STYLE>
		<p>Your order has been received and in moments our staff will be giving each other high fives and cheering at the prospect of you creating something awesome.  Next, your order will make its way to our warehouse where highly trained Fabric Ninjas will slice and pack your items with care.  '.$trackIt.'You can review your order details below:</p>
		<table cellpadding="0" cellspacing="0">
			<tr>
				<td colspan="3" style="border:1px solid #000;">Order Status</td>
			</tr>
			<tr>
				<td class="gray" style="width:170px;">Order #:</td>
				<td class="gray" style="width:303px;">'.$_COOKIE['custid'].'</td>
				'.$extraCol.'
			</tr>
			<tr>
				<td>Order Date:</td>
				<td>'.date("m-d-Y").'</td>
				<td>&nbsp;</td>
			</tr>
			<tr>
				<td class="gray">Ship Method:</td>
				<td class="gray">'.$_SESSION['shipNfo']['service'].'</td>
				<td class="gray">&nbsp;</td>
			</tr>
			<tr>
				<td>Expected Delivery Date:</td>
				<td>'.$_SESSION['shipNfo']['delDate'].'</td>
				<td>&nbsp;</td>
			</tr>
		</table>
		<br />
		<table cellpadding="0" cellspacing="0">
			<tr>
				<td colspan="6" style="border:1px solid #000">Ordered Items</td>
			</tr>
			<tr>
				<td style="width:130px"><b>Item #</b></td>
				<td style="width:138px"><b>Product Name</b></td>
				<td style="width:70px"><b>Quantity</b></td>
				<td style="width:75px"><b>Price</b></td>
				<td style="width:60px"><b>Total</b></td>
				'.$extraColHead.'
			</tr>
			'.$cartloop.'	
			<tr>
				<td></td>
				<td></td>
				<td></td>
				<td>Subtotal:</td>
				<td>$'.number_format(($subtotal),2,'.','').'</td>
				'.$extraCol2.'
			</tr>
			<tr>
				<td></td>
				<td></td>
				<td></td>
				<td>Tax:</td>
				<td>$'.number_format(($tax),2,'.','').'</td>
				<td>&nbsp;</td>
			</tr>
			<tr>
				<td></td>
				<td></td>
				<td></td>
				<td style="border-bottom:1px solid #000">Shipping:</td>
				<td style="border-bottom:1px solid #000">$'.number_format($_SESSION['shipNfo']['cost'],2,'.','').'</td>
				<td style="border-bottom:1px solid #000">&nbsp;</td>
			</tr>
			'.$couponRow.'
			'.$shipOnRollRow.'
			<tr>
				<td></td>
				<td></td>
				<td></td>
				<td>Total:</td>
				<td>$'.number_format($gtotal,2,'.','').'</td>
				<td>&nbsp;</td>
			</tr>
		</table>
		<br>
		<p>Thank you '.$_POST['billfirst'].' for shopping at MJTrends.com!<br />
		Should you have any questions or if there is anything else we can help you with, feel free to contact us by emailing:
		<a href="mailto:orders@MJTrends.com">orders@MJTrends.com</a>
		</p>
		Sincerely,<br>
		MJTrends.com Customer Service</body></html>
		';

	// To send HTML mail, the Content-type header must be set
	$headers  = 'MIME-Version: 1.0' . "\r\n";
	$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";

	// Additional headers
	//$headers .= 'To: '.$_POST['billfirst'].' <'.$_POST['email'].'>' . "\r\n";
	$headers .= 'From: MJTrends <orders@mjtrends.com>' . "\r\n";
	$headers .= 'Bcc: Orders <orders@mjtrends.com>' . "\r\n";

	// Mail it
	mail($to, $subject, $message, $headers);
	
	setConvo();
}

function setConvo(){
	$db = DB::getInstance();
	$custid = $db->real_escape_string($_COOKIE['custid']);
	
	if($_COOKIE['convo'] != ""){
		$newsid = $db->real_escape_string($_COOKIE['convo']);
		
		$query  = "UPDATE newsletter_stats SET newsletter_stats.custid = '$custid', newsletter_stats.type = 'conversion' WHERE newsletter_stats.ID = '$newsid'";
		$result = $db->query($query);
	}
	if($_COOKIE['adCookie']){
		$id = $db->real_escape_string($_COOKIE['adCookie']);
		$query  = "UPDATE ad_stats SET conversion = '$custid' WHERE uid = $id";
		$result = $db->query($query);
	}
	if($_COOKIE['catTest']){
		$cookie_array = explode("+", $_COOKIE['catTest']);
		$variation = $cookie_array[0];
		
		include('mvt.php');
		$mvt = new mvt;
		$mvt->saveConversion($variation);
		
		setcookie("catTest", "", time() - 3600); //delete the cookie
	}
}

function setShipOnRoll($subtotal){
	global $shipInfoCost, $addedShipOnRoll, $coupon, $colspan, $rateArray, $style, $tax;
	
	$shipInfoCost	= (float)$_SESSION['shipNfo']['cost'];
	$gtotal	= $subtotal+$_SESSION['shipNfo']['cost']+$tax+$_SESSION['addedShipOnRoll'];
	if(isset($_SESSION['couponCode'])) $gtotal -= $coupon->total_discount;
	
	$_SESSION['shipCost'] = $_SESSION['shipNfo']['cost']+$_SESSION['addedShipOnRoll'];
	$_SESSION['gtotal'] = $gtotal;
	$_SESSION['completed'] = true;
}