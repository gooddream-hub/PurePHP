<?php
//ZIP example USA: 20164 Canada: T2C3Z4
require(dirname(dirname(__FILE__)).'/logic/xmlparser.php');
//require(dirname(dirname(__FILE__)).'/usps/USPSEasy.php');
require(dirname(dirname(__FILE__)).'/usps/uspsRate.php');
require(dirname(dirname(__FILE__)).'/fedex/fedexRate.php');
require(dirname(dirname(__FILE__)).'/lib/packer/load.php');

class ShipRate{

	static $fedex_services = array(
			'STANDARD_OVERNIGHT_FIRST' => array(
				'title'=>'Fedex Express',
				'add_cost' => 5.00
				),
			'INTERNATIONAL_ECONOMY_FIRST' => array(
				'title'=>'Global Envelope',
				'add_cost' => 3.00
				),
			'GROUND_HOME_DELIVERY' => array(
				'title'=>'FedEx Ground',
				'add_cost' => 1.00
				),
			'FEDEX_2_DAY' => array(
				'title'=>'FedEx 2 Day',
				'add_cost' => 4.00
				),
			'STANDARD_OVERNIGHT' => array(
				'title'=>'FedEx Overnight',
				'add_cost' => 8.00
				),
			'FEDEX_GROUND' => array(
				'title'=>'FedEx Ground',
				'add_cost' => 0.00
				),
			'INTERNATIONAL_ECONOMY' => array(
				'title'=>'FedEx Intl Economy',
				'add_cost' => 0.00
				),
			'INTERNATIONAL_PRIORITY' => array(
				'title'=>'FedEx Intl Priority',
				'add_cost' => 4.00
				),
		);
	static $usps_services = array(
			'FIRST CLASS' => array(
				'title'=>'USPS 1st Class',
				'add_cost' => 1.5,
			),
			'PRIORITY' => array(
				'title'=>'USPS Priority',
				'add_cost' => 0.00,
				),
			'INTL PRIORITY' => array(
				'title'=>'Priority Letter',
				'add_cost' => 0.85,
				),
			'INTL FIRST' => array(
				'title'=>'USPS 1st Class',
				'add_cost' => 1.5,
				),
			'FLATRATE' => array(
				'title'=>'USPS Flat Rate',
				'add_cost' => 0.85,
				),
			'EXPRESS' => array(
				'title'=>'USPS Express',
				'add_cost' => 4.00,
				),
			'PARCELSELECT' => array(
				'title'=>'USPS ParcelSelect',
				'add_cost'=>0.85,
				),
			'REGIONALRATEBOXA' => array(
				'title'=>'USPS Regional Rate BoxA',
				'add_cost'=>0.45,
				),
			'REGIONALRATEBOXB' => array(
				'title'=>'USPS Regional Rate BoxB',
				'add_cost'=>0.45,
				),
			'INTL' => array(
				'title'=>'USPS Intl',
				'add_cost'=>2.00,
				),
			'INTL FLATRATE' => array(
				'title'=>'USPS Intl Flat Rate',
				'add_cost'=>0.45,
				),
		);
	
	public $shipCountry = false;

	public $uspsPacker;
	public $fedexPacker;

	public $uspsBoxes = array();
	public $fedexBoxes = array();

	public $uspsService = array();
	public $fedexService = array();

	public $firstClass = false;
	public $flatRate = false;
	public $RegionalRateBoxA = false;
	public $RegionalRateBoxB = false;
	protected $db = false;
	
	/*
	*	We need know country and cart item firstly
	*	depend of it max box size
	**/
	function init($cart, $shipCountry=false) {
		// Set shipping country
		if($shipCountry) {
			$this->shipCountry = $shipCountry;
		} else {
			die('Error: Shipping country not set.');
		}

		// USPS Packer
		$av_usps_boxes = Boxes::allAvailableBoxes($shipCountry, 'USPS');
		$this->uspsPacker = new Packer($cart, $av_usps_boxes);
		$this->uspsBoxes = $this->uspsPacker->zippack($av_usps_boxes);

		// FedEx Packer
		$av_fedex_boxes = Boxes::allAvailableBoxes($shipCountry, 'FEDEX');
		$this->fedexPacker = new Packer($cart, $av_fedex_boxes);
		$this->fedexBoxes = $this->fedexPacker->zippack($av_fedex_boxes);

		if( !$this->uspsBoxes && !$this->fedexBoxes ) {
			$this->msg = array('status' => 'error', 'msg' => 'ERROR: Have no found available boxes.');
		}

		// Is First Class, FlateRate or RegionalBox
		/* First Class, Flate Rate and Regional Boxes we use only if all in one box */
		if( 1 == count($this->uspsBoxes) ) { //Check for special boxes
			if($regional_boxes = $this->uspsPacker->is_in_box(Boxes::$regional_boxes)) {
				$this->$regional_boxes = true;
			}
			if($this->uspsPacker->is_in_box(Boxes::$flate_boxes)) {
				$this->flatRate = true;
			}
		}
		$first = array_values($this->uspsBoxes);
		if( count($this->uspsBoxes) == 1 && $first[0]['box'] == 'FirstClass' ) {
			$this->firstClass = true; // USPS AND FEDEX for now
		}
		/* END First Class, Flate Rate and Regional Boxes we use only if all in one box */
	}

	function validate($country, $zip){
		if($country == '' OR $zip == ''){
			$this->msg = array('status' => 'error', 'msg' => 'Country or Postal Code missing.');
			return false;
		} else {
			$zip = str_replace(' ', '', $zip);
			if($country == 'US') { // US postal code have 5 digital.
				if(preg_match('|[0-9]{5}|', $zip)) {
					return true;
				} else {
					$this->msg = array('status' => 'error', 'msg' => 'Not valid US Postal Code.');
					return false;
				}
			} elseif($country == 'CA') { // Ex: M4G3V7
				if(preg_match('|[A-Z]{1}[0-9]{1}[A-Z]{1}[0-9]{1}[A-Z]{1}[0-9]{1}|i', $zip)) {
					return true;
				} else {
					$this->msg = array('status' => 'error', 'msg' => 'Not valid Canadian Postal Code.');
					return false;
				}
			} else {
				return true;
			}
		}
	}

	function setServiceType(){
		// Set service type by Max Box size
		// Even we have multiple boxes we use one service for all with one delivery date

		if($this->firstClass == true && $this->shipCountry == 'US'){
			$this->uspsService['FIRST CLASS']				= 'USPS 1st Class';
			$this->uspsService['PRIORITY'] 					= 'USPS Priority';
			$this->fedexService['STANDARD_OVERNIGHT']		= 'Fedex Express';
		} elseif($this->firstClass == true && $this->shipCountry != 'US'){
			$this->uspsService['INTL PRIORITY'] 			= 'Priority Letter';
			$this->uspsService['INTL FIRST']				= 'USPS 1st Class';
			$this->fedexService['INTERNATIONAL_ECONOMY']	= 'Global Envelope';
			#$this->uspsService['ExpressMailInternational']	= 'USPS Intl Express';
		} else {
			if($this->shipCountry == 'US'){
				$this->fedexService['GROUND_HOME_DELIVERY']		= 'FedEx Ground';
				$this->fedexService['FEDEX_2_DAY']				= 'FedEx 2 Day';
				$this->fedexService['STANDARD_OVERNIGHT']		= 'FedEx Overnight';
				$this->uspsService['PRIORITY']					= 'USPS Priority';
				if($this->flatRate == true) {
					$this->uspsService['FLATRATE']				= 'USPS Flat Rate';
				}
				$this->uspsService['EXPRESS']					= 'USPS Express';
				$this->uspsService['PARCELSELECT']				= 'USPS ParcelSelect';
				if($this->RegionalRateBoxA == true) {
					$this->uspsService['REGIONALRATEBOXA']		= 'USPS Regional Rate BoxA';
				} elseif($this->RegionalRateBoxB == true) {
					$this->uspsService['REGIONALRATEBOXB']		= 'USPS Regional Rate BoxB';
				}
			} else {
				if($this->shipCountry == 'CA') 
					$this->fedexService['FEDEX_GROUND']			= 'FedEx Ground';
				$this->fedexService['INTERNATIONAL_ECONOMY']	= 'FedEx Intl Economy';	
				$this->fedexService['INTERNATIONAL_PRIORITY']	= 'FedEx Intl Priority';	
				$this->uspsService['INTL']						= 'USPS Intl';
				if($this->flatRate == true) {
					$this->uspsService['INTL FLATRATE']			= 'USPS Intl Flat Rate';
				}
				#$this->uspsService['ExpressMailInternational']	= 'USPS Intl Express';
			}
		}
	}

	function setServiceFromSession(){
		$service = strtoupper($_SESSION['shipNfo']['code']);
		$this->setService($service);
	}

	function setService($service){
		if(array_key_exists($service, self::$fedex_services)) {
			$this->fedexService[$service] = self::$fedex_services[$service]['title'];
		} elseif(array_key_exists($service, self::$usps_services)) {
			$this->uspsService[$service] = self::$usps_services[$service]['title'];
			if($service == 'INTL FIRST' || $service == 'FIRST CLASS') {
				$this->firstClass = 1;
			}
		} else {
			$this->msg = array('status' => 'error', 'msg' => 'Unknown shipping service.');
		}
	}

	function getFedexRates($user, $dest) {
		if($this->fedexBoxes) {
			// Groped boxes to make less calls to USPS
			foreach ($this->fedexBoxes as $box) {
				$rate = $this->getFedexBoxRates($user, $dest, $box);
				foreach ($rate as $r) {
					$rates[$r['service']][] = $r['price'] * $box['quantity'];
					$shipDates[$r['service']] = array('transit'=>$r['transit']);
				}
			}
			foreach ($rates as $srv => $value) {
				$price = array_sum($value);
				$box_count = count($value);
				if(is_numeric($price) AND $price != 0){
					if($srv == "FEDEX_GROUND" OR $srv == "GROUND_HOME_DELIVERY"){
						$ship1 = $this->getShipDate($shipDates[$srv]['transit']+1);
						$ship2 = $this->getShipDate($shipDates[$srv]['transit']+3);
						($ship1 == $ship2) ? $delDate = $ship1 : $delDate = $ship1. " to ".$ship2;
						$this->rateArray[] = array('code' => $srv, 'service' => $this->fedexService[$srv], 'cost' => $price, 'shipDate' => $this->shipDate, 'shipTime' => ($shipDates[$srv]['transit']+1) .'-'.($shipDates[$srv]['transit']+3) .' days', 'delDate' => $delDate, 'guaranteed' => 'No');
					} elseif($srv == "FEDEX_2_DAY"){
						//if wed add 2 days for sat and sun
						//if thur add 1 day for sun
						if(date('N') == 3){//wed
							$ship1 = $this->getShipDate(4);
							$ship2 = $this->getShipDate(5);
						} elseif(date('N') == 4) {//thur
							$ship1 = $this->getShipDate(3);
							$ship2 = $this->getShipDate(4);
						} else {
							$ship1 = $this->getShipDate(2);
							$ship2 = $this->getShipDate(3);
						}

						($ship1 == $ship2) ? $delDate = $ship1 : $delDate = $ship1. " to ".$ship2;
						$this->rateArray[] = array('code' => $srv, 'service' => $this->fedexService[$srv], 'cost' => $price, 'shipDate' => $this->shipDate, 'shipTime' => '2-3 days', 'delDate' => $delDate, 'guaranteed' => 'Yes');
					} elseif($srv == "INTERNATIONAL_ECONOMY"){
						$this->rateArray[] = array('code' => $srv, 'service' => $this->fedexService[$srv], 'cost' => $price, 'shipDate' => $this->shipDate, 'shipTime' => '7-12 days', 'delDate' => $this->getShipDate(7)." to ".$this->getShipDate(12), 'guaranteed' => 'No');
					}elseif($srv == "INTERNATIONAL_PRIORITY"){
						$this->rateArray[] = array('code' => $srv, 'service' => $this->fedexService[$srv], 'cost' => $price, 'shipDate' => $this->shipDate, 'shipTime' => '3-7 days', 'delDate' => $this->getShipDate(3)." to ".$this->getShipDate(7), 'guaranteed' => 'No');
					} elseif($srv == "STANDARD_OVERNIGHT"){
						$ship1 = $this->getShipDate(1);
						$ship2 = $this->getShipDate(2);
						($ship1 == $ship2) ? $delDate = $ship1 : $delDate = $ship1. " to ".$ship2;
						$this->rateArray[] = array('code' => $srv, 'service' => $this->fedexService[$srv], 'cost' => $price, 'shipDate' => $this->shipDate, 'shipTime' => '1-2 days', 'delDate' => $delDate, 'guaranteed' => 'Yes');
					}
				}
			}
		} else {
			$this->msg = array('status' => 'error', 'msg' => 'ERROR: Have no found available boxes.');
		}
	}

	function getFedexBoxRates($user, $dest, $box){
		$fedex = new Fedex;
		$fedex->setWeightUnits("LBS");
		if($this->firstClass == true){
			$fedex->setPackaging("FEDEX_ENVELOPE");
			$fedex->setLength(13);
			$fedex->setWidth(9);
			$fedex->setHeight(0);
			$fedex->setUnits("IN");
			$fedex->setWeight(.4);
		} else {
			$nowBox = Boxes::getBox($box['box']);
			$fedex->setPackaging("YOUR_PACKAGING");
			$fedex->setLength( $nowBox['size']['length'] );
			$fedex->setWidth( $nowBox['size']['width'] );
			$fedex->setHeight( $nowBox['size']['height'] );
			$fedex->setUnits("IN");
			$fedex->setWeight( $box['weight'] + $nowBox['weight'] );
		}
		$fedex->setOriginStateOrProvinceCode("VA");
		$fedex->setOriginPostalCode(20170);
		$fedex->setOriginCountryCode("US");

		$fedex->setDestPostalCode($user->shipzip);
		$fedex->setDestCountryCode($user->shipco);

		if($dest) $fedex->setResidentialDelivery($dest);

		$fedex->setPayorType("SENDER");
		$results = $fedex->getMultiPrice();
		if(isset($fedex->error->description)) {
			$error = $fedex->error->description;
		} else {
			$error = '';
		}

		if($error == ''){
			foreach( $results as $srv ) {
				if(array_key_exists($srv['service'], $this->fedexService))
				if(is_numeric($srv['rate']) AND $srv['rate'] != 0) {
					if($srv['service'] == "FEDEX_GROUND" OR $srv['service'] == "GROUND_HOME_DELIVERY"){
						$rates[] = array('service'=>$srv['service'], 'price'=>$srv['rate'], 'transit'=>$srv['transit'] );
					} elseif($srv['service'] == "FEDEX_2_DAY"){
						$rates[] = array('service'=>$srv['service'], 'price'=>($srv['rate']+4), 'transit'=>$srv['transit'] );
					} elseif($srv['service'] == "INTERNATIONAL_ECONOMY"){
						$rates[] = array('service'=>$srv['service'], 'price'=>$srv['rate'], 'transit'=>$srv['transit'] );
					}elseif($srv['service'] == "INTERNATIONAL_PRIORITY"){
						$rates[] = array('service'=>$srv['service'], 'price'=>($srv['rate']+4), 'transit'=>$srv['transit'] );
					} elseif($srv['service'] == "STANDARD_OVERNIGHT"){
						$rates[] = array('service'=>$srv['service'], 'price'=>($srv['rate']+8), 'transit'=>$srv['transit'] );
					}
				}
			}
		} else {
			$rates['error'] = $error;
		}
		return $rates;
	}

	function getUSPSRates($user, $subtotal) {
		if( $this->uspsBoxes ) {
			$i = 0;
			$usps = new USPS;
			if($this->shipCountry == 'US'){
				$usps->setAPI('RateV4');
				if($this->firstClass == true)
					$usps->setContainer('FLAT RATE ENVELOPE');
			} else {
				$usps->setAPI('IntlRateV2');
				$usps->setMailType('Package');
				if($this->firstClass == true)
					$usps->setMailType('Envelope');
			}
			$usps->setUserId('496MJTRE2583');
			$usps->setZipOrigin(20170);
			$usps->setZipDest($user->shipzip);
			$country = $this->convertCountry($user->shipco);
			$usps->setCountry($country);
			
			foreach($this->uspsService as $service => $title){
				if( $service == 'PARCELSELECT' ) { //Use EasyPost API for PARCELSELECT
					$uspsEasy = new USPSEasy();
					$uspsEasy->setAddress($user->shipzip, $this->convertCountry($user->shipco));
					foreach ($this->uspsBoxes as $box) {
						$box_details = Boxes::getBox($box['box']);
						$uspsEasy->setWeight($box['weight'] + $box_details['weight']); // We need own weight for each box
						$uspsEasy->setSizes($box_details['size']);
						$shipment = $uspsEasy->getDefaultRates();
						if($shipment) {
							foreach ($shipment->rates as $r) {
								if($r->service == 'ParcelSelect') {
									$parcel_rates['PARCELSELECT'][] = $r->rate * $box['quantity'];
								}
							}
						}
						unset($shipment);
					}
				} else {
					$k=0;
					foreach ($this->uspsBoxes as $box) {
						$box_details = Boxes::getBox($box['box']);
						
						if( ($service == 'FLATRATE' || $service == 'INTL FLATRATE') ) { // && $this->flatRate == true
							$usps->setContainer('MD FLAT RATE BOX');
							$usps->setService('PRIORITY');
						} elseif( ($service == 'REGIONALRATEBOXA' ) || ($service == 'REGIONALRATEBOXB' )){ //&& $this->RegionalRateBoxA && $this->RegionalRateBoxB
							$usps->setService('PRIORITY COMMERCIAL');
							$usps->setContainer($service);
						} else {
							$usps->setContainer('');
							$usps->setService($service);
						}
						$usps->setWeight($box['weight'] + $box_details['weight']);
						for($l=0; $l<$box['quantity'];$l++) {
							$k = $k+$l;
							$usps->setPackageId($service .'.'. $k);
							$usps->generateRequest($subtotal, $box_details['size']['width'], $box_details['size']['length'], $box_details['size']['height']);
						}
						$k++;
					}
					$i++;
				}
			}
			$rates = $usps->getResponse();
			
			if(isset($parcel_rates)) {
				$rates['PARCELSELECT'] = $parcel_rates['PARCELSELECT'];
			}
			$rates = $this->clearExpensive($rates);

			foreach ($rates as $srv => $value){
				$price = array_sum($value);
				$box_count = count($value);
				if(is_numeric($price) AND $price != 0) {
					if(isset(self::$usps_services[$srv]['add_cost'])) {
						$rate = $price + ( self::$usps_services[$srv]['add_cost'] * $box_count);
					} else {
						$rate = $price + ( 0.85 * $box_count);
					}

					if($srv == "REGIONALRATEBOXA" || $srv == 'REGIONALRATEBOXB'){
						$this->rateArray[] = array('code' => $srv, 'service' => $this->uspsService[$srv], 'cost' => $rate, 'shipDate' => $this->shipDate, 'shipTime' => '4-6 days', 'delDate' => $this->getShipDate(4)." to ".$this->getShipDate(6), 'guaranteed' => 'No');
					} elseif($srv == "PARCELSELECT"){
						$this->rateArray[] = array('code' => $srv, 'service' => $this->uspsService[$srv], 'cost' => $rate, 'shipDate' => $this->shipDate, 'shipTime' => '4-6 days', 'delDate' => $this->getShipDate(4)." to ".$this->getShipDate(6), 'guaranteed' => 'No');
					} elseif($srv == "FIRST CLASS"){
						$ship1 = $this->getShipDate(2);
						$ship2 = $this->getShipDate(4);
						($ship1 == $ship2) ? $delDate = $ship1 : $delDate = $ship1. " to ".$ship2;
						$this->rateArray[] = array('code' => $srv, 'service' => $this->uspsService[$srv], 'cost' => $rate, 'shipDate' => $this->shipDate, 'shipTime' => '2-4 days', 'delDate' => $delDate, 'guaranteed' => 'No');
					} elseif($srv == "PRIORITY"){
						$ship1 = $this->getShipDate(2);
						$ship2 = $this->getShipDate(4);
						($ship1 == $ship2) ? $delDate = $ship1 : $delDate = $ship1. " to ".$ship2;
						$this->rateArray[] = array('code' => $srv, 'service' => $this->uspsService[$srv], 'cost' => $rate, 'shipDate' => $this->shipDate, 'shipTime' => '2-4 days', 'delDate' => $delDate, 'guaranteed' => 'No');
					} elseif($srv == "EXPRESS"){
						$ship1 = $this->getShipDate(2);
						$ship2 = $this->getShipDate(3);
						($ship1 == $ship2) ? $delDate = $ship1 : $delDate = $ship1. " to ".$ship2;
						$this->rateArray[] = array('code' => $srv, 'service' => $this->uspsService[$srv], 'cost' => $rate, 'shipDate' => $this->shipDate, 'shipTime' => '2-3 days', 'delDate' => $delDate, 'guaranteed' => 'Yes');
					} elseif($srv == "INTL"){
						$this->rateArray[] = array('code' => $srv, 'service' => $this->uspsService[$srv], 'cost' => $rate, 'shipDate' => $this->shipDate, 'shipTime' => '7-12 days', 'delDate' => $this->getShipDate(7)." to ".$this->getShipDate(12), 'guaranteed' => 'No');
					} else {
						$this->rateArray[] = array('code' => $srv, 'service' => $this->uspsService[$srv], 'cost' => $rate, 'shipDate' => $this->shipDate, 'shipTime' => '4-6 days', 'delDate' => $this->getShipDate(4)." to ".$this->getShipDate(6), 'guaranteed' => 'No');
					}
				}
			}

			if($this->firstClass == true && $this->shipCountry != "US"){
				$this->rateArray[] = array('code' => $srv, 'service' => "USPS First Class", 'cost' => 3.85, 'shipDate' => $this->shipDate, 'shipTime' => '7-12 days', 'delDate' => $this->getShipDate(7)." to ".$this->getShipDate(12), 'guaranteed' => 'No');
			}
		} else {
			$this->msg = array('status' => 'error', 'msg' => 'ERROR: Have no found available USPS boxes.');
		}
	}
	
	function custom_sort($a, $b){
		return $a['cost'] > $b['cost'];
	}

	function format_rates(&$a){
		$a['cost'] = number_format($a['cost'], 2);
	}

	function setRateOrder($is_format=true){
		if(count($this->rateArray) > 1){
			usort($this->rateArray, array($this, "custom_sort"));
			if($is_format) {
				array_walk($this->rateArray, array($this, "format_rates"));
			}
		}
	}

	function validateRates(){
		if(count($this->rateArray) == 0){
			$this->msg = array('status' => 'error', 'msg' => 'ERROR: Shipping Rate Failure');
		} else {
			$this->msg = array('status' => 'success');
		}
	}

	function getShipDate($days){
		putenv('TZ=America/New_York');
		$hour = date('G');

		if($hour < 17){ //orders that come in before 6:00pm today go out in 1 day
			$shipDay = 1 + $days;
		} else {
			$shipDay = 2 + $days; //orders that come in after 6:00pm today go out in 2 days
		}

		if(date('N') == 5) $shipDay += 3; //if shipdate is a Fri, push shipdate to Mon
		if(date('N') == 6) $shipDay += 2; //if shipdate is a Sat., push shipdate to Mon
		if(date('N') == 7) $shipDay += 1; //if shipdate is a Sun, push shipdate to Mon
		
		return date("D n/j", strtotime("+".$shipDay." day"));
	}

	function convertCountry($iso){
		//conn(); // Not good idea, but when we call this class from ajaxController we have no connection.
		//include('global.php');
		$db = DB::getInstance();
		$sql = "SELECT name FROM country WHERE iso_code = '".$iso."'";
		$result = $db->query($sql);
		
		$row = $result->fetch_row();
		return $row[0];
	}

	/* this function must run with cron */
	function countryParams($offset=0, $limit=10) {
		$usps = new USPS;
		$usps->setUserId('496MJTRE2583');
		$usps->setAPI('IntlRateV2');
		$usps->setZipOrigin(20170);
		$this->db = mysqliConn();
		
		$sql = "SELECT iso_code, name FROM country WHERE iso_code != 'US' LIMIT ". $offset .", ". $limit;
		$query = $this->db->query($sql);
		$results = array();
		while( $row = $query->fetch_array(MYSQLI_ASSOC) ) {
			if($result = $usps->getMaxParams($row['name'])) {
				$results[$result['country']] = $result;
				$sql_up = "UPDATE country SET srv_desc=?, priority_max_length=?, priority_max_combined=?, priority_max_weight=? WHERE iso_code = ?";
				$stmt = $this->db->prepare($sql_up);
				$stmt->bind_param('siiis', $srv_desc, $priority_max_length, $priority_max_combined, $priority_max_weight, $iso_code);
				extract($result);
				$iso_code = $row['iso_code'];
				$stmt->execute();
			}
			unset($result);
		}
		return $results;
	}

	function clearExpensive($rates) {
		$minrate = array('PRIORITY','FLATRATE','PARCELSELECT','REGIONALRATEBOXA', 'REGIONALRATEBOXB');
		$alowrates = array();
		foreach ($minrate as $val) {
			if(isset($rates[$val])) {
				$alowrates[$val] = array('cost'=>array_sum($rates[$val]));
			}
		}
		uasort($alowrates, array($this, "custom_sort"));
		array_shift($alowrates);
		foreach ($alowrates as $srv => $rate) {
			if($srv != 'PRIORITY') {
				unset($rates[$srv]);
			}
		}
		return $rates;
	}

	function returnRate(){
		$msgArray = $this->returnRateArray();
		echo json_encode($msgArray);
	}

	function returnRateArray(){
		if(!empty($this->rateArray)){
			$msgArray['status'] = 'success';
			$msgArray['rates'] = $this->rateArray;
		} else {
			$msgArray['status'] = 'failure';
			$msgArray['msg'] = $this->msg['msg'];
		}
		return $msgArray;
	}
}