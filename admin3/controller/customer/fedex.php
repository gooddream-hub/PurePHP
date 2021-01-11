<?php

function createFedexLabel($name, $address1, $address2, $company, $city, $state, $zip, $co, $phone, $weight, $length, $width, $height, $serviceType, $custid)
{
	require_once('../../libraries/nusoap/nusoap.php');
	$proxyhost = '';
	$proxyport = '';
	$proxyusername = '';
	$proxypassword = '';
	$newline = "<br />";
	$client = new nusoap_client("../../libraries/nusoap/ShipService_v10.wsdl", true, $proxyhost, $proxyport, $proxyusername, $proxypassword);

	$request['WebAuthenticationDetail'] = array('UserCredential' => array('Key' => '2XOHPWjnZPvRA9cP', 'Password' => 'EGNV2NTEjGWrL2ZONVpOAeikG')); // Replace 'XXX' and 'YYY' with FedEx provided credentials
	$request['ClientDetail'] = array('AccountNumber' => '322190360', 'MeterNumber' => '5621032'); // Replace 'XXX' with client's account and meter number
	$request['TransactionDetail'] = array('CustomerTransactionId' => '1');
	$request['Version'] = array('ServiceId' => 'ship', 'Major' => '10', 'Intermediate' => '0', 'Minor' => '0');
	$request['RequestedShipment'] = array('ShipTimestamp' => date('c'), //iso 8601 date
										'DropoffType' => 'STATION', // valid values REGULAR_PICKUP, REQUEST_COURIER, DROP_BOX, BUSINESS_SERVICE_CENTER and STATION
										'ServiceType' => $serviceType, // valid values STANDARD_OVERNIGHT, PRIORITY_OVERNIGHT, FEDEX_GROUND, ...
										'PackagingType' => 'YOUR_PACKAGING', // valid values FEDEX_BOX, FEDEX_PAK, FEDEX_TUBE, YOUR_PACKAGING, ...
										'Shipper' => array('Contact' => array('CompanyName' => 'MJTrends',
																			  'PhoneNumber' => '5716439918'),
														   'Address' => array('StreetLines' => array('2611 E Meredith Dr'),
																			  'City' => 'Vienna',
																			  'StateOrProvinceCode' => 'VA',
																			  'PostalCode' => '22181',
																			  'CountryCode' => 'US')),
										'Recipient' => array('Contact' => array('PersonName' => $name,
																				'CompanyName' => $company,
																				'PhoneNumber' => $phone),
															 'Address' => array('StreetLines' => array($address1, $address2),
																				'City' => $city,
																				'StateOrProvinceCode' => $state,
																				'PostalCode' => $zip,
																				'CountryCode' => $co)),
										'ShippingChargesPayment' => array('PaymentType' => 'SENDER', // valid values RECIPIENT, SENDER and THIRD_PARTY
																		  'Payor' => array('AccountNumber' => '322190360', // Replace 'XXX' with your account number
																						   'CountryCode' => 'US')),
										//  'SpecialServicesRequested' => array('SpecialServiceTypes' => array('COD'),
										//                                     'CodDetail' => array('CollectionType' => 'ANY')), // ANY, GUARANTEED_FUNDS
										'LabelSpecification' => array('LabelFormatType' => 'COMMON2D', // valid values COMMON2D, LABEL_DATA_ONLY
																	  'ImageType' => 'PNG',  // valid values DPL, EPL2, PDF, ZPLII and PNG
																	  'LabelStockType' => 'PAPER_7X4.75'),
										'RateRequestTypes' => array('ACCOUNT'), // valid values ACCOUNT and LIST
										'PackageCount' => 1,
										'PackageDetail' => 'INDIVIDUAL_PACKAGES',
										'RequestedPackageLineItems' => array('0' => array('SequenceNumber' => '1',
																	 'Weight' => array('Value' => $weight, 'Units' => 'LB'), // valid values LB or KG
																	 'Dimensions' => array('Length' => $length,
																						   'Width' => $width,
																						   'Height' => $height,
																						   'Units' => 'IN'), // valid values IN or CM
																	 'CustomerReferences' => array('0' => array('CustomerReferenceType' => 'INVOICE_NUMBER', 'Value' => $custid))))); // valid values CUSTOMER_REFERENCE, INVOICE_NUMBER, P_O_NUMBER and SHIPMENT_INTEGRITY),

										if($co == "CA" OR $co == "CANADA") {
											$request['RequestedShipment']['CustomsClearanceDetail'] = addCustomClearanceDetail($weight);

										}
	//print_r($request);
	$response = $client->call('processShipment', array($request));

	if ($response['HighestSeverity'] != 'FAILURE' && $response['HighestSeverity'] != 'ERROR')
	{

		$tracking = $response['CompletedShipmentDetail']['CompletedPackageDetails']['TrackingIds']['TrackingNumber'];

		if (!file_exists('/var/www/mjtrends/admin3/assets/labels/fedex/'.$custid)) {
			mkdir('/var/www/mjtrends/admin3/assets/labels/fedex/'.$custid);
		}

		$file = fopen('/var/www/mjtrends/admin3/assets/labels/fedex/'.$custid.DIRECTORY_SEPARATOR.$tracking .'.png','wb');
		$image = $response['CompletedShipmentDetail']['CompletedPackageDetails']['Label']['Parts']['Image'];
		fwrite($file,base64_decode($image));
		fclose($file);

		$tracking = $response['CompletedShipmentDetail']['CompletedPackageDetails']['TrackingIds']['TrackingNumber'];

		return $tracking;

	}
	else
	{
		echo 'Error in processing transaction.'. $newline. $newline;
		print_r($response);
	}
}


function addCustomClearanceDetail($weight){
	$customerClearanceDetail = array(
		'DutiesPayment' => array(
			'PaymentType' => 'RECIPIENT', // valid values RECIPIENT, SENDER and THIRD_PARTY
		),
		'DocumentContent' => 'NON_DOCUMENTS',                                                                                            
		'CustomsValue' => array(
			'Currency' => 'USD', 
			'Amount' => 9.0
		),
	'Commodities' => array(
		'0' => array(
			'NumberOfPieces' => 1,
			'Description' => 'Fabric',
			'CountryOfManufacture' => 'US',
			'Weight' => array(
				'Units' => 'LB', 
				'Value' => $weight
			),
			'Quantity' => 1,
			'QuantityUnits' => 'EA',
			'UnitPrice' => array(
				'Currency' => 'USD', 
				'Amount' => 9.000000
			),
			'CustomsValue' => array(
				'Currency' => 'USD', 
				'Amount' => 9.000000
			)
		)
	),
	'ExportDetail' => array('B13AFilingOption' => 'NOT_REQUIRED')
	);

	return $customerClearanceDetail;
}


function getFedexType($type){
	if($type == 'FedEx Ground' || $type == 'FedEx Intl Priority'){
		return 'FEDEX_GROUND';
	} elseif($type == 'FedEx Overnight' OR $type == 'Fedex Express') {
		return 'STANDARD_OVERNIGHT';
	} elseif($type == 'FedEx 2 Day'){
		return 'FEDEX_2_DAY';
	}
}