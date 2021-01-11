<?php

class USPSLabel{

    function setService($service) {
        $this->service = $service;
    }

	function setDestName($destName){
		$this->destName = $destName;
	}
		
	function setDestAddress($destAddress){
		$this->destAddress = str_replace("#","",$destAddress);
	}
		
	function setDestAddress2($destAddress2){
		$this->destAddress2 = str_replace("#","",$destAddress2);
	}
			
	function setDestCompany($destCompany){
		$this->destCompany = $destCompany;
	}
			
	function setDestCity($destCity){
		$this->destCity = $destCity;
	}
			
	function setDestState($destState){
		$this->destState = $destState;
	}
	
	function setDestZip($destZip){
		$this->destZip = $destZip;
	}
		
	function setDestCountry($destCountry){
		$this->destCountry = $destCountry;
	}
			
	function setDestPhone($destPhone){
		$this->destPhone = $destPhone;
	}
	
	function setPounds($pounds){
		$this->pounds = $pounds;
	}
		
	function setOunces($ounces){
		$this->ounces = $ounces;
	}
		
	function setValue($value){
		$this->value = $value;
	}
	
    function getLabel() {
		if($this->service != 'INTL'){
			$api = 'DeliveryConfirmationV3';
			$xml  = '<DeliveryConfirmationV3.0Request USERID="496MJTRE2583">';
			$xml .= 	'<Option>1</Option>';
			$xml .= 	'<ImageParameters />';
			$xml .= 	'<FromName>Ry Jenkins</FromName>';
			$xml .= 	'<FromFirm>MJTrends</FromFirm>';
			$xml .= 	'<FromAddress1></FromAddress1>';
			$xml .= 	'<FromAddress2>10712 Oldfield Dr.</FromAddress2>';
			$xml .= 	'<FromCity>Reston</FromCity>';
			$xml .= 	'<FromState>VA</FromState>';
			$xml .= 	'<FromZip5>20191</FromZip5>';
			$xml .= 	'<FromZip4 />';
			$xml .= 	'<ToName>'.$this->destName.'</ToName>';
			$xml .= 	'<ToFirm>'.$this->destCompany.'</ToFirm>';
			$xml .=		'<ToAddress1>'.$this->destAddress.'</ToAddress1>';
			$xml .= 	'<ToAddress2>'.$this->destAddress2.'</ToAddress2>';
			$xml .= 	'<ToCity>'.$this->destCity.'</ToCity>';
			$xml .= 	'<ToState>'.$this->destState.'</ToState>';
			$xml .= 	'<ToZip5>'.$this->destZip.'</ToZip5>';
			$xml .= 	'<ToZip4 />';
			$xml .= 	'<WeightInOunces>'.$this->ounces.'</WeightInOunces>';
			$xml .= 	'<ServiceType>'.$this->service.'</ServiceType>';
			$xml .= 	'<POZipCode />';
			$xml .= 	'<ImageType>PDF</ImageType>';
			$xml .= 	'<LabelDate />';
			$xml .= '</DeliveryConfirmationV3.0Request>';
		} else {
			$api = 'PriorityMailIntl';
			$xml  = '<PriorityMailIntlRequest USERID="496MJTRE2583">';
			$xml .= 	'<Option/>';
			$xml .= 	'<ImageParameters/>';
			$xml .= 	'<FromFirstName>Michael</FromFirstName>';
			$xml .= 	'<FromMiddleInitial></FromMiddleInitial>';
			$xml .= 	'<FromLastName>Harris</FromLastName>';
			$xml .= 	'<FromFirm>MJTrends</FromFirm>';
			$xml .= 	'<FromAddress1>STE 3</FromAddress1>';
			$xml .= 	'<FromAddress2>201 N. Fillmore Ave.</FromAddress2>';
			$xml .= 	'<FromCity>Sterling</FromCity>';
			$xml .= 	'<FromState>VA</FromState>';
			$xml .= 	'<FromZip5>20164</FromZip5>';
			$xml .= 	'<FromZip4 />';
			$xml .= 	'<FromPhone>7577532833</FromPhone>';
			$xml .= 	'<ToName>'.$this->destName.'</ToName>';
			$xml .= 	'<ToFirm>'.$this->destCompany.'</ToFirm>';
			$xml .=		'<ToAddress1>'.$this->destAddress.'</ToAddress1>';
			$xml .= 	'<ToAddress2>'.$this->destAddress2.'</ToAddress2>';
			$xml .= 	'<ToAddress3 />';
			$xml .= 	'<ToCity>'.$this->destCity.'</ToCity>';
			$xml .= 	'<ToProvince>'.$this->destState.'</ToProvince>';
			$xml .= 	'<ToCountry>'.$this->destCountry.'</ToCountry>';
			$xml .= 	'<ToPostalCode>'.$this->destZip.'</ToPostalCode>';
			$xml .= 	'<ToPOBoxFlag>N</ToPOBoxFlag>';
			$xml .= 	'<ToPhone>'.$this->destPhone.'</ToPhone>';
			$xml .= 	'<NonDeliveryOption>Return</NonDeliveryOption>';
			$xml .= 	'<ShippingContents>';
			$xml .= 		'<ItemDetail>';
			$xml .= 			'<Description>Fabric</Description>';
			$xml .= 			'<Quantity>1</Quantity>';
			$xml .= 			'<Value>'.$this->value.'</Value>';
			$xml .= 			'<NetPounds>'.$this->pounds.'</NetPounds>';
			$xml .= 			'<NetOunces>0</NetOunces>';
			$xml .= 			'<HSTariffNumber>590310</HSTariffNumber>';
			$xml .= 			'<CountryOfOrigin>United States</CountryOfOrigin>';
			$xml .= 		'</ItemDetail>';
			$xml .= 	'</ShippingContents>';
			$xml .= 	'<GrossPounds>'.$this->pounds.'</GrossPounds>';
			$xml .= 	'<GrossOunces>0</GrossOunces>';
			$xml .= 	'<ContentType>Merchandise</ContentType>';
			$xml .= 	'<Agreement>Y</Agreement>';
			$xml .= 	'<ImageType>PDF</ImageType>';
			$xml .= '</PriorityMailIntlRequest>';
		}
			
		$header[] = "Host: www.mjtrends.com";
		$header[] = "MIME-Version: 1.0";
		$header[] = "Content-type: multipart/mixed; boundary=----doc";
		$header[] = "Accept: text/xml";
		$header[] = "Cache-Control: no-cache";
		$header[] = "Connection: close \r\n";
		
		$url = 'https://secure.shippingapis.com//ShippingAPI.dll?API='.$api.'&XML='.$xml;
		$url = str_replace(" ","%20",$url);
		
		$curl = curl_init();
		curl_setopt($curl, CURLOPT_URL, $url);
		curl_setopt($curl, CURLOPT_HTTPHEADER, $header);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($curl, CURLOPT_TIMEOUT, 10);

		$data = curl_exec($curl); // execute the curl command

		curl_close($curl); // close the connection

		$xmlParser = new xmlparser();
		$array = $xmlParser->GetXMLTree($data);
		//$xmlParser->printa($array);
		 
		if($this->service != "INTL"){
			$this->tracking = $array['DELIVERYCONFIRMATIONV3.0RESPONSE'][0]['DELIVERYCONFIRMATIONNUMBER'][0]['VALUE'];
			$this->label = $array['DELIVERYCONFIRMATIONV3.0RESPONSE'][0]['DELIVERYCONFIRMATIONLABEL'][0]['VALUE'];
		} else {
			$this->tracking = $array['PRIORITYMAILINTLRESPONSE'][0]['BARCODENUMBER'][0]['VALUE'];
			$this->label = $array['PRIORITYMAILINTLRESPONSE'][0]['LABELIMAGE'][0]['VALUE'];
		}
		return $this; // and finally, return data
	}
}
