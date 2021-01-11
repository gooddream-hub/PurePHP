<?php

class Boxes {

	static $standard_boxes = array(
			'FirstClass' => array(
				'maxsize' => array('length' => 12, 'width' => 9, 'height' => 0.25),
				'size' => array('length' => 12, 'width' => 9, 'height' => 1),
				'girth' => 20,
				'weight' => 0.15,
				'maxvolume' => 27
			),
			'Box1' => array(
				'maxsize' => array('length' => 13, 'width' => 10, 'height' => 4),
				'size' => array('length' => 13, 'width' => 10, 'height' => 4),
				'girth' => 28,
				'weight' => 0.65,
				'maxvolume' => 520
			),
			'Box2' => array(
				'maxsize' => array('length' => 16, 'width' => 12, 'height' => 4),
				'size' => array('length' => 16, 'width' => 12, 'height' => 4),
				'girth' => 32,
				'weight' => 0.85,
				'maxvolume' => 768
			),
			'Box3' => array(
				'maxsize' => array('length' => 20, 'width' => 16, 'height' => 6),
				'size' => array('length' => 20, 'width' => 16, 'height' => 6),
				'girth' => 44,
				'weight' => 1.25,
				'maxvolume' => 1920
			),
			'Box4' => array(
				'maxsize' => array('length' => 24, 'width' => 18, 'height' => 12),
				'size' => array('length' => 24, 'width' => 18, 'height' => 12),
				'girth' => 60,
				'weight' => 2.1,
				'maxvolume' => 5184
			),
			'Box5' => array( // not for USPS INTL
				'maxsize' => array('length' => 30, 'width' => 20, 'height' => 12),
				'size' => array('length' => 30, 'width' => 20, 'height' => 12),
				'girth' => 64,
				'weight' => 2.5,
				'maxvolume' => 7200
			),
			/*'Box6' => array(
				'maxsize' => array('length' => 60, 'width' => 12, 'height' => 12),
				'size' => array('length' => 60, 'width' => 12, 'height' => 12),
				'girth' => 48, //?
				'weight' => 2.5, //?
				'maxvolume' => 8640
			),*/
		);
	
	static $flate_boxes = array(
			'FlateRate' => array(
				'maxsize' => array('length' => 13.625, 'width' => 11.875, 'height' => 3.375),
				'size' => array('length' => 13.625, 'width' => 11.875, 'height' => 3.375),
				'girth' => 30.5,
				'weight' => 0.75, //?
				'maxvolume' => 546
			)
		);
	static $regional_boxes = array(
			'RegionalRateBoxA' => array(
				'maxsize' => array('length' => 12.8, 'width' => 10.9, 'height' => 2.375),
				'size' => array('length' => 12.8, 'width' => 10.9, 'height' => 2.375),
				'girth' => 26.55,
				'weight' => 0.65, //?
				'maxvolume' => 331
			),
			'RegionalRateBoxB' => array(
				'maxsize' => array('length' => 15.875, 'width' => 14.375, 'height' => 2.875),
				'size' => array('length' => 15.875, 'width' => 14.375, 'height' => 2.875),
				'girth' => 34.5,
				'weight' => 0.85, //?
				'maxvolume' => 656
			)
		);

	static function getBox($box) {
		if(array_key_exists($box, self::$standard_boxes)) {
			return self::$standard_boxes[$box];
		} else if(array_key_exists($box, self::$flate_boxes)) {
			return self::$flate_boxes[$box];
		} else if(array_key_exists($box, self::$regional_boxes)) {
			return self::$regional_boxes[$box];
		}
		return false;
	}

	static function allBoxes() {
		$all_boxes = self::$standard_boxes;
		foreach (self::$flate_boxes as $key => $value) {
			$all_boxes[$key] = $value;
		}
		foreach (self::$regional_boxes as $key => $value) {
			$all_boxes[$key] = $value;
		}
		return $all_boxes;
	}

	static function allAvailableBoxes($country, $postal_company) {
		$country_max = self::countryMaxBox($country);
		if($postal_company == 'USPS') {
			$all_boxes = array();
			foreach (self::$standard_boxes as $key => $value) {
				if( ($value['girth'] + $value['size']['length']) <= $country_max['length_girth'] ) {
					$all_boxes[$key] = $value;
				}
			}
			return $all_boxes;
		} else if ($postal_company == 'FEDEX') {
			$all_boxes = self::$standard_boxes;
			return $all_boxes;
		} else {
			return false;
		}
	}

	static function countryMaxBox($country) {
		if($country == 'US') {
			return array(
				'max_length'=> 60,
				'length_girth' => 108,
				'weight' => 70
			);
		} else {
			$db = DB::getInstance();
			$sql = "SELECT iso_code, name, priority_max_weight, priority_max_length, priority_max_combined FROM country WHERE iso_code = '". $country ."'";
			$query = $db->query($sql);
			$result = $query->fetch_array();

			if($result['priority_max_combined'] != 0 && $result['priority_max_length'] != 0 ) {
				return array(
					'max_length'=> $result['priority_max_length'],
					'length_girth' => $result['priority_max_combined'],
					'weight' => $result['priority_max_weight']
				);
			} else {
				return array( // Use minimal params
					'max_length'=> 24,
					'length_girth' => 79,
					'weight' => 66
				);
			}
		}
	}
}