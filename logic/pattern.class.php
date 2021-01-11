<?php
class Pattern{
	public $mysqli;
	public $styles;
	public $sizing;
	public $standard_sizes;

	function getTutorials(){
		$this->tutorials = array();
		$sql = "SELECT id, title, thumb, vid_url FROM articles WHERE id IN(".$this->style['tutorials'].") ORDER BY rand() LIMIT 3";
		$result = $this->mysqli->query($sql);

		if($result){
			while ($row = $result->fetch_assoc()) {
				$this->tutorials[] = $row;	        
		    }
		}
	}

	function getPosts(){
		$this->posts = array();
		$sql = "SELECT * FROM forum WHERE thread_num IN(".$this->details['posts'].") AND sub_num = 0 ORDER BY rand() LIMIT 3";
		$result = $this->mysqli->query($sql);

		if($result){
			while ($row = $result->fetch_assoc()) {
				$this->posts[] = $row;	        
			}		
		}
	}
	
	function getPatternType($gender){
		$query = "SELECT patID, type FROM pattern_type where gender = '$gender'";
		$result = $mysqli->query($query);
		
		while ($row = $result->fetch_assoc()) {
			$types .= '{"type" : "'.$row['type'].'", "patID" : "'.$row['patID'].'"},';
		}
		$types = rtrim($types, ",");
		
		echo "[".$types."]"; //json notation - [{"type" : "top", "patID" : "432"},{"type" : "bottom", "patID" : "123"}, {"type" : "skirt", "patID" : 245"}]
	}
	
	function getPatternDetails($gender, $style, $styleID){
		$style = str_replace('-', ' ', $style);
		
		if($styleID != ""){
			$query = "SELECT styleID, style, features, videos, images, price, price_sale, gender, descrip, fabTypes, recommended FROM pattern_style WHERE styleID = $styleID ";		
		} else {
			$query = "SELECT styleID, style, features, videos, images, price, price_sale, gender, descrip, fabTypes, recommended FROM pattern_style WHERE gender = '$gender' and style = '$style' ";		
		}
		
		$result = $this->mysqli->query($query);		
		$row = $result->fetch_assoc();
		
		$this->styles = $row;
		$this->styles['images'] = json_decode($row['images'],true);
		$this->styles['features'] = json_decode($row['features'],true);
		$this->styles['videos'] = json_decode($row['videos'],true);
		$this->styles['recommended'] = json_decode($row['recommended'],true);
		$this->styles['fabTypes'] = json_decode($row['fabTypes'],true);

	}

	function getPatternStyles(){
		$query = "SELECT styleID, style, features, videos, images, price, price_sale, gender FROM pattern_style ORDER BY gender DESC, styleID";	

		$result = $this->mysqli->query($query);
		
		while ($row = $result->fetch_assoc()) {
			$this->styles[] = $row;
			$this->styles['images'] = json_decode($row['images'],true);
			$this->styles['features'] = json_decode($row['features'],true);
			$this->styles['videos'] = json_decode($row['videos'],true);
 		}
		
	}

	function getPatternSizing($styleID){
		$query = "SELECT sizing FROM pattern_style where styleID = $styleID ";
		$result = $this->mysqli->query($query);
		$row = $result->fetch_assoc();
		
		$this->sizing = json_decode($row['sizing'], true);
		
	}

	function getStandardSizing($styleID){
		$query = "SELECT size, measurements FROM pattern_sizing where styleID = $styleID";
		$result = $this->mysqli->query($query);
		
		while ($row = $result->fetch_assoc()) {
			$this->standard_sizes[] = $row;
		}
	}

	function getFabTypes($styleID){
		$query = "SELECT fabTypes FROM pattern_style WHERE styleID = $styleID";
		$result = $this->mysqli->query($query);
		
		$row = $result->fetch_row();
		echo $row[0];
	}

	function savePattern($data, $custid){ 
		include_once("cart.class.php");
		$cart = new Cart;

		$data = stripslashes($data);
		$data = explode('*',$data);
		$query = "INSERT INTO pattern_order(custid, styleID, size) VALUES($custid, $data[0], '$data[1]')";
		$result = $this->mysqli->query($query);
		$insert_id = $this->mysqli->insert_id; 
		
		$query = "SELECT pattern_type.gender, style, price, price_sale, images, TRIM(TRAILING 's' FROM pattern_type.type) AS type FROM pattern_type LEFT JOIN pattern_style on pattern_style.patID = pattern_type.patID WHERE pattern_style.styleID = $data[0]";
		$result = $this->mysqli->query($query);
		$row = $result->fetch_assoc();
		$image = json_decode($row['images'], true);
		$image = $image[0]['path'];
		
		$patterns = array('/{/', '/}/', '/"/');
		$replacements = array('','','');
		$sizing = preg_replace($patterns, $replacements, $data[1]);
		 
		$itemsArray = $cart->getCart();
		$itemsArray[] = array("type" => $row['gender'], "color" => $row['style'].' pattern', "quant" => 1, "price" => $row['price'], "invid" => $insert_id, "amt" => 10, "sale" => $row['price_sale'], "img" => $image, "cat" => "Pattern", "weight" => ".2", "volume" => 5, "minLength" => 11, "minWidth" => 8, "minHeight" => ".05", "rollcost" => "no");
		$cart->saveCart($custid, $itemsArray);

		$msg = array("status"=>"success", "custid"=>$custid);
		echo json_encode($msg);
	}

	function getStandardSize(){	
		$size = $mysqli->real_escape_string($_POST['size']);
		$gender = $mysqli->real_escape_string($_POST['gender']);

		$query = "SELECT measurements FROM pattern_sizing where size = '$size' and gender = '$gender' ";
		$result = $mysqli->query($query);
		$row = $result->fetch_row();

		echo $row[0];
	}

}