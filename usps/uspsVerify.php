<?php
// working:
// http://production.shippingapis.com/ShippingAPI.dll?API=Verify&XML=%3CAddressValidateRequest%20USERID=%22477MJTRE3134%22%3E%20%3CAddress%3E%3CAddress1%3E%3C/Address1%3E%3CAddress2%3E6406%20Ivy%20Lane%3C/Address2%3E%3CCity%3ELongfield%3C/City%3E%3CState%3EMD%3C/State%3E%3CZip5%3E%3C/Zip5%3E%3CZip4%3E%3C/Zip4%3E%20%3C/Address%3E%3C/AddressValidateRequest%3E


//http://production.shippingapis.com/ShippingAPI.dll?API=Verify%26XML%3D%3C%3Fxml+version%3D%221.0%22+encoding%3D%22UTF-8%22%3F%3E%3CAddressValidateRequest+USERID%3D%22477MJTRE3134%22%3E%3CAddress%3E%3CFirmName%3E%3C%2FFirmName%3E%3CAddress1%3E201+N.+Fillmore+Ave%3C%2FAddress1%3E%3CAddress2%3E%3C%2FAddress2%3E%3CCity%3ESterling%3C%2FCity%3E%3CState%3EVA%3C%2FState%3E%3CZip5%3E%3C%2FZip5%3E%3CZip4%3E20170%3C%2FZip4%3E%3C%2FAddress%3E%3C%2FAddressValidateRequest%3E

include("../logic/xmlparser.php");

$header[] = "Host: www.mjtrends.com";
$header[] = "MIME-Version: 1.0";
$header[] = "Content-type: multipart/mixed; boundary=----doc";
$header[] = "Accept: text/xml";
$header[] = "Cache-Control: no-cache";
$header[] = "Connection: close \r\n";

$api = 'Verify&XML=<?xml version="1.0" encoding="UTF-8"?>';


$xml  = '<AddressValidateRequest USERID="477MJTRE3134">';
$xml .= '<Address>';
$xml .= '<FirmName></FirmName>';
$xml .= '<Address1>'.$_POST["shipAdone"].'</Address1>';
$xml .= '<Address2>'.$_POST["shipAdtwo"].'</Address2>';
$xml .= '<City>'.$_POST["shipCity"].'</City>';
$xml .= '<State>'.$_POST["shipState"].'</State>';
$xml .= '<Zip5></Zip5>';
$xml .= '<Zip4>'.$_POST["shipZip"].'</Zip4>';
$xml .= '</Address>';
$xml .= '</AddressValidateRequest>';

// $xml  = '<AddressValidateRequest USERID="477MJTRE3134">';
// $xml .= '<Address>';
// $xml .= '<FirmName></FirmName>';
// $xml .= '<Address1>201</Address1>';
// $xml .= '<Address2></Address2>';
// $xml .= '<City>sterling</City>';
// $xml .= '<State>VA</State>';
// $xml .= '<Zip5></Zip5>';
// $xml .= '<Zip4>20164</Zip4>';
// $xml .= '</Address>';
// $xml .= '</AddressValidateRequest>';

//$xml = str_replace('&', '&amp;', $xml);
$xml = urlencode($xml);
//$api = urlencode($api);
//print_r($xml);


//$url = 'http://production.shippingapis.com/ShippingAPI.dll?API='.$api.'&XML='.$xml;
$url = 'http://production.shippingapis.com/ShippingAPI.dll?API='.$api.$xml;
$url = str_replace(" ","%20",$url);
//echo $url;
//echo "\n";


$curl = curl_init();
curl_setopt($curl, CURLOPT_URL, $url);
curl_setopt($curl, CURLOPT_HTTPHEADER, $header);
curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($curl, CURLOPT_TIMEOUT, 10);

$data = curl_exec($curl); // execute the curl command

curl_close($curl); // close the connection

$xmlParser = new xmlparser();
$array = $xmlParser->GetXMLTree($data);

//print_r($array);
$msg = array("status" => "success");

if(@$array['ADDRESSVALIDATERESPONSE'][0]['ADDRESS'][0]['RETURNTEXT'][0]['VALUE'] != ""){ // more address info needed
	$msg = array("status" => "The address you entered was found but more information is needed (such as an apartment, suite, or box number) to match to a specific address");
} elseif(@$array['ADDRESSVALIDATERESPONSE'][0]['ADDRESS'][0]['ERROR'][0]['DESCRIPTION'][0]['VALUE'] != ""){ //address not found
	$msg = array("status" => $array['ADDRESSVALIDATERESPONSE'][0]['ADDRESS'][0]['ERROR'][0]['DESCRIPTION'][0]['VALUE']);
}

echo json_encode($msg);
