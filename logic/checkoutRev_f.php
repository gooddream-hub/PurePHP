<?
$user = new User;
$userInfo = getUserInfo();
$user->setAddress($userInfo);
$user->getAddress();
$user->setUserCookie($userInfo);

$cart = new Cart;
$cart->setSessionCart();
$cart->setSubtotal();
$cart->setTax($user);

if(strcasecmp($user->fedCo, 'US') == 0 OR strcasecmp($user->fedCo, 'CA') == 0) {
	validateAddress();
}

$rate = new ShipRate;
$rate->setSwatch();
$rate->setSize();
$rate->setWeight();
$rate->setFlatRate();

if($_SESSION['shipNfo'] != "" && !((substr($_SESSION['shipNfo']['code'],0,11) == 'FIRST CLASS' OR substr($_SESSION['shipNfo']['code'],0,10) == 'INTL First') && $rate->swatch == false)){
	$rate->setServiceFromSession();
} else {
	unset($_SESSION['shipNfo']); //unset it because it may have been set to first class
	$rate->setServiceType($user->shipco);
}

$rate->shipDate = $rate->getShipDate(0);
if(!empty($rate->fedexService)) $rate->getFedexRates($user, 'true');   
if(!empty($rate->uspsService))  $rate->getUSPSRates($user); 
$rate->setRateOrder();
$rate->validateRates();

if($_SESSION['shipNfo'] != "" && $rate->msg['status'] == 'error'){ //if there was an error and it came from the service in session
	unset($_SESSION['shipNfo']); //unset it because it has errored out
	$rate->setServiceType($user->shipco);
	if(!empty($rate->fedexService)) $rate->getFedexRates($user, 'true');   
	if(!empty($rate->uspsService))  $rate->getUSPSRates($user); 
	$rate->setRateOrder();
	$rate->validateRates();
}

//session only had the code.  Had to get rates again.  Now put into session for checkout_complete
if($_SESSION['shipNfo'] != ""){
	$_SESSION['shipNfo'] = array('code' => $rate->rateArray[0]['code'],'serviceName' => $rate->rateArray[0]['service'], 'shipTime' => $rate->rateArray[0]['shipTime'], 'cost' => $rate->rateArray[0]['cost'], 'delDate' => $rate->rateArray[0]['delDate']);
}

$coupon = new Coupon();
$coupon->validate();

$shipRoll = new ShipOnRoll;
$shipRoll->setShipRollCost();

/***********************
Helper functions below
************************/

function getUserInfo(){
	$target = array('#','.','-');

	if(isset($_POST['payType'])){
		$payType = $_POST['payType'];
		$shipFirst = $_POST['shipfirst'];
		$shipLast = $_POST['shiplast'];
		$shipComp = $_POST['shipComp'];
		$shipAd1 = $_POST['shipAd1'];
		$shipAd2 = $_POST['shipAd2'];
		$shipCity = $_POST['shipCity'];
		$shipState = $_POST['shipState'];
		$shipZip = $_POST['shipZip'];
		$fedCo = $_POST['fedCo'];
		//$uspsCo = $_POST['uspsCo'];
		$shipPh = $_POST['shipPh1']."-".$_POST['shipPh2']."-".$_POST['shipPh3']."-".$_POST['shipPh4'];
		$email = $_POST['email'];
		$billFirst = $_POST['billfirst'];
		$billLast = $_POST['billlast'];
		$billComp = $_POST['billComp'];
		$billAd1 = $_POST['billAd1'];
		$billAd2 = $_POST['billAd2'];
		$billCity = $_POST['billCity'];
		$billState= $_POST['billState'];
		$billZip = $_POST['billZip'];
		$billCo = $_POST['billCo'];
		$billPh = $_POST['billPh1']."-".$_POST['billPh2']."-".$_POST['billPh3']."-".$_POST['billPh4'];

		return array('shipfirst' => $shipFirst, 'shiplast' => $shipLast, 'shipcomp' => $shipComp, 'shipad1' => str_replace($target,'',$shipAd1), 'shipad2' => str_replace($target,'',$shipAd2), 'shipcity' => $shipCity, 'shipstate' => $shipState, 'shipzip' => $shipZip, 'shipco' => $fedCo,  'shipPh' => $shipPh, 'email' => $email,'billfirst' => $billFirst, 'billlast' => $billLast, 'billcomp' => $billComp, 'billad1' => str_replace($target,'',$billAd1), 'billad2' => str_replace($target, '',$billAd2), 'billcity' => $billCity, 'billstate' => $billState, 'billzip' => $billZip, 'billco' => $billCo, 'billPh' => $billPh, 'payType' => $payType);
	} else {
		return getPayPal();
	}
}

function validateAddress(){
	require_once('nusoap/nusoap.php');
	$proxyhost = '';
	$proxyport = '';
	$proxyusername = '';
	$proxypassword = '';

	$client = new nusoap_client("nusoap/AddressValidationService_v2.wsdl", true, $proxyhost, $proxyport, $proxyusername, $proxypassword);
	
	$request['WebAuthenticationDetail'] = array('UserCredential' => array('Key' => '2XOHPWjnZPvRA9cP', 'Password' => 'EGNV2NTEjGWrL2ZONVpOAeikG')); // Replace 'XXX' and 'YYY' with FedEx provided credentials 
	$request['ClientDetail'] = array('AccountNumber' => '322190360', 'MeterNumber' => '5621032'); // Replace 'XXX' with client's account and meter number
	$request['TransactionDetail'] = array('CustomerTransactionId' => '1');
	$request['Version'] = array('ServiceId' => 'aval', 'Major' => '2', 'Intermediate' => '0', 'Minor' => '0');
	$request['RequestTimestamp'] = date('Y-m-d').'T'.date('H:i:s').'+00:00';
	$request['Options'] = array('CheckResidentialStatus' => 0,
	                             'MaximumNumberOfMatches' => 5,
	                             'StreetAccuracy' => 'LOOSE',
	                             'DirectionalAccuracy' => 'LOOSE',
	                             'CompanyNameAccuracy' => 'LOOSE',
	                             'ConvertToUpperCase' => 0,
	                             'RecognizeAlternateCityNames' => 1,
	                             'ReturnParsedElements' => 1);
	$request['AddressesToValidate'] = array(0 => array('AddressId' => rand(),
	                             'Address' => array('StreetLines' => array($user->shipad1." ".$user->shipad2),
	                             'City' => $user->shipcity,
	                             'StateOrProvinceCode' => $user->shipstate,
	                             'CountryCode' => $user->fedCo,
	                             'PostalCode' => $user->shipzip)));
	//print_r($request);
	$result = $client->call('addressValidation', array($request));

	if($result['HighestSeverity'] == 'SUCCESS'){ 
		if($result['AddressResults']['ProposedAddressDetails']['DeliveryPointValidation'] == 'CONFIRMED' OR $result['AddressResults']['ProposedAddressDetails']['DeliveryPointValidation'] == 'UNAVAILABLE'){
			// update address w/o notifying user
			$address['shipad1'] = $result['AddressResults']['ProposedAddressDetails']['Address']['StreetLines'];
			$address['shipad2'] = '';
			$address['shipcity'] = $result['AddressResults']['ProposedAddressDetails']['Address']['City'];
			$address['shipstate'] = $result['AddressResults']['ProposedAddressDetails']['Address']['StateOrProvinceCode'];
			$address['shipzip'] = $result['AddressResults']['ProposedAddressDetails']['Address']['PostalCode'];
			$user->setAddress($address);
			$user->getAddress();
		} elseif($result['AddressResults']['ProposedAddressDetails']['DeliveryPointValidation'] == 'UNCONFIRMED'){//we have a change that user needs to approve or modify (missing apartment number, incorrectable zip code / city/ etc.)
			if(!is_array($result['AddressResults']['ProposedAddressDetails']['Changes'])){
				$errorArray = array($result['AddressResults']['ProposedAddressDetails']['Changes']);
			} else {
				foreach($result['AddressResults']['ProposedAddressDetails']['Changes'] as $row){
					if(strcasecmp($row,'MODIFIED_TO_ACHIEVE_MATCH') != 0){
						$errorArray[] = str_replace('_',' ',$row);
					}
				}
			}
			//$addError = 'block';  turn this back on to show address errors
		} 
	}

}

function getPayPal(){
	global $shipArr, $billArr, $resArray;
	/*==================================================================
	 PayPal Express Checkout Call
	 ===================================================================
	*/
	// Check to see if the Request object contains a variable named 'token'	
	

	$token = "";
	if (isset($_REQUEST['token']))
	{
		$token = $_REQUEST['token'];
	}

	// If the Request object contains the variable 'token' then it means that the user is coming from PayPal site.	
	if ( $token != "" ){
		require_once ("paypalfunctions.php");

		/*
		'------------------------------------
		' Calls the GetExpressCheckoutDetails API call
		'
		' The GetShippingDetails function is defined in PayPalFunctions.jsp
		' included at the top of this file.
		'-------------------------------------------------
		*/
		

		$resArray = GetShippingDetails( $token );
		$ack = strtoupper($resArray["ACK"]);
		if( $ack == "SUCCESS" )
		{
			/*
			' The information that is returned by the GetExpressCheckoutDetails call should be integrated by the partner into his Order Review 
			' page		
			*/

			$email 				= $resArray["EMAIL"]; // ' Email address of payer.
			$payerId 			= $resArray["PAYERID"]; // ' Unique PayPal customer account identification number.
			$payerStatus		= $resArray["PAYERSTATUS"]; // ' Status of payer. Character length and limitations: 10 single-byte alphabetic characters.
			$shipToName			= $resArray["SHIPTONAME"]; // ' Person's name associated with this address.
			$addressStatus 		= $resArray["ADDRESSSTATUS"]; // ' Status of street address on file with PayPal   
			$invoiceNumber		= $resArray["INVNUM"]; // ' Your own invoice or tracking number, as set by you in the element of the same name in SetExpressCheckout request .
			
			$_SESSION['payer_id'] 	= $payerId;
			$_SESSION['token']		= $token;
		
			return array("shipfirst" => $resArray["FIRSTNAME"], "shiplast" => $resArray["LASTNAME"], "shipcomp" => $resArray["BUSINESS"], "shipad1" => $resArray["SHIPTOSTREET"], "shipad2" => $resArray["SHIPTOSTREET2"], "shipcity" => $resArray["SHIPTOCITY"], "shipstate" => $resArray["SHIPTOSTATE"], "shipzip" => $resArray["SHIPTOZIP"], "shipco" => $resArray["SHIPTOCOUNTRYCODE"], "shipPh" => $resArray["PHONENUM"], "billfirst" => $resArray["FIRSTNAME"], "billlast" => $resArray["LASTNAME"], "billcomp" => $resArray["BUSINESS"], "billad1" => $resArray["SHIPTOSTREET"], "billad2" => $resArray["SHIPTOSTREET2"], "billcity" => $resArray["SHIPTOCITY"], "billstate" => $resArray["SHIPTOSTATE"], "billzip" => $resArray["SHIPTOZIP"], "billco" => $resArray["SHIPTOCOUNTRYCODE"], "billPh" => $resArray["PHONENUM"], "payType" => "pp", "email" => $email);
		} 
		else  
		{
			//Display a user friendly Error on the page using any of the following error information returned by PayPal
			$ErrorCode = urldecode($resArray["L_ERRORCODE0"]);
			$ErrorShortMsg = urldecode($resArray["L_SHORTMESSAGE0"]);
			$ErrorLongMsg = urldecode($resArray["L_LONGMESSAGE0"]);
			$ErrorSeverityCode = urldecode($resArray["L_SEVERITYCODE0"]);
			
			return array("error" => true, "errorMsg" => "PayPal Payment Error: ".$ErrorLongMsg);
		}
	}
}
