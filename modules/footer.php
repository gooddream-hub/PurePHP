<?php
/*
//footer is custom now

include ("../logic/global.php");
conn();

function getArticles(){
	$query = "SELECT title FROM articles ORDER BY date ASC";
	$result = mysql_query($query);

	while ($row = mysql_fetch_assoc($result)){
		echo '<li class="listed"><a href="http://www.mjtrends.com/tutorial,'.str_replace(" ", "-", $row['title']).'">'.$row['title'].'</a></li>';
	}
}

function getIndex(){
	$query = "SELECT distinct letter FROM site_index ORDER BY letter ASC";
	$result = mysql_query($query);
	$arr = array();
	while($row = mysql_fetch_assoc($result)){
		$arr[] = $row['letter'];
	}		
	
	foreach(range('B','Z') as $i){
		if(in_array($i, $arr)){
			echo '<a href="http://www.MJTrends.com/site-index-'.$i.'">'.$i.'</a>';
		} else {
			echo "<span>".$i."</span>";
		}
	}
	
}

ob_start(); 
?>

<div class="footer">
	<p>Articles</p>
    <ul>
        <li class="listed fst"><a href="http://www.mjtrends.com/sewing-pvc-vinyl-fabric-page1.php">Sewing pvc fabric</a></li>
        <?getArticles();?>
    </ul>
	
	
	<p>Categories</p>
    <ul>
        <li class="listed fst"><a href="http://www.mjtrends.com/">Home</a></li>
        <li class="listed"><a href="http://www.mjtrends.com/sitemap.php">Site Map</a></li>
        <li class="listed"><a href="http://www.mjtrends.com/faq.php">Privacy Policy</a></li>
        <li class="listed"><a href="http://www.mjtrends.com/faq.php">Delivery Charges</a></li>
		<li class="listed"><a href="http://www.mjtrends.com/faq.php">Return Policy</a></li>
        <li class="listed"><a href="http://www.mjtrends.com/tracking.php">Order Tracking</a></li>
        <li class="listed"><a href="http://www.mjtrends.com/wholesale.php">Wholesale</a></li>
    </ul>
	<div class="index">
		<a href="" class="first">A</a>
		<?getIndex();?>
	</div>
    <p class="trdm">&copy; MJTrends<br>1-888-292-0175 <a href="mailto:sales@MJTrends.com">sales@MJTrends.com</a></p>
</div>

<!-- Start Google Analytics tag -->
  <script type="text/javascript">  (function() {
    var ga = document.createElement('script');     ga.type = 'text/javascript'; ga.async = true;
    ga.src = ('https:'   == document.location.protocol ? 'https://ssl'   : 'http://www') + '.google-analytics.com/ga.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
    })();
   </script>
<!-- End Google Analytics tag -->

<!-- Start Quantcast tag -->
<script type="text/javascript">
_qoptions={
qacct:"p-dbY5aCVWlQbx-"
};
</script>
<script type="text/javascript" src="//secure.quantserve.com/quant.js"></script>
<noscript>
<img src="//secure.quantserve.com/pixel/p-dbY5aCVWlQbx-.gif" style="display: none;" border="0" height="1" width="1" alt="Quantcast"/>
</noscript>
<!-- End Quantcast tag -->

<?php
$cachefile = "../cache/footer.html";
// open the cache file "cache/home.html" for writing
$fp = fopen($cachefile, 'w');
// save the contents of output buffer to the file
fwrite($fp, ob_get_contents());
// close the file
fclose($fp);
// Send the output to the browser
ob_end_flush(
*/
?>
