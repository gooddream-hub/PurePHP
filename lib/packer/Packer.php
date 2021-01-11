<?php
class Packer {

	protected $items;

	public $volume; 	// in cubic inches
	public $weight; 	// pounds
	public $minWidth; 	// in inches
	public $minLength; 	// in inches
	public $minHeight; 	// in inches
	public $maxBoxVolume;
	public $maxWeight = 62.5; // in pounds

	public function __construct($items, $boxes) {
		$this->maxBoxVolume = max(array_column($boxes, 'maxvolume'));

		$volume = 0;
		$weight = 0;
		foreach ($items as $item) {
			$maxvolume = $item['volume'] * $item['quant'];
			$maxweight = $item['weight'] * $item['quant'];

			if($maxvolume > $this->maxBoxVolume) { //We can try separate item to different boxes
				if($item['quant'] > 1) {
					$inbox_quantity = floor($this->maxBoxVolume/$item['volume']);
					if($inbox_quantity >=1) {
						$all = $item['quant'];
						do {
							if($all - $inbox_quantity > 0) {
								$item['quant'] = $inbox_quantity;
							} else {
								$item['quant'] = $all;
							}
							$vitems[] = $item;
							$all -= $inbox_quantity;
						} while( $all > 0);
					}
				} else {
					// Can't pack items in box.
				}
			} else {
				$vitems[] = $item;
			}
		}

		foreach ($vitems as $item) {
			if($maxweight > $this->maxWeight) { //We can try separate item to different boxes
				if($item['quant'] > 1) {
					$inbox_quantity = floor($this->maxWeight/$item['weight']);
					if($inbox_quantity >=1) {
						$all = $item['quant'];
						do {
							if($all - $inbox_quantity > 0) {
								$item['quant'] = $inbox_quantity;
							} else {
								$item['quant'] = $all;
							}
							$this->items[] = $item;
							$all -= $inbox_quantity;
						} while( $all > 0);
					}
				} else {
					// Can't pack items in box.
				}
			} else {
				$this->items[] = $item;
			}
		}
		foreach($this->items as &$item){
			$item['maxvolume'] = $item['volume'] * $item['quant'];
			$item['maxweight'] = $item['weight'] * $item['quant'];
			$volume += $item['volume'] * $item['quant'];
			$weight += $item['weight'] * $item['quant'];
		}
		
		$this->weight = round($weight,2);
		$this->volume = $volume;

		$this->minWidth = max(array_column($this->items, 'minWidth'));
		$this->minLength = max(array_column($this->items, 'minLength'));
		$this->minHeight = max(array_column($this->items, 'minHeight'));
	}

	public function zippack($boxes){
		$packed_boxes = array();
		$maxVolume = max(array_column($boxes, 'maxvolume'));
		usort($this->items, array($this, 'volume_sort'));
		$unpacked = $this->items;
		$k = count($unpacked);
		while( $k > 0 ) {
			if(count($unpacked) > 0) {
				$res = $this->pack_box($unpacked, $boxes);
				if($res) {
					$unpacked = $res['unpacked'];
					$packed_boxes[] = $res['box'];
				} else {
					// We can't pack items (maybe one item have volume more then max box)
					return array();
				}
			}
			$k--;
		}
		//Group boxes with the same weight and size
		$final_boxes = array();
		foreach ($packed_boxes as $pb) {
			if(array_key_exists($pb['box'] .'_'. $pb['weight'], $final_boxes)) {
				$final_boxes[$pb['box'] .'_'. $pb['weight']]['quantity']++;
			} else {
				$final_boxes[$pb['box'] .'_'. $pb['weight']] = $pb;
				$final_boxes[$pb['box'] .'_'. $pb['weight']]['quantity'] = 1;
			}
			# code...
		}
		return $final_boxes;
	}

	public function pack_box($items, $boxes) {
		$maxVolume = max(array_column($boxes, 'maxvolume'));
		$maxWeight = 65 - 2.5; // Max available weight for maxbox
		$unpacked = $items;
		$volume = 0;
		$weight = 0;
		$tmp_item = array();
		$pack_box = false;
		foreach ($items as $key => $item) {
			if( ($volume + $item['maxvolume']) < $maxVolume && ($weight + $item['maxweight']) < $maxWeight ) {
				$volume += $item['maxvolume'];
				$weight += $item['maxweight'];
				$tmp_item[] = $item;
				unset($unpacked[$key]);
			} else {
				// Before pack box, check is we can add to this something from unpacked items
				$availableVolume = $maxVolume - $volume;
				$availableWeight = $maxWeight - $weight;
				$max_iteration = count($unpacked);
				$found_item = false;
				while($availableVolume > 0 && $availableWeight > 0 && $max_iteration > 0) {
					foreach ($unpacked as $key => $value) {
						if($value['maxvolume'] < $availableVolume && $value['maxweight'] < $availableWeight) {
							$volume += $value['maxvolume'];
							$weight += $value['maxweight'];
							$availableVolume = $availableVolume - $value['maxvolume'];
							$availableWeight = $availableWeight - $value['maxweight'];
							$found_item = true;
							break;
						}
					}
					if($found_item) {
						unset($unpacked[$key]);
						$found_item = false;
					}
					$max_iteration--;
				}
				$box = $this->getBox($volume, $boxes, $tmp_item);
				if($box) {
					$pack_box = array('box'=>$box, 'volume'=>$volume, 'weight'=>$weight);
				}
				break;
			}
		}
		
		if(!$pack_box) { // Put rest of itmes in box
			$box = $this->getBox($volume, $boxes, $tmp_item);
			if($box) {
				$pack_box = array('box'=>$box, 'volume'=>$volume, 'weight'=>$weight);
			}
		}

		if($pack_box) {
			return array('unpacked'=> $unpacked, 'box'=>$pack_box);
		} else {
			return false;
		}
	}

	public function is_in_box($boxes) {
		return $this->getBox($this->volume, $boxes, $this->items);
	}

	public function getBox($volume, $boxes, $items) {
		if($volume > 0 && count($items) > 0) {
			$minWidth = max(array_column($items, 'minWidth'));
			$minLength = max(array_column($items, 'minLength'));
			$minHeight = max(array_column($items, 'minHeight'));

			foreach ($boxes as $key => $box) {
				if( $volume <= $box['maxvolume'] && $minLength <= $box['maxsize']['length'] && $minWidth <= $box['maxsize']['width'] && $minHeight <= $box['maxsize']['height'] ) {
					return $key;
				}
			}
		}
		return false;
	}

	public function volume_sort($a, $b) {
		return $a['maxvolume'] <= $b['maxvolume'];
	}
}