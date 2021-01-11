<?
include ("logic/global.php");
include ("logic/product_f.php");
conn();
getProd();
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
		<link rel="stylesheet" type="text/css" href="http://mjtrends.r.worldssl.net/css/main4-min.css" />
		<title>MJTrends: Wholesale fabric</title>
		<meta Name="keywords" Content="<?=$meta_key?>">
		<meta Name="description" Content="<?=$meta_desc?>">

        <script type="text/javascript">
			var _gaq = _gaq || [];
			_gaq.push(['_setAccount', 'UA-36049628-1']);
			_gaq.push(['_trackPageview']);
		</script>
		
	</head>
	<body>
        <div class="content">
            <div class="header">
                <?include ('header.php')?>
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
					        <li><a href="http://www.mjtrends.com">Homepage</a></li>
					        <li>Wholesale</li>
					    </ul>
					</div>
					
	               <h2><span>Wholesale Fabrics</h2>
				   <p>Wholesale prices are offered on purchases of 50 yard bolts of select fabrics. The wholesale section is under construction.
				   Until the wholesale section is finished, please send wholesale inquiries to: <a href="mailto:sales@mjtrends.com">sales@mjtrends.com</a>.</p>
				   <br><p>Thank you for your patience!</p>
				</div>	
				<div class="right">
					<? include ("right.php");?>
				</div>
                <div class="content_btm"></div>
            </div>
        </div>
        <?php include ('cache/footer.html'); ?>
	</body>
</html>