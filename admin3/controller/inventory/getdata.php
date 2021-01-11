<?php 
include('../../common/database.php');

$sql = "SELECT * FROM inven_mj ORDER BY invid DESC";

$suggest = array();
$phpObject = array();

$jsObject = "data = new Array();
	function addProd(invid, cat, type, color, retail, saleprice, clearAmt, title, descr, meta_key, meta_desc, features, video, tutorials, wholesale, purch, invamount, weight, volume, minwidth, minheight, minlength, active, sort_order){
	this.invid 		= invid;
	this.cat 		= cat;
	this.type 		= type;
	this.color 		= color;
	this.retail 	= retail;
	this.saleprice 	= saleprice;
	this.clearAmt 	= clearAmt;
	this.title 		= title;
	this.descr 		= descr;
	this.meta_key 	= meta_key;
	this.meta_desc 	= meta_desc;
	this.features 	= features;
	this.video 		= video;
	this.tutorials 	= tutorials;

	this.wholesale 	= wholesale;
	this.purch		= purch;
	this.invamount 	= invamount;
	this.weight 	= weight;
	this.volume		= volume;
	this.minwidth   = minwidth;
	this.minheight  = minheight;
	this.minlength  = minlength;
	this.active     = active;
	this.sort_order = sort_order;

	};
	";

if($result = $mysqli->query($sql)){
    while($row = $result->fetch_assoc() ){
        $descr = str_replace("\r","",$row['descr']); //remove carriage returns
        $descr = str_replace("\n","",$descr); //remove newlines
        $descr = str_replace("'","&#39;",$descr); //replace single quote with html

        $features = str_replace("\r","",$row['features']); //remove carriage returns
        $features = str_replace("\n","",$features); //remove newlines
        $features = str_replace("'","&#39;",$features); //replace single quote with html

        $featureArray = json_decode($features,true);
        $feature = '';
        if(is_array($featureArray)){
            foreach($featureArray as $key => $val){
                $feature .= $key.": ".$val."<br>";
            }
        }

        $meta_key = str_replace("\r","",$row['meta_key']); //remove carriage returns
        $meta_key = str_replace("\n","",$meta_key); //remove newlines
        $meta_key = str_replace("'","&#39;",$meta_key); //replace single quote with html

        $meta_desc = str_replace("\r","",$row['meta_desc']); //remove carriage returns
        $meta_desc = str_replace("\n","",$meta_desc); //remove newlines
        $meta_desc = str_replace("'","&#39;",$meta_desc); //replace single quote with html

        $jsObject .= "data[data.length++] = new addProd('". $row['invid'] ."', '". $row['cat'] ."', '". $row['type'] ."', '". $row['color'] ."', '". $row['retail'] ."', '". $row['saleprice'] ."', '". $row['clearAmt'] ."', '". $row['title'] ."', '". $descr ."', '". $meta_key ."', '". $meta_desc ."', '". $feature ."', '". $row['video'] .
            "', '". $row['tutorials'] .
            "', '" . $row['wholesale'] ."', '". $row['purch'] ."', '". $row['invamount'] ."', '" .$row['weight']. "', '".$row['volume']."', '".$row['minwidth']."', '".$row['minheight']."', '".$row['minlength']."', '".$row['active']."', '".$row['sort_order']."');";
        $phpObject[] = array('invid' => $row['invid'], 'cat' => $row['cat'], 'type' => $row['type'], 'color' => $row['color']);

        $clearNum = $row['invid'];
        if($row['invid'] < 5000) $newNum = $row['invid'];
    }  

    $newNum += 1;
    $clearNum += 1;

    $i = 0;
    foreach($phpObject as $val) {
        $suggest[] = array(
            'name' => $val['invid']." ".$val['cat']." ".$val['type']." ".$val['color'],
            'label' =>  $val['invid']." ".$val['cat']." ".$val['type']." ".$val['color']." ".$val['cat']." ".$val['color']." ".$val['type']." ".$val['type']." ".$val['cat']." ".$val['color']." ".$val['type']." ".$val['color']." ".$val['cat']." ".$val['color']." ".$val['type']." ".$val['cat']." ".$val['color']." ".$val['cat']." ".$val['type'],
            'id' => $i
        );
        $i++;
    }
}

?>