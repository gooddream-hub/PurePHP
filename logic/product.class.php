<?php 
class Product{
	
	public $tutorials;
	public $posts;
	public $details;
	public $mysqli;
	public $traits;
	public $breadcrumbs;

	function getTutorials(){
		$this->tutorials = array();
		$sql = "SELECT id, title, thumb, vid_url FROM articles WHERE id IN(".$this->details['tutorials'].") ORDER BY rand() LIMIT 3";
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

	function getItemDetails(){
		$type  = isset($_GET['type']) ? $this->mysqli->real_escape_string(strip_tags($_GET['type'])) : '';
		$color = $this->mysqli->real_escape_string(strip_tags($_GET['color']));
		$cat   = $this->mysqli->real_escape_string(strip_tags($_GET['cat']));
		$invid = @$this->mysqli->real_escape_string(strip_tags($_GET['invid']));
	
		if($invid != "") {
			$sql = "SELECT inven_mj.*, inven_traits.length, inven_traits.seperating, inven_traits.hidden, inven_traits.teeth_size, inven_traits.teeth_type, inven_traits.3_way FROM inven_mj LEFT JOIN inven_traits ON inven_mj.invid = inven_traits.invid WHERE inven_mj.invid = '$invid' ";
		} else {
			$sql = "SELECT inven_mj.*, inven_traits.length FROM inven_mj LEFT JOIN inven_traits ON inven_mj.invid = inven_traits.invid WHERE type = '$type' and color = '$color' and cat = '$cat'";
		}

		$result = $this->mysqli->query($sql);
	
		while ($row = $result->fetch_assoc()) {
			$this->details = $row;
		}
		
		$this->details['img'] = json_decode($this->details['img'], true);
		$this->details['features'] = json_decode($this->details['features'], true); 

		if($this->details['video'] != ""){
			$this->details['video'] = json_decode($this->details['img'], true);
		} else {
			$this->details['video'] = array();
		}

		if($this->details['cat'] == 'Latex-Sheeting'){
			$this->details['swatch'] = 'Latex-sheeting';
		} else {
			$this->details['swatch'] = $this->details['type'];
		}
		
		if($this->details['cat'] == 'Clearance'){
			$this->details['clearance'] = "disabled value=\"".$this->details['invamount']."\"";
		} else {
			$this->details['clearance'] = "";
		}

		if($this->details['cat'] == 'Fabric' OR $this->details['cat'] == 'Latex Sheeting'){
			$this->details['unit'] = 'yards';
		} else {
			$this->details['unit'] = '';
		}

	}

	//*********************
	//Group functions start
	//*********************
	function getGroupTraits() {
		if (empty($this->details['teeth_type'])) {
			$sql = "SELECT inven_traits.invid, length FROM inven_traits LEFT JOIN inven_mj ON inven_traits.invid = inven_mj.invid WHERE length IS NOT NULL AND inven_mj.cat = '".$this->details['cat']."' AND inven_mj.type = '".$this->details['type']."' AND inven_mj.color = '".$this->details['color']."' ORDER BY invid ";
		}
		else {
			$sql = "SELECT inven_mj.saleprice, inven_mj.retail, inven_traits.invid, length, inven_traits.seperating, inven_traits.hidden, inven_traits.teeth_size, inven_traits.teeth_type, inven_traits.3_way FROM inven_traits LEFT JOIN inven_mj ON inven_traits.invid = inven_mj.invid "
				. "WHERE length IS NOT NULL AND inven_mj.cat = '".$this->details['cat']."' AND inven_mj.type = '".$this->details['type']."' AND inven_mj.color = '".$this->details['color']."' "
				. "AND inven_traits.teeth_type = '".$this->details['teeth_type']."' AND inven_traits.seperating = '".$this->details['seperating']."' AND inven_traits.3_way ".($this->details['3_way'] ? "= '".((int)$this->details['3_way'])."'" : "IS NULL")." AND inven_traits.hidden = '".$this->details['hidden']."' "
				. "ORDER BY invid ";
		}
		$result = $this->mysqli->query($sql);

		while ($row = $result->fetch_assoc()) {
			$this->traits[] = $row;
		}
	}

	function getDiffTutorials(){
		//if any tutorials in the group are different, return the values for each item, otherwise return nothing
		$sql = "select tutorials from inven_mj where type = '".$this->details['type']."' and color = '".$this->details['color']."' ";
		$result = $this->mysqli->query($sql);
		$row = $result->fetch_assoc();

		if(count(array_unique($row)) !== 1){//array has non-matching values in it
			$this->getTutorials();
		} else {
			$this->tutorials = "";
		}

	}


	function getDiffPosts(){
		//if any posts in the group are different, return the values for each item, otherwise return nothing
		$sql = "select posts from inven_mj where type = '".$this->details['type']."' and color = '".$this->details['color']."' ";
		$result = $this->mysqli->query($sql);
		$row = $result->fetch_assoc();

		if(count(array_unique($row)) !== 1){//array has non-matching values in it
			$this->getPosts();
		} else {
			$this->posts = "";
		}
	}

	function getDiffImages(){
		//if any posts in the group are different, return the values for each item, otherwise return nothing
		$sql = "select img from inven_mj where type = '".$this->details['type']."' and color = '".$this->details['color']."' ";
		$result = $this->mysqli->query($sql);
		$row = $result->fetch_assoc();

		if(count(array_unique($row)) === 1){//all values in array are the same so override the img value and set to empty string
			$this->details['img'] = "";
		} 
	}

	function getDiffVideos(){
		//if any posts in the group are different, return the values for each item, otherwise return nothing
		$sql = "select video from inven_mj where type = '".$this->details['type']."' and color = '".$this->details['color']."' ";
		$result = $this->mysqli->query($sql);
		$row = $result->fetch_assoc();

		if(count(array_unique($row)) === 1){//all values in array are the same so override the img value and set to empty string
			$this->details['video'] = "";
		} 
	}
	//*********************
	//Group functions end
	//*********************

	function getKitItems(){
		$name = $this->mysqli->real_escape_string(strip_tags($_GET['name']));
		$name = str_replace("-", " ", $name);
		$_GET['name'] = $name;
		$sql = "SELECT * FROM kit WHERE title = '$name' ";
		$result = $this->mysqli->query($sql);

		while ($row = $result->fetch_assoc()) {
			$this->kitDetails = $row;
		}
		$this->details['img'][] = json_decode($this->kitDetails['img'], true);
		$this->kitDetails['prods'] = json_decode($this->kitDetails['items'],true);

		foreach($this->kitDetails['prods'] as $key=>$val){
			$invids .= $key.",";
		}
		$invids = rtrim($invids, ',');

		$sql = "SELECT * FROM inven_mj where invid IN($invids)";
		$result = $this->mysqli->query($sql);

		$i = 0;
		while ($row = $result->fetch_assoc()) {
			$amount = 1;
			//$price = ($row['saleprice'] > 0) ? $row['saleprice'] : $row['retail'];
			$price = $row['retail'];
			if($this->kitDetails['prods'][$i][1] > 1){
				$amount = $this->kitDetails['prods'][$i][1];
			}
			$this->kitDetails['total'] += $amount * $price;
			$this->kitItems[] = $amount." ".$row['title'];

			$img = json_decode($row['img'], true);
			$this->details['img'][] = array("path" => $row['invid']."/".$img[0]['path'], "alt" => $img[0]['alt']);

			$this->details['tutorials'] .= $row['tutorials'].",";
			$this->details['posts'] .= $row['posts'].",";

			$this->details['video'] = array();

			$i++;
		}
		$this->details['tutorials'] = rtrim($this->details['tutorials'], ',');
		$this->details['tutorials'] = ltrim($this->details['tutorials'], ',');
		$this->details['posts'] = rtrim($this->details['posts'], ',');
		$this->details['posts'] = ltrim($this->details['posts'], ',');
	}

	function getKitGridValue($name){
		$name = $this->mysqli->real_escape_string(strip_tags($name));
		$sql = "SELECT items FROM kit WHERE title = '$name' ";
		$result = $this->mysqli->query($sql);

		$row = $result->fetch_assoc();
		$prods = json_decode($row['items'],true);

		foreach($prods as $key=>$val){
			$invids .= $key.",";
		}
		$invids = rtrim($invids, ',');

		$sql = "SELECT * FROM inven_mj where invid IN($invids)";
		$result = $this->mysqli->query($sql);
		
		$i = 0;
		while ($row = $result->fetch_assoc()) {
			$imgArray = json_decode($row['img'], true);
			$amount = $prods[$row['invid']];
			$gridValue .= $row['cat'].",".$row['type'].",".$row['color'].",".$row['weight'].",".$row['volume'].",".$row['retail'].",".$amount.",".$amount.",".$row['invid'].",".$row['saleprice'].",".$imgArray[0]['path'].",".$row['minwidth'].','.$row['minlength'].','.$row['minheight']."*";
			$i++;
		}
		return $gridValue;
	}

	function setRecommendedArray($invid, $cat, $type, $color, $saleprice, $retail, $imgArray, $length='', $seperating='', $teeth_type=''){
		include(SITE_ROOT_PATH.'config/config.php');
		if($saleprice > 0){
				$price = '<del>$'.$retail.'</del> <span class="rPrice sale">$'.$saleprice.'</span>';
		} else {
			$price = '<span class="rPrice">$'.$retail.'</span>';
		}

		$imgArray = json_decode($imgArray, true);
		
		if($type == 'Zippers'){
			$length = str_replace(' ', '-', $length);
			$seperating = ($seperating == 0) ? 'non-separating' : 'separating';
			$item = '<li><a href="'.$config->domain.'products.'.$color.','.$length.','.$seperating.','.$teeth_type.',Zippers,'.$invid.' "><img src="'.$config->CDN.'images/product/'.$invid.'/'.$imgArray[0]["path"].'_130x98.jpg" alt="'.$imgArray[0]["alt"].'"></a>'.str_replace('-', ' ', $color).' '.$teeth_type.' '.$seperating.' '.str_replace('-', ' ', $type).$price.'</li>';
		} else{
			$item = '<li><a href="'.$config->domain.'products.'.$color.','.$type.','.$cat.' "><img src="'.$config->CDN.'images/product/'.$invid.'/'.$imgArray[0]["path"].'_130x98.jpg" alt="'.$imgArray[0]["alt"].'"></a>'.str_replace('-', ' ', $color).' '.str_replace('-', ' ', $type).$price.'</li>';
		}

		return $item;
	}

	function getKitRecommended($kitName){
		if(stripos($kitName, 'Latex') !== false){
			$query = "SELECT invid, cat, type, color, img, retail, saleprice FROM inven_mj where cat = 'Latex-Sheeting' AND active = 1 order by rand() limit 8";
			$result = $this->mysqli->query($query);		

			while ($row = $result->fetch_assoc()) {
				$prodArray[] = $this->setRecommendedArray($row['invid'], $row['cat'], $row['type'], $row['color'], $row['saleprice'], $row['retail'], $row['img']);
			}
		} elseif(stripos($kitName, 'Vinyl') !== false){
			$query = "SELECT invid, cat, type, color, img, retail, saleprice FROM inven_mj where cat !='Clearance' AND active = 1 order by rand() limit 8";
			$result = $this->mysqli->query($query);

			while ($row = $result->fetch_assoc()) {
				$prodArray[] = $this->setRecommendedArray($row['invid'], $row['cat'], $row['type'], $row['color'], $row['saleprice'], $row['retail'], $row['img']);
			}
		} elseif(stripos('Lingerie French Curve Pro Pack', $kitName) !== false){
			$query = "SELECT invid, cat, type, color, img, retail, saleprice FROM inven_mj where (cat = 'Latex-Sheeting' OR cat = 'Fabric') AND active = 1 order by rand() limit 8";
			$result = $this->mysqli->query($query);		

			while ($row = $result->fetch_assoc()) {
				$prodArray[] = $this->setRecommendedArray($row['invid'], $row['cat'], $row['type'], $row['color'], $row['saleprice'], $row['retail'], $row['img']);
			}
		}

		return $prodArray;
	}

	function getRecommended($type, $invid){
		$prodArray = array();
		if(	$_GET['cat'] == 'Latex-Sheeting'){

			//get priority items
			$query = "SELECT invid, cat, type, color, img, retail, saleprice FROM inven_mj where invid IN(2401, 2402, 2404, 2504, 2505, 3000, 3001, 3004, 3010, 3011, 3012, 3013, 3014, 3015, 3020, 3030) AND active = 1 order by rand()";
			$result = $this->mysqli->query($query);
			$i = 0;

			while ($row = $result->fetch_assoc()) {				
				if($i < 2){
					$priorityArray[] = $this->setRecommendedArray($row['invid'], $row['cat'], $row['type'], $row['color'], $row['saleprice'], $row['retail'], $row['img']);
				} else {
					$prodArray[] = $this->setRecommendedArray($row['invid'], $row['cat'], $row['type'], $row['color'], $row['saleprice'], $row['retail'], $row['img']);
				}
				
			}

			//get other latex items
			$query = "SELECT invid, cat, type, color, img, retail, saleprice FROM inven_mj where type = '$type' AND active = 1 AND invid != $invid order by rand() limit 4";
			$result = $this->mysqli->query($query);			

			while ($row = $result->fetch_assoc()) {
				$prodArray[] = $this->setRecommendedArray($row['invid'], $row['cat'], $row['type'], $row['color'], $row['saleprice'], $row['retail'], $row['img']);
			}

			//get a mix of notions
			$query = "SELECT inven_mj.invid, cat, type, color, img, retail, saleprice, inven_traits.seperating, inven_traits.teeth_type, inven_traits.length FROM inven_mj LEFT JOIN inven_traits ON inven_mj.invid = inven_traits.invid where inven_mj.invid NOT IN(2401, 2402, 2404, 2504, 2505, 3000, 3001, 3004, 3010, 3011, 3012, 3013, 3014, 3015, 3020, 3030) AND cat = 'Notions' AND active = 1 order by rand() limit 8";
			$result = $this->mysqli->query($query);	
			
			while ($row = $result->fetch_assoc()) {
				$prodArray[] = $this->setRecommendedArray($row['invid'], $row['cat'], $row['type'], $row['color'], $row['saleprice'], $row['retail'], $row['img'], $row['length'], $row['sepereting'], $row['teeth_type']);
			}
			shuffle($prodArray);
			$prodArray = $priorityArray + $prodArray;

		} elseif(stripos('PVC Patent-Vinyl Stretch-PVC Snakeskin Faux-Leather', $_GET['type']) !== false) {	
			//get priority items
			$query = "SELECT invid, cat, type, color, img, retail, saleprice FROM inven_mj where invid IN(3000, 3001, 3002, 3003, 3004, 3011, 4024, 4023, 4026) AND active = 1 order by rand()";
			$result = $this->mysqli->query($query);
			$i = 0;

			while ($row = $result->fetch_assoc()) {			
				if($i < 2){
					$priorityArray[] = $this->setRecommendedArray($row['invid'], $row['cat'], $row['type'], $row['color'], $row['saleprice'], $row['retail'], $row['img']);
				} else {
					$prodArray[] = $this->setRecommendedArray($row['invid'], $row['cat'], $row['type'], $row['color'], $row['saleprice'], $row['retail'], $row['img']);
				}
				
			}

			//get other vinyl items
			$query = "SELECT invid, cat, type, color, img, retail, saleprice FROM inven_mj where type = '$type' AND active = 1 AND invid != $invid order by rand() limit 8";
			$result = $this->mysqli->query($query);			

			while ($row = $result->fetch_assoc()) {
				$prodArray[] = $this->setRecommendedArray($row['invid'], $row['cat'], $row['type'], $row['color'], $row['saleprice'], $row['retail'], $row['img']);
			}

			//get a mix of notions
			$query = "SELECT inven_mj.invid, cat, type, color, img, retail, saleprice, inven_traits.seperating, inven_traits.teeth_type, inven_traits.length FROM inven_mj LEFT JOIN inven_traits ON inven_mj.invid = inven_traits.invid where inven_mj.invid NOT IN(2401, 2402, 2404, 2504, 2505, 3000, 3001, 3002, 3003, 3004, 3011, 3020) AND cat = 'Notions' AND active = 1 order by rand() limit 10";
			$result = $this->mysqli->query($query);	
			
			while ($row = $result->fetch_assoc()) {
				$prodArray[] = $this->setRecommendedArray($row['invid'], $row['cat'], $row['type'], $row['color'], $row['saleprice'], $row['retail'], $row['img'], $row['length'], $row['seperating'], $row['teeth_type']);
			}
			shuffle($prodArray);
			$prodArray = $priorityArray + $prodArray;

		} else { // for other fabrics and notions
			$query = "SELECT inven_mj.invid, cat, type, color, img, retail, saleprice, inven_traits.seperating, inven_traits.teeth_type, inven_traits.length FROM inven_mj LEFT JOIN inven_traits ON inven_mj.invid = inven_traits.invid where type = '$type' AND active = 1 AND inven_mj.invid != $invid order by rand() limit 8";
			$result = $this->mysqli->query($query);		

			while ($row = $result->fetch_assoc()) {
				$prodArray[] = $this->setRecommendedArray($row['invid'], $row['cat'], $row['type'], $row['color'], $row['saleprice'], $row['retail'], $row['img'], $row['length'], $row['seperating'], $row['teeth_type']);
			}

			$query = "SELECT inven_mj.invid, cat, type, color, img, retail, saleprice, inven_traits.seperating, inven_traits.teeth_type, inven_traits.length FROM inven_mj LEFT JOIN inven_traits ON inven_mj.invid = inven_traits.invid where cat = 'Notions' AND active = 1 AND inven_mj.invid != $invid order by rand() limit 16";
			$result = $this->mysqli->query($query);	

			while ($row = $result->fetch_assoc()) {
				$prodArray[] = $this->setRecommendedArray($row['invid'], $row['cat'], $row['type'], $row['color'], $row['saleprice'], $row['retail'], $row['img'], $row['length'], $row['seperating'], $row['teeth_type']);
			}
			shuffle($prodArray);

		}		

		return $prodArray;

	}
	
	function getSwatchColor(){
		$type = strtolower($this->mysqli->real_escape_string(strip_tags($_GET['color'])));
	
		($type == 'latex-sheeting') ? $sql = "SELECT color, type FROM inven_mj WHERE cat = '$type' AND invamount > 0 ORDER BY color asc, type asc" : $sql = "SELECT color, type FROM inven_mj WHERE type = '$type' AND cat != 'Clearance' AND invamount > 0 ORDER BY color asc, type asc";
		$result = $this->mysqli->query($sql);
	
		while ($row = $result->fetch_assoc()){
			$color = str_replace("-"," ",$row['color']);
			($type == 'latex-sheeting') ? $this->details['swatchColors'][] = $color." ".$row['type'] : $this->details['swatchColors'][] = $color;
		}
	}

	function getBreadcrumbs() {
		$text = '';
		$link = '';
		$title = '';
		$current_page = '';

		if (empty($this->details['teeth_type'])) {
			$text = $this->details['type'] . " " . str_replace("-", " ", $this->details['cat']);
			$link = 'categories-' . $this->details['type'] . "," . $this->details['cat'];
			$title = $_GET['type'] . " " . str_replace("-", " ", $this->details['cat']);
			$current_page = ' &raquo; ' . $this->details['color'] . " " . $this->details['type'] . " " . str_replace("-", " ", $this->details['cat']);
			?>
			<?php
		} else {
			$t = $this->details['teeth_type'] . " " . ($this->details['seperating'] ? 'separating' : 'non-separating');
			$url = '/categories-' . $this->details['teeth_type'] . '-' . ($this->details['seperating'] ? 'separating' : 'non-separating') . ',Zippers';
			if ($this->details['hidden']) {
				$t = $this->details['teeth_type'] . " hidden";
				$url = '/categories-hidden,Zippers';
			} elseif ($this->details['3_way']) {
				$t = "3-way";
				$url = '/categories-3-way,Zippers';
			}
			$t .= ' Zippers';
			$text = $t;
			$link = $url;
			$title = $text;
		}

		$this->breadcrumbs['text'] = $text;
		$this->breadcrumbs['link'] = $link;
		$this->breadcrumbs['title'] = $title;
		$this->breadcrumbs['current_page'] = $current_page;

	}
}
