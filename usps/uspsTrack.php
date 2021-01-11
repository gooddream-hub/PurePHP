<?php

class uspsTrack
{

	function __construct()
	{
		include_once "../logic/global.php";
		include_once "../config/config.php";

		$this->countSentEmails = 0;


		$this->globalConfig = $config;
	}

	function checkStatus()
	{
		try {

			//gets array with tracking numbers from database
			$trackingList = $this->getTrackingNumbers();

			//divides the array into parts of 30 numbers because FedEx is limited to 30 numbers per request
			$chunks = $this->chunkList($trackingList);

			// prepares the request and receives a response with an array of data on the status of packages
			$response = $this->prepareRequest($chunks);

			$deliveredArray = $this->normalizeResponse($response);

			$full_delivered = $this->updateStatus($deliveredArray);

			$this->sendEmail($full_delivered);

		} catch (Exception $e) {
			echo 'USPS TRACKING - ', $e->getMessage(), "</br>";
		}
	}

	private function getTrackingNumbers()
	{

		$numbersArray = $this->getTrackingData();

		if (!$numbersArray) {
			throw new Exception("There are no shipped packages");
		}

		$this->countShippedPackages = count($numbersArray);
		foreach ($numbersArray as $value) {
			if ( is_array($value['tracking']) ) {
				foreach ($value['tracking'] as $key => $v) {
					if($v == 0) {
						$this->numbersList[$value['custid']][] = $key;
					}
				}
			}
		}

		/*$this->numbersList = array_map(function ($entry) {
			return $entry['tracking'];
		}, $numbersArray);*/
		
		return $this->numbersList;
	}

	private function getTrackingData()
	{
		$this->mysqli = mysqliConn();

		if ($this->mysqli->connect_errno) {
			throw new Exception("Connection failed: " . $this->mysqli->connect_error);
		}

		$this->numbersData = array();
		$sql = "SELECT c.* FROM custinfo AS c LEFT JOIN payment AS p USING (custid) WHERE c.track_status = 1 AND p.ship_type LIKE 'USPS%'";
		$result = $this->mysqli->query($sql);

		if ($result) {
			while ($row = $result->fetch_assoc()) {
				$row['tracking'] = json_decode($row['tracking'], true);
				$this->numbersData[$row['custid']] = $row;
			}
		}
		return $this->numbersData;
	}

	private function _getTrackingData()
	{
		$this->mysqli = mysqliConn();

		if ($this->mysqli->connect_errno) {
			throw new Exception("Connection failed: " . $this->mysqli->connect_error);
		}

		$this->numbersData = array();
		$sql = "SELECT c.* FROM custinfo AS c LEFT JOIN payment AS p USING (custid) WHERE c.track_status = 1 AND p.ship_type LIKE 'USPS%'";
		$result = $this->mysqli->query($sql);

		if ($result) {
            while ($row = $result->fetch_assoc()) {
                $row['packing'] = json_decode($row['packing'], true);
                $this->numbersData[$row['custid']] = $row;
            }
        }

		return $this->numbersData;
	}

	private function chunkList($array)
	{
		$lists = array();
		foreach ($array as $val) {
			foreach ($val as $t) {
				$lists[] = $t;
			}
		}
		$numbersArrayMore = array_chunk($lists, 35);
		return $numbersArrayMore;
	}

	private function prepareRequest($array)
	{
		$statusArray = array();
		foreach ($array as $row) {
			$trackIds = '';
			foreach ($row as $track) {
				$trackIds .= '<TrackID ID="' . $track . '"></TrackID>';
			}
			$dataRequest = '<TrackRequest USERID="496MJTRE2583">' . $trackIds . '</TrackRequest>';
			$status = $this->getStatusData($dataRequest);

			if ($status) {
				$statusArray[] = $status;
			}
			else {
				throw new Exception("Data isn't received from FedEx server");
			}
		}
		return $statusArray;
	}


	private function getStatusData($request)
	{
		#$test_response = json_decode('{"TrackInfo":[{"@attributes":{"ID":"2499907123456123456782"},"TrackSummary":"Your item was delivered."},{"@attributes":{"ID":"CJ123456789US"},"TrackSummary":"Your your item was delivered."},{"@attributes":{"ID":"CJ234567891US"},"TrackSummary":"The Postal Service could not locate the tracking information for your request."},{"@attributes":{"ID":"9499907123456123456780"},"TrackSummary":"Your item was delivered."},{"@attributes":{"ID":"9499907123456123456783"},"TrackSummary":"Your item was delivered."}]}');
		#return $test_response;
		$url = 'http://production.shippingapis.com/ShippingAPI.dll';
		$api = 'API=TrackV2&XML=<?xml version="1.0" encoding="UTF-8" ?>';

		$ch = curl_init($url);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $api . $request);
		curl_setopt($ch, CURLOPT_TIMEOUT, 60);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

		$error = curl_error($ch);

		if (!$error) {
			$result = curl_exec($ch);
			$result = simplexml_load_string($result);
			return $result;
		} else {
			return false;
		}
	}

	private function normalizeResponse($array) {
		$delivered = array();
		foreach ($array as $row) {
			foreach ($row->TrackInfo as $value) {
				if (preg_match("/your item was delivered/i", $value->TrackSummary) || preg_match("/your item has been delivered/i", $value->TrackSummary)) {
					$param = '@attributes';
					$track = $value->{$param}->ID; //$value['ID'];
					#$key = array_search($value['ID'], $this->numbersList);
					#$delivered[] = $this->numbersData[$key];
					foreach ($this->numbersList as $custid => $v) {
						if(($k = array_search($track, $v)) !== false) {
							// if ($custid == 70336) {
								// echo "USPS Track" . $track . '<br>'; 
								// print_r( $this->numbersData[$custid] );
							// }

							if ( $track && $track != '' && array_key_exists($track, $this->numbersData[$custid]['tracking']) ) {
								$this->numbersData[$custid]['tracking'][$track] = 1;
								$delivered[$custid] = $this->numbersData[$custid];
							}




						}
					}
				}
			}
		}
		$this->countDeliveredPackages = count($delivered);
		return $delivered;
	}

	private function _normalizeResponse($array) {
		$delivered = array();
		foreach ($array as $row) {
			foreach ($row->TrackInfo as $value) {
				if (preg_match("/your item was delivered/i", $value->TrackSummary) || preg_match("/your item has been delivered/i", $value->TrackSummary)) {
					$param = '@attributes';
					$track = $value->{$param}->ID; //$value['ID'];
					#$key = array_search($value['ID'], $this->numbersList);
					#$delivered[] = $this->numbersData[$key];
					foreach ($this->numbersList as $custid => $v) {
						if(($k = array_search($track, $v)) !== false) {
							
							 foreach ($this->numbersData[$custid]['packing'] as $key => $packing) {
                                if ($track && $track != '' && $packing['tracking'] === $track) {
                                    $this->numbersData[$custid]['packing'][$key]['tracking_status'] = 1;
                                    $delivered[$custid] = $this->numbersData[$custid];
                                }
                            }

						}
					}
				}
			}
		}
		$this->countDeliveredPackages = count($delivered);
		return $delivered;
	}

	private function updateStatus($array)
	{
		$full_delivered = array();
		foreach ($array as $custid => $value) {
			if(in_array(0, $value['tracking'])) {
				$sql = "UPDATE custinfo SET tracking = '". json_encode($value['tracking']) ."' WHERE ID = ". $value['ID'];
			} else {
				$sql = "UPDATE custinfo SET tracking = '". json_encode($value['tracking']) ."', track_status = 2 WHERE ID = ". $value['ID'];
				$full_delivered[] = $value;
			}

			try {

				$this->mysqli->query($sql);

			} catch(Exception $e) {
    			echo "Database wasn't updated. Error:" . $e->getMessage();
 			}  
			/* if (!) {
				throw new Exception("Database wasn't updated. Error: %s\n", $this->mysqli->error);
				throw new Exception("Database wasn't updated. Error: %s\n", $this->mysqli->error);
			} */
		}
		$this->mysqli->close();
		return $full_delivered;
	}

	private function _updateStatus($array)
	{
		$full_delivered = array();
		foreach ($array as $custid => $value) {
			if(in_array(0, $value['tracking'])) {
                $sql = "UPDATE custinfo
                        SET tracking = '". json_encode($value['tracking']) ."', packing = '". json_encode($value['packing']) ."'
                        WHERE ID = ". $value['ID'];
            } else {
                $sql = "UPDATE custinfo
                        SET tracking = '". json_encode($value['tracking']) ."', packing = '". json_encode($value['packing']) ."' track_status = 2
                        WHERE ID = ". $value['ID'];
                $full_delivered[] = $value;
            }

			try {

				$this->mysqli->query($sql);

			} catch(Exception $e) {
    			echo "Database wasn't updated. Error:" . $e->getMessage();
 			}  
			/* if (!) {
				throw new Exception("Database wasn't updated. Error: %s\n", $this->mysqli->error);
				throw new Exception("Database wasn't updated. Error: %s\n", $this->mysqli->error);
			} */
		}
		$this->mysqli->close();
		return $full_delivered;
	}

	private function sendEmail($array)
	{
		$i = 0;



		foreach ($array as $value) {
			$subject = 'Order #' . $value['custid'] . ' has been delivered';

			$headers = 'MIME-Version: 1.0' . "\r\n";
			$headers .= 'Content-type: text/html; charset=utf-8' . "\r\n";
			$headers .= 'From: MJTrends <orders@mjtrends.com>' . "\r\n";
			$headers .= 'Bcc: ryland22@gmail.com' . "\r\n";

			$trackings = array_keys($value['tracking']);
			$trackings = implode(', ', $trackings);


			$trackings = [];

			/*
            foreach ($packing['packing'] as $packing) {
                $trackings[] = $packing['tracking'];
            }
            $trackings = implode(', ', $trackings);
            */

			$to = $value['email'];
			$message = '
				<html>
					<head>
					</head>
					<body>
						 <h4>' . $value['shipfirst'] . ',</h4>
						 <p>Your order #'.$value['custid'].' has been delivered.  You waited patiently and now it’s go time!  However, in the unlikely and unfortunate event that you don’t have your package, here is the pertinent info you’ll need to help track it down:</p>
						 <br>
						 <br>
						 <b>Tracking Number(s):</b>  '. $trackings .'
						 <br><b>USPS phone number:</b> <a href="tel:+18002758777">1-800-275-8777</a>
						 <br><b>USPS tracking website:</b> <a href="https://tools.usps.com/go/TrackConfirmAction_input">http://www.usps.com</a>
						 <br>
						 <p>
							 <b>Delivery Address:</b>
							 <br>' . $value['shipadone'] . '
							 <br>' . $value['shipadtwo'] . '
							 <br>' . $value['shipcity'] . ', ' . $value['shipstate'] . ' ' . $value['shipzip'] . '
							 <br>' . $value['shipco'] . '
						</p>
						 <br>
						 <br>
						 <br>Customer Service
						 <br><a href="mailto:sales@MJTrends.com">sales@MJTrends.com</a>
						 <br><a href="http://www.MJTrends.com">www.MJTrends.com</a>
						 <br>
						 <span style="font-weight:bold"><span style="color:#3871ab;font-size:1.5em">{</span>We help create!  Check out helpful links below<span style="color:#3871ab;font-size:1.5em">}</span></span>
						 <br>
						<a href="http://www.mjtrends.com/articles.php">Vinyl and latex sheeting video tutorials</a>
						<br>
						<a href="http://www.mjtrends.com/forum/index.php">Community forums with tons of existing content and helpful posters.</a>
						<br>
						<a href="http://www.pinterest.com/mjtrends"><img src="http:'.$this->globalConfig->CDN.'images/icons/pinterest_20x20.png" style="align:bottom;position:relative; top:3px"></a>
						<a href="http://www.facebook.com/mjtrendsCreate"><img src="http:'.$this->globalConfig->CDN.'images/icons/facebook_20x20.png" style="align:bottom;position:relative;top:3px;"></a>
						<a href="http://www.mjtrends.com/blog"><img src="http:'.$this->globalConfig->CDN.'images/icons/blogger_20x20.png" style="align:bottom;position:relative;top:3px;"></a>
						<br>
					</body>
				</html>
			';
			
			$result = mail($to, $subject, $message, $headers);

			if (!$result) {
				throw new Exception("Email was not sent");
			}

			print_r($message);
			echo $to . '<br>';

			$i += 1;


		
		    $log_file_data = 'log_' . date('d-M-Y') . '.log';
		    
		    file_put_contents($log_file_data, $to . "\n" . $message . "\n", FILE_APPEND);


		}

		$this->countSentEmails = $i;
	}
}
