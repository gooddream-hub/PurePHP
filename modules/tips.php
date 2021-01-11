<?php
include ("../logic/global.php");
conn();

function tips(){
	global $tip;
	$query = "SELECT * FROM tips";
	$result = mysql_query($query); 
	$numRows = mysql_num_rows($result);
	$row = mysql_fetch_assoc($result);
	
	//get the week number (1-52), if numRows is less than the week number, then go to the modulus of 52
	$weekNum = date('W');
	if($numRows < $weekNum){
		$num = rand(0,($numRows-1));
	} else {
		$num = $weekNum;
	}
	
	$i = 0;
	while ($row = mysql_fetch_assoc($result)){
		if($i == $num) $tip = $row['tip'];
		$i++;
	}
}

tips();
// start the output buffer
ob_start(); 
?>

<div class="tips">
	<div class="container">
		<div class="header">Weekly Quick Tip</div>
		<div class="wrapper" itemscope itemtype="http://schema.org/Article">
			<div class="photo"><img src="http://mjtrends.r.worldssl.net/images/dress-form.jpg" /></div>
			<div class="text">
				<p itemprop="text"><?=$tip?></p>
			</div>
		</div>
	</div>
</div>

<?php
$cachefile = "../cache/tips.html";
// open the cache file "cache/home.html" for writing
$fp = fopen($cachefile, 'w');
// save the contents of output buffer to the file
fwrite($fp, ob_get_contents());
// close the file
fclose($fp);
// Send the output to the browser
ob_end_flush();
?>