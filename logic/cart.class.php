<?php

class Cart{
	public $subtotal;
	public $tax;
	public $items;
	private $mysqli;

	function __construct()
	{
		include_once('global.php');
		$this->mysqli = mysqliConn();
	}

	function getCart() {
		if(isset($_COOKIE['custid']) OR isset($_POST['custid']) ){ //POST custid when coming from cart
			$custid = (isset($_POST['custid'])) ? $_POST['custid'] : $_COOKIE['custid'];
			$query 	= "SELECT cart FROM tmp_cart WHERE custid = $custid ";
			$result = $this->mysqli->query($query);
			$row 	= $result->fetch_row();

			if($result->num_rows == 0 && !isset($_POST['grid'])) { //do not destroy cookie if coming from grid page because we have already checked / created custid cookie
				setcookie ("custid", "", time() - 3600, "/", "");//destroy the cookie b/c user may have already checked out
			} else {
				$contents = str_replace("+","\"",$row[0]);
				$this->items = unserialize($contents);
				return $this->items;
			}
		}
	}

	//gets the cart from db when coming from non-ssl to ssl
	function setSessionCart(){
		$custid = $_POST['custid'];
		$custid = $this->mysqli->real_escape_string($custid);

		$query 	= "SELECT cart FROM tmp_cart WHERE custid = '$custid'";
		$result = $this->mysqli->query($query);
		$row 	= $result->fetch_row();
		
		$cart = str_replace("+","\"",$row[0]);
		$_SESSION['cart'] = unserialize($cart);
	}

	function setTax($user){
		if ($user->shipco == "US" OR $_SESSION['user']['shipco'] == "US"){
			$this->tax = $this->subtotal *.045;
		} else {
			$this->tax = 0;
		}
		$this->tax = number_format($this->tax,2,'.','');
	}

	function addFromGrid(){
		$contentArr = explode('*',$_POST['grid']);
		array_pop($contentArr);
		foreach($contentArr as $val){
			$indVals = explode(',',$val);
			$this->addToCart($indVals[0], $indVals[1], $indVals[2], $indVals[3], $indVals[4], $indVals[5], round($indVals[6]), $indVals[7], $indVals[8], $indVals[9], $indVals[10], $indVals[11], $indVals[12], $indVals[13]);			
		}
	}

	function updateCart(){
		$arr = $this->getCart();

		foreach ($arr as $row=>$value){
			if($_GET['update'.$arr[$row]['invid']]==$arr[$row]['invid']){
				if($_GET['qty'.$arr[$row]['invid']]!=$arr[$row]['quant']){//if new quant not equal old quant
					if($_GET['qty'.$arr[$row]['invid']]==0){
						$gtotal -= $arr[$row]['price']*$arr[$row]['quant'];
						unset($arr[$row]);
					}
					elseif (is_numeric($_GET['qty'.$arr[$row]['invid']])){
						if(round($_GET['qty'.$arr[$row]['invid']])>$arr[$row]['amt']){//check to see if adding more than remains in inventory
							$gtotal += ($arr[$row]['price']*$arr[$row]['amt'])-($arr[$row]['price']*$arr[$row]['quant']);
							$arr[$row]['quant'] = $arr[$row]['amt'];//set quantity equal to max remaining
							echo "<script>alert('There are only ".$arr[$row]['amt']." ".$arr[$row]['color']."  ".$arr[$row]['type']." units remaining in inventory.')</script>";
						} else {
							$gtotal += ($arr[$row]['price']*$_GET['qty'.$arr[$row]['invid']])-($arr[$row]['price']*$arr[$row]['quant']);
							$arr[$row]['quant']=round($_GET['qty'.$arr[$row]['invid']]);//set quantity
						}
					}
				}
			}
		}
		$this->saveCart($_COOKIE['custid'], $arr);
	}

	function addToCart($cat, $type, $color, $weight, $volume, $price, $quant, $amt, $invid, $sale, $img, $minWidth, $minLength, $minHeight){
		$cart = $this->getCart();
		$custid = $this->getID();

		$new_item[] = array("type" => $type, "color" => $color, "quant" => $quant, "price" => $price, "invid" => $invid, "amt" => $amt, "sale" => $sale, "img" => $img, "cat" => $cat, "weight" => $weight, "volume" => $volume, "minLength" => $minLength, "minWidth" => $minWidth, "minHeight" => $minHeight, "rollcost" => "no");

		if($cart == "" && $quant > 0){
			//add cart contents to table			
			$contents = str_replace("\"","+",serialize($new_item));
			$query = "INSERT INTO tmp_cart(custid, cart, date) VALUES ($custid, '$contents', CURDATE() )";
			$result = $this->mysqli->query($query);
		} elseif($cart != "" && $quant > 0) {
			//update cart
			#check to make sure page not just being refreshed, new products actually being added
			#check to see if product already in array, if so then increase quantity, if not write new product to array
			#Run loop through array to check if prod_id already exists in array
			foreach ($cart as $rownum=>$value){
				if ($cart[$rownum]['invid']==$invid){
					if($quant+$cart[$rownum]['quant'] > $amt){ //check to see if adding more than remains in inventory
						$cart[$rownum]['quant'] = $amt;
						$msg = array("status" => "error", "amt" => $amt);
						
						return json_encode($msg);
					} else {
						$cart[$rownum]['quant']=$cart[$rownum]['quant']+$quant;
					}
					$foundit="no";
				}	
			}
			
			if ($foundit!="no"){ //item not existing in cart - add as new item
				$cart[] = array("type" => $type, "color" => $color, "quant" => $quant, "price" => $price, "invid" => $invid, "amt" => $amt, "sale" => $sale, "img" => $img, "cat" => $cat, "weight" => $weight, "volume" => $volume, "minLength" => $minLength, "minWidth" => $minWidth, "minHeight" => $minHeight, "rollcost" => "no");
			}
			
			$this->saveCart($custid, $cart);

		}

		$msg = array("status" => "success");

		return json_encode($msg);
	}


	function setProdClick(){
		if(isset($_COOKIE['prodTest'])){
			$cookie_array = explode("+",$_COOKIE['prodTest']);
			$variation = $cookie_array[0];

			if($cookie_array[1] == 0){	
				// include('mvt.php');
				// conn();
				// $mvt = new mvt;
				// $mvt->saveClick($variation);

				// $variation = $variation."+1";
				// setcookie("prodTest", $variation, time()+2592000 );// 30 days
			} 
		}
	}

	function setCheckoutCookie(){
		setcookie("custid", $_POST['custid'], time()+3600*24*3, "/", "");//set for 3 days 
	}

	function getSubtotal(){
		foreach ($this->items as $row){
			if($row['sale']>0){
				$saleprice = $row['sale'];
			} else {
				$saleprice = $row['price'];
			}
			$this->subtotal = ($row['quant']*$saleprice)+$this->subtotal;
		}
		$this->subtotal = number_format($this->subtotal,2,'.','');
		return $this->subtotal;
	}

	function getShipRollCost($country){
		$rollCost = 0;

		if ($_SESSION['ship_on_roll']['shipSeperate'] == 1 &&  $country == 'US') {
			for ($i = 1; $i<=count($_SESSION['ship_on_roll']['items']); $i++) {
				$rollCost +=18;
			}
		} else {
			for ($i = 1; $i<=count($_SESSION['ship_on_roll']['items']); $i++) {
				if($i == 1) {
					$rollCost +=18;
				}
				else {
					$rollCost +=6;
				}
			}
		}
		return $rollCost;
	}

	function getID(){
		if (!isset($_COOKIE['custid'])){
			$query1 = "UPDATE getid set custid = custid +1";
			$result1 = $this->mysqli->query($query1);
			
			$query = "SELECT custid from getid";
			$result =  $this->mysqli->query($query);
			$row = $result->fetch_assoc();
			
			setcookie("custid", $row['custid'], time()+3600*24*30, "/", "");//set for 30 days 
			return $row['custid'];
		} else {
			return $_COOKIE['custid'];
		}
	}

	function saveCart($custid, $cart) { //take cart as an associative array
		$cart = str_replace("\"","+",serialize($cart));
		$query 	= "REPLACE INTO tmp_cart(custid,cart,date) VALUES('$custid','$cart',CURDATE())";
		$result = $this->mysqli->query($query);
	}
}
