<?php
include('../../common/constants.php');
include('../../assets/helpers/cache_helper.php');
include('../../libraries/oauth/oauth_client.php');
class MY_Etsy {
	
	public $CI;
	public $client;

	public function __construct(){
		if (session_status() == PHP_SESSION_NONE) {
			session_start();
		}

		require(SITE_ROOT_PATH.'admin3/libraries/oauth/http.php');

		$this->client = new oauth_client_class;
		
		$this->client->debug              = true;
		$this->client->debug_http         = true;
		$this->client->server             = 'Etsy';
		$this->client->redirect_uri       = 'http://' . $_SERVER['HTTP_HOST'] . '/admin3/libraries/etsy/callback.php'; // Here is change
		// $this->client->redirect_uri       = 'https://' . $_SERVER['HTTP_HOST'] . '/admin3/libraries/etsy/callback.php'; // Here is change
		$this->client->configuration_file = SITE_ROOT_PATH.'admin3/libraries/oauth/oauth_configuration.json';
		
		$this->client->client_id          = 'ajocsi6rfi22tzv7kx5g8iop';//'bpcs5km3fjeshyozkh2p7azc';//
		$this->client->client_secret      = 'rgvd7ju59k';//'ri5rr651e6';//
	}

	public function getAccessToken($redirect_uri){
		if(!$this->isAccessToken()) {
			$access_file = SITE_ROOT_PATH.'admin3/libraries/etsy/access_token.json';
			if($access = readCache($access_file, 3600*24*300)) { //Cache json request on 300 days (looks like Etsy give permanent access token)
				$this->setAccessToken(json_decode($access, true));
				// redirect($this->client->redirect_uri.'?r='.urlencode($redirect_uri));
				$this->etsyAccessToken($redirect_uri);
			} else {
				$this->etsyAccessToken($redirect_uri);
				return $access_token;
			}

		} else {
			// Need to besure that access token is valid
			if($this->client->Initialize()) {
				if($this->client->Process()) {
				}
			}
		}
	}

	public function etsyAccessToken($redirect_uri) {
		$this->client->redirect_uri = $this->client->redirect_uri.'?r='.urlencode($redirect_uri);
		if($success = $this->client->Initialize()) {
			if($success = $this->client->Process()) {
				if(strlen($this->client->access_token)) {
					$access_file = SITE_ROOT_PATH.'admin3/libraries/etsy/access_token.json';
					$access['https://openapi.etsy.com/v2/oauth/access_token'] = $_SESSION['OAUTH_ACCESS_TOKEN']['https://openapi.etsy.com/v2/oauth/access_token'];
					writeCache(json_encode($access), $access_file);
					// file_put_contents($access_file, json_encode($access));
					return $this->client->access_token;
				} else {
					return false;
				}
			}
			$success = $this->client->Finalize($success);
		}
	}

	function scopes() {
		$listing_data = array();
		$api_url = 'https://openapi.etsy.com/v2/oauth/scopes';
		$success = $client->CallAPI($api_url, 'GET', $listing_data, array('FailOnAccessError'=>true), $response);
		
		if (empty($response->results) || !in_array('listings_w', $response->results)) {
			header('Location:http://'.$_SERVER['HTTP_HOST'].dirname(strtok($_SERVER['REQUEST_URI'],'?')).'/etsy_callback.php?reset=1');
			die;
		}
		else {
			'You have not permission to create Etsy listing.<br><a href="http://'.$_SERVER['HTTP_HOST'].dirname(strtok($_SERVER['REQUEST_URI'],'?')).'/index.php">Return</a>';
		}
	}

	public function setAccessToken($access) {
		$_SESSION['OAUTH_ACCESS_TOKEN'] = $access;
		return true;
	}

	public function isAccessToken() {
		if(!empty($_SESSION['OAUTH_ACCESS_TOKEN'])) {
			if(!empty($_SESSION['OAUTH_ACCESS_TOKEN']['https://openapi.etsy.com/v2/oauth/access_token'])) {
				$access_token = $_SESSION['OAUTH_ACCESS_TOKEN']['https://openapi.etsy.com/v2/oauth/access_token']['value'];
				return true;
			}
		}

		return false;
	}

	public function getEtsyUser(){
		$user_file = BASEPATH .'cache/user.json';
		if($json = readCache($user_file, 3600*24*90)) { //Cache json request on 90 days
			$response = json_decode($json);
		} else {
			if($this->client->access_token){
				$listing_data = array();
				$api_url = 'https://openapi.etsy.com/v2/users/__SELF__';
				$success = $this->client->CallAPI($api_url, 'GET', $listing_data, array('FailOnAccessError'=>true), $response);
				$result['user_id'] = $response->results[0]->user_id;
				$result['login_name'] = $response->results[0]->login_name;
				$result['primary_email'] = $response->results[0]->primary_email;
				$json = json_encode($result);
				writeCache($json, $user_file);
				$response = json_decode($json);
			} else {
				// Need re-new access
			}
		}
		return $response;
	}

	public function getShippingTemplates() {
		
		$shiping_tpl_file = SITE_ROOT_PATH .'admin3/cache/shiping_tpl.json';
		if($json = readCache($shiping_tpl_file, 3600*24*90)) { //Cache json request on 90 days
			$shipping_temlates = json_decode($json);
		} else {
			$shipping_temlates = array();
			if($this->client->access_token){
				$user = $this->getEtsyUser();
				$listing_data = array(
					'user_id' => $user->user_id,
					'limit'   => 100
				);
				$api_url = 'https://openapi.etsy.com/v2/users/'. $user->user_id .'/shipping/templates';
				$success = $this->client->CallAPI($api_url, 'GET', $listing_data, array('FailOnAccessError'=>true), $response);
				if (!empty($response->results)) {
					foreach ($response->results as $result) {
						$shipping_temlates[] = array(
							'id'    => $result->shipping_template_id,
							'title' => $result->title,
						);
					}
				}
			}
			$json = json_encode($shipping_temlates);
			writeCache($json, $shiping_tpl_file);
			$shipping_temlates = json_decode($json);
		}
		return $shipping_temlates;
	}

	public function getTaxanomy() {
		$taxonomy_file = SITE_ROOT_PATH .'admin3/cache/taxonomy.json';
		$response = array();

		if($json = readCache($taxonomy_file, 3600*24*30)) { //Cache json request on 30 days
			$response = json_decode($json);
		} else {
			$api_url = 'https://openapi.etsy.com/v2/taxonomy/seller/get?api_key='. $this->client->client_id;

			$json = file_get_contents($api_url);
			$result = json_decode($json);
			$response = $this->taxanomy_array($result->results);
			$json = json_encode($response);
			writeCache($json, $taxonomy_file);
			$response = json_decode($json);
		}
		return $response;
	}

	public function taxanomy_array($result, $level=0) {
		$level++;
		foreach ($result as $cat) {
			$resp['id'] = $cat->id;
			$resp['name'] = $cat->name;
			if($level < 5) {
				if($cat->children) {
					$new_level = array();
					$new_level = $this->taxanomy_array($cat->children, $level);
					$resp['child'] = $new_level;
				}
			}
			$response[$cat->id] = $resp;
		}
		return $response;
	}

	public function getCategory($parent) {
		$response = array();
		if($parent) {
			$api_url = 'https://openapi.etsy.com/v2/taxonomy/categories/'. $parent .'?api_key='. $this->client->client_id;
		} else {
			$api_url = 'https://openapi.etsy.com/v2/taxonomy/categories?api_key='. $this->client->client_id;
		}
		$text = file_get_contents($api_url);
		$response = json_decode($text);
		return $response;
	}

	public function postListing($invid, $listing) {

		$basepath = 'http://localhost';//http://mjtrends.b-cdn.net';
		if($this->client->access_token){
			try {
				$api_url = 'https://openapi.etsy.com/v2/listings';
				$this->client->CallAPI($api_url, 'POST', $listing['listing_data'], array('FailOnAccessError'=>true), $response);
				
				var_dump($response);
				exit;

				if(isset($response->results) && !empty($response->results[0]->listing_id)) {
					$listing_id = $response->results[0]->listing_id; // Etsy id
					$resp[$invid]['data'] = 'success';
					$resp[$invid]['msg'] = 'Successfully posted to Etsy.com!';
					// ConnectEtsy($invid, (int)$response->results[0]->listing_id);
					$etsy_id = (int)$response->results[0]->listing_id;
					include('../../../common/database.php');
					$sql = "Insert INTO etsy_transactions (invid, listing_id) VALUES ('$invid', '$etsy_id')";
					$result = $mysqli->query($sql);
						
					if ($listing['listing_img']) {
						$imgs = $listing['listing_img'];
						$i = 1;
						foreach ($imgs as $img) {
							$source_file = realpath($basepath.'/'.$img.'_924x699.jpg');
							if($source_file) {
								$listing_data = array(
									'image'      => $source_file,
									'listing_id' => $listing_id,
									'rank'       => $i++,
									'overwrite'  => 0,
								);
								$options = array(
									'FailOnAccessError' => true,
									'RequestContentType' => 'multipart/form-data',
									'Files' => array(
										'image' => array(
											'Type' => 'FileName',
											'ContentType' => 'image/jpeg',
										),
									),
								);
								$api_url = 'https://openapi.etsy.com/v2/listings/'.$listing_id.'/images';
								$this->client->CallAPI($api_url, 'POST', $listing_data, $options, $img_response);
								if($i >= 5) {
									break;
								}
							}
						}
					}
				} else {
					$resp[$invid]['data'] = 'error';
					$resp[$invid]['msg'] = str_replace('_', ' ', implode('<br>', array_keys($response)));					
				}
			} catch (OAuthException $e) {
				$resp[$invid]['access'] = 'error';
				$resp[$invid]['msg'] = $e->getMessage();
			}
		} else {
			$resp[$invid]['access'] = 'error';
			$resp[$invid]['msg'] = 'No access token';
		}
		return $resp;
		exit;
	}

	public function validateListing($listing) {
		$is_valid = true;
		$errors = array();
		if(empty($listing['listing_data']['price']) || !preg_match('|^[0-9]{0,4}(\.[0-9]{1,2})?$|', $listing['listing_data']['price'])){
			$is_valid = false;
			$errors[] = 'price';
		}
		if(empty($listing['listing_data']['quantity']) || !preg_match('|^[1-9]{1}[0-9]{0,3}$|', $listing['listing_data']['quantity'])){
			$is_valid = false;
			$errors[] = $listing['listing_data']['quantity'] .' quantity';
		}
		if(empty($listing['listing_data']['item_weight']) || $listing['listing_data']['item_weight'] == 0 || !preg_match('|^[0-9]{0,6}(\.[0-9]{1,3})?$|', $listing['listing_data']['item_weight'])){
			$is_valid = false;
			$errors[] = 'weight';
		}
		if(empty($listing['listing_data']['item_length']) || $listing['listing_data']['item_length'] == 0 || !preg_match('|^[0-9]{0,6}(\.[0-9]{1,3})?$|', $listing['listing_data']['item_length'])){
			$is_valid = false;
			$errors[] = 'length';
		}
		if(empty($listing['listing_data']['item_width']) || $listing['listing_data']['item_width'] == 0 || !preg_match('|^[0-9]{0,6}(\.[0-9]{1,3})?$|', $listing['listing_data']['item_width'])){
			$is_valid = false;
			$errors[] = 'width';
		}
		if(empty($listing['listing_data']['item_height']) || $listing['listing_data']['item_height'] == 0 || !preg_match('|^[0-9]{0,6}(\.[0-9]{1,3})?$|', $listing['listing_data']['item_width'])){
			$is_valid = false;
			$errors[] = 'height';
		}
		$valid['is_valid'] = $is_valid;
		$valid['errors'] = $errors;
		return $valid;
	}

	public function submitTracking($receipt_id, $carrier_name, $tracking_code) {
        $SHOP_ID = '10548297';
		$put_data = [ 'carrier_name' => $carrier_name, 'tracking_code' => $tracking_code];


		$api_url = 'https://openapi.etsy.com/v2/shops/'.$SHOP_ID.'/receipts/'.$receipt_id .'/tracking';

	 	$success = $this->client->CallAPI($api_url, 'POST', $put_data, [], $response);
        return $response;
	}

	public function postTracking($receipt_id, $tracking_num){
		echo "post tracking";
		//if($this->client->access_token){
			$api_url = 'https://openapi.etsy.com/v2/shops/10548297/receipts/'.$receipt_id.'/tracking';
			echo $api_url;
			$tracking_data=array("tracking_code"=>$tracking_num, "carrier_name"=>"USPS");
			$results = $this->client->CallAPI($api_url, 'POST', $tracking_data, [], $response);
			print_r($results);
			print_r($response);
		//}
	}

	public function updateReceipt($receipt_id, $params) {
		if (count($params) < 1) {
            return FALSE;
        }



        $put_data = [];

        if (isset($params['was_paid'])) {
            $put_data['was_paid'] = $params['was_paid'];
        }

        if (isset($params['was_shipped'])) {
            $put_data['was_shipped'] = $params['was_shipped'];
        }





		$api_url = 'https://openapi.etsy.com/v2/receipts/'.$receipt_id;


        $success = $this->client->CallAPI($api_url, 'PUT', $put_data, [], $response);
        return $response;
	}



	public function getReceipt($receipt_id) {
		

		$api_url = 'https://openapi.etsy.com/v2/receipts/'.$receipt_id;
        $success = $this->client->CallAPI($api_url, 'GET', ['receipt_id' => $receipt_id], array('FailOnAccessError'=>true), $response);
        return $response;


	}

	function getShops() {
        $this->ci =& get_instance();

		$this->ci->load->model('etsytransactions_model');

		$user = $this->getEtsyUser();
		$limit = 1;
		
		$listing_data = array(
			'user_id' => $user->user_id,
			'limit'   => $limit
		);
		$api_url = 'https://openapi.etsy.com/v2/users/'.$user->user_id.'/shops';
		$success = $this->client->CallAPI($api_url, 'GET', $listing_data, array('FailOnAccessError'=>true), $response);
		return $response;
		$receipts = array();
		$listing_ids = array();

		if (!empty($response->results)) {
			$today   = date('d-m-Y');
			$end_time = strtotime('+1 day', strtotime($today));
			$start_time = strtotime('-14 day', strtotime($today));
			$start_day = date('d-m-Y', $start_time);
			$end_day = date('d-m-Y', $end_time);
			$transactions = $this->ci->etsytransactions_model->getAllTransations($start_time);
			foreach ($response->results as $shop) {
				$listing_data = array(
					'min_created' => $start_day,
					'max_created' => $end_day,
					'was_paid'    => 'true',
					'limit'       => $limit
				);
				$api_url = 'https://openapi.etsy.com/v2/shops/'.$shop->shop_id.'/receipts';
				$success = $this->client->CallAPI($api_url, 'GET', $listing_data, array('FailOnAccessError'=>true), $response_rc);
				
				foreach ($response_rc->results as $rc) {
					$receipts[$rc->receipt_id] = $rc;
					$listing_data = array(
						'limit'   => $limit
					);
					$api_url = 'https://openapi.etsy.com/v2/receipts/'.$rc->receipt_id.'/transactions';
					$success = $this->client->CallAPI($api_url, 'GET', $listing_data, array('FailOnAccessError'=>true), $response_tr);
					foreach ($response_tr->results as $tr) {
						if (!isset($transactions[$tr->transaction_id])) {
							$listing_ids[] = $tr->listing_id;
							if (isset($receipts[$tr->receipt_id])) {
								$receipts[$tr->receipt_id]->transaction[] = $tr;
							}
						}
						else {
							unset($receipts[$tr->receipt_id]);
						}
					}
				}
			}
		}
		$listing_ids = array_unique($listing_ids);
	}

	public function get_carrier_name_by_ship_type($ship_type) {
		$types = ['usps', 'fedex'];
		$sType = strtolower($ship_type);
		foreach ($types as $type) {
			preg_match('/^'.$type.'/', $sType, $matches, PREG_OFFSET_CAPTURE);
			if ($matches) {
				return $type;
			}
		}
		return null;
	}

	public function test_etsy_token(){
		$key = "ajocsi6rfi22tzv7kx5g8iop";
		$secret = "rgvd7ju59k";
		$oauth = new OAuth($key, $secret);
		$oauth->disableSSLChecks();

		$access_file = SITE_ROOT_PATH.'admin3/libraries/etsy/access_token.json';
		$json = file_get_contents($access_file);
		$t = json_decode($json,true);

		$value = "";
		$secret = "";
// print_r($t['value']); echo "\n"; exit;
		forEach($t as $value) { 
			forEach($value as $res => $a) { 
				if ($res === 'value') {
					$value = $a;
				} else if ($res === 'secret') {
					$secret = $a;
				}

			}
		}
		
		$oauth->setToken($value, $secret);

		$api_url = "https://openapi.etsy.com/v2/users/__SELF__";
	 	$data = $oauth->fetch($api_url, null, OAUTH_HTTP_METHOD_GET);
	 	$json = $oauth->getLastResponse();
	 	$response = json_decode($json);
        
	 	if( empty($response->results) ){
	 		return false;
	 	} else {
	 		return true;
	 	}
	}

	public function etsy_get_token(){
		$key = "ajocsi6rfi22tzv7kx5g8iop";
		$secret = "rgvd7ju59k";

		$oauth = new OAuth($key, $secret);
		$oauth->disableSSLChecks();
		$domain = "http://".$_SERVER['HTTP_HOST'];

	    $req_token = $oauth->getRequestToken("https://openapi.etsy.com/v2/oauth/request_token", $domain."/admin3/libraries/etsy/etsy_callback.php", "GET");
		//$tokenFile = fopen("/var/www/mjtrends/admin3/libraries/etsy/token.txt", "w") or die("Unable to open file!");
		//$tokenFile = fopen("/xampp/htdocs/LAMP/admin3/libraries/etsy/token.txt", "w") or die("Unable to open file!");
		$tokenFile = fopen(SITE_ROOT_PATH."admin3/libraries/etsy/token.txt", "w") or die("Unable to open file!");
	    fwrite($tokenFile, $req_token['oauth_token'] . "\n");
	    fwrite($tokenFile, $req_token['oauth_token_secret'] . "\n");

	    $login_url = sprintf(
	        "%s?oauth_consumer_key=%s&oauth_token=%s",
	        $req_token['login_url'],
	        $req_token['oauth_consumer_key'],
	        $req_token['oauth_token']
	    );

	    header("Access-Control-Allow-Origin: *, Location: " . $login_url);
	}

}