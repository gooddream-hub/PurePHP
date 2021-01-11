<?php
	require(dirname(dirname(__FILE__)).'/admin/application/libraries/easypost/easypost.php');
	
	define('EASYPOST_API_KEY', 'g7qys2B5AMTtcFhsHvnQPg'); //production
	#define('EASYPOST_API_KEY', '4cu9YuoaAfMP4t6zmopTEg'); //testing

	class USPSEasy {

		public static $_auth = false;
		public $to_address = false;
		public $from_address = false;
		public $size = array();
		public $ounces = 0;
		public $packages = array();
		public static $services = array(
			'FIRST CLASS' => array(
				'service' => 'FIRST CLASS',
				'serviceName' => 'USPS 1st Class',
				'add_rate' => 0.45
			),
			'PRIORITY' => array('service' => 'Priority', 'serviceName' => 'USPS Priority'),  // only USA
			'Express' => array('service' => 'Express', 'serviceName' => 'USPS Express'), // only USA
			'ParcelSelect' => array('service' => 'ParcelSelect', 'serviceName' => 'USPS ParcelSelect'), // only USA
			'FlatRate' => array('service' => 'FlatRate', 'serviceName' => 'USPS Flat Rate'), // only USA
			'RegionalRateBoxA' => array('service' => 'RegionalRateBoxA', 'serviceName' => 'USPS Regional Rate BoxA'), // only USA
			'RegionalRateBoxB' => array('service' => 'RegionalRateBoxB', 'serviceName' => 'USPS Regional Rate BoxB'), // only USA
			
			'INTL Priority' => array('service' => 'PriorityMailInternational', 'serviceName'	=> 'USPS Priority Intl'), // non USA
			'ExpressMailInternational' => array('service' => 'ExpressMailInternational', 'serviceName' => 'USPS INTL Express'), // non USA
			'FirstClassPackageInternationalService' => array('service' => 'FirstClassPackageInternationalService', 'serviceName' => 'USPS 1st Class'), // non USA
		);

		public function USPSEasy() {
			$this->auth();

		}

		public static function services() {
			return self::$services;
		}

		public function auth() {
			if(!self::$_auth) {
				$key = \EasyPost\EasyPost::setApiKey(EASYPOST_API_KEY);
				self::$_auth = true;
			}
		}

		public function setAddress($zip, $country) {
			$this->to_address = \EasyPost\Address::create(array(
				'zip' => $zip,
				'country' => $country
			));

			$this->from_address = \EasyPost\Address::create(array(
				'zip' => 20170,
				'country' => 'UNITED STATES'
			));
		}

		public function setSizes($size) {
			$this->size = $size;
		}

		public function setWeight($weight) {
			$this->ounces = number_format($weight*16, 2, '.', '');
		}

		public function getDefaultRates() {
			$parcel = \EasyPost\Parcel::create(array(
				"length" =>  $this->size['length'],
				"width" => $this->size['width'],
				"height" => $this->size['height'],
				"weight" => $this->ounces,
				"predefined_package" => null
			));

			$shipment = $this->shipment($parcel);
			return $shipment;
		}

		public function getPreDefinedRates($package) {
			$parcel = \EasyPost\Parcel::create(array(
				"predefined_package" => $package,
				"weight" => $this->ounces
			));

			$shipment = $this->shipment($parcel);
			return $shipment;
		}

		protected function shipment($parcel) {
			$shipment = \EasyPost\Shipment::create(array(
				"to_address" => $this->to_address,
				"from_address" => $this->from_address,
				"parcel" => $parcel
			));
			return $shipment;
		}
	}