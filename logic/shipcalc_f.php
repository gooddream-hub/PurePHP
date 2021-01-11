<?php 
chkContents();

function init(){
	global $showRate;
	$showRate = 'none';
	if(isset($_POST['shipZip'])) setServiceType($_POST['fedCo'],''); 
	if(isset($_POST['shipType'])) setShipSession();//user has selected their service and is saving
}

function getUsr(){
	$query = "SELECT shipstate, shipzip FROM users WHERE email = '". $_COOKIE['usr'] ."'";
	$result = mysql_query($query);
	$row = mysql_fetch_assoc($result);
		
	setInputs($row['shipstate'], $row['shipzip']);
}

function setInputs($state, $zip){
	echo "<script>document.getElementById('shipZip').value = '". $zip ."'; setSelect('shipState','". $state ."');</script>";
}

function chkContents(){//if contents are empty, base shipping rates on 3lb package, tell user
	global $emptCart;
	if(empty($_SESSION['cart'])){
		$emptCart = '<b>Your cart is empty</b>: therefore an average package weight of 3 lbs will be used to estimate a shipping cost.  After adding items to your cart, your rate may increase or decrease.';
	} else {
		$emptCart = '';
	}
}

function setServiceType($country,$address){
	$swatch = checkSwatch();
	if($swatch == true && $country == 'US'){
		$uspsService['FIRST CLASS'] 		= 'USPS 1st Class';
		$uspsService['PRIORITY'] 			= 'USPS Priority';
		$fedexService['STANDARD_OVERNIGHT']	= 'Fedex Express';
	} elseif($swatch == true && $country != 'US'){
		$uspsService['INTL Priority']			= 'Priority Letter';
		$uspsService['INTL First']				= 'USPS 1st Class';
		$fedexService['INTERNATIONAL_ECONOMY']	= 'Global Envelope';
	} else {
		if($country == 'US'){
			$fedexService['GROUND_HOME_DELIVERY']    = 'FedEx Ground';
			$fedexService['FEDEX_2_DAY']             = 'FedEx 2 Day';
			$fedexService['STANDARD_OVERNIGHT']     = 'FedEx Overnight';
			$uspsService['PRIORITY']			   = 'USPS Priority';
			$uspsService['EXPRESS'] 			   = 'USPS Express';
		} else {
			if($country == 'CA') $fedexService['FEDEX_GROUND']    = 'FedEx Ground';
			$fedexService['INTERNATIONAL_ECONOMY']  = 'FedEx Intl Economy';	
			$fedexService['INTERNATIONAL_PRIORITY'] = 'FedEx Intl Priority';	
			$uspsService['INTL']  				   = 'USPS Intl';	
		}
	}
	if(checkPOB($country, $address) == false) getFedRates($fedexService, $swatch);
	getUSPS($uspsService, $swatch, false);
	setFlatRate($country);
	setRateOrder();
}

function setFlatRate($country){
	$flatRate = checkFlatRate();
	if($flatRate == true){
		if($country == 'US'){
			$uspsService['PRIORITY'] = 'USPS Flat Rate';
			getUSPS($uspsService, $swatch, $flatRate);
		} else {
			$uspsService['INTL'] = 'USPS Flat Rate';
			getUSPS($uspsService, $swatch, $flatRate);
		}
	}
}

function getFedRates($fedexService, $swatch){
	global $rateArray, $error, $shipDate, $resArray;
	$error = '';
	$size = getSize();
	$shipDate = getShipDate(0,date('G'));
	
	if($resArray){
		$state = $resArray["SHIPTOSTATE"];
		$zip = $resArray["SHIPTOZIP"];
		$fedCo = $resArray["SHIPTOCOUNTRYCODE"];
		$dest = 'true';	
	} else {
		(!isset($_POST['shipState'])) ? $state = $_SESSION['shipNfo']['state'] : $state = $_POST['shipState'];
		(!isset($_POST['shipZip'])) ? $zip = $_SESSION['shipNfo']['zip'] : $zip = $_POST['shipZip'];
		(!isset($_POST['fedCo'])) ? $fedCo = $_SESSION['shipNfo']['fedCo'] : $fedCo = $_POST['fedCo'];
		(!isset($_POST['dest'])) ? $dest = $_SESSION['shipNfo']['dest'] : $dest = $_POST['dest'];
	}
	if($state == " ") $state = "";
	if($fedCo != "US" && $fedCo != "CA") $state = "";
	if($fedCo == "CA" && strlen($state) > 2 ) $state = convertState($state);
	
	foreach($fedexService as $service=>$serviceName){
		$fedex = new Fedex;
		$fedex->setWeightUnits("LBS");
		if($swatch == true){
			$fedex->setPackaging("FEDEX_ENVELOPE");
			$fedex->setLength(13);
			$fedex->setWidth(9);
			$fedex->setHeight(0);
			$fedex->setUnits("IN");
			$fedex->setWeight(.4);
		} else {
			$fedex->setPackaging("YOUR_PACKAGING");
			$fedex->setLength($size['length']);
			$fedex->setWidth($size['width']);
			$fedex->setHeight($size['height']);
			$fedex->setUnits("IN");
			$fedex->setWeight(getWeight());
		}
		$fedex->setOriginStateOrProvinceCode("VA");
		$fedex->setOriginPostalCode(20164);
		$fedex->setOriginCountryCode("US");
		$fedex->setDestStateOrProvinceCode($state);
		$fedex->setDestPostalCode($zip);
		$fedex->setDestCountryCode($fedCo);
		$fedex->setResidentialDelivery($dest);
		$fedex->setService($service, $serviceName);
		$fedex->setPayorType("SENDER");

		$price = $fedex->getPrice();

		$error = $fedex->error->description;
		
		if($error == ''){
			if(is_numeric($fedex->price->rate) AND $fedex->price->rate != 0){
				if($service == "FEDEX_GROUND" OR $service == "GROUND_HOME_DELIVERY"){
					$rateArray[] = array('code' => $service, 'service' => $serviceName, 'cost' => $fedex->price->rate+2, 'shipDate' => $shipDate, 'shipTime' => ($fedex->transit+1) .'-'.($fedex->transit+3) .' days', 'delDate' => getShipDate($fedex->transit+1,date('G'))." to ".getShipDate($fedex->transit+3,date('G')));
				} elseif($service == "FEDEX_2_DAY" && $fedex->price->rate != 0){
					$ship1 = getShipDate(2,date('G'));
					$ship2 = getShipDate(3,date('G'));
					($ship1 == $ship2) ? $delDate = $ship1 : $delDate = $ship1. " to ".$ship2;
					$rateArray[] = array('code' => $service, 'service' => $serviceName, 'cost' => $fedex->price->rate+8, 'shipDate' => $shipDate, 'shipTime' => '2-3 days', 'delDate' => $delDate);
				} elseif($service == "INTERNATIONAL_ECONOMY"){
					$rateArray[] = array('code' => $service, 'service' => $serviceName, 'cost' => $fedex->price->rate+12, 'shipDate' => $shipDate, 'shipTime' => '7-12 days', 'delDate' => getShipDate(7,date('G'))." to ".getShipDate(12,date('G')));
				}elseif($service == "INTERNATIONAL_PRIORITY"){
					$rateArray[] = array('code' => $service, 'service' => $serviceName, 'cost' => $fedex->price->rate+12, 'shipDate' => $shipDate, 'shipTime' => '3-7 days', 'delDate' => getShipDate(3,date('G'))." to ".getShipDate(7,date('G')));
				} elseif($service == "STANDARD_OVERNIGHT" && $fedex->price->rate != 0){
					$ship1 = getShipDate(1,date('G'));
					$ship2 = getShipDate(2,date('G'));
					($ship1 == $ship2) ? $delDate = $ship1 : $delDate = $ship1. " to ".$ship2;
					$rateArray[] = array('code' => $service,'service' => $serviceName, 'cost' => $fedex->price->rate+13, 'shipDate' => $shipDate, 'shipTime' => '1-2 days', 'delDate' => $delDate);
				}
			}
		}
	}
}

function getUSPS($uspsService, $swatch, $flatRate){
	global $rateArray, $shipDate, $resArray;
	$i = 0;
	
	if(isset($resArray)){
		$zip = $resArray["SHIPTOZIP"];
		$uspsCo = convertCountry($resArray["SHIPTOCOUNTRYCODE"]);
	} else {
		(!isset($_POST['shipZip'])) ? $zip = $_SESSION['shipNfo']['zip'] : $zip = $_POST['shipZip'];
		(!isset($_POST['uspsCo'])) ? $uspsCo = $_SESSION['shipNfo']['uspsCo'] : $uspsCo = $_POST['uspsCo'];
	}
	
	foreach($uspsService as $service=>$serviceName){
		$usps = new USPS;
		if(substr($service,0,4) == 'INTL'){
			$usps->setAPI('IntlRate');
		} else {
			$usps->setAPI('RateV3');
			$zip = substr($zip, 0, 5);
		}
		$usps->setService($service);
		$usps->setUserId('496MJTRE2583');
		$usps->setPackageId($i);
		$usps->setZipOrigin(20164);
		$usps->setZipDest($zip); 
		$usps->setPounds(getWeight());
		$usps->setCountry($uspsCo);
		($flatRate == true && $swatch != true) ? $usps-> setContainer('FLAT RATE Box') : $usps -> setContainer('');
		if($swatch == true) $usps-> setContainer('FLAT RATE ENVELOPE');
		if(substr($service,0,4) == 'INTL') $usps-> setMailType('Package');
		if($swatch == true && substr($service,0,4) == 'INTL') $usps-> setMailType('Envelope');
		$i++;
		
		$price = $usps->getPrice();
		($uspsCo == 'CANADA') ? $addon = 4 : $addon = 8;
		
		if(!is_numeric($usps->price) OR $usps->price == 0){
			$rateArray[] = array('code' => $service, 'service' => 'USPS Error', 'cost' => 'error', 'shipDate' => 'error', 'shipTime' => 'error', 'delDate' => 'error');
		} else {
			if($service == "PRIORITY"){
				$ship1 = getShipDate(2,date('G'));
				$ship2 = getShipDate(4,date('G'));
				if(!is_null($usps->price)){
					($swatch == true) ? $rate = $usps->price+.25 : $rate = $usps->price+3;
				} else {
					$rate = "error";
				}
				($ship1 == $ship2) ? $delDate = $ship1 : $delDate = $ship1. " to ".$ship2;
				$rateArray[] = array('code' => $service, 'service' => $serviceName, 'cost' => $rate, 'shipDate' => $shipDate, 'shipTime' => '2-4 days', 'delDate' => $delDate);
			} elseif($service == "EXPRESS"){
				$ship1 = getShipDate(2,date('G'));
				$ship2 = getShipDate(3,date('G'));
				(!is_null($usps->price)) ? $rate = $usps->price+12 : $rate = "error";
				($ship1 == $ship2) ? $delDate = $ship1 : $delDate = $ship1. " to ".$ship2;
				$rateArray[] = array('code' => $service, 'service' => $serviceName, 'cost' => $rate, 'shipDate' => $shipDate, 'shipTime' => '2-3 days', 'delDate' => $delDate);
			}
			elseif($service == "INTL"){
				if($swatch == true) $addon = 2;
				(!is_null($usps->price)) ? $rate = $usps->price+$addon : $rate = "error";
				$rateArray[] = array('code' => $service, 'service' => $serviceName, 'cost' => $rate, 'shipDate' => $shipDate, 'shipTime' => '7-12 days', 'delDate' => getShipDate(7,date('G'))." to ".getShipDate(12,date('G')));
			}else {
				$rate = $usps->price+1.35;
				$rateArray[] = array('code' => $service, 'service' => $serviceName, 'cost' => $rate, 'shipDate' => $shipDate, 'shipTime' => '4-6 days', 'delDate' => getShipDate(4,date('G'))." to ".getShipDate(6,date('G')));
			}
		}
	}
}

function errCheck(){
	global $error;
	if($error != '' && trim($error) != 'Service is not allowed.') header("Location: shipcalc.php?error=". $error ."");
}

// Define the custom sort function
function custom_sort($a,$b) {
  return $a['cost']>$b['cost'];
}

function setRateOrder(){
	global $rateArray;
	// Sort the multidimensional array
	usort($rateArray, "custom_sort");
}

function convertCountry($iso){
	$query = "SELECT name FROM country WHERE iso_code = '".$iso."'";
	$result = mysql_query($query);
	
	$row = mysql_fetch_row($result);
	return $row[0];
}

function convertState($state){
	$stateArr[] = array("state" => "Alberta", "iso" => "AB");
	$stateArr[] = array("state" => "British Columbia", "iso" => "BC");
	$stateArr[] = array("state" => "Manitoba", "iso" => "MB");
	$stateArr[] = array("state" => "New Brunswick","iso" => "NB");
	$stateArr[] = array("state" => "Newfoundland and Labrador","iso" => "NL");
	$stateArr[] = array("state" => "Northwest Territories","iso" => "NT");
	$stateArr[] = array("state" => "Nova Scotia","iso" => "NS");
	$stateArr[] = array("state" => "Nunavut","iso" => "NU");
	$stateArr[] = array("state" => "Ontario","iso" => "ON");
	$stateArr[] = array("state" => "Prince Edward Island","iso" => "PE");
	$stateArr[] = array("state" => "Quebec","iso" => "QC");
	$stateArr[] = array("state" => "Saskatchewan","iso" => "SK");
	$stateArr[] = array("state" => "Yukon","iso" => "YT");
	
	foreach($stateArr as $row){
		if($row['state'] == $state){
			$iso = $row['iso'];
			break;
		}
	}
	return $iso;
}