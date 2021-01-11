<?php

class fedexTrack
{

    function __construct()
    {
        // echo "__DIR__" . __DIR__;
        include_once __DIR__."/../logic/global.php";

        $this->countSentEmails = 0;

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

            $this->updateStatus($deliveredArray);

            $this->sendEmail($deliveredArray);

        } catch (Exception $e) {
            echo 'FEDEX TRACKING - ', $e->getMessage(), "</br>";
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
        $sql = "SELECT c.* FROM custinfo AS c LEFT JOIN payment AS p USING (custid) WHERE c.track_status = 1 AND p.ship_type LIKE 'FedEx%'";
        $result = $this->mysqli->query($sql);

        if ($result) {
            while ($row = $result->fetch_assoc()) {
                $row['tracking'] = json_decode($row['tracking'], true);
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

            $dataFields = array(
                'TrackPackagesRequest' => array(
                    'appType' => 'WTRK',
                    'uniqueKey' => '',
                    'processingParameters' => (Object)array(),
                    'trackingInfoList' => array()
                )
            );

            foreach ($row as $key => $value) {

                $trackNumbersInfo = (object)array(
                    'trackNumberInfo' => array(
                        'trackingNumber' => $value,
                        'trackingQualifier' => '',
                        'trackingCarrier' => ''
                    )
                );

                array_push($dataFields['TrackPackagesRequest']['trackingInfoList'], $trackNumbersInfo);
            }

            $dataRequest = array(
                "action" => "trackpackages",
                'format' => 'json',
                'locale' => 'en_US',
                'version' => '1',
                "data" => json_encode($dataFields)
            );

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


    private function getStatusData($request) {
        $ch = curl_init('http://www.fedex.com/trackingCal/track');
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($request));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $error = curl_error($ch);

        if (!$error) {

            $result = json_decode(curl_exec($ch));
            return $result;

        } else {
            return false;
        }
    }

    private function normalizeResponse($arrays) {
        $delivered = array();

        foreach ($arrays as $array){

            $array = $array->TrackPackagesResponse->packageList;

            for ($i = 0; $i < count($array); $i++) {

                if ($array[$i]->keyStatus == 'Delivered') {
                    #$cell = array_search($array[$i]->trackingNbr, $this->numbersList);
                    #$delivered[] = $this->numbersData[$cell];

                    foreach ($this->numbersList as $custid => $v) {
                        if(($k = array_search($array[$i]->trackingNbr, $v)) !== false) {
                            

                            if ($track && $track != '' && array_key_exists($track, $this->numbersData[$custid]['tracking']) ) {
                                $this->numbersData[$custid]['tracking'][$array[$i]->trackingNbr] = 1;
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
            if (!$this->mysqli->query($sql)) {
                throw new Exception("Database wasn't updated. Error: %s\n", $this->mysqli->error);
            }
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
            $headers .= 'From: MJTrends <admin@mjtrends.com>' . "\r\n";
            $headers .= 'Bcc: ryland22@gmail.com' . "\r\n";

            $trackings = array_keys($value['tracking']);
            $trackings = implode(', ', $trackings);
            
            $to = $value['email'];
            $message = '
                <html>
                    <head>
                    </head>
                    <body>
                         <h4>' . $value['shipfirst'] . ',</h4>
                         <p>Your order #'.$value['custid'].' has been delivered.  You waited patiently and now it’s go time!  However, in the unlikely and unfortunate event that you don’t have your package, here is the pertinent info you’ll need to help track it down:</p>
                         <br>
                         <br><b>Tracking Number:</b>  '. $trackings .' 
                         <br><b>FedEx phone number:</b> <a href="tel:+18004633339">1-800-463-3339</a>
                         <br><b>FedEx tracking website:</b> <a href="http://www.fedex.com/us/track/">http://www.FedEx.com</a>
                         <br>
                         <br><b>Delivery Address:</b>
                         <br>' . $value['shipadone'] . '
                         <br>' . $value['shipadtwo'] . '
                         <br>' . $value['shipcity'] . ', ' . $value['shipstate'] . ' ' . $value['shipzip'] . '
                         <br>' . $value['shipco'] . '
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
                        <a href="http://www.pinterest.com/mjtrends"><img src="http://mjtrends.b-cdn.net/images/icons/pinterest_20x20.png" style="align:bottom;position:relative; top:3px"></a>
                        <a href="http://www.facebook.com/mjtrendsCreate"><img src="http://mjtrends.b-cdn.net/images/icons/facebook_20x20.png" style="align:bottom;position:relative;top:3px;"></a>
                        <a href="http://www.mjtrends.com/blog"><img src="http://mjtrends.b-cdn.net/images/icons/blogger_20x20.png" style="align:bottom;position:relative;top:3px;"></a>
                        <br>
                    </body>
                </html>			';
            $result = mail($to, $subject, $message, $headers);


            print_r($message);

            if (!$result) {
                throw new Exception("Email was not sent");
            }

            echo $to . '<br>';


            $i += 1;

            $log_file_data = 'log_' . date('d-M-Y') . '.log';
            file_put_contents($log_file_data, $to . "\n" . $message . "\n", FILE_APPEND);


        }

        $this->countSentEmails = $i;
    }
}