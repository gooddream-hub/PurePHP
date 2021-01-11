<?php
// Copyright 2009, FedEx Corporation. All rights reserved.
// Version 2.0.1   

require_once('fedex-common.php');

//The WSDL is not included with the sample code.
//Please include and reference in $path_to_wsdl variable.
$path_to_wsdl = "AddressValidationService_v4.wsdl"; 

ini_set("soap.wsdl_cache_enabled", "0");

$client = new SoapClient($path_to_wsdl, array('trace' => 1)); // Refer to http://us3.php.net/manual/en/ref.soap.php for more information

$request['WebAuthenticationDetail'] = array(
	'UserCredential' => array(
        'Key' => 'j8NslrlZckEUa2vh', 
        'Password' => 'j7w45OchFNfUydVu55AmZU8Qb'
	)
);

$request['ClientDetail'] = array(
    'AccountNumber' => '322190360', 
    'MeterNumber' => '113649085'
);
$request['TransactionDetail'] = array('CustomerTransactionId' => ' *** Address Validation Request using PHP ***');
$request['Version'] = array(
	'ServiceId' => 'aval', 
	'Major' => '4', 
	'Intermediate' => '0', 
	'Minor' => '0'
);
$request['InEffectAsOfTimestamp'] = date('c');

$request['AddressesToValidate'] = array(
	0 => array(
		'ClientReferenceId' => 'ClientReferenceId1',
     	'Address' => array(
     		'StreetLines' => array($_POST['shipadone'], @$_POST['shipadtwo']),
           	'PostalCode' => $_POST['postal'],
     		'City' => $_POST['city'],
     		'StateOrProvinceCode' => $_POST['state'],
           	'CountryCode' => $_POST['countryCode']
		)
	)
);

try {
	if(setEndpoint('changeEndpoint')){
		$newLocation = $client->__setLocation(setEndpoint('endpoint'));
	}

    $response = $client ->addressValidation($request);

    if ($response -> HighestSeverity != 'FAILURE' && $response -> HighestSeverity != 'ERROR'){
        foreach($response -> AddressResults as $addressResult){
        	echo 'Client Reference Id: ' . $addressResult->ClientReferenceId . Newline;
        	echo 'State: ' . $addressResult->State . Newline;
        	echo 'Classification: ' . $addressResult->Classification . Newline;
        	if($addressResult->EffectiveAddress){
        		echo 'Proposed Address:' . Newline;
        		echo '<table border="1">';
        		printAddress($addressResult->EffectiveAddress);
        		echo '</table>';
        	}
        	if(array_key_exists("Attributes", $addressResult)){
        		echo Newline . 'Address Attributes' . Newline;
        		echo '<table border="1">';
        		foreach($addressResult->Attributes as $attribute){
        			echo '<tr><td>' . $attribute -> Name . '</td><td>' . $attribute -> Value . '</td></tr>'; 
        		}
        		echo '</table>';
        	}
        	echo Newline;
        }
    	
    	printSuccess($client, $response);
    }else{
        printError($client, $response);
    } 
    
    writeToLog($client);    // Write to log file   
} catch (SoapFault $exception) {
    printFault($exception, $client);
}

function printAddress($addressLine){
	foreach ($addressLine as $key => $value){
		if(is_array($value) || is_object($value)){
			printAddress($value);
		}else{ 
			echo '<tr><td>'. $key . '</td><td>' . $value . '</td></tr>';
		}
	}
}

?>