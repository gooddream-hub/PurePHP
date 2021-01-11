<?
function tracking(){
	$db = DB::getInstance();
	global $cust, $tracking, $trackUrl, $order, $billnfo, $shipnfo , $delivery, $prod, $shipRate, $gTotal, $subtotal, $tax, $show_login, $show_track, $orders, $err;
	$show_login = "block";
	$show_track = "none";
	$err = "none";

	if($_GET['email']){
		$email  = $db->real_escape_string(strip_tags($_GET['email']));
		$billzip = $db->real_escape_string(strip_tags($_GET['billzip']));
		
		$query = "SELECT * from custinfo where email ='$email' AND billzip = '$billzip' ORDER BY id DESC LIMIT 6";
		$result = $db->query($query); 
		$row = $result->fetch_assoc();
	
		if(!$row['ID']){
			$err = "block";
		} else {
			$result->data_seek(0);
			$i = 0;
			while ($row = $result->fetch_assoc()){
				if(!isset($_GET['id']) && $i == 0){
					$orders[] = array("<span>".$row['custid']."</span>"); // no link
				} elseif($_GET['id'] == $row['custid']){
					$orders[] = array("<span>".$row['custid']."</span>"); // no link
				} else {
					$orders[] = array('<a href="tracking.php?email='.$_GET['email'].'&billzip='.$_GET['billzip'].'&id='.$row['custid'].'">'.$row['custid'].'</a>'); // link	
				}
				$i++;
			}
	
			if(isset($_GET['id'])){
				$query = "SELECT * from custinfo where custid = ".$db->real_escape_string($_GET['id'])." AND email = '$email'";
				$result = $db->query($query); 
				$row = $result->fetch_assoc();
			}
			$show_login = "none";
			$show_track = "block";
			
			$result->data_seek(0);
			$row = $result->fetch_assoc();
			
			$orderDate = $row['order_date'];
			$shipDate = $row['ship_date'];
			$delDate = $row['del_date'];
		
			$custid = $row['custid'];
			
			// $tracking = $row['tracking'];
			
			// DES-514 UPDATES
			$tracking = '';
			$packings = json_decode($row['packing'], true);
			if ( isset($packings[0]) ) {
				$packing = $packings[0];
				if ( isset($packing['tracking'])) {
					$tracking = $packing['tracking'];
				}
			}
			


			
			$billnfo = array($row['billfirst']." ".$row['billlast'], $row['billcomp'], $row['billadone'],$row['billadtwo'],$row['billcity'].", ".$row['billstate']." ".$row['billzip'], $row['billco']);
			$shipnfo = array($row['shipfirst']." ".$row['shiplast'], $row['shipcomp'], $row['shipadone'],$row['shipadtwo'],$row['shipcity'].", ".$row['shipstate']." ".$row['shipzip'], $row['shipco'], $row['phone']);
			
			$query2 = "SELECT * from payment where custid = '$custid'";
			$result2 = $db->query($query2); 
			$row2 = $result2->fetch_assoc();
			
			$shipRate = number_format($row2['shipping'],2,'.','');
			$tax = number_format($row2['tax'],2,'.','');
			$gTotal = number_format($row2['grandtotal'],2,'.','');
			$shipMeth = $row2['ship_type'];
			
			switch($shipMeth){
			case "FedEx Ground":
				$del = 4;
				$trackUrl = "http://www.fedex.com/Tracking?ascend_header=1&clienttype=dotcom&cntry_code=us&language=english&tracknumbers=".$tracking;
				break;
			case "FedEx 2 Day":
				$del = 3;
				$trackUrl = "http://www.fedex.com/Tracking?ascend_header=1&clienttype=dotcom&cntry_code=us&language=english&tracknumbers=".$tracking;
				break;
			case "FedEx Overnight":
				$del = 1;
				$trackUrl = "http://www.fedex.com/Tracking?ascend_header=1&clienttype=dotcom&cntry_code=us&language=english&tracknumbers=".$tracking;
				break;
			case "USPS Priority":
				$del = 3;
				$trackUrl = "https://tools.usps.com/go/TrackConfirmAction?qtc_tLabels1=".$tracking;
				break;
			case "USPS Flat Rate":
				$del = 3;
				$trackUrl = "https://tools.usps.com/go/TrackConfirmAction?qtc_tLabels1=".$tracking;
				break;
			case "USPS Express":
				$del = 1;
				$trackUrl = "https://tools.usps.com/go/TrackConfirmAction?qtc_tLabels1=".$tracking;
				break;
			case "FedEx Intl Economy":
				$del = 6;
				$trackUrl = "http://www.fedex.com/Tracking?ascend_header=1&clienttype=dotcom&cntry_code=us&language=english&tracknumbers=".$tracking;
				break;
			case "FedEx Intl Priority":
				$del = 6;
				$trackUrl = "http://www.fedex.com/Tracking?ascend_header=1&clienttype=dotcom&cntry_code=us&language=english&tracknumbers=".$tracking;
				break;
			case "USPS Intl":
				$del = 6;
				$trackUrl = "https://tools.usps.com/go/TrackConfirmAction?qtc_tLabels1=".$tracking;
				break;
			}
			$delivery = array($orderDate, $shipMeth, $shipDate, $delDate);
			
			if(is_numeric($_GET['id'])){
				$custid = $_GET['id'];
			}
			$query3  = "SELECT orderdetails.*, img from orderdetails left join inven_mj on orderdetails.invid = inven_mj.invid where custid = $custid ";
			$result3 = $db->query($query3); 
			
			while($row3 = $result3->fetch_assoc()){
				$subtotal += $row3['price']*$row3['quant'];
				$img = json_decode($row3['img'],true);
				$img = $img[0]['path'];
				$prod[] = array($row3['invid'], $row3['type']." ".$row3['cat'], $row3['color'], $row3['quant'], number_format($row3['price'],2,'.',''),$img);
			}
			$subtotal = number_format($subtotal,2,'.','');
		}
	}
}
?>