<?
$amterr = "none";
$empty = "none";

function Cinit(){
	if(!empty($_GET['x'])){
		update();
	} else{	
		getContents();
	}

	if(isset($_COOKIE['usr']) && !isset($_SESSION['user'])){
		$user = new User;
		$user->getUser();
	}
	if(!empty($_COOKIE['current_custid'])) {
		$custid = intval($_COOKIE['current_custid']);
		if ($custid > 0) {
			$sql = "SELECT cart FROM tmp_cart WHERE custid = ".$custid;
			$res = mysql_query($sql);
			$row = mysql_fetch_assoc($res);
			if ($row) {
				$cart = str_replace("+","\"",$row['cart']);
				$_SESSION['cart'] = unserialize($cart);
				$gtotal = 0;
				foreach($_SESSION['cart'] as $row=>$value) {
					$gtotal += $value['price']*$value['quant'];
				}
				$_SESSION['gtotal'] = $gtotal;
			}
			$_SESSION['custid'] = $custid;
		}
	}
	if (empty($_COOKIE['current_custid']) && $_SESSION['custid']) {
		setcookie("current_custid", $_SESSION['custid'], time()+3600*24*30);
	}
}

function update(){
global $arr;
	$gtotal = $_SESSION['gtotal'];
	$arr = $_SESSION['cart'];
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
						$arr[$row]['total'] = number_format($arr[$row]['quant']*$arr[$row]['price'],2,'.','');//set total
						echo "<script>alert('There are only ".$arr[$row]['amt']." ".$arr[$row]['color']."  ".$arr[$row]['type']." units remaining in inventory.')</script>";
					} else {
						$gtotal += ($arr[$row]['price']*$_GET['qty'.$arr[$row]['invid']])-($arr[$row]['price']*$arr[$row]['quant']);
						$arr[$row]['quant']=round($_GET['qty'.$arr[$row]['invid']]);//set quantity
						$arr[$row]['total'] = number_format($arr[$row]['quant']*$arr[$row]['price'],2,'.','');//set total
					}
				}
			}
		}
	}
	$_SESSION['cart']=$arr;

	if (empty($_SESSION['custid'])) {
		getID();
	}
	save_cart($_SESSION['cart'],$_SESSION['custid']);
	($gtotal > 0) ? $_SESSION['gtotal'] = $gtotal : $_SESSION['gtotal'] = 0;
	cartErr();
}

function getContents(){
	if(isset($_POST['grid'])){
		$contentArr = explode('*',$_POST['grid']);
		array_pop($contentArr);
		foreach($contentArr as $val){
			$indVals = explode(',',$val);
			addCart($indVals[0], $indVals[1], $indVals[2], $indVals[3], $indVals[4], $indVals[5], round($indVals[6]), $indVals[7], $indVals[8], $indVals[9], $indVals[10]);			
		}
	} elseif(isset($_POST['invid'])) {
		$invid = $_POST['invid'];
		$type = $_POST['type'];
		$color = $_POST['color'];
		$amt = $_POST['amt'];
		$price = $_POST['price'];		
		$sale = $_POST['sale'];
		$quant = round($_POST['quant']);
		$img = $_POST['img'];
		$cat = $_POST['cat'];
		$weight = $_POST['weight'];
		$volume = $_POST['volume'];
		
		addCart($invid, $type, $color, $amt, $sale, $price, $quant, $img, $cat, $weight, $volume);
	} elseif(isset($_GET['pattern'])){
		save_cart($_SESSION['cart'],$_SESSION['custid']);
	}
	cartErr();
}

function addCart($invid, $type, $color, $amt, $sale, $price, $quant, $img, $cat, $weight, $volume){
global $tprice, $arr, $amterr, $cartProd;

$tprice = $price;
$gtotal = $_SESSION['gtotal'];

	if($sale > 0){
		$tprice = "<s>".$price."</s> ".$sale."";
	}

	if ($quant > $amt){
		$amterr = "block";
		$cartProd = "none";
	} else{
		if ($_SESSION['cart']=="" && $quant > 0){
				# put items in array and put array in new SESSION
				$total = number_format($price * $quant,2,'.','');
				$arr[] = array("type" => $type, "color" => $color, "quant" => $quant, "price" => $price, "invid" => $invid, "amt" => $amt, "sale" => $sale, "img" => $img, "cat" => $cat, "weight" => $weight, "volume" => $volume);
				$_SESSION['cart']= $arr;
				$_SESSION['gtotal'] = $quant*$price;
				if (empty($_SESSION['custid'])) {
					getID();
				}
				save_cart($_SESSION['cart'],$_SESSION['custid']);
			} elseif ($_SESSION['cart']!="" && $quant > 0) {
				# put SESSION in array, add to array, put array back in SESSION
				#check to make sure page not just being refreshed, new products actually being added
				#check to see if product already in array, if so then increase quantity, if not write new product to array
				#Run loop through array to check if prod_id already exists in array
				$arr = $_SESSION['cart'];
				foreach ($arr as $rownum=>$value){
					if ($arr[$rownum]['invid']==$invid){
						if($quant+$arr[$rownum]['quant'] > $amt){ //check to see if adding more than remains in inventory
							$arr[$rownum]['quant'] = $amt;
							$_SESSION['gtotal'] += $arr[$rownum]['quant']*$arr[$rownum]['price'];
							echo "<script>alert('There are only ".$amt." ".$arr[$rownum]['color']." ".$arr[$rownum]['type']." units remaining in inventory.')</script>";
						} else {
							$arr[$rownum]['quant']=$arr[$rownum]['quant']+$quant;
							$_SESSION['gtotal'] += $quant*$arr[$rownum]['price'];
					}
					$foundit="no";
					}	
				}
				if ($foundit!="no"){
					$total = number_format($price * $quant,2,'.','');
					$arr[] = array("type" => $type, "color" => $color, "quant" => $quant, "price" => $price, "invid" => $invid, "amt" => $amt, "sale" => $sale, "img" => $img, "cat" => $cat, "weight" => $weight, "volume" => $volume);
					$_SESSION['gtotal'] += $total;
				}
				$_SESSION['cart']= $arr;
				save_cart($_SESSION['cart'],$_SESSION['custid']);
			}
	}
}

function cartErr(){
global $empty, $cartProd;
	if ((count($_SESSION['cart']))==0){
		$empty = "block";
		$cartProd = "none";
	} else {
		getID();
	}
}

function getID(){
	if ($_SESSION['custid']==""){
		$query1 = "UPDATE getid set custid = custid +1";
		$result1 = mysql_query($query1);
		$query = "SELECT custid from getid";
		$result = mysql_query($query); 
		$row = mysql_fetch_assoc($result);
		$_SESSION['custid']=$row['custid'];
		set_cookie('current_custid',$_SESSION['custid'],time()+3600*24*30);
	}
	calc();
}

function calc(){
	global $subtotal;
	$arr= $_SESSION['cart'];
	$tquant = 0;
	foreach ($arr as $row){
		$tquant += $row['quant'];

		if($row['sale']>0){
			$saleprice = $row['sale'];
		} else {
			$saleprice = $row['price'];
		}
		$subtotal = ($row['quant']*$saleprice)+$subtotal;
		$subtotal = number_format($subtotal,2,'.','');
	}
	ship($subtotal);
}

function ship($subtotal){
	global $shipMeth;//standard is used for the modify shipping calculations
	
	$rate = new ShipRate;
	$user = new User;
	
	$user->getAddress();
	$rate->setSize();
	$rate->setWeight();
	//$rate->setFlatRate();

	$rate->setServiceFromSession();

	if(!empty($rate->fedexService)) $rate->getFedexRates($user, 'true');   
	if(!empty($rate->uspsService))  $rate->getUSPSRates($user); 
	if($rate->flatRate == true) $rate->setRateOrder();

	$shipMeth = $rate->rateArray[0]['service']."<br>".$rate->rateArray[0]['shipTime'];
	
	$user = new user;
	$shipDetails = array('code' => $rate->rateArray[0]['code'], 'cost' => $rate->rateArray[0]['cost'], 'service' => $rate->rateArray[0]['service']);
	$user->setShipping($shipDetails);

	gtotal($rate->rateArray[0]['cost'], $subtotal);
}

function gtotal($shipRate, $subtotal){
	global $gtotal;
	(is_numeric($shipRate)) ? $gtotal = number_format($shipRate + $subtotal,2,'.','') : $gtotal = number_format($subtotal,2,'.','');
}

function save_cart($cart, $custid) {
	setcookie("current_custid", $custid, time()+3600*24*30);
	$cart = str_replace("\"","+",serialize($cart));
	$query 	= "REPLACE INTO tmp_cart(custid,cart,date) VALUES('$custid','$cart',CURDATE())";
	mysql_query($query);
}
