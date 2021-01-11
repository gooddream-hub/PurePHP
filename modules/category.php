<?
include("../config/config.php");
include ("../logic/global.php");
$mysqli = mysqliConn();
include ("../logic/category_f.php");
include ("../logic/Minify_HTML.class.php");


getDesc();
if (!empty($_GET['trait'])) {
    $colors = get_zippers_colors();
}

ob_start();

?>

<!doctype html>
<html>
<head>
    <title>MJTrends: <?=$prods[0]['type']?> <?=$prods[0]['cat']?></title>
    <meta charset="UTF-8">
    <?if($_GET['type'] == 'Spikes'):?>
        <link rel="stylesheet" property='stylesheet' type="text/css" href="<?php echo $config->secondary_css; ?>" />
    <?endif;?>
    <meta name="description" content="<?=$_GET['type']?>: <?=$desc?>" />
    <meta name="keywords" content="<?=$prods[0]['type']?>, <?=$prods[0]['cat']?>" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="<?=$config->bootstrap_css ?>">
    <link rel="stylesheet" type="text/css" href="<?=$config->colorbox_css ?>"/>
    <link rel="stylesheet" type="text/css" href="<?=$config->forum_css ?>"/>
    <link rel="stylesheet" type="text/css" href="<?=$config->jquery_ui_css; ?>">
</head>
<body>
<header>
    <div class="header">
        <? include("../cache/header-responsive.html"); ?>

        <div class="mainbody">
            <div class="container">
                <div class="row to-home-page">
                    <a href="<?=$config->domain?>">Homepage</a> <span>&raquo;</span> <?if($title!=""):?> <?=$title?> <?else:?> <?=str_replace('-', ' ', $prods[0]['type'])?><?endif;?>
                </div>
                <div class="orderMn">
                    <p class="category_descr"><?=$desc?></p>
                </div>

            <div class="filter-wrapper">
                <div class="category">
                    <?php include_once('category-fabrics.php');?>
                </div><!--category-->

                <div class="grid-view">
                    <?if($_GET['cat'] == 'Fabric' || $_GET['cat'] == 'Latex-Sheeting'):?>
                        <a href="gridview-<?=$_GET['type']?>">Grid View</a>
                    <?endif;?>
                </div>
            </div>

            <!--row-1-->
            <div class="row-1">
                <?php
                $spikes_end = '';
                include_once 'category-spikes.php';
                ?>
                <?=$noprod?>
            </div><!--row-1-->

            <?=$noprod?>

            <?if($prods):?>
                <div class="row-1">
                    <div class="filterCategories"></div><!-- DON'T remove. Used by function filterCategories at logic/function.js-->
                    <?php include_once('category-generic.php');?>
                    <?php include_once('category-kits.php');?>
                    <?php include_once('category-grouped.php');?>
                    <?php include_once('category-zippers.php');?>
                </div>
            <?endif;?>
            <?php echo $spikes_end; ?>


            </div>
            <!--container-->
        </div>
        <!--mainbody-->

        <!--footer -->
        <?php  include ('../cache/footer-responsive.html'); ?>
        <!-- footer END-->
        <script src="<?=$config->jquery_js?>"></script>
        <script src="<?=$config->custom_js; ?>"></script>
        <script src="<?=$config->functions_js?>"></script>
        <script src="<?=$config->forum_js?>"></script>
        <script src="<?=$config->colorbox_js?>"></script>
        <script src="<?=$config->boostrap_js?>"></script>
        <script src="<?=$config->jquery_ui_js; ?>"></script>
        <?if($_GET['type'] == 'Spikes'):?>
            <script src="http://mjtrends-672530.c.cdn77.org/js/spikes.min.js"></script>
        <?endif;?>
        <script>
            $( document ).ready(function() {
                $.post( "<?=$config->domain?>/logic/ajaxController.php", {function: 'setBounce'});
            });
        </script>

        <!--script for category-grouped.php-->
        <script>
            $(document).ready(function () {
                category_init();
            });
        </script>

</body>
</html>

<?php
$cachefile = "../cache/cat/".strtolower($_GET['type']).".html";
if (!empty($_GET['trait'])) {
    $cachefile = "../cache/cat/".strtolower($_GET['trait']).'-'.strtolower($_GET['type']).".html";
}
// open the cache file "cache/home.html" for writing
$fp = fopen($cachefile, 'w');
// save the contents of output buffer to the file
fwrite($fp, Minify_HTML::minify(ob_get_contents()));
// close the file
fclose($fp);
// Send the output to the browser
ob_end_flush();
?>
