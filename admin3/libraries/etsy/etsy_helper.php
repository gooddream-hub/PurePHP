<?php 

function feature_text($feature) {
	$featureArray = json_decode($feature, true);
	$feature_text = '';
	if(is_array($featureArray)){
		foreach($featureArray as $key => $val){
			$feature_text .= $key.": ".$val."\n";
		}
	} else {
		$feature_text = str_replace('<br>', "\n", $feature);
	}
	return $feature_text;
}

function images_array($img, $invid) {
	$CI =& get_instance();
	$cdn_url = $CI->config->item('cdn_url_https');
	$images = !empty($img) ? json_decode($img, true) : array();
	$imgArray = array();
	if($images) {
		$k = 0;
		foreach ($images as $value) {
			$im['url']  = $cdn_url . '/images/product/' . $invid .'/'. $value['path'] .'_115x87.jpg';
			$im['alt']  = $value['alt'];
			$im['path'] = 'images/product/'. $invid .'/'. $value['path'];
			$imgArray[] = $im;
			$k++;
			if($k > 4) {
				break;
			}
		}
	}
	return $imgArray;
}

function etsy_descr_text($descr, $feature) {
	$description = "Description:\n\n";
	$description .= strip_tags($descr);
	$description .= "\n\nFeatures:\n\n";
	$description .= strip_tags($feature);
	return $description;
}