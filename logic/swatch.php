<? 
include ("logic/global.php");
include ("logic/product_f.php");
conn();
getProd();
getSwatchColor();
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
		<link rel="stylesheet" type="text/css" href="http://mjtrends.r.worldssl.net/css/main4-min.css" />
		<title>MJTrends: <?=$color?> <?=$type?>, sample fabric</title>
		<meta Name="keywords" Content="<?=$meta_key?>">
		<meta Name="description" Content="<?=$meta_desc?>">

        <script type="text/javascript">
			var _gaq = _gaq || [];
			_gaq.push(['_setAccount', 'UA-36049628-1']);
			_gaq.push(['_trackPageview']);
		</script>

	</head>
	<body itemscope itemtype="http://schema.org/WebPage">
        <div class="content">
            <div class="header">
                <?include ('header.php')?>
				<script>
					 $(document).ready(function() {
						optionValues = new Array();
						$('.sinfo: select').change(function(){
							checkSwatch(this);
						});
					});
				</script>
            </div>
            <div class="main_content">
                <div class="left_tower"> </div>
                <div class="left_nav">
                    <?include ('cache/navigation.html')?>
                    <div class="hor_spacer"> </div>
                    <?include ('cache/top7.html')?>
                    <div class="hor_spacer"> </div>
                    <div class="hor_spacer3"> </div> 
					<?include ('modules/newsletter.html')?>
	                <div class="lnav_advert">
					    <div class="topborder"></div>
					    <!---
						<div class="topborder"></div>
						<div class="advbody">
					        <div class="advlb"></div>
					        <img width="160" height="217" src="images/ln_adv_img.gif" />
					        <div class="advrb"></div>
					    </div>
						--->
					    <div class="btmborder"></div>
					</div>
                </div>
                <div class="center product">
					<div class="breadcr">
					    <ul>
					        <li><a itemprop="breadcrumb" href="http://www.mjtrends.com">Homepage</a></li>
					        <li><a itemprop="breadcrumb" href="http://www.mjtrends.com/categories-swatches,swatches"><?=str_replace("-"," ",$row['cat'])?></a></li>
					        <li><?=$color." ".str_replace("-"," ",$row['cat']);?></li>
					    </ul>
					</div>

				<h2><span><?=$color?></span> <?=$type?></h2>
				<div class="prod swatch" itemscope itemtype="http://schema.org/Product">
				<form action="cart.php" method="post">
					<a href="javascript:popup('prod/<?=$row['big_url']?>', '350','350');"><img src="http://mjtrends.r.worldssl.net/images/product/<?=$row['invid']?>/<?=$row['img']?>" width="175" height="155" alt="" /></a>
					<h1 itemprop="name"><?=$row['title']?></h1>
					<div class="sinfo">
						<?=$list?>
						<div class="sale" itemprop="offers" itemscope itemtype="http://schema.org/Offer"><span>Our Price:</span>$<?=$ourPrice?></div>
							<ul>
								<?php if(strncmp(strtolower($_GET['color']), 'all', 3) == 0):?> 
									<li>Color: 
										<select id="color0" name="c0">
											<option value="All">All</option>
										</select>
									</li>
								<? else: for($i = 1; $i <= 5; $i++):?>
									<li>Color #<?=$i?>: <select id="color<?=$i?>" name="c<?=$i?>">
										<option>Select</option>
										<?php if($colorArr): foreach($colorArr as $val):?>
											<option value="<?=$val?>"><?=$val?></option>
										<?php endforeach; endif;?>
										</select>
									</li>
								<? endfor; endif;?>
							</ul>
							
						</div>

						<input name="invid" type="hidden" value="<?=$invid?>-<?=rand(0,999)?>">
						<input name="type" type="hidden" value="<?=$color?> swatches:">
						<input name="color" id="color" type="hidden" value="">
						<input name="quant" type="hidden" value="1">
						<input name="amt" type="hidden" id="remaining" value="<?=$amt?>">
						<input name="price" type="hidden" value="<?=$price?>">
						<input name="sale" type="hidden" value="<?=$sale?>">
						<input name="whole" type="hidden" value="<?=$whole?>">
						<input name="img" type="hidden" value="<?=$row['img_swatch']?>">
						<input name="cat" type="hidden" value="swatch">
						<input name="weight" type="hidden" value="<?=$weight?>">
						<input name="" type="image" src="images/add_to_cart.gif" onclick="swatch();">
					</form>
					<a href="javascript:popup('prod/<?=$row['big_url']?>', '350','350');"><img src="http://mjtrends.r.worldssl.net/images/zoom.gif"></a>
					<ul class="desc" itemprop="description">
		 				<?=$row['descr']?>
					</ul>
				</div>
            </div>
			<div class="right">
				<? include ("right.php");?>
			</div>
			<div class="content_btm"></div>
        </div>
    <?php include ('cache/footer.html'); ?>
	</body>
</html>

