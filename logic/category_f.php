<?
function getDesc(){
	$mysqli = mysqliConn();

	global $title, $desc;
	$type = $mysqli->real_escape_string(strip_tags($_GET['type']));
	$trait = empty($_GET['trait']) ? false : strtolower($mysqli->real_escape_string(strip_tags($_GET['trait'])));
	$query = "SELECT * FROM cat WHERE type ='$type'";
	$result = $mysqli->query($query); 
	$row = $result->fetch_assoc();

	$title = $row['title'];
	$desc = $row['desc'];

	if($_GET['type'] == "kits"){
		getKits();
	} else {
		getProds($type, $trait);	
	}
	
}

function getProds($type, $trait = false){
	$mysqli = mysqliConn();
	global $prods, $noprod;
	$noprod="";
	
	$query = "SELECT inven_mj.*, inven_traits.length, inven_traits.seperating, inven_traits.hidden, inven_traits.teeth_size, inven_traits.teeth_type, inven_traits.3_way"
		.   " FROM inven_mj LEFT JOIN inven_traits ON inven_mj.invid = inven_traits.invid WHERE type = '$type' AND cat != 'Clearance' AND active = 1 ORDER BY sort_order asc, color, invid asc";
	if($type == "sale" ) 	 $query = "SELECT inven_mj.*,inven_traits.length, inven_traits.seperating, inven_traits.hidden, inven_traits.teeth_size, inven_traits.teeth_type FROM inven_mj LEFT JOIN inven_traits ON inven_mj.invid = inven_traits.invid WHERE saleprice !='' AND cat != 'Clearance' AND active = 1 ORDER BY type asc, color asc";
	if($type == "clearance")  $query = "SELECT * FROM inven_mj WHERE cat='clearance' AND invamount > 0 and active = 1 ORDER BY type asc, color asc";
	if($type == "upholstery") $query = "SELECT * FROM inven_mj WHERE upholstery = 1 AND cat != 'Clearance' AND active = 1 ORDER BY type asc, color asc";
	if($trait) {
		$query = "SELECT inven_mj.*, inven_traits.length, inven_traits.seperating, inven_traits.hidden, inven_traits.teeth_size, inven_traits.teeth_type, inven_traits.3_way"
		.   " FROM inven_mj LEFT JOIN inven_traits ON inven_mj.invid = inven_traits.invid ";
		
		$where = "WHERE type = '$type' AND cat != 'Clearance' AND active = 1 ";
		$order = 'ORDER BY color, CAST(length as DECIMAL) asc';
		switch ($trait) {
			case '3-way':
				$where .= 'AND inven_traits.3_way = 1 ';
				break;
			case 'hidden':
				$where .= 'AND inven_traits.hidden = 1 ';
				break;
			default :
				$parts = explode('-', $trait, 2);
				$_GET['teeth_type'] = $parts[0];
				$_GET['separating'] = $parts[1];
				if (count($parts) == 2) {
					$where .= "AND (inven_traits.3_way = 0 OR inven_traits.3_way IS NULL) AND inven_traits.hidden = 0 AND inven_traits.teeth_type = '".$parts[0]."' ";
					if ($parts[1] == 'non-separating') {
						$where .= "AND inven_traits.seperating = 0 ";
					}
					else {
						$where .= "AND inven_traits.seperating = 1 ";
					}
				}
				break;
		}
		$query = $query.$where.$order;
	}
	
	$result = $mysqli->query($query); 
	$row = $result->fetch_assoc();

	if(!$row){
		$noprod = "Sorry, there are currently no products in this category.  Please check back at a later date.";
	} else {
		$i=0;
		$result->data_seek(0);
		while ($row = $result->fetch_assoc()){	
			$i++;
			
			if (($i%3)==0){
				$class ="end";
			} else {
				$class = "";
			}

			$price = $row['retail'];
			if ($row['saleprice'] > 0){
				$sale = "<span class=\"sale\">Sale Price$".number_format(($row['saleprice']),2, '.', '')."</span>";
				$price = $row['saleprice'];
			} 
			if ($cat == "clearance"){
				$sale = "<span class=\"sale\">".$row['invamount']." yard(s) for $".number_format(($row['saleprice']*$row['invamount']),2, '.', '')."</span>";
			}
			$imgArray = json_decode($row['img'], true);
			$prods[] = array(
				"invid"  => $row['invid'],
				"cat"    => $row['cat'],
				"type"   => $row['type'],
				"color"  => $row['color'],
				"title"  => $row['title'],
				"retail" => number_format(($row['retail']),2, '.', ''),
				"sale"   => ($row['saleprice'] > 0) ? $sale : "",
				"price"  => $price,
				"img"    => $imgArray[0]["path"],
				"alt"    => $imgArray[0]["alt"],
				"class"  => $class,
				"length" => $row['length'],
				
				"seperating" => $row['seperating'],
				"hidden"     => $row['hidden'],
				"teeth_size" => $row['teeth_size'],
				"teeth_type" => $row['teeth_type'],
				"3_way"      => $row['3_way'],
			);
			$sale = "";
		}
	}
}

function getKits(){
	global $prods, $noprod;
	$noprod = "";
	$invid = "";
	
	$query = "SELECT * FROM kit";	
	$result = $mysqli->query($query); 

	if(mysql_num_rows($result)==0){
		$noprod = "Sorry, there are currently no products in this category.  Please check back at a later date.";
	} else {
		
		$i=0;

		while ($row = mysql_fetch_assoc($result)){	
			$i++;
			$total = 0;
			$j = 0;
			$sale="";

			$items = json_decode($row['items'],true);

			$invids = '';
			foreach($items as $key=>$val){
				$invids .= $key.",";
			}
			$invids = rtrim($invids, ',');

			$sql = "SELECT * FROM inven_mj where invid IN($invids)";
			$itemRes = $mysqli->query($sql); 

			$total = 0;
			$id = '';
			while ($itemRow = mysql_fetch_assoc($itemRes)){	
				$id = $itemRow['invid'];
				$total += $itemRow['retail'] * $items[$id];
				$j++;
			}
			mysql_free_result($itemRes);

			if (($i%3)==0){
				$class ="end";
			} else {
				$class = "";
			}
			if ($row['discount'] > 0){
				$saleprice = $total - $row['discount'];
				$sale = "<span class=\"sale\">Sale Price $".number_format($saleprice ,2, '.', '')."</span>";
			} 

			$imgArray = json_decode($row['img'], true);
			$prods[] = array("invid" => $row['kid'], "cat" => 'kit', "type" => $row['title'], "color" => $row['title'], "retail" => number_format($total, 2, '.', ''), "sale" =>$sale, "img" => $imgArray["path"], "alt"=> $imgArray["alt"], "class" => $class);
		}
	}

}


function filter_prods($filter = array()) {
	$cache_fname = array();
	
	$columns = array(
		'stretch', 
		'type', 
		'transparent', 
		'upholstery',
		'price',
		'finish-type',
		'thickness',
		'color',
		'teeth_type',
		'zipper_style',
		'teeth_size',
		'separating',
	);
	$where = '';
	foreach ($filter as $name => $value) {
		if (in_array($name, $columns)) {
			$value = $mysqli->real_escape_string(strip_tags($value));
			switch ($name) {
				case 'price':
					switch ($value) {
						case '0-8':
							$where .= ' AND (IF (saleprice > 0, saleprice, retail) <= 8) ';
							break;
						case '9-14':
							$where .= ' AND (IF (saleprice > 0, saleprice, retail) >= 9 and IF (saleprice > 0, saleprice, retail) <= 14) ';
							break;
						default :
							$where .= ' AND (IF (saleprice > 0, saleprice, retail) > 14) ';
							break;
					}
					$where .= ' AND `cat` = "Latex-Sheeting" ';
					break;
				case 'stretch':
					$where .= ' AND `'.$name.'` = "'.$value.'" ';
					break;
				case 'type':
					if ($value == 'all') {
						$where .= ' AND `type` IN ("Faux-Leather", "Patent-Vinyl", "PVC", "Snakeskin", "Stretch-PVC") ';
					}
					else {
						$where .= ' AND `type` = "'.$value.'" ';
					}
					break;
				case 'transparent':
					if ($value == '0') {
						$where .= ' AND (`transparent` = 0 OR `transparent` IS NULL) ';
						$value = 'nontransparent';
					}
					else {
						$where .= ' AND `transparent` = 1 ';
						$value = 'transparent';
					}
					break;
				case 'upholstery':
					if ($value == '0') {
						$where .= ' AND (`upholstery` = 0 OR `upholstery` IS NULL) ';
						$value = 'fashion';
					}
					else {
						$where .= ' AND `upholstery` = 1 ';
						$value = 'upholstery';
					}
					break;
				case 'finish-type':
					switch ($value) {
						case 'metallic':
							$where .= ' AND `metallic` = 1 ';
							break;
						case 'solid':
							$where .= ' AND `pattern` = "solid" ';
							break;
						case 'print':
							$where .= ' AND `pattern` = "pattern" ';
							break;
						case 'double':
							$where .= ' AND `pattern` = "double" ';
					}
					break;
				case 'thickness':
					$where .= ' AND `type` = "'.$value.'" ';
					break;
				case 'color':
					$where .= ' AND `type` = "Zippers" AND `color` = "'.$value.'" ';
					break;
				case 'teeth_type':
					$where .= ' AND `type` = "Zippers" AND `teeth_type` = "'.$value.'" ';
					break;
				case 'zipper_style':
					$where .= ' AND `type` = "Zippers" ';
					if ($value == 'hidden') {
						$where .= ' AND `hidden` = 1 ';
					}
					elseif ($value == '3-way') {
						$where .= ' AND `3_way` = 1 ';
					}
					else {
						$where .= ' AND `hidden` = 0 AND (`3_way` = 0 OR `3_way` IS NULL) ';
					}
					break;
				case 'teeth_size':
					$where .= ' AND `type` = "Zippers" AND `teeth_size` = "'.$value.'" ';
					break;
				case 'separating':
					$where .= ' AND `type` = "Zippers" AND `seperating` = "'.$value.'" ';
					break;
			}
			if ($value) {
				$cache_fname[] = $value;
			}
		}
	}
	$query = "SELECT * "
		. "FROM inven_mj im "
		. "INNER JOIN inven_traits it ON im.invid = it.invid "
		. "WHERE active = 1 ".$where
		. "ORDER BY color asc";
	
	$result = $mysqli->query($query);
	$row = $result->fetch_assoc();

	$html = "<div class='product-empty'>Sorry, there are currently no products in this category.  Please check back at a later date.</div>";
	if ($row) {
		$html = '';
		if ($row['type'] == 'Zippers') {
			$result->data_seek(0);
			$html = render_zippers_results($result);
		}
		else {
			$i=0;
			$result->data_seek(0);
			while ($row = $result->fetch_assoc()){	
				$i++;

				if (($i%3)==0) {
					$class ="end";
				} else {
					$class = "";
				}

				$sale = '';
				if ($row['saleprice'] > 0){
					$sale = "<span class=\"sale\">Sale Price$".number_format(($row['saleprice']),2, '.', '')."</span>";
				} 

				$imgArray = json_decode($row['img'], true);
				$prods[] = array("invid" => $row['invid'], "cat" => $row['cat'], "type" => $row['type'],"color" => $row['color'], "retail" => number_format(($row['retail']),2, '.', ''), "sale" =>'', "img" => $imgArray[0]["path"], "alt"=> $imgArray[0]["alt"], "class" => $class); 
				$html .= ''.
					'<div class="col '.$class.'" id="pid-'.$row['invid'].'" itemscope itemtype="http://schema.org/IndividualProduct">
						<a class="catImg" href="products.'.$row['color'].','.$row['type'].','.$row['cat'].'"><img itemprop="image" class="catProd" src="http://mjtrends.b-cdn.net/images/product/'.$row['invid'].'/'.$imgArray[0]["path"].'_370x280.jpg" alt="'.$imgArray[0]["alt"].'" /></a> 
						<a href="products.'.$row['color'].','.$row['type'].','.$row['cat'].'" itemprop="url"><h4 itemprop="name">'.str_replace("-"," ",$row['color'])." ".str_replace("-"," ",$row['type']).'</h4></a>
						<span>List Price $'.number_format(($row['retail']),2, '.', '').'</span>
						'.$sale;
				if ($sale != '') {
					$html .= '
						<img class="imgSale" src="http://mjtrends.b-cdn.net/images/sale-star.png">
						';
				}
				$html .= '</div>';
			}
		}
	}
	$cachefile = "../cache/filters/".strtolower(implode('_',$cache_fname)).".html";
	// open the cache file "cache/home.html" for writing
	$fp = fopen($cachefile, 'w');
	// save the contents of output buffer to the file
	fwrite($fp, $html);
	// close the file
	fclose($fp);
	
	return $html;
}

function render_zippers_results($result)
{
	$_GET['cat'] = 'Zippers';
	$prods = array();
	$i=0;
	while ($row = $result->fetch_assoc()) {	
		$i++;
			
		if (($i%3)==0){
			$class ="end";
		} else {
			$class = "";
		}

		$price = $row['retail'];
		if ($row['saleprice'] > 0){
			$sale = "<span class=\"sale\">Sale Price$".number_format(($row['saleprice']),2, '.', '')."</span>";
			$price = $row['saleprice'];
		} 
		$imgArray = json_decode($row['img'], true);
		$prods[] = array(
			"invid"  => $row['invid'],
			"cat"    => $row['cat'],
			"type"   => $row['type'],
			"color"  => $row['color'],
			"title"  => $row['title'],
			"retail" => number_format(($row['retail']),2, '.', ''),
			"sale"   => empty($sale) ? "" : $sale,
			"price"  => $price,
			"img"    => $imgArray[0]["path"],
			"alt"    => $imgArray[0]["alt"],
			"class"  => $class,
			"length" => $row['length'],

			"seperating" => $row['seperating'],
			"hidden"     => $row['hidden'],
			"teeth_size" => $row['teeth_size'],
			"teeth_type" => $row['teeth_type'],
			"3_way"      => $row['3_way'],
		);
	}

	ob_start();
	include_once('../modules/category-zippers.php');
	$html = ob_get_contents();
	ob_end_clean();
	
	return $html;
}

function get_zippers_colors()
{
	$colors = array();
	$query = "SELECT color FROM inven_mj WHERE TYPE = 'Zippers' GROUP BY color ORDER BY color";
	$result = $mysqli->query($query); 

	if (mysql_num_rows($result)) {
		while ($row = mysql_fetch_assoc($result)) {	
			$colors[$row['color']] = $row['color'];
		}
	}
	
	return $colors;
}
