<?php
include ("../logic/global.php");
conn();

function leftnav(){
	$query = "SELECT cat, type FROM inven_mj where cat != 'Clearance' AND cat != 'Swatches' and active != 0 order by cat asc, type asc";
	$result = mysql_query($query);
	$row = mysql_fetch_assoc($result);

	#loop through results and create new arr with just unique categories
	mysql_data_seek($result,0);
	while ($row = mysql_fetch_assoc($result)){
		$cat_arr[] = $row['cat'];
	}

	$cat2_arr = array_unique($cat_arr);
	#loop through category arr, and creat inner loop that goes through all the products where their category == category arr value
	foreach ($cat2_arr as $value){
		if($value == 'Latex-Sheeting'){
			echo '<li class="bold"><a href="http://www.mjtrends.com/categories-kits,DIY">KITS</a></li>';
			echo '<li class="bold"><a href="http://www.mjtrends.com/pattern.php">PATTERNS</a></li>';
			echo '<li class="bold"><a href="http://www.mjtrends.com/categories-upholstery,fabric">Upholstery Fabrics</a></li>';
			echo '<li class="bold">Latex Sheeting</li>';
			echo '<li><a href="http://www.mjtrends.com/gridview-latex-sheeting">Grid view...</a></li>';
		} else {
			echo '<li class="bold">'.str_replace("-"," ",$value).'</li>';
		}
		#echo each type in that category
		mysql_data_seek($result,0);
		while ($row = mysql_fetch_assoc($result))
		{
			if ($row['cat']==$value && $row['cat'] != 'Latex-Sheeting'){
				$type_arr[] = $row['type'];
			} elseif($row['cat']==$value && $row['cat'] == 'Latex-Sheeting') {
				$type_arr[] = $row['type']; //lowercase the "m's"
			}
		}
		if(!empty($type_arr)){
			$type2_arr = array_unique($type_arr);
			foreach ($type2_arr as $value2){
			echo "<li><a href=\"http://www.mjtrends.com/categories-".$value2.",".$value."\">".str_replace("-"," ",$value2)."</a></li>";
			}
		}
	#reset type_arr so that next category won't show same types as previous category
	unset($type_arr);
	}
	echo '<li class="bold"><a href="http://www.mjtrends.com/categories-swatches,swatches">Swatches</a></li>';
	echo '<li class="bold"><a href="http://www.mjtrends.com/categories-clearance,sale">Clearance</a></li>';
}

// start the output buffer
ob_start();
?>

<div class="navigation">
    <ul>
        <?leftnav()?>
    </ul>
</div>

<?php
$cachefile = "../cache/navigation.html";
// open the cache file "cache/home.html" for writing
$fp = fopen($cachefile, 'w');
// save the contents of output buffer to the file
fwrite($fp, ob_get_contents());
// close the file
fclose($fp);
// Send the output to the browser
ob_end_flush();
?>
