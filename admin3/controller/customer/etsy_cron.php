<?php
class Etsy_cron{

	private $mysqli;

	function __construct(){
		include_once('global.php');
		$this->mysqli = mysqliConn();
	}

	function test_token(){
		$etsy = new MY_Etsy;

		if($etsy->test_etsy_token() == false){
			$etsy->etsy_get_token();
		}
	}


	function get_receipts(){
		$today   = date('d-m-Y');
		$end_time = strtotime('+1 day', strtotime($today));
		$start_time = strtotime('-14 day', strtotime($today));
		$start_day = date('d-m-Y', $start_time);
		$end_day = date('d-m-Y', $end_time);

		$listing_data = array(
			'min_created' => $start_day,
			'max_created' => $end_day,
			'was_paid'    => 'true',
			'limit'       => $limit
		);

		$api_url = 'https://openapi.etsy.com/v2/shops/10548297/receipts';
		$success = $this->my_etsy->client->CallAPI($api_url, 'GET', $listing_data, array('FailOnAccessError'=>true), $response_rc);

		return $response_rc->results;
	}


	function get_receipt_items($receipts, $completed){
		$orders = array();

		foreach ($receipts->results as $rc) {

			$orders[$rc->receipt_id] = $rc;
			$listing_data = array(
				'limit'   => 100
			);
			$api_url = 'https://openapi.etsy.com/v2/receipts/'.$rc->receipt_id.'/transactions';
			$success = $this->my_etsy->client->CallAPI($api_url, 'GET', $listing_data, array('FailOnAccessError'=>true), $response_tr);

			foreach ($response_tr->results as $tr) {
				if (!isset($completed[$tr->transaction_id])) {
					$listing_ids[] = $tr->listing_id;
					if (isset($orders[$tr->receipt_id])) {
						$orders[$tr->receipt_id]->transaction[] = $tr;
					}
				} else {
					unset($orders[$tr->receipt_id]);
				}
			}
		}

		$listing_ids = array_unique($listing_ids);
		return array($orders, $listing_ids);

	}


	function get_order_user($orders, $listing_ids) {
		$countries = array();
		$custinfo = array();
		$payment = array(); 

		if($orders) {
			$products = $products_amount = array();
			$results = $this->Product_model->getProductsByEtsyIds($listing_ids);

			foreach ($results as $row) {
				$products[$row['listing_id']]   = $row;
				$products_amount[$row['invid']] = (int)$row['invamount'];
			}
			unset($results);
			
			$amount = 0;
			foreach($orders as $order){
				$user_info = explode(' ', $order->name);
				if(!isset($countries[$order->country_id])){
					$api_url = 'https://openapi.etsy.com/v2/countries/'.$order->country_id;
					$success = $this->my_etsy->client->CallAPI($api_url, 'GET', $listing_data, array('FailOnAccessError'=>true), $response_c);
					$countries[$order->country_id] = $response_c->results[0]->iso_country_code;
				}
				$last  = array_pop($user_info);
				$first = implode(' ', $user_info);

				$custinfo = array(
					'shiplast'   => $last,
					'shipfirst'  => $first,
					'shipadone'  => $order->first_line,
					'shipadtwo'  => $order->second_line,
					'shipcity'   => $order->city,
					'shipstate'  => $order->state,
					'shipzip'    => $order->zip,
					'shipco'     => $countries[$order->country_id],

					'billlast'   => $last,
					'billfirst'  => $first,
					'billadone'  => $order->first_line,
					'billadtwo'  => $order->second_line,
					'billcity'   => $order->city,
					'billstate'  => $order->state,
					'billzip'    => $order->zip,
					'billco'     => $countries[$order->country_id],

					'order_date' => date('Y-m-d'),
					'email'      => $order->buyer_email,
					'newsletter' => 'yes',
				);

				$payment = array(
					'custid'     => $data['custid'],
					'payType'    => 'Etsy',
					'shipping'   => $order->total_shipping_cost,
					'ship_type'  => set_ship_method($order->shipping_details->shipping_method),
					'grandtotal' => $order->grandtotal,
				);

				$amount++;
			}
		return array($custinfo, $payment, $amount);
		}
	}

	function get_custid(){
		$query = "UPDATE getid set custid = custid +1";
		$result = $this->mysqli->query($query);

		$query = "SELECT custid from getid";
		$result =  $this->mysqli->query($query);
		$row = $result->fetch_row();

		return $row[0];
	}

	function get_completed(){
		$start_time = strtotime('-14 day', strtotime($today));
		$data = array();

		$sql = "Select * FROM etsy_transactions WHERE transaction_id > 0 AND added >= ".$start_time;
		$result =  $this->mysqli->query($query);
		
		while ($row = $result->fetch_assoc()) {
			$data[] = $row;
		}

		return $data;

	}


	function insert_custinfo($custinfo, $custid){
		$sql = "INSERT INTO custinfo(custid, shipfirst, shiplast, shipadone, shipadtwo, shipcity, shipstate, shipzip, shipco, billlast, billfirst, billadone, billadtwo, billcity, billstate, billzip, billco, order_date) VALUES($custid, '".$custinfo['shipfirst']."', '".$custinfo['shiplast']."', '".$custinfo['shipadone']."', '".$custinfo['shipadtwo']."', '".$custinfo['shipcity']."', '".$custinfo['shipstate']."', '".$custinfo['zip']."', '".$custinfo['shipco']."', '".$custinfo['billfirst']."', '".$custinfo['billlast']."', '".$custinfo['billadone']."', '".$custinfo['billadtwo']."', '".$custinfo['billcity']."', '".$custinfo['billstate']."', '".$custinfo['billzip']."', '".$custinfo['billco']."', NOW() )";
	}

	function insert_payment($payment, $custid){

	}

	function insert_orderdetails(){

	}

	function set_ship_method(){

	}

} //end Etsy Class

$etsy = new Etsy_cron();

$etsy->test_token();
$reciepts = $etsy->get_receipts();
$completed = $etsy->get_completed();
list($orders, $listing_ids) = $etsy->get_receipt_items($receipts, $completed);
list($custinfo, $payment,$amount) = $etsy->get_order_user($orders, $listing_ids);	

if( !empty($custinfo) ){
	$custid = $etsy->get_custid();
	$etsy->insert_custinfo($custid, $custinfo);
	$etsy->insert_payment($custid, $payment);
}

foreach($receipt->transaction as $transaction){
	if(isset($products[$transaction->listing_id])){
		$this->etsytransactions_model->insert_transaction(
			$transaction->transaction_id,
			$transaction->listing_id,
			$products[$transaction->listing_id]['invid'],
			$receipt->creation_tsz,
			$receipt->receipt_id,
			$data['custid']
		);
		$product = $products[$transaction->listing_id];
		$order = array(
			'custid'     => $data['custid'],
			'invid'      => $product['invid'],
			'cat'        => $product['cat'],
			'type'       => $product['type'],
			'color'      => $product['color'],
			'price'      => $transaction->price,
			'quant'      => $transaction->quantity,
			'total'      => $receipt->total_price,
		);
		$this->orderdetails_model->save($order);
		$products_amount[$product['invid']] = $products_amount[$product['invid']] ? ($products_amount[$product['invid']] - $transaction->quantity) : 0;
		$this->Product_model->UpdateProduct($product['invid'], array('invamount' => $products_amount[$product['invid']]),0);
	}
}

			
			// } else {
				//This etsy order already added
			// }

	/* Not sure part */
	
	// 	}
	// 	echo $amount.' transaction(s) received';
	// } else {
	// 	echo 'you have no transactions';
	// }