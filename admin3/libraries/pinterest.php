<?php

class myPinterest {

	private $username;
	private $password;
	
	private $ch;
	
	private $token;
	private $app_version;
	
	private $domain_name;
	
	private $hhb_curl_domainCache = "";

	public function __construct($login, $pass) {
		$this->username = $login;
		$this->password = $pass;
		
		$this->hhb_curl_init(array(
			CURLOPT_USERAGENT => "Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/40.0.2214.115 Safari/537.36",
			CURLOPT_HEADER    => true
		));
	}

	public function login_old()
	{
		$info = $this->hhb_curl_exec('https://www.pinterest.com/login/?next=https%3A%2F%2Fwww.pinterest.com%2F&prev=https%3A%2F%2Fwww.pinterest.com%2F'); //get session cookie and stuff (should be handled by curl automatically)
		
		$this->find_token($info);
		
		curl_setopt_array($this->ch, array(
			CURLOPT_URL => 'https://www.pinterest.com/resource/UserSessionResource/create/',
			CURLOPT_POST => true,
			CURLOPT_ENCODING => "deflate",
			CURLOPT_HTTPHEADER => array(
				'Accept:application/json, text/javascript, */*; q=0.01',
				'Accept-Language:nb-NO,nb;q=0.8,no;q=0.6,nn;q=0.4,en-US;q=0.2,en;q=0.2',
				'Connection:keep-alive',
				'Content-Type:application/x-www-form-urlencoded; charset=UTF-8',
				'Host:www.pinterest.com',
				'Origin:https://www.pinterest.com',
				'Referer:https://www.pinterest.com/',
				'X-APP-VERSION:'.$this->app_version,
				'X-CSRFToken:' . $this->token,
				'X-NEW-APP:1',
				'X-Pinterest-AppState:active',
				'X-Requested-With:XMLHttpRequest',
			),
			CURLOPT_POSTFIELDS =>
				'source_url=' . rawurlencode('/login/?next=https%3A%2F%2Fwww.pinterest.com%2F&prev=https%3A%2F%2Fwww.pinterest.com%2F') .
				'&data=' . rawurlencode('{"options":{"username_or_email":"' . $this->username . '","password":"' . $this->password . '"},"context":{}}') .
				'&module_path=' . rawurlencode('App()>LoginPage()>Login()>Button(text=Logg inn, size=large, class_name=primary, type=submit)')
		));
		$info = $this->hhb_curl_exec('https://www.pinterest.com/resource/UserSessionResource/create/');
		
		$this->find_token($info);

		curl_setopt_array($this->ch, array(
			CURLOPT_URL => "https://www.pinterest.com/resource/UserRegisterTrackActionResource/update/",
			CURLOPT_POST => true,
			CURLOPT_ENCODING => "deflate",
			CURLOPT_HTTPHEADER => array(
				"Origin:https://www.pinterest.com",
				"Accept-Language:nb-NO,nb;q=0.8,no;q=0.6,nn;q=0.4,en-US;q=0.2,en;q=0.2",
				"Accept:application/json, text/javascript, * /*; q=0.01",
				"X-Requested-With:XMLHttpRequest",
				"X-NEW-APP:1",
				"X-APP-VERSION:".$this->app_version,
				"X-Pinterest-AppState:active",
				"Referer:https://www.pinterest.com/",
				"Connection:keep-alive",
				"Content-Type:application/x-www-form-urlencoded; charset=UTF-8",
				"Host:www.pinterest.com",
				"X-CSRFToken:" . $this->token
			),
			CURLOPT_POSTFIELDS =>
				'source_url=' . rawurlencode('/login/?next=https%3A%2F%2Fwww.pinterest.com%2F&prev=https%3A%2F%2Fwww.pinterest.com%2F') .
				'&data=' . rawurlencode('{"options":{"action":"setting_new_window_location"},"context":{}}') .
				'&module_path=' . rawurlencode('App()>LoginPage()>Login()>Button(text=Logg inn, size=large, class_name=primary, type=submit)')
		));
		$info = $this->hhb_curl_exec('https://www.pinterest.com/resource/UserRegisterTrackActionResource/update/');

		curl_setopt_array($this->ch, array(
			CURLOPT_URL => "https://www.pinterest.com/resource/UserRegisterTrackActionResource/update/",
			CURLOPT_POST => false,
			CURLOPT_ENCODING => "deflate",
			CURLOPT_HTTPHEADER => array(
				"Accept:text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8",
				"Accept-Language:nb-NO,nb;q=0.8,no;q=0.6,nn;q=0.4,en-US;q=0.2,en;q=0.2",
				"Connection:keep-alive",
				"Host:www.pinterest.com",
				"Referer:https://www.pinterest.com/"
			)
		));

		$this->hhb_curl_exec('https://www.pinterest.com');
	}

	public function login(){
		$url = 'https://api.pinterest.com/oauth/';
		$fields = array(
			'response_type' => 'code',
			'redirect_uri' => urlencode('https://secure51.webhostinghub.com/~mjtren5/admin/beta/marketing/pinterest/get_token'),
			'client_id' => '4864781692199512140',
			'scope' => urlencode('read_public,write_public&'),
			'state' => 'uimjtr8987a'
		);

		//url-ify the data for the POST
		foreach($fields as $key=>$value) { $fields_string .= $key.'='.$value.'&'; }
		rtrim($fields_string, '&');

		//open connection
		$ch = curl_init();

		//set the url, number of POST vars, POST data
		curl_setopt($ch,CURLOPT_URL, $url);
		curl_setopt($ch,CURLOPT_POST, count($fields));
		curl_setopt($ch,CURLOPT_POSTFIELDS, $fields_string);

		//execute post
		$result = curl_exec($ch);

		//close connection
		curl_close($ch);

		$this->get_token($result);
	}

	public function get_token($result){
		echo "toke is: ".$result;
	}
	
	public function get_boards($domain_name)
	{
		$boards = array();
		$this->domain_name = $domain_name;
		$info = $this->hhb_curl_exec('https://www.pinterest.com/'.$this->domain_name);
		/*preg_match("/all_boards.*(\[.*\])/U", $info, $matches);
		if (!empty($matches[1])) {
			$boards = json_decode($matches[1]);
		}*/
		if(preg_match('|\<script type\=\"application\/json\" id\=\'jsInit1\'\>(.*?)\<\/script\>|si', $info, $matches)) {
			$boards = json_decode($matches[1]);
			$boards = $boards->tree->children[3]->children[3]->children[0]->children[0]->data;
		}
		return $boards;
	}
	
	public function post_img($url, $board, $desc = 'test - ', $link = '')
	{
		if (!$link) {
			$link = $url;
		}
		curl_setopt_array($this->ch, array(
			CURLOPT_URL        => "https://www.pinterest.com/resource/PinResource/create/",
			CURLOPT_POST       => true,
			CURLOPT_HEADER     => false,
			CURLOPT_ENCODING   => "deflate", 
			CURLOPT_HTTPHEADER => array(
				"Origin:https://www.pinterest.com",
				"Accept-Language:nb-NO,nb;q=0.8,no;q=0.6,nn;q=0.4,en-US;q=0.2,en;q=0.2",
				"Accept:application/json, text/javascript, * /*; q=0.01",
				"X-Requested-With:XMLHttpRequest",
				"X-NEW-APP:1",
				"X-APP-VERSION:".$this->app_version,
				"X-Pinterest-AppState:active",
				"Referer:https://www.pinterest.com/",
				"Connection:keep-alive",
				"Content-Type:application/x-www-form-urlencoded; charset=UTF-8",
				"Host:www.pinterest.com",
				"X-CSRFToken:" . $this->token
			),
			CURLOPT_POSTFIELDS =>
				'source_url=' . rawurlencode('/pin/find/?url='.$url) .
				'&data=' . rawurlencode('{"options":{"board_id":"'.$board->id.'","description":"'.$desc.'","link":"'.$link.'","image_url":"'.$url.'","method":"scraped"},"context":{}}') .
				'&module_path=' . rawurlencode('App()>ImagesFeedPage(resource=FindPinImagesResource(url='.$url.'))>Grid()>GridItems()>Pinnable()>ShowModalButton(module=PinCreate)#Modal(module=PinCreate())')
		));
		$info = $this->hhb_curl_exec('https://www.pinterest.com/resource/PinResource/create/');
		
		$res = json_decode($info);
		
		return empty($res->resource_response->error);
	}
	
	private function hhb_curl_init($custom_options_array = array())
	{
		if (empty($custom_options_array)) {
			$custom_options_array = array();
		}
		if (!is_array($custom_options_array)) {
			throw new InvalidArgumentException('wrong params');
		};
		$options_array = array(
			CURLOPT_AUTOREFERER => true,
			CURLOPT_BINARYTRANSFER => true,
			CURLOPT_COOKIESESSION => true,
			CURLOPT_FOLLOWLOCATION => true,
			CURLOPT_FORBID_REUSE => false,
			CURLOPT_HTTPGET => true,
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_SSL_VERIFYPEER => false,
			CURLOPT_CONNECTTIMEOUT => 10,
			CURLOPT_TIMEOUT => 11,
			CURLOPT_ENCODING => "deflate",
			CURLOPT_REFERER => 'mjtrends.loc',
			CURLOPT_USERAGENT => 'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/33.0.1750.146 Safari/537.36',
			CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_0
		);
		if (!array_key_exists(CURLOPT_COOKIEFILE, $custom_options_array)) {
			static $curl_cookiefiles_arr       = array(); //workaround for https://bugs.php.net/bug.php?id=66014
			$curl_cookiefiles_arr[]            = $options_array[CURLOPT_COOKIEFILE] = tmpfile();
			$options_array[CURLOPT_COOKIEFILE] = stream_get_meta_data($options_array[CURLOPT_COOKIEFILE]);
			$options_array[CURLOPT_COOKIEFILE] = $options_array[CURLOPT_COOKIEFILE]['uri'];
		}

		foreach ($custom_options_array as $key => $val) {
			$options_array[$key] = $val;
		}
		
		unset($key, $val, $custom_options_array);
		$this->ch = curl_init();
		curl_setopt_array($this->ch, $options_array);
	}
	
	private function hhb_curl_exec($url)
	{
		if (!is_resource($this->ch) || get_resource_type($this->ch) !== 'curl') {
			throw new InvalidArgumentException('$ch must be a curl handle!');
		}
		if (!is_string($url)) {
			throw new InvalidArgumentException('$url must be a string!');
		}
		$tmpvar = "";
		if (parse_url($url, PHP_URL_HOST) === null) {
			if (substr($url, 0, 1) !== '/') {
				$url = $this->hhb_curl_domainCache . '/' . $url;
			} else {
				$url = $this->hhb_curl_domainCache . $url;
			}
		};
		curl_setopt($this->ch, CURLOPT_URL, $url);
		$html = curl_exec($this->ch);
		
		if (curl_errno($this->ch)) {
			throw new Exception('Curl error (curl_errno=' . curl_errno($this->ch) . ') on url ' . var_export($url, true) . ': ' . curl_error($this->ch));
			// echo 'Curl error: ' . curl_error($ch);
		}
		if ($html === '' && 203 != ($tmpvar = curl_getinfo($this->ch, CURLINFO_HTTP_CODE)) /* 203 is "success, but no output".. */) {
			throw new Exception('Curl returned nothing for ' . var_export($url, true) . ' but HTTP_RESPONSE_CODE was ' . var_export($tmpvar, true));
		};
		//remember that curl (usually) auto-follows the "Location: " http redirects..
		$this->hhb_curl_domainCache = parse_url(curl_getinfo($this->ch, CURLINFO_EFFECTIVE_URL), PHP_URL_HOST);
		
		$this->find_app_version($html);
		
		return $html;
	}
	
	private function find_token($info)
	{
		$matches = array();
		preg_match("/csrftoken\=([^\;]*)/", $info, $matches);
		if (!empty($matches[1])) {
			$this->token = $matches[1];
		}
		else {
//			print_r($matches);
//			print_r($info);
		}
	}
	
	private function find_app_version($info)
	{
		$matches = array();
		preg_match("/Pinterest-Version:([^\n]*)/", $info, $matches);
		if (!empty($matches[1])) {
			$this->app_version = trim($matches[1]);
		}
	}
}

error_reporting(E_ALL);
set_error_handler("exception_error_handler");

function exception_error_handler($errno, $errstr, $errfile, $errline)
{
	if (!(error_reporting() & $errno)) {
		// This error code is not included in error_reporting
		return;
	}
	throw new ErrorException($errstr, 0, $errno, $errfile, $errline);
}
