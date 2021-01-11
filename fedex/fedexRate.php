<?php

class Fedex {
	var $destPostalCode;
	var $destStateOrProvinceCode;
	var $error;
	var $services = array(
		'STANDARD_OVERNIGHT' => 'Fedex Express', //1st class
		'INTERNATIONAL_ECONOMY' => 'Global Envelope',

		'GROUND_HOME_DELIVERY' => 'FedEx Ground',
		'FEDEX_2_DAY' => 'FedEx 2 Day',
		'STANDARD_OVERNIGHT' => 'FedEx Overnight',
		'FEDEX_GROUND' => 'FedEx Ground',
		'INTERNATIONAL_ECONOMY' => 'FedEx Intl Economy',
		'INTERNATIONAL_PRIORITY' => 'FedEx Intl Priority',
	);

    // Functions    
    function setServer($server) {
        $this->server = $server;
    }

    function setAccountNumber($accountNumber) {
        $this->accountNumber = $accountNumber;
    }

    function setMeterNumber($meterNumber) {
        $this->meterNumber = $meterNumber;
    }

    function setCarrierCode($carrierCode) {
        $this->carrierCode = $carrierCode;
    }
    
    function setDropoffType($dropoffType) {
        $this->dropoffType = $dropoffType;
    }

    function setService($service, $name) {
        if($name == 'FedEx Ground' && $this->residentialDelivery == '0'){
        	$this->service = 'FEDEX_GROUND';
        } else {
            $this->service = $service;
        }
        $this->serviceName = $name;
    }

    function setPackaging($packaging) {
        $this->packaging = $packaging;
    }
    
    function setWeightUnits($units) {
        $this->weightUnits = $units;
    }
    
    function setWeight($weight) {
        $this->weight = $weight;
    }
	
	function setLength($length) {
        $this->length = $length;
    }
	
	function setWidth($width) {
        $this->width = $width;
    }
	
	function setHeight($height) {
        $this->height = $height;
    }
	
	function setUnits($units) {
        $this->units = $units;
    }
    
    function setOriginStateOrProvinceCode($originState) {
        $this->originStateOrProvinceCode = $originState;
    }
    
    function setOriginPostalCode($originPostal) {
        $this->originPostalCode = $originPostal;
    }
    
    function setOriginCountryCode($originCo) {
        $this->originCountryCode = $originCo;
    }
    
    function setDestStateOrProvinceCode($destState) {
        $this->destStateOrProvinceCode = $destState;
    }
    
    function setDestPostalCode($destPostal) {
        $this->destPostalCode = $destPostal;
    }
    
    function setDestCountryCode($destCo) {
        $this->destCountryCode = $destCo;
    }

	function setValue($value) {
		$this->value = $value;
	}

	function setCurrencyCode($currency) {
		$this->currencyCode = $currency;
	}
	
	function setResidentialDelivery($residentialDelivery){
		$this->residentialDelivery = $residentialDelivery;
	}

	function setSignatureOption($signatureOption){
		$this->signatureOption = $signatureOption;
	}

	function setPayorType($type) {
		$this->payorType = $type;
	}

	function getMultiPrice()
	{
		$newline = "<br />";
		$path_to_wsdl = "http://www.mjtrends.com/nusoap/RateService_v10.wsdl";

		ini_set("soap.wsdl_cache_enabled", "0");
		
		$client = new SoapClient($path_to_wsdl, array('trace' => 1)); // Refer to http://us3.php.net/manual/en/ref.soap.php for more information
		
		$request['WebAuthenticationDetail'] = array(
			'UserCredential' =>array(
				'Key' => '2XOHPWjnZPvRA9cP', 
				'Password' => 'EGNV2NTEjGWrL2ZONVpOAeikG'
			)
		); 
		$request['ClientDetail'] = array(
			'AccountNumber' => '322190360', 
			'MeterNumber' => '5621032'
		);
		$request['TransactionDetail'] = array('CustomerTransactionId' => '1');
		$request['Version'] = array(
			'ServiceId' => 'crs', 
			'Major' => '10', 
			'Intermediate' => '0', 
			'Minor' => '0'
		);
		$request['ReturnTransitAndCommit'] = true;
		$request['RequestedShipment']['DropoffType'] = 'STATION'; // valid values REGULAR_PICKUP, REQUEST_COURIER, ...
		$request['RequestedShipment']['ShipTimestamp'] = date('c');
		// For Rate Available Services (multiple services returned), do not include the ServiceType. The system will then return a list of services. (page 35 of doc)
		#$request['RequestedShipment']['ServiceType'] = ''; //$this->service; // valid values STANDARD_OVERNIGHT, PRIORITY_OVERNIGHT, FEDEX_GROUND, ...
		$request['RequestedShipment']['PackagingType'] = $this->packaging; // valid values FEDEX_BOX, FEDEX_PAK, FEDEX_TUBE, YOUR_PACKAGING, ...
		$request['RequestedShipment']['TotalInsuredValue']=array('Ammount'=>100,'Currency'=>'USD');
		$request['RequestedShipment']['Shipper'] = $this->addShipper();
		$request['RequestedShipment']['Recipient'] = $this->addRecipient();
		$request['RequestedShipment']['ShippingChargesPayment'] = $this->addShippingChargesPayment();
		$request['RequestedShipment']['PackageCount'] = '1';
		$request['RequestedShipment']['RequestedPackageLineItems'] = $this->addPackageLineItem1();
		$request['RequestedShipment']['RecipientCustomsId'] = array('RecipientCustomsIdType' => 'INDIVIDUAL');
		
		try
		{
			$response = $client->getRates($request);
			if ($response->HighestSeverity != 'FAILURE' && $response->HighestSeverity != 'ERROR')
			{
				foreach ($response->RateReplyDetails as $rateReply) {

					$transitTime = '';
					if(array_key_exists('TransitTime',$rateReply)) {
						$transitTime = $this->convertTransitTime($rateReply->TransitTime, 'text'); //FOUR_DAYS
					} elseif(array_key_exists('DeliveryTimestamp',$rateReply)) {
						$transitTime = $this->convertTransitTime($rateReply->DeliveryTimestamp, 'date'); //2012-02-28T19:00:00
					}
					
					$price = new fedexPrice();
					if(is_array($rateReply->RatedShipmentDetails)){
						$rate = number_format($rateReply->RatedShipmentDetails[0]->ShipmentRateDetail->TotalNetCharge->Amount,2,".",",");
					} else {
						$rate = number_format($rateReply->RatedShipmentDetails->ShipmentRateDetail->TotalNetCharge->Amount,2,".",",");
					}
					$results[] = array('service'=>$rateReply->ServiceType, /*'price'=>$price,*/ 'rate'=>$rate, 'transit'=>$transitTime);
				}
			}
			else
			{
				$msg = '<pre>' . htmlspecialchars($client->__getLastResponse()). '</pre>';
				$this->emailError($msg);
				
				$error = new fedexError();
				$error->number = 0;
				$error->description = $response->Notifications->Message;
				$error->response = $response;
				$this->error = $error;
				return false;
			} 
			
			return $results;

		} catch (SoapFault $exception) {
			$msg = '<pre>' . htmlspecialchars($client->__getLastResponse()). '</pre>';
			if(trim($error) != 'Service is not allowed.') $this->emailError($msg);
			
			$error = new fedexError();
			$error->number = 0;
			$error->description = 'Fedex Rate Return Error';
			$error->response = $response;
			$this->error = $error;
			return false;
		}
	}

	function getPrice()
	{
		$newline = "<br />";
		$path_to_wsdl = "http://www.mjtrends.com/nusoap/RateService_v10.wsdl";

		ini_set("soap.wsdl_cache_enabled", "0");
		
		$client = new SoapClient($path_to_wsdl, array('trace' => 1)); // Refer to http://us3.php.net/manual/en/ref.soap.php for more information
		
		$request['WebAuthenticationDetail'] = array(
			'UserCredential' =>array(
				'Key' => '2XOHPWjnZPvRA9cP', 
				'Password' => 'EGNV2NTEjGWrL2ZONVpOAeikG'
			)
		); 
		$request['ClientDetail'] = array(
			'AccountNumber' => '322190360', 
			'MeterNumber' => '5621032'
		);
		$request['TransactionDetail'] = array('CustomerTransactionId' => '1');
		$request['Version'] = array(
			'ServiceId' => 'crs', 
			'Major' => '10', 
			'Intermediate' => '0', 
			'Minor' => '0'
		);
		$request['ReturnTransitAndCommit'] = true;
		$request['RequestedShipment']['DropoffType'] = 'STATION'; // valid values REGULAR_PICKUP, REQUEST_COURIER, ...
		$request['RequestedShipment']['ShipTimestamp'] = date('c');
		$request['RequestedShipment']['ServiceType'] = $this->service; // valid values STANDARD_OVERNIGHT, PRIORITY_OVERNIGHT, FEDEX_GROUND, ...
		$request['RequestedShipment']['PackagingType'] = $this->packaging; // valid values FEDEX_BOX, FEDEX_PAK, FEDEX_TUBE, YOUR_PACKAGING, ...
		$request['RequestedShipment']['TotalInsuredValue']=array('Ammount'=>100,'Currency'=>'USD');
		$request['RequestedShipment']['Shipper'] = $this->addShipper();
		$request['RequestedShipment']['Recipient'] = $this->addRecipient();
		$request['RequestedShipment']['ShippingChargesPayment'] = $this->addShippingChargesPayment();
		$request['RequestedShipment']['PackageCount'] = '1';
		$request['RequestedShipment']['RequestedPackageLineItems'] = $this->addPackageLineItem1();
		$request['RequestedShipment']['RecipientCustomsId'] = array('RecipientCustomsIdType' => 'INDIVIDUAL');
		
		try 
		{
			$response = $client->getRates($request);
			if ($response->HighestSeverity != 'FAILURE' && $response->HighestSeverity != 'ERROR')
			{  	
				$rateReply = $response->RateReplyDetails;
				$transitTime = '';
				if(array_key_exists('TransitTime',$rateReply)){
					$transitTime = $this->convertTransitTime($rateReply->TransitTime, 'text'); //FOUR_DAYS
				}else {
					$transitTime = $this->convertTransitTime($rateReply->DeliveryTimestamp, 'date'); //2012-02-28T19:00:00
				}
				
				$price = new fedexPrice();
				if(is_array($rateReply->RatedShipmentDetails)){
					$price->rate = number_format($rateReply->RatedShipmentDetails[0]->ShipmentRateDetail->TotalNetCharge->Amount,2,".",",");
				} else {
					$price->rate = number_format($rateReply->RatedShipmentDetails->ShipmentRateDetail->TotalNetCharge->Amount,2,".",",");
				}
				
				$price->service = $this->serviceName;
				$price->response = $response;
				$this->price = $price;  
				$this->transit = $transitTime;
				
			}
			else
			{
				$msg = '<pre>' . htmlspecialchars($client->__getLastResponse()). '</pre>';
			   	$this->emailError($msg);  
				
				$error = new fedexError();
				$error->number = 0;
				$error->description = $response->Notifications->Message;
				$error->response = $response;
				$this->error = $error;
			} 
			
			return $this;

		} catch (SoapFault $exception) {
			   $msg = '<pre>' . htmlspecialchars($client->__getLastResponse()). '</pre>';
			   if(trim($error) != 'Service is not allowed.') $this->emailError($msg);      
			   
			   	$error = new fedexError();
				$error->number = 0;
				$error->description = 'Fedex Rate Return Error';
				$error->response = $response;
				$this->error = $error;
		}
	}

	function addShipper(){
		$shipper = array(
			'Contact' => array(
				'PersonName' => 'Skylar Rylands',
				'CompanyName' => 'MJTrends',
				'PhoneNumber' => '2022533378'),
			'Address' => array(
				'StreetLines' => array('10712 Oldfield Dr'),
				'City' => 'Reston',
				'StateOrProvinceCode' => 'VA',
				'PostalCode' => '20191',
				'CountryCode' => 'US')
		);
		return $shipper;
	}

	function addRecipient(){
		$recipient = array(
			'Contact' => array(
				'PersonName' => '',
				'CompanyName' => '',
				'PhoneNumber' => ''
			),
			'Address' => array(
				'StreetLines' => array(''),
				'City' => '',
				'StateOrProvinceCode' => $this->destStateOrProvinceCode,
				'PostalCode' => $this->destPostalCode,
				'CountryCode' => $this->destCountryCode,
				'Residential' => $this->residentialDelivery)
		);
		return $recipient;	                                    
	}

	function addShippingChargesPayment(){
		$shippingChargesPayment = array(
			'PaymentType' => 'SENDER', // valid values RECIPIENT, SENDER and THIRD_PARTY
			'Payor' => array(
				'AccountNumber' => '322190360',
				'CountryCode' => 'US')
		);
		return $shippingChargesPayment;
	}

	function addPackageLineItem1(){
		$packageLineItem = array(
			'SequenceNumber'=>1,
			'GroupPackageCount'=>1,
			'Weight' => array(
				'Value' => number_format($this->weight, 1, '.', ''),
				'Units' => 'LB'
			),
			'Dimensions' => array(
				'Length' => $this->length,
				'Width' => $this->width,
				'Height' => $this->height,
				'Units' => 'IN'
			)
		);
		return $packageLineItem;
	}
	
	function convertTransitTime($time, $format){
		if($format == "text"){
			$dayMap = array('ONE'=>1, 'TWO'=>2, 'THREE'=>3, 'FOUR'=>4, 'FIVE'=>5, 'SIX'=>6, 'SEVEN'=>7, 'EIGHT'=>8, 'NINE'=>9, 'TEN'=>10);
			$timeArray = explode("_", $time);
			$num_of_days = $dayMap[$timeArray[0]];
			return $num_of_days;
		} else {
			$deliveryDate = substr($time, 0, 10);
			$num_of_days = round((strtotime($deliveryDate)-strtotime('today')) / 86400);
			return $num_of_days;
		}
	}
	
	function emailError($msg){
		$to = "Michael <ryland22@gmail.com>";
		$subject = 'MJTrends Fedex Failure';
		$message = $msg;
		// To send HTML mail, the Content-type header must be set
		$headers  = 'MIME-Version: 1.0' . "\r\n";
		$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
		$headers .= 'From: MJTrends <orders@mjtrends.com>' . "\r\n";
		mail($to, $subject, $message, $headers);
	}
}

class fedexError
{
    var $number;
    var $description;
    var $response;
}
class fedexPrice
{
    var $service;
    var $rate;
    var $response;
}