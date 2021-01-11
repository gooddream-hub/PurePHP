<?php

include ("../logic/global.php");
conn();


//get categories
$query = "SELECT DISTINCT(CONCAT(type,',',cat)) AS cat FROM inven_mj WHERE type NOT LIKE('%.%') AND type !='Latex' AND cat !='Clearance' ORDER BY type ASC;";
$result = mysql_query($query); 

while ($row = mysql_fetch_assoc($result)){
	$category_array[] = $row['cat'];
}

//get products
$query = "SELECT DISTINCT(CONCAT(color, ',', type, ',', cat)) AS prod FROM inven_mj WHERE cat != 'Clearance' and active =1 ORDER BY type, color asc;";
$result = mysql_query($query); 

while ($row = mysql_fetch_assoc($result)){
	$product_array[] = $row['prod'];
}

//get tutorials
$query = "SELECT replace(title, ' ', '-') AS title FROM articles where id != 4";
$result = mysql_query($query); 

while ($row = mysql_fetch_assoc($result)){
	$tutorial_array[] = $row['title'];
}

//get index pages
$query = "SELECT distinct(concat('site-index-', letter)) AS pages FROM site_index ORDER BY letter ASC";
$result = mysql_query($query); 

while ($row = mysql_fetch_assoc($result)){
	$siteIndex_array[] = $row['pages'];
}

//get blog entry pages
$query = "SELECT CONCAT(DATE_FORMAT(post_date, '%Y/%m'), '/', post_name) AS pages FROM wp_posts where post_status = 'publish'";
$result = mysql_query($query); 

while ($row = mysql_fetch_assoc($result)){
	$blogEntry_array[] = $row['pages'];
}

//get threads from forum
$query = "SELECT distinct(thread_num), topic FROM forum where forum_type = 'vinyl' ";
$result = mysql_query($query); 

while ($row = mysql_fetch_assoc($result)){
	$forumThread_array[] = '/forum/thread/'.$row['thread_num'].'/'.$row['topic'];
}

$query = "SELECT distinct(thread_num), topic FROM forum where forum_type = 'latex' ";
$result = mysql_query($query); 

while ($row = mysql_fetch_assoc($result)){
	$forumThread_array[] = '/forum/thread/'.$row['thread_num'].'/'.$row['topic'];
}

// start the output buffer
ob_start(); 
?>
<?php echo '<?xml version="1.0" encoding="UTF-8"?>';?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
   <url>
      <loc>http://www.mjtrends.com/</loc>
   </url>
   
   <?php foreach($category_array as $val):?>
	   <url>
		  <loc>http://www.mjtrends.com/categories-<?=$val?></loc>
	   </url>
   <?endforeach;?>
   <url>
		<loc>http://www.mjtrends.com/gridview-latex-sheeting</loc>
   </url>

   <?php foreach($product_array as $val):?>
	   <url>
		  <loc>http://www.mjtrends.com/products.<?=$val?></loc>
	   </url>
   <?endforeach;?>
   
   <url>
	  <loc>http://www.mjtrends.com/articles.php</loc>
   </url>
   <?php foreach($tutorial_array as $url):?>
	   <url>
		  <loc>http://www.mjtrends.com/tutorial,<?=$url?></loc>
	   </url>
   <?endforeach;?>
	   <url>
		  <loc>http://www.mjtrends.com/sewing-pvc-vinyl-fabric-page1.php</loc>
	   </url>
	   <url>
		  <loc>http://www.mjtrends.com/sewing-pvc-vinyl-fabric-page2.php</loc>
	   </url>
	   <url>
		  <loc>http://www.mjtrends.com/sewing-pvc-vinyl-fabric-page3.php</loc>
	   </url>
	   <url>
		  <loc>http://www.mjtrends.com/sewing-pvc-vinyl-fabric-page4.php</loc>
	   </url>
	   <url>
		  <loc>http://www.mjtrends.com/sewing-pvc-vinyl-fabric-page5.php</loc>
	   </url>

   <?php foreach($siteIndex_array as $url):?>
	   <url>
		  <loc>http://www.mjtrends.com/<?=$url?></loc>
	   </url>
   <?endforeach;?>   
   
   <?php foreach($blogEntry_array as $url):?>
	   <url>
		  <loc>http://www.mjtrends.com/blog/<?=$url?></loc>
	   </url>
   <?endforeach;?>   
   
   <?php foreach($forumThread_array as $url):?>
	   <url>
		  <loc>http://www.mjtrends.com<?=$url?></loc>
	   </url>
   <?endforeach;?>
	<url>
		<loc>http://www.mjtrends.com/forum/index.php</loc>
	</url>
	   
</urlset> 


<?php
$cachefile = "../sitemap.xml";
// open the cache file "cache/home.html" for writing
$fp = fopen($cachefile, 'w');
// save the contents of output buffer to the file
fwrite($fp, ob_get_contents());
// close the file
fclose($fp);
// Send the output to the browser
ob_end_flush();
?>