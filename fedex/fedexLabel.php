<?php
class FedexLabel { 
    
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
    
    function setExpirationDate($expirationDate) {
        $this->expirationDate = $expirationDate;
    }

    function setMerchantEmail($merchantEmail) {
        $this->merchantEmail = $merchantEmail;
    }

    function setMerchantPhone($merchantPhone) {
        $this->merchantPhone = $merchantPhone;
    }
	
    function setService($service) {
        $this->service = $service;
    }
    	
    function setPackaging($packaging) {
        $this->packaging = $packaging;
    }

    function setMerchantName($merchantName) {
        $this->merchantName = $merchantName;
    }
    
    function setOriginAddress($originAddress) {
        $this->originAddress = $originAddress;
    }
          
    function setOriginAddress2($originAddress2) {
        $this->originAddress2 = $originAddress2;
    }
        
    function setOriginCity($originCity) {
        $this->originCity = $originCity;
    }
    
    function setOriginStateOrProvinceCode($code) {
        $this->originStateOrProvinceCode = $code;
    }
    
    function setOriginPostalCode($code) {
        $this->originPostalCode = $code;
    }
    
    function setOriginCountryCode($code) {
        $this->originCountryCode = $code;
    }
        
    function setDestName($destName) {
        $this->destName = $destName;
    }
            
    function setDestCompany($destCompany) {
        $this->destCompany = $destCompany;
    }
                
    function setDestPhone($destPhone) {
        $this->destPhone = $destPhone;
    }    
    
	function setDestAddress($destAddress) {
        $this->destAddress = $destAddress;
    }
           
	function setDestAddress2($destAddress2) {
        $this->destAddress2 = $destAddress2;
    }
        
    function setDestCity($destCity) {
        $this->destCity = $destCity;
    }
    
    function setDestStateOrProvinceCode($code) {
        $this->destStateOrProvinceCode = $code;
    }
    
    function setDestPostalCode($code) {
        $this->destPostalCode = $code;
    }
    
    function setDestCountryCode($code) {
        $this->destCountryCode = $code;
    }
    
    function setPayorType($type) {
        $this->payorType = $type;
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
    	
	function setDescription($description) {
        $this->description = $description;
    }
    
    function getLabel() {
       
        $str = '<?xml version="1.0" encoding="UTF-8" ?>';
        $str .= '    <FDXEmailLabelRequest xmlns:api="http://www.fedex.com/fsmapi" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:noNamespaceSchemaLocation="FDXEmailLabelRequest.xsd">';
        $str .= '        <RequestHeader>';
        $str .= '            <AccountNumber>'.$this->accountNumber.'</AccountNumber>';
        $str .= '            <MeterNumber>'.$this->meterNumber.'</MeterNumber>';
        $str .= '            <CarrierCode>'.$this->carrierCode.'</CarrierCode>';
        $str .= '        </RequestHeader>';
        $str .= '        <URLExpirationDate>'.$this->expirationDate.'</URLExpirationDate>';
        $str .= '        <URLNotificationE-MailAddress>'.$this->merchantEmail.'</URLNotificationE-MailAddress>';
        $str .= '        <MerchantPhoneNumber>'.$this->merchantPhone.'</MerchantPhoneNumber>';
        $str .= '        <Service>'.$this->service.'</Service>';
        $str .= '        <Packaging>'.$this->packaging.'</Packaging>';
        $str .= '        <WeightUnits>'.$this->weightUnits.'</WeightUnits>';
        $str .= '        <CurrencyCode>USD</CurrencyCode>';
        $str .= '        <Origin>';
        $str .= '        	<Contact>';
        $str .= '        		<CompanyName>'.$this->merchantName.'</CompanyName>';
        $str .= '        		<PhoneNumber>'.$this->merchantPhone.'</PhoneNumber>';
        $str .= '        	</Contact>';
        $str .= '        	<Address>';
        $str .= '        		<Line1>'.$this->originAddress.'</Line1>';
        $str .= '        		<Line2>'.$this->originAddress2.'</Line2>';
        $str .= '        		<City>'.$this->originCity.'</City>';
        $str .= '            	<StateOrProvinceCode>'.$this->originStateOrProvinceCode.'</StateOrProvinceCode>';
        $str .= '            	<PostalCode>'.$this->originPostalCode.'</PostalCode>';
        $str .= '            	<CountryCode>'.$this->originCountryCode.'</CountryCode>';
        $str .= '        	</Address>';
        $str .= '        </Origin>';
        $str .= '        <Destination>';
        $str .= '        	<Contact>';
        $str .= '        		<PersonName>'.$this->destName.'</PersonName>';
if($this->destCompany != "") $str .= '        		<CompanyName>'.$this->destCompany.'</CompanyName>';
        $str .= '        		<PhoneNumber>'.$this->destPhone.'</PhoneNumber>';
        $str .= '        	</Contact>';
		$str .= '        	<Address>';
        $str .= '        		<Line1>'.$this->destAddress.'</Line1>';
if($this->destAddress2 != "")        $str .= '        		<Line2>'.$this->destAddress2.'</Line2>';
        $str .= '        		<City>'.$this->destCity.'</City>';
        $str .= '            	<StateOrProvinceCode>'.$this->destStateOrProvinceCode.'</StateOrProvinceCode>';
        $str .= '            	<PostalCode>'.$this->destPostalCode.'</PostalCode>';
        $str .= '            	<CountryCode>'.$this->destCountryCode.'</CountryCode>';
        $str .= '        	</Address>';
        $str .= '        </Destination>';
        $str .= '        <Payment>';
        $str .= '            <PayorType>'.$this->payorType.'</PayorType>';
        $str .= '            <Payor>';
        $str .= '            	<AccountNumber>'.$this->accountNumber.'</AccountNumber>';
        $str .= '            </Payor>';
        $str .= '        </Payment>';
        $str .= '        <DimensionsUnits>'.$this->units.'</DimensionsUnits>';
        $str .= '        <Package>';
        $str .= '        	<Weight>'.number_format($this->weight,1,'.','').'</Weight>';
if($this->length != ""){
		$str .= '        	<Dimensions>';
        $str .= '        		<Length>'.$this->length.'</Length>';
        $str .= '        		<Width>'.$this->width.'</Width>';
        $str .= '        		<Height>'.$this->height.'</Height>';
        $str .= '        	</Dimensions>';
}
        $str .= '        	<ItemDescription>'.$this->description.'</ItemDescription>';
        $str .= '        </Package>';
        $str .= '    </FDXEmailLabelRequest>';

        $header[] = "Host: www.smart-shop.com";
        $header[] = "MIME-Version: 1.0";
        $header[] = "Content-type: multipart/mixed; boundary=----doc";
        $header[] = "Accept: text/xml";
        $header[] = "Content-length: ".strlen($str);
        $header[] = "Cache-Control: no-cache";
        $header[] = "Connection: close \r\n";
        $header[] = $str;

        $ch = curl_init();
        //Disable certificate check.
        // uncomment the next line if you get curl error 60: error setting certificate verify locations
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        // uncommenting the next line is most likely not necessary in case of error 60
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        //-------------------------
        //curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 1);
        //curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
        //curl_setopt($ch, CURLOPT_CAINFO, "c:/ca-bundle.crt");
        //-------------------------
        curl_setopt($ch, CURLOPT_URL,$this->server);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_TIMEOUT, 4);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST,'POST');
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
            
        $data = curl_exec($ch);        
        if (curl_errno($ch)) {
            $this->getLabel();
        } else {
            // close curl resource, and free up system resources
            curl_close($ch);
            $xmlParser = new xmlparser();
            $array = $xmlParser->GetXMLTree($data);
            //$xmlParser->printa($array);
            if(count($array['FDXEMAILLABELREQUEST'][0]['ERROR'])) { // If it is error
                $error = new fedexError();
                $error->code = $array['FDXEMAILLABELREQUEST'][0]['ERROR'][0]['CODE'][0]['VALUE'];
                $error->message = $array['FDXEMAILLABELREQUEST'][0]['ERROR'][0]['MESSAGE'][0]['VALUE'];
                $this->error = $error;
            } else if (count($array['FDXEMAILLABELREPLY'][0]['URL'])) {
                $label = new fedLabel();
                $label->url = $array['FDXEMAILLABELREPLY'][0]['URL'][0]['VALUE'];
                $label->tracking = $array['FDXEMAILLABELREPLY'][0]['PACKAGE'][0]['TRACKINGNUMBER'][0]['VALUE'];
                $label->timeStamp = $array['FDXEMAILLABELREPLY'][0]['REQUESTTIMESTAMP'][0]['VALUE'];
				$this->label = $label;            
            }
            return $this;
        }
    }
}
class fedexError
{
    var $code;
    var $message;
}
class fedLabel
{
    var $url;
    var $tracking;
	var $timeStamp;
}
