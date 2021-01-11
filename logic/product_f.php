<?
function getProd(){
	global $row, $type, $meta_key, $meta_desc, $color, $invid, $whole, $sale, $list, $usave, $unit, $price, $weight, $volume, $amt, $showquant, $clearanceQ, $ourPrice;
	$type = mysql_real_escape_string(strip_tags($_GET['type']));
	$color = mysql_real_escape_string(strip_tags($_GET['color']));
	$cat = mysql_real_escape_string(strip_tags($_GET['cat']));
	
	$query = "SELECT * FROM inven_mj WHERE type = '$type' and color = '$color' and cat = '$cat'";
	$result = mysql_query($query); 
	$row = mysql_fetch_assoc($result);
	
	$invid = $row['invid'];
	$price = number_format(($row['retail']),2, '.', '');
	$ourPrice = number_format(($row['retail']),2, '.', '');
	$sale = number_format(($row['saleprice']),2,'.','');
	$amt = $row['invamount'];
	$meta_key = $row['meta_key'];
	$meta_desc = $row['meta_desc'];
	$weight = $row['weight'];
	$volume = $row['volume'];
	$type = str_replace("-"," ",$type);
	$color = str_replace("-"," ",$color);

	if ($row['saleprice'] > 0){
		$list = "<div><span>List Price:</span>$".$price."</div>";
		$savings = number_format(($price-$row['saleprice']),2,'.','');
		$price = number_format($row['saleprice'],2, '.', '');
		$ourPrice = number_format($row['saleprice'],2, '.', '');
		$percent = round($savings/$price*100);
		$usave = "<div><span>You Save:</span>$".$savings." (".$percent."%)</div>";
	}
	if ($cat == "Clearance"){
		$savings = ($row['retail']-($row['saleprice']))*$row['clearAmt'];
		if($row['retail'] > 0){
			$percent = round((($row['retail']-($row['saleprice']))/$row['retail'])*100);
		} else {
			$percent = 0;
		}
		$usave = "<div><span>You Save:</span>$".number_format($savings,2,'.','')." (".$percent."%)</div>";
		$price = number_format($row['saleprice'],2, '.', '');
		$ourPrice = number_format($row['saleprice']*$row['clearAmt'],2,'.','')." for ".$row['clearAmt']." yard(s)";
		$clearanceQ = "<input name=\"quant\" type=\"hidden\" value=\"".$row['clearAmt']."\">";
		$showquant = "disabled value=\"".$row['clearAmt']."\"";
	}
	if ($row['cat']=="Fabric" OR $row['cat']=="latex sheeting"){
		$unit ="(yards)";
	} else{
		$unit ="";
	}
	getRating($invid);
	recent($invid, $_GET['type'], $_GET['color'], $_GET['cat']);
}

function getSwatchColor(){
	global $colorArr;
	$type = strtolower(mysql_real_escape_string(strip_tags($_GET['color'])));
	
	($type == 'latex-sheeting') ? $query = "SELECT color, type FROM inven_mj WHERE cat = '$type' AND invid < 5000 AND invamount > 0 ORDER BY color asc, type asc" : $query = "SELECT color, type FROM inven_mj WHERE type = '$type' AND invid < 5000 AND invamount > 0 ORDER BY color asc, type asc";
	$result = mysql_query($query); 
	
	while ($row = mysql_fetch_assoc($result)){
		$color = str_replace("-"," ",$row['color']);
		($type == 'latex-sheeting') ? $colorArr[] = $color." ".$row['type'] : $colorArr[] = $color;
	}
}

function getRating($invid){
	global $rated, $notRated, $overall, $quality, $value, $repeats, $revArr, $total;
	$query = "SELECT *, DATE_FORMAT(review.date1, '%M %d, %Y') as date1 from review WHERE invid = '$invid' AND approve = 1 ORDER BY id asc";
	$result = mysql_query($query);
	$row = mysql_fetch_assoc($result);

	if($row['approve']=='1'){
		$rated = "block";
		$notRated = "none";
		$repeats = 0;
		$total = 0;
	} else {
		$rated = "none";
		$notRated = "block";		
	}
	
	if($row['approve']!=""){
		mysql_data_seek($result,0);
		while ($row = mysql_fetch_assoc($result)){
			if($row['approve']=='1'){
				$total++;
				$overall = $overall + $row['overall'];
				$quality = $quality + $row['quality'];
				$value = $value + $row['value'];
				$revArr[]=array($row['title'],$row['date1'],$row['name'],$row['comm'],$row['overall'],$row['quality'],$row['value'],$row['purch']);
				if($row['purch']=="yes"){$repeats++;};
			}
		$overall = ceil($overall/$total);
		$quality = ceil($quality/$total);
		$value = ceil($value/$total);
		}
	}else{
		$overall = 1;
		$quality = 1;
		$value = 1;
	}
}

function recent($invid, $type, $color, $cat){
	if (!isset($_SESSION['recent'])) {
	   $_SESSION['recent'][] = array($invid, $type, $color, $cat);
	} else {
		//loop through session array, make sure not adding a duplicate
		$found = false;
		foreach($_SESSION['recent'] as $row){
			if($row[0]==$invid){
				$found = true;
				break;
			}
		}
		if(!$found){
		$_SESSION['recent'][] = array($invid, $type , $color, $cat);
			if(count($_SESSION['recent'])>10){
				array_shift($_SESSION['recent']);
			}
		}
	}
}

function getRecommended(){
	if(	$_GET['cat'] == 'Latex-Sheeting'){
		$prodArray =  array('<div class="recom"><a href="http://www.mjtrends.com/categories-Cutting-Mat,Notions?adid=602"><img src="http://www.mjtrends.com/prod/rotary-cutting-mat-for-fabric-and-crafts.jpg">Cutting Mat</a></div>',
				'<div class="recom"><a href="http://www.mjtrends.com/categories-Rotary-Cutter,Notions?adid=603"><img src="http://www.mjtrends.com/prod/45mm-rotary-cutter.jpg">Rotary Cutter</a></div>',
				'<div class="recom"><a href="http://www.mjtrends.com/products.45mm-Single-Blade,Rotary-Cutter,Notions?adid=604"><img src="http://www.mjtrends.com/prod/rotary-cutting-blades-for-latex-sheeting.jpg">Rotary Blades</a></div>',
				'<div class="recom"><a href="http://www.mjtrends.com/categories-Adhesive,Notions?adid=605"><img src="http://www.mjtrends.com/prod/latex-adhesive.jpg">Latex Adhesive</a></div>',
				'<div class="recom"><a href="http://www.mjtrends.com/categories-Latex-Shine,Notions?adid=606"><img src="http://www.mjtrends.com/prod/latex-sheeting-conditioner.jpg">Latex Shine</a></div>',
				'<div class="recom"><a href="http://www.mjtrends.com/categories-Adhesive-Cleaner,Notions?adid=607"><img src="http://www.mjtrends.com/products.Latexine,Adhesive-Cleaner,Notions">Latex Cleaner</a></div>',
				'<div class="recom"><a href="http://www.mjtrends.com/products.Black,Latex-Seam-Roller,Notions?adid=608"><img src="http://www.mjtrends.com/prod/latex-sheeting-seam-roller.jpg">Latex seam roller</a></div>');

		$rand_keys = array_rand($prodArray, 4); //randomize the keys
		return $prodArray[$rand_keys[0]].$prodArray[$rand_keys[1]].$prodArray[$rand_keys[2]].$prodArray[$rand_keys[3]];

	} elseif(stripos('PVC Patent-Vinyl Stretch-PVC', $_GET['type']) !== false){
		return '<div class="recom"><a href="http://www.mjtrends.com/products.Vinyl,Adhesive,Notions?adid=609"><img src="http://www.mjtrends.com/prod/vinyl-adhesive.jpg"></a>Vinyl Adhesive</div>
				<div class="recom"><a href="http://www.mjtrends.com/categories-Rotary-Cutter,Notions?adid=603"><img src="http://www.mjtrends.com/prod/45mm-rotary-cutter.jpg"></a>Rotary Cutter</div>
				<div class="recom"><a href="http://www.mjtrends.com/products.45mm-Single-Blade,Rotary-Cutter,Notions?adid=604"><img src="http://www.mjtrends.com/prod/rotary-cutting-blades-for-latex-sheeting.jpg">Rotary Blades</a></div>
				<div class="recom"><a href="http://www.mjtrends.com/categories-Cutting-Mat,Notions?adid=602"><img src="http://www.mjtrends.com/prod/rotary-cutting-mat-for-fabric-and-crafts.jpg">Cutting Mat</a></div>
			';
	} else {
		$type = mysql_real_escape_string(strip_tags($_GET['type']));
		$products = array();
		
		$query = "SELECT cat, type, color, img_url FROM inven_mj where type = '$type' order by rand() limit 4";
		$result = mysql_query($query);
		$row = mysql_fetch_assoc($result);
		
		while($row = mysql_fetch_assoc($result)) {
			$prodArray[] = '<div class="recom"><a href="http://www.mjtrends.com/products.'.$row["color"].','.$row["type"].','.$row["cat"].'"><img src="prod/'.$row["img_url"].' "></a>'.$row["color"].' '.$row["type"].'</div>';
		}

		$prodArray[] = '<div class="recom"><a href="http://www.mjtrends.com/products.Vinyl,Adhesive,Notions?adid=609"><img src="http://www.mjtrends.com/prod/vinyl-adhesive.jpg"></a>Vinyl Adhesive</div>';
		$prodArray[] = '<div class="recom"><a href="http://www.mjtrends.com/categories-Rotary-Cutter,Notions?adid=603"><img src="http://www.mjtrends.com/prod/45mm-rotary-cutter.jpg"></a>Rotary Cutter</div>';
		$prodArray[] = '<div class="recom"><a href="http://www.mjtrends.com/products.45mm-Single-Blade,Rotary-Cutter,Notions?adid=604"><img src="http://www.mjtrends.com/prod/rotary-cutting-blades-for-latex-sheeting.jpg">Rotary Blades</a></div>';
		$prodArray[] = '<div class="recom"><a href="http://www.mjtrends.com/categories-Cutting-Mat,Notions?adid=602"><img src="http://www.mjtrends.com/prod/rotary-cutting-mat-for-fabric-and-crafts.jpg">Cutting Mat</a></div>';
		
		$rand_keys = array_rand($prodArray, 4); //randomize the keys
		return $prodArray[$rand_keys[0]].$prodArray[$rand_keys[1]].$prodArray[$rand_keys[2]].$prodArray[$rand_keys[3]];
	}

}

?>