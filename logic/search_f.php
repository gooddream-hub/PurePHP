<?php
/** $restype array for ajax search or html for casual search
 *	return result array or result html
 *
 */
function getQType($restype = 'html') {
	global $config;

	$db = DB::getInstance();

	$search = rtrim($db->real_escape_string(strip_tags($_GET['search'])));
	$input = explode(" ",$search);
	$query = '';
	$type = '';
	$cat = '';
	$color = '';
	$res = '';
	$data = array();
	$faq = '';
	
	if($search == ''){
		$data["error"] = "Oops, we did not recieve a search term.  Please try your search again.";
	} else {
		foreach($input as $val){
			if (strpos($search, 'zipper') !== false) {
				$type = 'zipper';
				$words = explode(' ', $search);
				if (count($words) > 1) {
					if( strpos($words[0], 'zipper') === false ) {
						$color = strtolower($words[0]);
					} else {
						$color = strtolower($words[1]);
					}
				}
				break;
			}

			if (strpos($search, 'patt') !== false) { //pattern
				$type = 'pattern';
				break;
			}

			if (strpos($search, 'cors') !== false || strpos($search, 'linge') !== false) {  //corset
				$cat = 'Notions';
				$type = array('Bra-hooks','Bra-slides','Busks', 'Eyelets-and-Snaps', 'Garter-clip', 'Garter-clip', 'Hook-Eye-Tape', 'Flat-boning', 'Spiral-boning', 'Polyester-boning');
				$words = explode(' ', $search);
				if (count($words) > 1) {
					if( strpos($words[0], 'cors') === false && strpos($words[0], 'linge') === false ) {
						$color = strtolower($words[0]);
					} else {
						$color = strtolower($words[1]);
					}
				}
				break;
			}

			$faq = faqCheck($val);
			if($faq) {
				if($faq['val'] == 'tutorial'){ // Add articles
					
					break 1; //exit both the if and foreach
				} elseif($faq != ''){
					$data[] = array(array("item" => array("title" => "FAQ Results", "links" => array('<a href="'.$config->domain.'search.php?search='.$_GET['search'].'">'.ucfirst($faq['page']).'</a>'))));
					$data['error'] = $faq['val'];
					break 1;
				}
			}
		
			if(colorCheck($val) == true){
				$color = $val; //color match, move to next word in search array
			}

			if(strcasecmp($val, 'crocadile') == 0){
				$color = 'crocodile';
			}

			if(strcasecmp($val, 'faux') == 0){
				$color = '';
			}

			if(typeCheck($val) == true) {
				$type = $val; //type match, move to next word in search array
			}

			if(catCheck($val) == true) {
				$cat = $val; //type match, move to next word in search array
			}

			if(strcasecmp($val, 'glue') == 0){
				$type = 'adhesive';
			}			

			if(strcasecmp($search, 'snake skin') == 0){
				$type = 'snakeskin';
			}
		}
		#pr('Color: '. $color .'<br>Type: '. $type .'<br>Cat: '. $cat .'<br>faq: '. $faq);
		if(!$faq){
			$query = setQuery($color, $type, '', $cat);
			if($restype == 'array') {
				$results = getDataArray($query);
			} else {
				$results = getData($query);
			}
			if(!empty($results)) $data[] = $results;
		} else {
			if($faq['val'] == 'tutorial'){
				$query = setQuery('','',true);
				if($restype == 'array') {
					$results = getDataArray($query);
				} else {
					$results = getData($query);
				}
				if(!empty($results)) $data[] = $results;
			}
		}
		
		if($type == '' OR strcasecmp($val, 'vinyl') == 0){// run through msc check and update results
			if(strcasecmp($val, 'vinyl') == 0){//vinyl is a special case
				$data = array();
			}

			$query = mscCheck($search, $color);
			if(!empty($query)) {
				if($restype == 'array') {
					$data[] = getDataArray($query);
				} else {
					$data[] = getData($query);
				}
			 }
		}	

		insertTerm($search);	
	}
	if($restype == 'array') {
		#pr($data);
		return $data;
	} else {
		return formatData($data);
	}
}

function setQuery($color, $type, $art, $cat = ''){
	$db = DB::getInstance();
	if(!is_array($type) && strcasecmp($type, "latex") == 0){
		$field = "cat";
		$type = "Latex-Sheeting";
	} else {
		$field = "type";
	}

	$inven_traits_select = "inven_traits.ID, inven_traits.stretch, inven_traits.pattern, inven_traits.metallic, inven_traits.transparent, inven_traits.length, inven_traits.seperating, inven_traits.hidden, inven_traits.teeth_size, inven_traits.teeth_type, inven_traits.3_way";

	if ($art == true) {
		$query = array("articles" => "SELECT title, thumb FROM articles ORDER BY date ASC");
	} elseif(is_array($type)) { //Corset
		$query = array(
			"categories" => "SELECT inven_mj.invid, color, cat, type, $inven_traits_select FROM inven_mj LEFT JOIN inven_traits ON inven_mj.invid = inven_traits.invid WHERE cat like '%". $cat."%' AND `type` IN ('". implode("', '", $type) ."') AND active = 1 GROUP BY type ORDER BY type ASC ", 
			"product" => 	"SELECT inven_mj.invid, color, cat, type, img, retail, saleprice, $inven_traits_select FROM inven_mj LEFT JOIN inven_traits ON inven_mj.invid = inven_traits.invid WHERE cat like '%". $cat ."%' AND `type` IN ('". implode("', '", $type) ."') AND CONCAT(`type`,' ',`color`) LIKE '%".$color."%' AND active = 1 ORDER BY type ASC, color ASC"
		);
	} elseif($type == 'pattern') {
		$query = array(
			"pattern" => 	"SELECT * FROM `pattern_style` WHERE 1 ORDER BY style ASC"
		);
		$search = rtrim($db->real_escape_string(strip_tags($_GET['search'])));
		$search = explode(' ', $search);

		$like_field = "CONCAT(color, ' ', cat, ' ', type, ' ', title)";
		$t = 0;
		$like_where = array();
		foreach ($search as $s) { $t++;
			if(strlen($s) > 2) {
				$like_where[] = $like_field ." LIKE '%". $s ."%'";
			}
			if($t > 3) {
				break;
			}
		}
		$like_where = implode(' AND ', $like_where);
		
		$query['product'] = "SELECT `inven_mj`.`invid`, color, cat, type, img, retail, saleprice, $inven_traits_select FROM inven_mj LEFT JOIN inven_traits ON inven_mj.invid = inven_traits.invid WHERE active =1 AND ($like_where)";
		
	} elseif($type == 'zipper') {
		$where = "WHERE type = 'Zippers' AND active = 1 ";
		if ($color) {
			switch ($color) {
				case 'separating':
					$where .= "AND seperating = 1 ";
					break;
				case 'hidden':
					$where .= "AND hidden = 1 ";
					break;
				case '3':
				case '4':
					$where .= "AND 3_way = 1 ";
					break;
				default:
					$where .= "AND (teeth_type LIKE '%".$color."%' OR color LIKE '%". $color ."%') ";
					break;
			}
		}
		$query = array(
			"categories" => ""
				. "SELECT inven_mj.invid, color, cat, type, $inven_traits_select "
				. "FROM inven_mj "
				. "INNER JOIN inven_traits ON inven_mj.invid = inven_traits.invid "
				. $where
				. "GROUP BY teeth_type, seperating ", 
			"product"    => ""
				. "SELECT inven_mj.invid, color, cat, type, img, retail, saleprice, $inven_traits_select "
				. "FROM inven_mj "
				. "INNER JOIN inven_traits ON inven_mj.invid = inven_traits.invid "
				. $where
				. "ORDER BY color ASC"
		);
	} elseif($color == '' AND $cat != '') {
		$query = array(
			"categories" => "SELECT inven_mj.invid, color, cat, type, $inven_traits_select FROM inven_mj LEFT JOIN inven_traits ON inven_mj.invid = inven_traits.invid WHERE cat like '%". $cat."%' AND active = 1 GROUP BY type ORDER BY type ASC ", 
			"product" => 	"SELECT inven_mj.invid, color, cat, type, img, retail, saleprice, $inven_traits_select FROM inven_mj LEFT JOIN inven_traits ON inven_mj.invid = inven_traits.invid AND `type`='Zippers' WHERE cat like '%". $cat ."%' AND active = 1 ORDER BY type ASC, color ASC"
		);
	} elseif($color != '' AND $cat != '') {
		$query = array(
			"categories" => "SELECT color, cat, type, $inven_traits_select FROM inven_mj LEFT JOIN inven_traits ON inven_mj.invid = inven_traits.invid WHERE cat like '%". $cat."%' AND active = 1 GROUP BY type ORDER BY type ASC ",
			"product" => 	"SELECT inven_mj.invid, color, cat, type, img, retail, saleprice, $inven_traits_select FROM inven_mj LEFT JOIN inven_traits ON inven_mj.invid = inven_traits.invid AND `type`='Zippers' WHERE color LIKE '%". $color."%' AND active =1 AND cat like '%". $cat."%' ", 
		);
	} elseif($color != '' AND $type == '') {
		$query = array(
			//"categories" => "SELECT color, cat, type, $inven_traits_select FROM inven_mj LEFT JOIN inven_traits ON inven_mj.invid = inven_traits.invid AND WHERE color LIKE '%". $color."%' AND active = 1 GROUP BY cat ORDER BY type ASC",
			"product" => "SELECT inven_mj.invid, color, cat, type, img, retail, saleprice, $inven_traits_select FROM inven_mj LEFT JOIN inven_traits ON inven_mj.invid = inven_traits.invid WHERE color LIKE '%". $color."%' AND active = 1 ORDER BY color ASC"
		);
	} elseif($color == '' AND $type != ''){
		$query = array(
			"categories" => "SELECT inven_mj.invid, color, cat, type, $inven_traits_select FROM inven_mj LEFT JOIN inven_traits ON inven_mj.invid = inven_traits.invid  WHERE $field like '%". $type."%' AND active = 1 GROUP BY type ORDER BY type ASC ", 
			"product" => "SELECT inven_mj.invid, color, cat, type, img, retail, saleprice, $inven_traits_select FROM inven_mj LEFT JOIN inven_traits ON inven_mj.invid = inven_traits.invid  WHERE $field like '%". $type ."%' AND active = 1 ORDER BY type ASC, color ASC"
		);
	} elseif($color != '' AND $type !=''){
		if($color == $type) {
			$concat = ' OR ';
		} else {
			$concat = ' AND ';
		}
		$query = array(
			"categories" => "SELECT color, cat, type, $inven_traits_select FROM inven_mj LEFT JOIN inven_traits ON inven_mj.invid = inven_traits.invid WHERE $field like '%". $type."%' AND active = 1 GROUP BY type ORDER BY type ASC ",
			"product" => "SELECT inven_mj.invid, color, cat, type, img, retail, saleprice, inven_traits.* FROM inven_mj LEFT JOIN inven_traits ON inven_mj.invid = inven_traits.invid WHERE (color LIKE '%". $color."%' $concat $field like '%". $type."%') AND active =1 ",
		);
	} else { //All vars empty
		$search = rtrim($db->real_escape_string(strip_tags($_GET['search'])));
		$search = explode(' ', $search);

		$like_field = "CONCAT(color, ' ', cat, ' ', type, ' ', title)";
		$pt_like_field = "CONCAT(style, ' ', gender)";
		$t = 0;
		$like_where = array();
		foreach ($search as $s) { $t++;
			if(strlen($s) > 2) {
				$like_where[] = $like_field ." LIKE '%". $s ."%'";
				$pt_like_where[] = $pt_like_field ." LIKE '%". $s ."%'";
			}
			if($t > 3) {
				break;
			}
		}
		$like_where = implode(' AND ', $like_where);
		$pt_like_where = implode(' AND ', $pt_like_where);
		
		$query = array(
			"product" => "SELECT inven_mj.invid, color, cat, type, img, retail, saleprice, $inven_traits_select FROM inven_mj LEFT JOIN inven_traits ON inven_mj.invid = inven_traits.invid WHERE active =1 AND ($like_where)",
			"pattern" => 	"SELECT * FROM `pattern_style` WHERE 1 AND ($pt_like_where) ORDER BY style ASC"
		);
	}

	
	if(!empty($query['product'])) {
		$query['product'] = $query['product'] .' LIMIT 30';
	}
	return $query;
}

function getData($query){
	global $config;

	$db = DB::getInstance();

	$data = array();
	//if pull down categories, also show releveant products as secondary data
	if(is_array($query)) foreach($query as $key => $v){
		$result = $db->query($v);
		switch($key){

			case "articles":
				$links = array();
				$i = 1;
				while($row = $result->fetch_assoc()) {
					($i %3 == 0 && $i != 0) ? $end = 'end' : $end = '';
					$search_res = '<div class="col-md-3 col-sm-4 col-xs-5 search_img search_img_small_screen text-center '.$end.'">';
					$search_res .= '<div class="art_overlay"></div>';
					$search_res .= '<a href="'.$config->domain.'tutorial,'. str_replace(' ', '-', $row['title']) .'" class=""><img alt="'. $row['title'] .'" src="'.$config->CDN.'images/video/'. $row['thumb'] .'_370x205.jpg" class="img-responsive"></a>';
					$search_res .= '<h4><a href="'.$config->domain.'tutorial,'. str_replace(' ', '-', $row['title']) .'">'. $row['title'] .'</a></h4>';
					$search_res .= '</div>';
					$links[] = $search_res;
				}
				$data[] = array("item" => array("title" => "Article Results", "links" => $links));
				break;

			case "pattern":
				$links = array();
				$i = 1;
				while($row = $result->fetch_assoc()) {
					$title = ucfirst($row['gender']) .' '. ucfirst($row['style']);
					$href = $config->domain .'pattern/'. $row['gender'] .'/'. str_replace(' ', '-', $row['style']);

					$img = json_decode($row['images'], true);
					$img_src = 'images/patterns/'. $row['gender'] .'/'. $img[0]['path'] .'_420x400.jpg';

					($i %3 == 0 && $i != 0) ? $end = 'end' : $end = '';
					$search_res = "";
					$search_res .= '<div class="col-md-2 col-sm-3 col-xs-4 search_img search_img_small_screen'. $end .'">';
					$search_res .= '<a href="'. $href .'"><img src="'.$config->CDN. $img_src .'" width="115" height="87" alt="'.$img[0]['alt'].'" /></a>'; 
					$search_res .= '<a href="'. $href .'"><h4>Pattern '. $title .'</h4></a>';
					$search_res .= '<span>List Price $'. $row['price'] .'</span>';
					$search_res .= '</div>';
					$i++;
					$links[] = $search_res;
				}
				$data[] = array("item" => array("title" => "Patterns Results", "links" => $links));
				break;

			case "product": 
				$links = array();
				$i = 1;
				while($row = $result->fetch_assoc()) {

					if($row['type'] == "Zippers"){
						$separating = (!empty($row['seperating']) && $row['seperating'] == 1 ? 'separating' : 'non-separating');
						$title = str_replace("-"," ",$row['color'])." ".str_replace(' ', '-', $row['length'])." ".$separating." ".$row['teeth_type']." ".$style;
						$url = $row['color'].','.str_replace(' ', '-', $row['length']).','.$separating.','.$row['teeth_type'].',Zippers,'.$row['invid'];
						$img = json_decode($row['img'], true);

						($i %3 == 0 && $i != 0) ? $end = 'end' : $end = '';
						$search_res = "";
						$search_res .= '<div class="col-md-2 col-sm-3 col-xs-4 search_img search_img_small_screen'. $end .'">';
						$search_res .= '<a href="'.$config->domain.'products.'. $url .'"><img src="'.$config->CDN.'images/product/'.$row['invid'].'/'.$img[0]['path'].'_370x280.jpg" width="115" height="87" alt="'.$img[0]['alt'].'" /></a>'; 
						$search_res .= '<a href="'.$config->domain.'products.'. $url .'"><h4>'. $title .'</h4></a>';
						$search_res .= '<span>List Price $'. $row['retail'] .'</span>';
						if($row['saleprice'] > 0) $search_res .= '<span class="sale">'. $row['saleprice'] .'</span>';
						$search_res .= '</div>';
						$i++;
						$links[] = $search_res;
					}
					elseif($row['cat'] != "Clearance"){
						$title = str_replace("-"," ",$row['color']);
						$title .= " ".str_replace("-"," ",$row['type']);
						if($row['type'] == 'Busks') {
							$title .= " ".str_replace('-', ' ', $row['length']);
						}

						$href = $row['color'];
						if($row['type'] == 'Busks') {
							$href .= "-".str_replace(' ', '-', $row['length']);
						}
						$href .= ','. $row['type'] .','. $row['cat'];
						$img = json_decode($row['img'], true);

						($i %3 == 0 && $i != 0) ? $end = 'end' : $end = '';
						$search_res = "";
						$search_res .= '<div class="col-md-2 col-sm-3 col-xs-4 search_img search_img_small_screen'. $end .'">';
						$search_res .= '<a href="'.$config->domain.'products.'. $href .'"><img src="'.$config->CDN.'images/product/'.$row['invid'].'/'.$img[0]['path'].'_115x87.jpg" width="115" height="87" alt="'.$img[0]['alt'].'" /></a>'; 
						$search_res .= '<a href="'.$config->domain.'products.'. $href .'"><h4>'. $title .'</h4></a>';
						$search_res .= '<span>List Price $'. $row['retail'] .'</span>';
						if($row['saleprice'] > 0) $search_res .= '<span class="sale">'. $row['saleprice'] .'</span>';
						$search_res .= '</div>';
						$i++;
						$links[] = $search_res;
					}
				}
				if($links) {
					$data[] = array("item" => array("title" => "Product Results", "links" => $links));
				}
				break;

			case "categories":
				$links = array();
				$zippers_main = false;
				while($row = $result->fetch_assoc()) {
					if ($row['type'] == 'Zippers') {
						$separating = (!empty($row['seperating']) && $row['seperating'] == 1 ? 'separating' : 'non-separating');
						if (!$zippers_main) {
							$links[] = '<a href="'.$config->domain.'cache/cat/zipper-types.html">'. str_replace("-"," ",$row['type']) .'</a><br>';
							$zippers_main = true;
						}
						$links[] = '<a href="'.$config->domain.'categories-'. $row['teeth_type'] .'-'. $separating . ',Zippers' .'">'. str_replace("-"," ",$row['teeth_type']) .' '. $separating . ' Zippers' .'</a><br>';
					}
					else {
						$links[] = '<a href="'.$config->domain.'categories-'. $row['type'] .','. $row['cat'] .'">'. str_replace("-"," ",$row['type']) .' '. str_replace("-"," ",$row['cat']) .'</a><br>';
					}
				}
				$data[] = array("item" => array("title" => "Category Results", "links" => $links));
				break;
		}
	}

	return $data;
}

function getDataArray($query){
	global $config;

	$db = DB::getInstance();

	$data = array();
	if(is_array($query)) foreach($query as $key => $v){
		$result = $db->query($v);
		switch($key){
			case "articles":
				$links = array();
				while($row = $result->fetch_assoc()) {
					$links[] = '<a href="'.$config->domain.'tutorial,'. str_replace(' ', '-', $row['title']) .'">'. $row['title'] .'</a>';
				}
				$data[] = array("item" => array("title" => "Article Results", "links" => $links));
				break;

			case "pattern":
				$links = array();
				while($row = $result->fetch_assoc()) {
					$links[] = '<a class="pull-left" href="'.$config->domain.'pattern/'. $row['gender'] .'/'. str_replace(' ', '-', $row['style']) .'">'. ucfirst($row['gender']) .' '. ucfirst($row['style']) .'</a> <span class="pull-right">$'. $row['price'] .'</span>';
				}
				$data[] = array("item" => array("title" => "Patterns Results", "links" => $links));
				break;

			case "product": 
				$products = array();
				while($row = $result->fetch_assoc()) {
					if($row['type'] == "Zippers"){
						$separating = (!empty($row['seperating']) && $row['seperating'] == 1 ? 'separating' : 'non-separating');
						$title = str_replace("-"," ",$row['color'])." ".str_replace(' ', '-', $row['length'])." ".$separating." ".$row['teeth_type']." ".$style;
						$title = formatTitle($title);
						$url = $row['color'].','.str_replace(' ', '-', $row['length']).','.$separating.','.$row['teeth_type'].',Zippers,'.$row['invid'];
						$products[] = '<a class="pull-left" href="'.$config->domain.'products.'. $url .'">'. $title .'</a> <span class="pull-right">$'. $row['retail'] .'</span>';
					} elseif($row['cat'] != "Clearance") {
						$title = str_replace("-"," ",$row['color']);
						$title .= " ".str_replace("-"," ",$row['type']);
						if($row['type'] == 'Busks') {
							$title .= " ".str_replace('-', ' ', $row['length']);
						}
						$title = formatTitle($title);

						$href = $row['color'];
						if($row['type'] == 'Busks') {
							$href .= "-".str_replace(' ', '-', $row['length']);
						}
						$href .= ','. $row['type'] .','. $row['cat'];
						$products[] = '<a class="pull-left" href="'.$config->domain.'products.'. $href .'">'. $title .'</a> <span class="pull-right">$'. $row['retail'] .'</span>';
					}
					
				}
				$data[] = array("item" => array("title" => "Product Results", "links" => $products));
				break;

			case "categories":
				$cats = array();
				$zippers_main = false;
				// while($row = $result->fetch_assoc()) {
				// 	if ($row['type'] == 'Zippers') {
				// 		$separating = (!empty($row['seperating']) && $row['seperating'] == 1 ? 'separating' : 'non-separating');
				// 		if (!$zippers_main) {
				// 			$title = str_replace("-"," ",$row['type']);
				// 			$title = formatTitle($title);
				// 			$cats[] = '<a href="'.$config->domain.'cache/cat/zipper-types.html">'. $title .'</a>';
				// 			$zippers_main = true;
				// 		}
				// 		$title = str_replace("-"," ",$row['teeth_type']) .' '. $separating . ' Zippers';
				// 		$title = formatTitle($title);
				// 		$cats[] = '<a href="'.$config->domain.'categories-'. $row['teeth_type'] .'-'. $separating . ',Zippers' .'">'. $title .'</a>';
				// 	}
				// 	else {
				// 		$title = str_replace("-"," ",$row['type']) .' '. str_replace("-"," ",$row['cat']);
				// 		$title = formatTitle($title);
				// 		$cats[] = '<a href="'.$config->domain.'categories-'. $row['type'] .','. $row['cat'] .'">'. $title .'</a>';
				// 	}
				// }
				// $data[] = array("item" => array("title" => "Category Results", "links" => $cats));
				// break;
		}
	}

	return $data;
}

function colorCheck($val){
	$db = DB::getInstance();
	$query = "SELECT distinct(color) FROM inven_mj WHERE cat = 'Fabric' OR cat = 'Latex-Sheeting' AND type != 'swatches' AND active = 1";
	$result = $db->query($query);
	while($row = $result->fetch_assoc()) {
		preg_match('/'.$val.'/i', $row['color'], $matches);
		if(@$matches[0] != ''){
			return true;
			break 1;
		}
	}
}

function typeCheck($val){
	$db = DB::getInstance();

	if(strcasecmp($val, "latex") == 0){
		return true;
	}

	$query = "SELECT distinct(type) FROM inven_mj";
	$result = $db->query($query);
	
	while($row = $result->fetch_assoc()) {
		preg_match('/'.$val.'/i', $row['type'], $matches);
		if(@$matches[0] != ''){
			return true;
			break 1;
		}
	}
}

function catCheck($val){
	$db = DB::getInstance();

	if(strcasecmp($val, "latex") == 0){
		return true;
	}

	$query = "SELECT distinct(cat) FROM inven_mj";
	$result = $db->query($query);
	
	while($row = $result->fetch_assoc()) {
		preg_match('/'.$val.'/i', $row['cat'], $matches);
		if(@$matches[0] != ''){
			return true;
			break 1;
		}
	}
}

function mscCheck($val, $color){
	$spec = array('plastic' => 'pvc, patent-vinyl', 'poly' => 'pvc, patent-vinyl, stretch-pvc', 'water' => 'pvc, patent-vinyl, latex', 'rain' => 'patent-vinyl, pvc, latex', 'upholstery' => 'patent-vinyl, matte-vinyl', 'rubber' => '.20mm, .30mm, .40mm, .50mm, .60mm, .70mm', 'latex' => '.20mm, .30mm, .40mm, .50mm, .60mm, .70mm', 'motorcycle' => 'patent-vinyl, matte-vinyl, snakeskin', 'leather' => 'matte-vinyl, patent-vinyl', 'vinil' => 'pvc, patent-vinyl, matte-vinyl, stretch-pvc', 'vynil' => 'pvc, patent-vinyl, matte-inyl, stretch-pvc', 'vinyl' => 'pvc, patent-vinyl, matte-vinyl, stretch-pvc', 'asian' => 'oriental-brocade', 'broc' => 'oriental-brocade', 'oriental' => 'oriental-brocade');
	$match = false;
	
	foreach($spec as $key => $v){
		preg_match('/'.$key.'/', $val, $matches);
		if($matches[0] != ''){
			$types = explode(', ',$v);
			foreach($types as $valType){
				$terms .= "type = '". $valType. "' OR "; 
			}
			$terms = rtrim($terms, " OR ");
			if($color != ''){
				$query = array("product" => "SELECT invid, color, cat, type, retail, saleprice, img FROM inven_mj WHERE color = '". $color ."' AND active = 1 AND (". $terms .") ORDER BY type ASC", "categories" => "SELECT invid, color, cat, type FROM inven_mj WHERE ". $terms ." AND active = 1 GROUP BY type ORDER BY type ASC");
			} else {
				$query = array("categories" => "SELECT invid, color, cat, type FROM inven_mj WHERE ". $terms ." AND active = 1 AND cat != 'Clearance' GROUP BY type ORDER BY type ASC", "product" => "SELECT invid, color, cat, type, retail, saleprice, img FROM inven_mj WHERE (". $terms .") AND active = 1 AND cat != 'Clearance' ORDER BY type ASC");
			}

		}
	}
	return $query;
}

function faqCheck($value){
	global $config;
	//if I get faq match, stop search and show only link to appropriate page w/ appropriate message.
	$faqArr = array("faq" => array('mail','return','address','info','support'), "sample" => array("sample", "swatch"), "contact" => array('phone', 'contact', 'email'), "shipping" => array('post','ship','international'), "art" => array('article','tutorial','idea','project','how'));
	
	$faq = '<br><br><h3>Please visit our Frequently asked questions page for information regarding '.$_GET['search'].': <br><br> <a href="'.$config->domain.'faq">F.A.Q.</a></h3>';
	$shipping = '<h3>To see shipping prices and delivery dates:</h3><br><br>
					<div class="col-md-6">
						<ol style="font-size:16px;">
							<li>Add items to your cart.</li>
							<li>Click the orange "Set Shipping" button and input your zip code.</li>
						</ol>
					</div>
					<div class="col-md-5">
						<img src="'.$config->domain.'images/shipping-options.png" class="text-center" />
					</div>
					
				';
	$contact = '<br><br><h3>Looks like you were trying to contact us.  Our contact info is as follows: <br><br>Email: <a href="mailto:sales@MJTrends.com">sales@MJTrends.com</a><br>Phone: 1-571-285-0000<br> Address: 10712 OldField Dr.<br>Reston, Virginia 20191<br>U.S.</h3>';
	$sample = '<br><br><h3>You can order samples at the following page:</h3><p><a href="'.$config->domain.'categories-swatches,swatches">Product Samples / Swatches</a></p>';
	$art = 'tutorial';
	$promo = 'If you would like to receive promotional codes, you need to sign up for our monthly newsletter.  You may do so here: <a href="#" onclick="popup(\'signup.php\',\'450\',\'385\');">Newsletter Signup</a>.  
			You may view sale items here:<br><a href="categories,sale">Sale Items</a><br><br>
			You may view clearance items (*** Note: quantity cannot be changed on clearance items ***) here: <br><a href="'.$config->domain.'categories,clearance">Clearance Items</a>';
	
	//compare words in search string to arrays and if get match, return the appropriate nfo, else return false
	foreach($faqArr as $key => $arr){
		foreach($arr as $v){
			preg_match('/'.$v.'/', $value, $matches);
			if(@$matches[0] != ''){
				eval("\$str = \$$key;");
				return array('page'=>$key, 'val'=>$str);
				break 2;
			}
		}
	}
	return false;
}

function insertTerm($search){
	$db = DB::getInstance();

	$user_id = isset($_COOKIE['usr']) ? $_COOKIE['usr'] : isset($_SESSION['user_search_id']) ? $_SESSION['user_search_id'] : isset($_COOKIE['tmp_usr']) ? $_COOKIE['tmp_usr'] : 0;
	if (!$user_id) {
		$user_query = 'SELECT uid FROM users ORDER BY RAND() LIMIT 1';
		$result = $db->query($user_query);
		$user = $result->fetch_array();
		$user_id = $user['uid'];
		setcookie('tmp_usr', $user_id);
	}
	if ($_SESSION['search_'.$user_id] != $search) {		
		$_SESSION['search_'.$user_id] = $search;
		$query = "INSERT INTO searches (user_id, search_terms, date) VALUES (".$user_id.", '$search', CURDATE())";
		$result = $db->query($query);
	}
}

function formatData($data){
	$output = "";

	if(empty($data)) {
		$output .= '<h2>Sorry, we did not find any results for the search term: '.$_GET['search'].'</h2><p>Please modify your search terms and try again, or you can email us with specific questions at: <a href="mailto:sales@mjtrends.com">sales@mjtrends.com</a>';
	} elseif ($data['error'] != ""){
		$output .= $data['error'];
	} else {
		foreach($data[0] as $row){
			$output .= "<h2>".$row['item']['title']."</h2>";
			$output .= "<div class='searchProdWrap'>";
			foreach($row['item']['links'] as $link){
				$output .= $link;
			}
			$output .= "</div>";
		}
	}

	return $output;
}

function formatTitle($title) {
	$title = preg_replace("/(". $_GET['search'] .")/i", '<strong>${1}</strong>', $title);
	return $title;
}