<?php

class USPS{

	public $xml='';

    function setApi($api) {
        $this->api = $api;
    }
	
    function setService($service) {
        $this->service = $service;
    }

	function setUserId($userid) {
        $this->userid = $userid;
    }
	
	function setPackageId($packageid){
		$this->packageid = $packageid;
	}
	
	function setZipOrigin($zipOrigin){
		$this->zipOrigin = $zipOrigin;
	}
	
	function setZipDest($zipDest){
		$this->zipDest = $zipDest;
	}
	
	function setWeight($weight){
		$this->pounds = floor($weight);
		$this->ounces = 16*($weight - $this->pounds);
		$this->ounces = number_format((float)$this->ounces, 2, '.', '');
	}
	
	function setContainer($container){
		$this->container = $container;
	}
	
	function setMailType($mailType){
		$this->mailType = $mailType;
	}
	
	function setCountry($country){
		$this->country = $country;
	}
	
	function generateRequest($subtotal, $width, $length, $height) {
		$this->packageid = str_replace(' ', '_', $this->packageid);
		if($this->api == 'RateV4') {
			if($this->service == "FIRST CLASS"){
				$this->xml .= 	'<Package ID="'.$this->packageid.'">'."\n";
				$this->xml .= 		'<Service>'.$this->service.'</Service>'."\n";
				$this->xml .= 		'<FirstClassMailType>LETTER</FirstClassMailType>'."\n";
				$this->xml .= 		'<ZipOrigination>'.$this->zipOrigin.'</ZipOrigination>'."\n";
				$this->xml .= 		'<ZipDestination>'.$this->zipDest.'</ZipDestination>'."\n";
				$this->xml .= 		'<Pounds>'.$this->pounds.'</Pounds>'."\n";
				$this->xml .= 		'<Ounces>'.$this->ounces.'</Ounces>'."\n";
				$this->xml .= 		'<Container></Container>'."\n";
				$this->xml .= 		'<Size>Regular</Size>'."\n";
				$this->xml .= 		'<Machinable>TRUE</Machinable>'."\n";
				$this->xml .= 	'</Package>'."\n";
			} else {
				$this->xml .= 	'<Package ID="'.$this->packageid.'">'."\n";
				$this->xml .= 		'<Service>'.$this->service.'</Service>'."\n";
				$this->xml .= 		'<ZipOrigination>'.$this->zipOrigin.'</ZipOrigination>'."\n";
				$this->xml .= 		'<ZipDestination>'.$this->zipDest.'</ZipDestination>'."\n";
				$this->xml .= 		'<Pounds>'.$this->pounds.'</Pounds>'."\n";
				$this->xml .= 		'<Ounces>'.$this->ounces.'</Ounces>'."\n";
				$this->xml .= 		'<Container>'.$this->container.'</Container>'."\n";
				$this->xml .= 		'<Size>Regular</Size>'."\n";
				$this->xml .= 	'</Package>'."\n";
			}
		} else { // $this->api == 'IntlRateV2'
			$girth = $width*2 + $height*2;
			if($this->service == "INTL First" || $this->service == 'INTL FIRST'){
				$this->xml .= 	'<Package ID="'.$this->packageid.'">'."\n";
				$this->xml .= 		'<Pounds>'.$this->pounds.'</Pounds>'."\n";		
				$this->xml .= 		'<Ounces>'.$this->ounces.'</Ounces>'."\n";
				$this->xml .= 		'<MailType>Envelope</MailType>'."\n";
				$this->xml .= 		'<ValueOfContents>1</ValueOfContents>'."\n";
				$this->xml .= 		'<Country>'.$this->country.'</Country>'."\n";
				$this->xml .= 		'<Container></Container>'."\n";
				$this->xml .= 		'<Size>Regular</Size>'."\n";
	    		$this->xml .= '<Width></Width> <Length></Length> <Height></Height> <Girth></Girth> <CommercialFlag>N</CommercialFlag>'."\n";
				$this->xml .= 	'</Package>'."\n";
			} else {
				$this->xml .= 	'<Package ID="'.$this->packageid.'">'."\n";
				$this->xml .= 		'<Pounds>'.$this->pounds.'</Pounds>'."\n";		
				$this->xml .= 		'<Ounces>'.$this->ounces.'</Ounces>'."\n";
				$this->xml .= 		'<MailType>'.$this->mailType.'</MailType>'."\n";
				$this->xml .= 		'<ValueOfContents>'.$subtotal.'</ValueOfContents>'."\n";
				$this->xml .= 		'<Country>'.$this->country.'</Country>'."\n";
				$this->xml .= 		'<Container>Rectangular</Container>'."\n";
				$this->xml .= 		'<Size>Large</Size>'."\n";
				$this->xml .= 		'<Width>'.$width.'</Width>'."\n";
				$this->xml .= 		'<Length>'.$length.'</Length>'."\n";
				$this->xml .= 		'<Height>'.$height.'</Height>'."\n";
				$this->xml .= 		'<Girth>'.$girth.'</Girth>'."\n";
				$this->xml .= 		'<OriginZip>20170</OriginZip>'."\n";
				$this->xml .= 	'</Package>'."\n";
			}
		}
	}

	function getResponse() {
		global $uspsError;
		
		if($this->api == 'IntlRateV2') {
			$this->xml = '<'.$this->api.'Request USERID="'.$this->userid.'"><Revision>2</Revision>'. str_replace("\n", '', $this->xml) .'</'.$this->api.'Request>';
		} else {
			$this->xml = '<'.$this->api.'Request USERID="'.$this->userid.'">'. str_replace("\n", '', $this->xml) .'</'.$this->api.'Request>';
		}

		$url = 'http://Production.ShippingAPIs.com/ShippingAPI.dll?API='.$this->api.'&XML='.$this->xml;
		$url = str_replace(" ","%20",$url);

		$header[] = "Host: www.mjtrends.com";
		$header[] = "MIME-Version: 1.0";
		$header[] = "Content-type: multipart/mixed; boundary=----doc";
		$header[] = "Accept: text/xml";
		$header[] = "Cache-Control: no-cache";
		$header[] = "Connection: close \r\n";

		$curl = curl_init();
		curl_setopt($curl, CURLOPT_URL, $url);
		curl_setopt($curl, CURLOPT_HTTPHEADER, $header);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($curl, CURLOPT_TIMEOUT, 10);
		$data = curl_exec($curl); // execute the curl command
		curl_close($curl); // close the connection
		//print_r($data);
		
		$xmlParser = new xmlparser();
		$array = $xmlParser->GetXMLTree($data);
		//print_r($array);

		if($this->api == "RateV4"){
			if(isset($array['RATEV4RESPONSE'])) {
				$array = $array['RATEV4RESPONSE'][0]['PACKAGE'];
			} else {
				return array();
			}
		} else {
			$array = $array['INTLRATEV2RESPONSE'][0]['PACKAGE'];
		}

		$results = array();
		foreach ($array as $value) {
			$serv = explode('.', $value['ATTRIBUTES']['ID']);
			
			if($this->api == "RateV4"){
				if( isset($value['POSTAGE'][0]['RATE']) && $value['POSTAGE'][0]['RATE'][0]['VALUE'] > 0 ) {
					$price = $value['POSTAGE'][0]['RATE'][0]['VALUE'];
				} else {
					$price = $value['POSTAGE'][0]['COMMERCIALRATE'][0]['VALUE'];
				}
				$results[str_replace('_', ' ', $serv[0])][$serv[1]] = $price;
			} else {
				$price = 0;
				for($i = 0; $i < count($value['SERVICE']); $i++){
					if($this->container == "MD FLAT RATE BOX"){
						if($value['SERVICE'][$i]['ATTRIBUTES']['ID'] == 12) $price = $value['SERVICE'][$i]['POSTAGE'][0]['VALUE'];
					}else if($this->container == "FLAT RATE Box"){
						if($value['SERVICE'][$i]['ATTRIBUTES']['ID'] == 11) $price = $value['SERVICE'][$i]['POSTAGE'][0]['VALUE'];
					}elseif($this->mailType == "Envelope"  && $serv[0] == "INTL_PRIORITY"){ //if it is swatch only order, show priority flat rate envelope
						if($value['SERVICE'][$i]['ATTRIBUTES']['ID'] == 2) $price = $value['SERVICE'][$i]['POSTAGE'][0]['VALUE'];
					} elseif($this->mailType == "Envelope" && $serv[0] == "INTL_FIRST"){
						if($value['SERVICE'][$i]['ATTRIBUTES']['ID'] == 13) $price = $value['SERVICE'][$i]['POSTAGE'][0]['VALUE'];
						//if($value['SERVICE'][$i]['ATTRIBUTES']['ID'] == 14) $price = $value['SERVICE'][$i]['POSTAGE'][0]['VALUE'];
					} elseif($this->container == ""){ // if not flat rate
						//global priority package:
						if($value['SERVICE'][$i]['ATTRIBUTES']['ID'] == 2) $price = $value['SERVICE'][$i]['POSTAGE'][0]['VALUE'];
					}
					$results[str_replace('_', ' ', $serv[0])][$serv[1]] = $price;
				}
			}
		}
		return $results;
	}

    function getPrice($subtotal, $width, $length, $height) {
		global $uspsError;
		$girth = $width*2 + $height*2;
		
		if($this->api == 'RateV4' && $this->service != "FIRST CLASS"){
			$xml  = '<'.$this->api.'Request USERID="'.$this->userid.'">';
			$xml .= 	'<Package ID="'.$this->packageid.'">';
			$xml .= 		'<Service>'.$this->service.'</Service>';
			$xml .= 		'<ZipOrigination>'.$this->zipOrigin.'</ZipOrigination>';
			$xml .= 		'<ZipDestination>'.$this->zipDest.'</ZipDestination>';
			$xml .= 		'<Pounds>'.$this->pounds.'</Pounds>';
			$xml .= 		'<Ounces>'.$this->ounces.'</Ounces>';
			$xml .= 		'<Container>'.$this->container.'</Container>';
			$xml .= 		'<Size>Regular</Size>';
			$xml .= 	'</Package>';
			$xml .= '</'.$this->api.'Request>';
		} else {
			$xml  = '<'.$this->api.'Request USERID="'.$this->userid.'">';
			$xml .= 	'<Revision>2</Revision>';
			$xml .= 	'<Package ID="'.$this->packageid.'">';
			$xml .= 		'<Pounds>'.$this->pounds.'</Pounds>';		
			$xml .= 		'<Ounces>'.$this->ounces.'</Ounces>';
			$xml .= 		'<MailType>'.$this->mailType.'</MailType>';
			$xml .= 		'<ValueOfContents>'.$subtotal.'</ValueOfContents>';
			$xml .= 		'<Country>'.$this->country.'</Country>';
			$xml .= 		'<Container>Rectangular</Container>';
			$xml .= 		'<Size>Large</Size>';
			$xml .= 		'<Width>'.$width.'</Width>';
			$xml .= 		'<Length>'.$length.'</Length>';
			$xml .= 		'<Height>'.$height.'</Height>';
			$xml .= 		'<Girth>'.$girth.'</Girth>';
			$xml .= 		'<OriginZip>20170</OriginZip>';
			$xml .= 	'</Package>';
			$xml .= '</'.$this->api.'Request>';
		}
		if($this->service == "FIRST CLASS"){
			$xml  = '<'.$this->api.'Request USERID="'.$this->userid.'">';
			$xml .= 	'<Package ID="'.$this->packageid.'">';
			$xml .= 		'<Service>'.$this->service.'</Service>';
			$xml .= 		'<FirstClassMailType>LETTER</FirstClassMailType>';
			$xml .= 		'<ZipOrigination>'.$this->zipOrigin.'</ZipOrigination>';
			$xml .= 		'<ZipDestination>'.$this->zipDest.'</ZipDestination>';
			$xml .= 		'<Pounds>'.$this->pounds.'</Pounds>';
			$xml .= 		'<Ounces>'.$this->ounces.'</Ounces>';
			$xml .= 		'<Container></Container>';
			$xml .= 		'<Size>Regular</Size>';
			$xml .= 		'<Machinable>TRUE</Machinable>';
			$xml .= 	'</Package>';
			$xml .= '</'.$this->api.'Request>';
		}
		if($this->service == "INTL First"){
			$xml  = '<'.$this->api.'Request USERID="'.$this->userid.'">';
			$xml .= 	'<Package ID="'.$this->packageid.'">';
			$xml .= 		'<Pounds>'.$this->pounds.'</Pounds>';		
			$xml .= 		'<Ounces>'.$this->ounces.'</Ounces>';
			$xml .= 		'<MailType>Envelope</MailType>';
			$xml .= 		'<ValueOfContents>1</ValueOfContents>';
			$xml .= 		'<Country>'.$this->country.'</Country>';
			$xml .= 		'<Container></Container>';
			$xml .= 		'<Size>Regular</Size>';
    		$xml .= '<Width></Width> <Length></Length> <Height></Height> <Girth></Girth> <CommercialFlag>N</CommercialFlag>';
			$xml .= 	'</Package>';
			$xml .= '</'.$this->api.'Request>';
		}

		$header[] = "Host: www.mjtrends.com";
		$header[] = "MIME-Version: 1.0";
		$header[] = "Content-type: multipart/mixed; boundary=----doc";
		$header[] = "Accept: text/xml";
		$header[] = "Cache-Control: no-cache";
		$header[] = "Connection: close \r\n";
		
		$url = 'http://Production.ShippingAPIs.com/ShippingAPI.dll?API='.$this->api.'&XML='.$xml;
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

		if($this->api == "RateV4"){
			if($this->service == 'PRIORITY COMMERCIAL') {
				$this->price = $array['RATEV4RESPONSE'][0]['PACKAGE'][0]['POSTAGE'][0]['COMMERCIALRATE'][0]['VALUE'];
			} else {
				$this->price = $array['RATEV4RESPONSE'][0]['PACKAGE'][0]['POSTAGE'][0]['RATE'][0]['VALUE'];
			}
		} else {//everything for INTL below
			for($i = 0; $i < count($array['INTLRATEV2RESPONSE'][0]['PACKAGE'][0]['SERVICE']); $i++){
				if($this->container == "FLAT RATE Box"){
					if($array['INTLRATEV2RESPONSE'][0]['PACKAGE'][0]['SERVICE'][$i]['ATTRIBUTES']['ID'] == 11) $this->price = $array['INTLRATEV2RESPONSE'][0]['PACKAGE'][0]['SERVICE'][$i]['POSTAGE'][0]['VALUE'];				
				}elseif($this->mailType == "Envelope"  && $this->service == "INTL Priority"){ //if it is swatch only order, show priority flat rate envelope
					if($array['INTLRATEV2RESPONSE'][0]['PACKAGE'][0]['SERVICE'][$i]['ATTRIBUTES']['ID'] == 2) $this->price = $array['INTLRATEV2RESPONSE'][0]['PACKAGE'][0]['SERVICE'][$i]['POSTAGE'][0]['VALUE'];
				} elseif($this->mailType == "Envelope" && $this->service == "INTL First"){
					if($array['INTLRATEV2RESPONSE'][0]['PACKAGE'][0]['SERVICE'][$i]['ATTRIBUTES']['ID'] == 13) $this->price = $array['INTLRATEV2RESPONSE'][0]['PACKAGE'][0]['SERVICE'][$i]['POSTAGE'][0]['VALUE'];
				} elseif($this->container == ""){ // if not flat rate
					//global priority package:
					if($array['INTLRATEV2RESPONSE'][0]['PACKAGE'][0]['SERVICE'][$i]['ATTRIBUTES']['ID'] == 2) $this->price = $array['INTLRATEV2RESPONSE'][0]['PACKAGE'][0]['SERVICE'][$i]['POSTAGE'][0]['VALUE'];
				} 
			}
		}
		$uspsError = $array['RATEV4RESPONSE'][0]['ERROR'][0]['DESCRIPTION'][0]['VALUE'];
		echo $uspsError;
		return $this; // and finally, return data
	}

	function getMaxParams($country) {
		global $uspsError;
		$girth = 10*2 + 4*2;
		
		$xml  = '<IntlRateV2Request USERID="'.$this->userid.'">';
		$xml .= 	'<Revision>2</Revision>';
		$xml .= 	'<Package ID="1">';
		#$xml .= 		'<Service>Priority</Service>';
		$xml .= 		'<Pounds>5</Pounds>';		
		$xml .= 		'<Ounces>5</Ounces>';
		$xml .= 		'<MailType>Package</MailType>';
		$xml .= 		'<ValueOfContents>20</ValueOfContents>';
		$xml .= 		'<Country>'. $country .'</Country>';
		$xml .= 		'<Container>Rectangular</Container>';
		$xml .= 		'<Size>Large</Size>';
		$xml .= 		'<Width>10</Width>';
		$xml .= 		'<Length>13</Length>';
		$xml .= 		'<Height>4</Height>';
		$xml .= 		'<Girth>'.$girth.'</Girth>';
		$xml .= 		'<OriginZip>20170</OriginZip>';
		$xml .= 	'</Package>';
		$xml .= '</IntlRateV2Request>';
		

		$header[] = "Host: www.mjtrends.com";
		$header[] = "MIME-Version: 1.0";
		$header[] = "Content-type: multipart/mixed; boundary=----doc";
		$header[] = "Accept: text/xml";
		$header[] = "Cache-Control: no-cache";
		$header[] = "Connection: close \r\n";
		
		$url = 'http://Production.ShippingAPIs.com/ShippingAPI.dll?API='.$this->api.'&XML='.$xml;
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
		
		if(isset($array['INTLRATEV2RESPONSE'][0]['PACKAGE'][0]['SERVICE'])) {
			$is_find = false;
			for($i = 0; $i < count($array['INTLRATEV2RESPONSE'][0]['PACKAGE'][0]['SERVICE']); $i++) {
				if( strpos($array['INTLRATEV2RESPONSE'][0]['PACKAGE'][0]['SERVICE'][$i]['SVCDESCRIPTION'][0]['VALUE'], 'Priority Mail International') !== false) {
					$country = $array['INTLRATEV2RESPONSE'][0]['PACKAGE'][0]['SERVICE'][$i]['COUNTRY'][0]['VALUE'];
					$max_demisions = $array['INTLRATEV2RESPONSE'][0]['PACKAGE'][0]['SERVICE'][$i]['MAXDIMENSIONS'][0]['VALUE'];
					
					$result['country'] = $country;
					$result['srv_desc'] = $array['INTLRATEV2RESPONSE'][0]['PACKAGE'][0]['SERVICE'][$i]['SVCDESCRIPTION'][0]['VALUE'];
					if(preg_match_all('|length ([0-9]{1,3})\"|s', $max_demisions, $res)) {
						$result['priority_max_length'] = $res[1][0];
					}
					if(preg_match_all('|combined ([0-9]{1,3})\"|s', $max_demisions, $res2)) {
						$result['priority_max_combined'] = $res2[1][0];
					}
					$result['priority_max_weight'] = $array['INTLRATEV2RESPONSE'][0]['PACKAGE'][0]['SERVICE'][$i]['MAXWEIGHT'][0]['VALUE'];
					$is_find = true;
				}
			}
			if(!$is_find) {
				$country = $array['INTLRATEV2RESPONSE'][0]['PACKAGE'][0]['SERVICE'][0]['COUNTRY'][0]['VALUE'];
				$max_demisions = $array['INTLRATEV2RESPONSE'][0]['PACKAGE'][0]['SERVICE'][0]['MAXDIMENSIONS'][0]['VALUE'];

				$result['country'] = $country;
				$result['srv_desc'] = $array['INTLRATEV2RESPONSE'][0]['PACKAGE'][0]['SERVICE'][0]['SVCDESCRIPTION'][0]['VALUE'];
				if(preg_match_all('|length ([0-9]{1,3})\"|s', $max_demisions, $res)) {
					$result['priority_max_length'] = $res[1][0];
				}
				if(preg_match_all('|combined ([0-9]{1,3})\"|s', $max_demisions, $res2)) {
					$result['priority_max_combined'] = $res2[1][0];
				}
				$result['priority_max_weight'] = $array['INTLRATEV2RESPONSE'][0]['PACKAGE'][0]['SERVICE'][0]['MAXWEIGHT'][0]['VALUE'];
			}
			return $result;
		} else {
			return false;
		}
	}
}