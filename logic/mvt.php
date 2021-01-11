<?php
class MVT{
	
	public function saveImpression($variation, $testName){
		$query = "INSERT INTO mvt (variation, testName, impressions) VALUES ($variation, '$testName', 1) ON DUPLICATE KEY UPDATE impressions=impressions+1;";
		$result = mysql_query($query); 
	}
	
	public function saveClick($variation){
		$query = "UPDATE mvt set clicks = clicks+1 WHERE variation = $variation";
		$result = mysql_query($query); 
	}
	
	public function saveConversion($variation){
		$query = "UPDATE mvt set conversions = conversions+1 WHERE variation = $variation";
		$result = mysql_query($query); 
	}

}