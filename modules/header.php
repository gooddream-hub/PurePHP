<?php
$host = "localhost";
$uname="mjtrends_hedon";
$pass="mikeyry";
$database="mjtrends_mjtrends";
$connection = mysql_connect($host, $uname, $pass);
$result =mysql_select_db($database);

getArticles();
getBlog();
getForums();

function getArticles(){
	global $article;
	$query = "SELECT title FROM articles ORDER BY date DESC LIMIT 4";
	$result = mysql_query($query);

	while ($row = mysql_fetch_assoc($result)){
		$article[] = $row['title'];
	}
}

function getBlog(){
	global $blog;
	$query = "Select post_title, post_name, DATE_FORMAT(post_date, '%Y/%m') as link_date FROM wp_posts where post_type = 'post' AND post_status = 'publish' ORDER BY post_date DESC LIMIT 4";
	$result = mysql_query($query);

	while ($row = mysql_fetch_assoc($result)){
		$blog[] = array("title" => $row['post_title'], "link" => $row['link_date']."/".$row['post_name']);
	}
}

function getForums(){
	global $forum;

	$query = "SELECT topic, date_time, thread_num FROM forum WHERE forum_type = 'latex' AND sub_num = 0 ORDER BY date_time DESC LIMIT 2";
	$result = mysql_query($query);

	$query2 = "SELECT topic, date_time, thread_num FROM forum WHERE forum_type = 'vinyl' AND sub_num = 0 ORDER BY date_time DESC LIMIT 2";
	$result2 = mysql_query($query2);

	while ($row = mysql_fetch_assoc($result)){
		$forum[] = array("topic" => $row['topic'], "thread_num" => $row['thread_num']);
	}

	while ($row = mysql_fetch_assoc($result2)){
		$forum[] = array("topic" => $row['topic'], "thread_num" => $row['thread_num']);
	}
}

// start the output buffer
//ob_start();
?>
    <div class="logo_search">
		<ul class="nav_links">
			<li><a id="mcart" href="http://www.mjtrends.com/cart.php">Shopping Cart : ($<?=number_format($_SESSION['gtotal'],2,'.','')?>)</a></li>
			<li class="fst"><a href="http://www.mjtrends.com/tracking.php">Track Order</a></li>
		</ul>
        <div class="logo_text">
			<div class="shadow">
				<a class="logo" href="http://www.mjtrends.com"><img src="http://mjtrends.r.worldssl.net/images/MJTrends_logo3.gif" width="234" height="59" title="MJTrends: We help create.  Vinyl fabric, pvc, faux leather, snakeskins, and sewing notions."></a>
			</div>
			<form method="get" id="searchFrm" action="http://www.mjtrends.com/search.php">			
				<input type="text" name="search" value="Search" maxlength="60" />
				<a class="go" href="javascript:void(0)" onclick="document.getElementById('searchFrm').submit(); disableForm(event,this);" >Go</a>
			</form>	
		</div>
		
		<div class="t_wrap">
			<div class="t_nav">
				<div class="shadow_corner"></div>
				<div class="primary fst" onmouseover="subNav.showHide('tutorial','block'); subNav.setNav(true);" onmouseout="subNav.setNav(false); subNav.delayedHide('tutorial', 'nav1');"><h6 id="nav1" onmouseover="setClass(this, 'active');">Tutorials</h6>
					<div class="subnav" id="tutorial" style="display:none" onmouseover="subNav.setNav(true);" onmouseout="subNav.setNav(false); subNav.delayedHide('tutorial', 'nav1');">
						<div class="leftImg">
							<div class="rightImg">
								<div class="content">
									<ul>
										<?php foreach($article as $val):?>
											<li><a href="http://www.mjtrends.com/tutorial,<?=str_replace(" ","-",$val);?>"><?=$val?></a></li>
										<?php endforeach;?>
										<li class="all"><a href="http://www.mjtrends.com/articles.php">VIEW ALL ...</a></li>
									</ul>
								</div>
							</div>
						</div>
					</div>
				</div>		
				
				<div class="primary" onmouseover="subNav.showHide('forums','block'); subNav.setNav(true);" onmouseout="subNav.setNav(false); subNav.delayedHide('forums', 'nav2');"><h6 id="nav2" onmouseover="setClass(this, 'active');">Forums</h6>
					<div class="subnav" id="forums" style="display:none" onmouseover="subNav.setNav(true);" onmouseout="subNav.setNav(false); subNav.delayedHide('forums', 'nav2');">
						<div class="leftImg">
							<div class="rightImg">
								<div class="content">
									<ul>
										<?php foreach($forum as $val):?>
											<li><a href="http://www.mjtrends.com/forum/thread/<?=$val['thread_num']?>/<?=str_replace(' ','-',$val['topic'])?>"><?=$val['topic']?></a></li>
										<?php endforeach;?>
										<li class="all"><a href="http://www.mjtrends.com/forum">VIEW ALL ...</a></li>
									</ul>
								</div>
							</div>
						</div>
					</div>
				</div>		
			
				<div class="primary lst" onmouseover="subNav.showHide('article','block'); subNav.setNav(true);" onmouseout="subNav.setNav(false); subNav.delayedHide('article', 'nav3');"><h6 id="nav3" onmouseover="setClass(this, 'active');">Blog</h6>
					<div class="subnav" id="article" style="display:none" onmouseover="subNav.setNav(true);" onmouseout="subNav.setNav(false); subNav.delayedHide('article', 'nav3');">
						<div class="leftImg">
							<div class="rightImg">
								<div class="content">
									<ul>
										<?php foreach($blog as $val):?>
											<li><a href="http://www.mjtrends.com/blog/<?=$val['link']?>"><?=$val['title']?></a></li>
										<?php endforeach;?>
										<li class="all"><a href="http://www.mjtrends.com/blog">VIEW ALL ...</a></li>
									</ul>
								</div>
							</div>
						</div>
					</div>
				</div>		
			</div>
		</div>
	</div>
	
    <div class="main_nav">
        <div class="nav">
        	<div class="primary fst" onmouseover="subNav.showHide('fabrics','block'); subNav.setNav(true);" onmouseout="subNav.setNav(false); subNav.delayedHide('fabrics', 'nav4');">
        	    <h6 id="nav4" onmouseover="setClass(this, 'active');">Fabrics</h6>
        		<div class="subnav" id="fabrics" style="display:none" onmouseover="subNav.setNav(true);" onmouseout="subNav.setNav(false); subNav.delayedHide('fabrics', 'nav4');">
					<ul>
                        <li><a href="http://www.mjtrends.com/categories-Interfacing,Fabric">Interfacing</a></li>
                        <li><a href="http://www.mjtrends.com/categories-Faux-Fur,Fabric">Faux Fur</a></li>
                        <li><a href="http://www.mjtrends.com/categories-Faux-Leather,Fabric">Faux Leather</a></li>
						<li><a href="http://www.mjtrends.com/categories-Faux-Leather,Fabric">Fleece</a></li>
						<li><a href="http://www.mjtrends.com/categories-Patent-Vinyl,Fabric">Patent Vinyl</a></li>
                        <li><a href="http://www.mjtrends.com/categories-PVC,Fabric">Vinyl</a></li>
						<li><a href="http://www.mjtrends.com/categories-Stretch-PVC,Fabric">Stretch Vinyl</a></li>
                        <li><a href="http://www.mjtrends.com/categories-Snakeskin,Fabric">Snakeskin</a></li>
                        <li><a href="http://www.mjtrends.com/categories-upholstery,fabric">Upholstery</a></li>
					</ul>
				</div>
        	</div>

        	<div class="primary" onmouseover="subNav.showHide('latex','block'); subNav.setNav(true);" onmouseout="subNav.setNav(false); subNav.delayedHide('latex', 'nav5');"><h6 id="nav5" onmouseover="setClass(this, 'active');">Latex Sheeting</h6>
        		<div class="subnav" id="latex" style="display:none" onmouseover="subNav.setNav(true);" onmouseout="subNav.setNav(false); subNav.delayedHide('latex', 'nav5');">
					<ul class="start">
                        <li class="bold">Gauges</li>
						<li><a href="http://www.mjtrends.com/categories-.20mm,Latex-Sheeting">.20mm</a></li>
						<li><a href="http://www.mjtrends.com/categories-.30mm,Latex-Sheeting">.35mm</a></li>
						<li><a href="http://www.mjtrends.com/categories-.40mm,Latex-Sheeting">.40mm</a></li>
						<li><a href="http://www.mjtrends.com/categories-.50mm,Latex-Sheeting">.50mm</a></li>
						<li><a href="http://www.mjtrends.com/categories-.60mm,Latex-Sheeting">.60mm</a></li>
                        <li><a href="http://www.mjtrends.com/categories-.80mm,Latex-Sheeting">.80mm</a></li>
                        <li><a href="http://www.mjtrends.com/gridview-Latex-Sheeting">Grid View</a></li>
					</ul>
					<ul class="start">
                        <li class="bold">Accessories</li>
                        <li><a href="http://www.mjtrends.com/categories-Adhesive,Notions">Adhesives</a></li>
                        <li><a href="http://www.mjtrends.com/categories-Adhesive-Cleaner,Notions">Adhesive Cleaner</a></li>
                        <li><a href="http://www.mjtrends.com/categories-Cutting-Mat,Notions">Cutting Matts</a></li>
						 <li><a href="http://www.mjtrends.com/products.Clear,Fabric-tape,Notions">Double Sided Tape</a></li>
                        <li><a href="http://www.mjtrends.com/categories-Gloves,Notions">Gloves</a></li>
                        <li><a href="http://www.mjtrends.com/categories-Latex-Shine,Notions">Latex Shine</a></li>
                        <li><a href="http://www.mjtrends.com/categories-Rotary-Cutter,Notions">Rotary Cutters</a></li>
                        <li><a href="http://www.mjtrends.com/categories-Rotary-Cutter,Notions">Rotary Blades</a></li>
                        <li><a href="http://www.mjtrends.com/categories-Latex-Seam-Roller,Notions">Seam Roller</a></li>
					</ul>
                    <ul>
                        <li class="bold">Kits</li>
                        <li><a href="http://www.mjtrends.com/kit.Latex-Starter">Beginner</a></li>
                        <li><a href="http://www.mjtrends.com/kit.Latex-Advanced">Advanced</a></li>
                        <li><a href="http://www.mjtrends.com/kit.Latex-Pro">Expert</a></li>
                    </ul>
				</div>
        	</div>

        	<div class="primary" onmouseover="subNav.showHide('notions','block'); subNav.setNav(true);" onmouseout="subNav.setNav(false); subNav.delayedHide('notions', 'nav6');"><h6 id="nav6" onmouseover="setClass(this, 'active');">Notions</h6>
        		<div class="subnav" id="notions" style="display:none" onmouseover="subNav.setNav(true);" onmouseout="subNav.setNav(false); subNav.delayedHide('notions', 'nav6');">
					<ul class="start">
                        <li class="bold">Corsetry / Lingerie</li>
                        <li><a href="http://www.mjtrends.com/categories-Bra-hooks,Notions">Bra Hooks</a></li> 
                        <li><a href="http://www.mjtrends.com/categories-Bra-slides,Notions">Bra Slides</a></li> 
                        <li><a href="http://www.mjtrends.com/categories-Busks,Notions">Corset Busks</a></li> 
                        <li><a href="http://www.mjtrends.com/categories-Eyelets-and-Snaps,Notions">Eyelets & Snaps</a></li> 
                        <li><a href="http://www.mjtrends.com/categories-Garter-clip,Notions">Garter Clips</a></li> 
                        <li><a href="http://www.mjtrends.com/categories-Hook-Eye-Tape,Notions">Hook & Eye Tape</a></li> 
                        <li><a href="http://www.mjtrends.com/categories-Spiral-boning,Notions">Spiral Boning</a></li> 
                        <li><a href="http://www.mjtrends.com/categories-Polyester-boning,Notions">Polyester Boning</a></li>
                        <li><a href="http://www.mjtrends.com/categories-Polyester-boning-precut,Notions">Polyester Boning Precut</a></li> 
                        <li class="bold">Cutting</li>
                        <li><a href="http://www.mjtrends.com/categories-Cutting-Mat,Notions">Cutting Mats</a></li> 
                        <li><a href="http://www.mjtrends.com/categories-Rotary-Cutter,Notions">Rotary Cutters</a></li>
                        <li><a href="http://www.mjtrends.com/categories-Rotary-Cutter,Notions">Rotary Blades</a></li>
                        <li><a href="http://www.mjtrends.com/categories-Seam-ripper,Notions">Seam Rippers</a></li>
                        <li><a href="http://www.mjtrends.com/categories-Scissors,Notions">Scissors</a></li>
                       <li class="bold">Measuring / Pattern Making</li>
                       <li><a href="http://www.mjtrends.com/categories-French-Curve,Notions">French Curves</a></li> 
                       <li><a href="http://www.mjtrends.com/categories-Tape-measure,Notions">Measuring Tape</a></li> 
                       <li><a href="http://www.mjtrends.com/categories-Tailors-Chalk,Notions">Tailors Chalk</a></li> 
                    </ul>
                    <ul class="start">
                        <li class="bold">Latex Accessories</li>
                        <li><a href="http://www.mjtrends.com/categories-Adhesive,Notions">Adhesives</a></li>
                        <li><a href="http://www.mjtrends.com/categories-Adhesive-Cleaner,Notions">Adhesive Cleaner</a></li>
                        <li><a href="http://www.mjtrends.com/categories-Gloves,Notions">Gloves</a></li>
                        <li><a href="http://www.mjtrends.com/categories-Latex-Shine,Notions">Latex Shine</a></li>
                        <li><a href="http://www.mjtrends.com/categories-Latex-Seam-Roller,Notions">Seam Roller</a></li>
                        <li class="bold">Vinyl Accessories</li>
                         <li><a href="http://www.mjtrends.com/products.Vinyl,Adhesive,Notions">Vinyl Adhesive</a></li> 
                         <li><a href="http://www.mjtrends.com/products.Roller,Presser-Foot,Notions">Roller Pressor Foot</a></li> 
                         <li><a href="http://www.mjtrends.com/categories-Presser-Foot,Notions">Teflon Pressor Foot</a></li> 
                        <li class="bold">Kits</li>
                        <li><a href="http://www.mjtrends.com/kit.Vinyl-Starter">Vinyl Starter Kit</a></li> 
                        <li><a href="http://www.mjtrends.com/kit.Vinyl-Advanced">Vinyl Advanced Kit</a></li> 
                        <li><a href="http://www.mjtrends.com/kit.Vinyl-Pro">Vinyl Pro Kit</a></li> 
                        <li><a href="http://www.mjtrends.com/kit.Latex-Starter">Latex Starter Kit</a></li> 
                        <li><a href="http://www.mjtrends.com/kit.Latex-Advanced">Latex Advanced Kit</a></li> 
                        <li><a href="http://www.mjtrends.com/kit.Latex-Pro">Latex Pro Kit</a></li> 
                        <li><a href="http://www.mjtrends.com/kit.Lingerie">Lingerie Kit</a></li> 
                        <li><a href="http://www.mjtrends.com/kit.French-Curve-Pro-Pack">French Curve Kit</a></li>       
                        <li><a href="http://www.mjtrends.com/categories-kits,DIY">View All</a></li>       
                    </ul>
                    <ul>
                        <li class="bold">Waists / Closures</li>
                        <li><a href="http://www.mjtrends.com/categories-Drawstring,Notions">Drawstring</a></li>                      
                        <li><a href="http://www.mjtrends.com/categories-Elastic,Notions">Elastic</a></li>                      
                        <li><a href="http://www.mjtrends.com/categories-Frog-closure,Notions">Frog Closures</a></li>                      
                        <li><a href="http://www.mjtrends.com/categories-Cord-lock,Notions">Cord Locks</a></li>                      
                        <li><a href="http://www.mjtrends.com/categories-Cord-Ends,Notions">Cord Ends</a></li>                      
                        <li><a href="http://www.mjtrends.com/categories-Velcro,Notions">Velcro</a></li>                      
                        <li class="bold">Zippers</li>
                        <li><a href="http://www.mjtrends.com/categories-aluminum-non-separating,Zippers">Aluminum Non-Separating</a></li>   
                        <li><a href="http://www.mjtrends.com/categories-aluminum-separating,Zippers">Aluminum Separating</a></li>                         
						<li><a href="http://www.mjtrends.com/categories-brass-non-separating,Zippers">Brass Non-Separating</a></li> 						
						<li><a href="http://www.mjtrends.com/categories-brass-separating,Zippers">Brass Separating</a></li>
						<li><a href="http://www.mjtrends.com/categories-hidden,Zippers">Hidden / Concealed</a></li>
						<li><a href="http://www.mjtrends.com/categories-nylon-non-separating,Zippers">Nylon Non-Separating</a></li>
						<li><a href="http://www.mjtrends.com/categories-nylon-separating,Zippers">Nylon Separating</a></li>	
						<li><a href="http://www.mjtrends.com/categories-plastic-non-separating,Zippers">Plastic Non-Separating</a></li>	
						<li><a href="http://www.mjtrends.com/categories-plastic-separating,Zippers">Plastic Separating</a></li>	
						<li><a href="http://www.mjtrends.com/categories-3-way,Zippers">3-way</a></li>
						<li><a href="http://www.mjtrends.com/cache/cat/zipper-types.html">View All</a></li>
                        <li class="bold">Miscellaneous</li>                          
                        <li><a href="http://www.mjtrends.com/products.Clear,Fabric-tape,Notions">Double Sided Tape</a></li>
                        <li><a href="http://www.mjtrends.com/products.Clear,Fabric-Glue,Notions">Fabric Glue</a></li>
                        <li><a href="http://www.mjtrends.com/categories-Fray-Check,Notions">Fray Check</a></li>
                        <li><a href="http://www.mjtrends.com/categories-Spikes,Notions">Spikes</a></li>
                        <li><a href="http://www.mjtrends.com/categories-Sewing-Needles,Notions">Twin needles</a></li>
                        <li><a href="http://www.mjtrends.com/categories-Tweezers,Notions">Tweezers</a></li>
                    </ul>
				</div>
        	</div>

        	<div class="primary" onmouseover="subNav.showHide('sale','block'); subNav.setNav(true);" onmouseout="subNav.setNav(false); subNav.delayedHide('sale', 'nav7');"><h6 id="nav7" onmouseover="setClass(this, 'active');">Sale / Clearance</h6>
        		<div class="subnav" id="sale" style="display:none" onmouseover="subNav.setNav(true);" onmouseout="subNav.setNav(false); subNav.delayedHide('sale', 'nav7');">
					<ul>
						<li><a href="http://www.mjtrends.com/categories-sale,sale">Sale Items</a></li>
						<li><a href="http://www.mjtrends.com/categories-clearance,sale">Clearance Items (limited quantity)</a></li>
					</ul>
				</div>
        	</div>

        	<div class="primary" onmouseover="subNav.showHide('upholstery','block'); subNav.setNav(true);" onmouseout="subNav.setNav(false); subNav.delayedHide('upholstery', 'nav8');"><h6 id="nav8" onmouseover="setClass(this, 'active');">Patterns</h6>
        		<div class="subnav" id="upholstery" style="display:none" onmouseover="subNav.setNav(true);" onmouseout="subNav.setNav(false); subNav.delayedHide('upholstery', 'nav8');">
					<ul class="start">
                        <li class="bold">Womens</li>
                        <li><a href="http://www.MJTrends.com/pattern.php">Mini Skirt</a></li>
                        <li><a href="http://www.MJTrends.com/pattern.php">Ruffle Skirt</a></li>
						<li><a href="http://www.MJTrends.com/pattern.php">Pencil Skirt</a></li>
                        <li><a href="http://www.MJTrends.com/pattern.php">Short Shorts</a></li>
					</ul>
                    <ul>
                        <li class="bold">Mens</li>
                        <li><a href="http://www.MJTrends.com/pattern.php">Bike Short</a></li>
                        <li><a href="http://www.MJTrends.com/pattern.php">Boxer Brief</a></li>
                        <li><a href="http://www.MJTrends.com/pattern.php">Short Sleeve Shirt</a></li>
                        <li><a href="http://www.MJTrends.com/pattern.php">Tank top</a></li>
                    </ul>
				</div>
        	</div>
        	
			<div class="primary" onmouseover="subNav.showHide('swatch','block'); subNav.setNav(true);" onmouseout="subNav.setNav(false); subNav.delayedHide('swatch', 'nav9');"><h6 id="nav9" onmouseover="setClass(this, 'active');">Swatches</h6>
        		<div class="subnav" id="swatch" style="display:none" onmouseover="subNav.setNav(true);" onmouseout="subNav.setNav(false); subNav.delayedHide('swatches', 'nav9');">
					<ul>
						<li><a href="http://www.mjtrends.com/products.Latex-sheeting,Swatches,Swatches">Latex Sheeting</a></li>
						<li><a href="http://www.mjtrends.com/products.Faux-Leather,Swatches,Swatches">Matte Vinyl / Faux Leather</a></li>
						<li><a href="http://www.mjtrends.com/products.Oriental-Brocade,Swatches,Swatches">Oriental Brocade</a></li>
						<li><a href="http://www.mjtrends.com/products.Patent-Vinyl,Swatches,Swatches">Patent Vinyl</a></li>
					</ul>
					<ul>
						<li><a href="http://www.mjtrends.com/products.Pinstripe,Swatches,Swatches">Pinstripe</a></li>
						<li><a href="http://www.mjtrends.com/products.PVC,Swatches,Swatches">PVC / PU vinyl</a></li>
						<li><a href="http://www.mjtrends.com/products.Snakeskin,Swatches,Swatches">Snakeskin</a></li>
						<li><a href="http://www.mjtrends.com/products.Stretch-PVC,Swatches,Swatches">Stretch PVC Vinyl</a></li>
						<li><a href="http://www.mjtrends.com/products.Velvet,Swatches,Swatches">Velvet</a></li>
					</ul>
				</div>
        	</div>
			
            <div class="primary lst" onmouseover="subNav.showHide('wholesale','block'); subNav.setNav(true);" onmouseout="subNav.setNav(false); subNav.delayedHide('wholesale', 'nav10');"><h6 id="nav10" onmouseover="setClass(this, 'active');">DIY Jewelry</h6>
        		<div class="subnav subnav_end" id="wholesale" style="display:none" onmouseover="subNav.setNav(true);" onmouseout="subNav.setNav(false); subNav.delayedHide('wholesale', 'nav10');">
        			<div class="leftImg">
        				<div class="rightImg">
        					<div class="content">
        						<ul>
        							<li><a href="http://www.mjtrends.com/categories-Chain-maille,DIY-jewelry">Chain Maille</a></li>
        						</ul>
        					</div>
        				</div>
        			</div>
        		</div>
        	</div>
        </div>

    </div>
    <div class="hor_spacer"></div>
    <div class="hor_spacer2">Unique fabrics and notions</div>

</div>
<script>
if(navigator.userAgent.indexOf("Opera") != -1) {
	document.getElementById('nav4').style.width = '51px';
} 
</script>
<?php
/*
$cachefile = "../cache/header.html";
// open the cache file "cache/home.html" for writing
$fp = fopen($cachefile, 'w');
// save the contents of output buffer to the file
fwrite($fp, ob_get_contents());
// close the file
fclose($fp);
// Send the output to the browser
ob_end_flush();
*/
?>

