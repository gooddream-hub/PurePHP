<?php
include("../config/config.php");
include("../logic/global.php");
include("../logic/product.class.php");
include("../logic/Minify_HTML.class.php");

$product = new Product;
$product->mysqli = mysqliConn();
$product->getKitItems();
$product->getTutorials();
$product->getPosts();
ob_start();
?>



<!doctype html>
<html>
<head>
    <title>MJTrends: <?= $product->details['title'] ?></title>
    <meta charset="UTF-8">
    <meta name="Keywords" content="<?php $meta_key ?>"/>
    <meta name="Description" content="<?php $meta_desc ?>"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="<?=$config->bootstrap_css ?>">
    <link rel="stylesheet" type="text/css" href="<?=$config->colorbox_css ?>"/>
    <link rel="stylesheet" type="text/css" href="<?=$config->forum_css ?>"/>
    <link rel="stylesheet" type="text/css" href="<?=$config->jquery_ui_css; ?>">
</head>
<body itemscope itemtype="http://schema.org/WebPage">
<header>
    <div class="header">
        <?php include("../cache/header-responsive.html"); ?>
        <div class="mainbody">
            <div class="container">
                <div class="row to-home-page">
                    <a href="<?=$config->domain?>">Homepage</a> &raquo;<a title="Kits"
                                                       href="categories-kits,DIY"> Kits</a> &raquo; <?php echo $_GET['name']; ?> Kit
                </div>
                <div id="prodNarrow">
                    <h2 itemprop="name" class="hidden-md hidden-lg"><?=$_GET['name']?> Kit</h2>
                </div>
                <div class="row main-product_myclass">
                    <div class="product-gallery col-md-6 col-sm-6 col-xs-12">
                        <div class="prod" itemscope itemtype="http://schema.org/Product">
                            <meta itemprop="color" content="<? $product->details['color'] ?>"/>
                            <div class="prodLeft prodLeft_myclass" id="prodImages">
                                <?php $style_string = "marginright_myclass"; ?>
                                <?php $countProductArr = count($product->details['img']); ?>

                                <? $i = 0; foreach ($product->details['img'] as $row): ?>
                                    <? if ($i == 0): ?>
                                        <div id="primaryImg">
                                            <a class="imgPop"
                                               href="<?=$config->CDN?>images/product/<?= $product->details['invid'] ?>/<?= $row['path'] ?>_924x699.jpg"
                                               title="<?= $row['alt'] ?>">
                                                <img
                                                    src="<?=$config->CDN?>images/product/<?= $product->details['invid'] ?>/<?= $row['path'] ?>_370x280.jpg"
                                                    alt="<?= $row['alt'] ?>" itemprop="image">
                                            </a>
                                        </div>
                                    <? endif;
                                    $i++ ?>
                                    <?php $countProductArr ?>
                                    <div class="imgPop_mystyle <?php echo $style_string; ?>">
                                        <a class="imgPop"
                                           href="<?=$config->CDN?>images/product/<?= $product->details['invid'] ?>/<?= $row['path'] ?>_924x699.jpg"
                                           title="<?= $row['alt'] ?>">
                                            <img class="imgThumb"
                                                 src="<?=$config->CDN?>images/product/<?= $product->details['invid'] ?>/<?= $row['path'] ?>_115x87.jpg"
                                                 data-src="<?=$config->CDN?>images/product/<?= $product->details['invid'] ?>/<?= $row['path'] ?>_"
                                                 width="115" height="87" alt="<?= $row['alt'] ?> thumbnail image.">
                                        </a>
                                    </div>
                                <? endforeach; ?>
                                <div id="prodVids">
                                    <? foreach ($product->details['video'] as $row): ?>
                                        <img class="vidThumb"
                                             src="<?=$config->CDN?>images/prod/<?= $row['thumb'] ?>_115x87.jpg"
                                             width="115" height="87" alt="<?= $row['alt'] ?>">
                                    <? endforeach; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!--product-gallery-->

                    <div class="product-descript col-md-6 col-sm-6 col-xs-12">

                        <div class="prodRight">
                            <h2 itemprop="name"
                                id="prodTitle"><?=$_GET['name']?> Kit
                            </h2>
                            <!-- price and add to cart -->
                            <div class="price_addtocart_myclass">
                                <div class="productPrice">
                                    <meta itemprop="priceCurrency" content="USD"/>
                                    <span class="strike">$<?=$product->kitDetails['total']?></span>
                                    <span itemprop="offers" itemscope itemtype="http://schema.org/Offer">
                                        <span itemprop="price">
                                            $<?=$product->kitDetails['total'] - $product->kitDetails['discount']?> as a kit
                                        </span>
                                    </span>
                                </div>

                                <div class="formWrapper">
                                    <form class="prod-form" action="cart.php" method="post"
                                          onSubmit="return checkKit();" id="prodFrm">
                                        <p class="quant">Quantity: <input id="quant" name="quant" <?= $product->details['clearance'] ?> type="text" size="3"><span>
                                                <b>In Stock: </b><span class="inv"></span></span></p>

                                        <div id="err" class="red" style="display:none">ERROR: whole numbers only</div>
                                        <div class="btnWrap">
                                            <button id="add" class="btn redBtn" type="submit">Add to
                                                Cart
                                            </button>
                                            <input type="hidden" id="grid" name="grid" value="<?=$product->getKitGridValue($_GET['name']);?>" />
                                            <input name="amt" type="hidden" id="remaining" value="<?=$product->details['invamount']?>">
                                            <input name="kit" type="hidden" value="<?=str_replace(" ","_",$_GET['name']);?>">
                                        </div>
                                    </form>
                                </div>
                            </div>

                            <div id="shareHlp" class="shareText"
                                 title="Email us a link to your shared content and we'll apply 5 points to your account">
                                Share and <b>earn points</b> for coupons and more!
                            </div>

                            <div class="social socialLinks">
                                <!-- AddThis Button BEGIN -->
                                <div class="addthis_sharing_toolbox addthis_default_style addthis_20x20_style social_my_class">
                                    <a class="addthis_button_facebook"></a>
                                    <a class="addthis_button_twitter"></a>
                                    <a class="addthis_button_pinterest_share"></a>
                                    <a class="addthis_button_google_plusone_share"></a>
                                </div>
                                <script
                                    type="text/javascript">var addthis_config = {"data_track_addressbar": true};</script>
                                <script type="text/javascript"
                                        src="//s7.addthis.com/js/300/addthis_widget.js#pubid=ra-527da9c76608df65"></script>
                                <!-- AddThis Button END -->
                            </div>

                            <div class="separator_myclass">
                                <h3 class="features-head features-head-my_class">Items Included:</h3>
                                <ul id="prodFeatures">
                                    <?foreach($product->kitItems as $row):?>
                                        <li><?=$row?></li>
                                    <?endforeach;?>
                                    <li> ** Limit 1 kit coupon per order.  Kit coupon cannot be combined with other coupons.**</li>
                                    <li><b>Save $<?=$product->kitDetails['discount']?> over buying each item individually!</b></li>
                                </ul>
                            </div>
                            <div class="separator_myclass">
                                <h3 class="descript-head descript-head-my_class">Description:</h3>

                                <div class="descrip-text descrip-text_myclass" itemprop="description">
                                    <?=$product->kitDetails['descrip']?>
                                </div>
                            </div>
                        </div>


                    </div>
                    <!--product-descript-->
                </div>
                <!--row-->
                <!-- Tutorials -->
                <div class="row tutorials-row-my_class">
                    <div class="col-md-6 col-sm-6 col-xs-12 prodTutorials_myclass">
                        <div class="prodWide">
                            <div id="prodTutorials">

                                <?php if (!empty($product->tutorials)): ?>
                                    <h3 class="descript-head-my_class">Tutorials related
                                        to <?= $product->details['title'] ?>:</h3>
                                    <div id="prodTuts">
                                        <? foreach ($product->tutorials as $row): ?>
                                            <div class="vidWrap">
                                                <a href="<?= $row['vid_url'] ?>" onclick="return false;" title="<?= $row['title'] ?>"
                                                   class="vidPop vidPop_myclass">
                                                    <img
                                                        src="<?=$config->CDN?>images/video/<?= $row['thumb'] ?>_115x87.jpg"
                                                        alt="<?= $row['title'] ?>">

                                                    <p class="title"><?= $row['title'] ?></p>
                                                </a>
                                            </div>
                                        <? endforeach; ?>
                                    </div>
                                <? endif; ?>
                            </div>
                        </div>
                    </div>
                    <div class="related-formum_myclass col-md-6 col-sm-6 col-xs-12">
                        <div class="prodWide">
                            <div id="prodPosts"<? if (empty($product->tutorials)): ?> style="width:100%"<? endif; ?>>
                                <? if (!empty($product->posts)): ?>
                                    <h3 class="descript-head-my_class">Related forum posts:</h3>
                                    <div id="prodPostContainer">
                                        <? foreach ($product->posts as $row): ?>
                                            <h4><a class="a_myclass"
                                                   href="/forum/thread/<?= $row['thread_num'] ?>/<?= str_replace(' ', '-', $row['topic']) ?>"><?= $row['topic'] ?></a>
                                            </h4>
                                            <p class="meta meta_myclass">Posted
                                                on: <?= date('M jS Y g:i:s a', strtotime($row['date_time'])) ?></p>
                                            <p><?= truncate(strip_tags($row['discussion']), 70) ?></p>
                                        <? endforeach; ?>
                                    </div>
                                <? endif; ?>
                            </div>
                        </div>
                    </div>

                </div>
                <!--row-->
                <!-- end:Tutorials -->
                <div class="row">
                    <?php if (count($product->details['img']) < 7) $prodMyClass = 'prodWide_myclass'; ?>
                    <div class="col-md-12 prodWide cartRecommend <?php echo $prodMyClass; ?>">
                        <div class="recomend_myclass"></div>
                        <h3 id="alsoRec" class="descript-head-my_class">We also recommend:</h3>

                        <div class="slider">
                            <ul class="recommended-products bxslider">
                            </ul>
                        </div>
                    </div>
                </div>
                <!--row-->

                <div class="row">
                    <div class="seals">
                        <!-- Begin Official PayPal Seal -->

                        <img src="<?=$config->CDN?>images/paypal-verification.gif"
                             alt="Official PayPal Seal">
                        <img src="<?=$config->CDN?>images/authorize.gif"
                             alt="Authorize.Net Verified Merchant">

                        <!-- End Official PayPal Seal -->

                        <!-- (c) 2005, 2012. Authorize.Net is a registered trademark of CyberSource Corporation -->
                        <div class="AuthorizeNetSeal">

                        </div>
                    </div>

                    <div class="modal fade" id="cartModal" style="display: none;" tabindex="-1" role="dialog">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                    <img class="ok" src="<?=$config->domain?>/images/ok.png">
                                    <h4 class="modal-title"><?=str_replace('-', ' ', $product->details['color'])?> <?=str_replace('-', ' ', $product->details['type'])?> has been added to your cart.</h4>
                                </div>
                                <div class="modal-body">
                                    <div class="cartItem">
                                        <div class="itemLeft">
                                            <img src="<?=$config->CDN?>images/product/<?=$product->details['invid']?>/<?=$product->details['img'][0]['path']?>_115x87.jpg" class="cartItemImg" width="115" height="87">
                                            <div class="descrip">
                                                <p id="cartTitle"><?=$product->details['title']?></p>
                                                <p>Quantity: <span id="CMquant"></span></p>
                                                <p>Subtotal: <span id="CMsubtotal"></span></p>
                                            </div>
                                        </div>
                                        <div class="itemRight">
                                            <a href="cart.php" class="btn redBtn">Checkout</a>
                                            <a class="whtBtn btn btn-default" data-dismiss="modal">Keep Shopping</a>
                                        </div>
                                    </div>
                                    <div class="cartRecommend">
                                        <h6>Recommended items you may find useful:</h6>
                                        <div class="slider">
                                            <ul class="cart-recommended-products bxslider">
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <a class="btn redBtn redBtn_myclass" href="cart.php">Checkout</a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="modal fade" id="stockLimitDialog" style="display: none;" tabindex="-1" role="dialog">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                    <h4 class="modal-title">Limited Stock</h4>
                                </div>
                                <div class="modal-body">
                                    <p>There are only <span class="inv"></span> left in stock.</p>
                                    <p>Please select <span class="inv"></span> or less.</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div style="display:none">
                        <div id="swatch_help">
                            <div>
                                <h2>Why order a swatch:</h2>
                                <br/>We hold up each fabric in natural sunlight, twist it, turn it, and then photograph
                                it and describe it with the eloquence of a Shakespearean writer. Regardless, words and
                                images pale in comparison to holding a sample in your hand to ensure it will work for
                                you.
                                <br/><br/>Plus our lawyers say: Variations in monitor or mobile color settings are vary
                                widely from device to device. Therefore we strongly encourage you to order a swatch
                                before purchasing.
                            </div>
                        </div>
                    </div>
                </div>
                <!--row-->
            </div>
            <!--container-->
        </div>
        <!--mainbody-->
        <!--footer -->
        <?php include('../cache/footer-responsive.html'); ?>
        <!-- footer END-->


        <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->

        <!-- Include all compiled plugins (below), or include individual files as needed -->

        <script src="<?= $config->jquery_js ?>"></script>
        <script src="<?= $config->bxslider_js ?>"></script>
        <script src="<?= $config->functions_js ?>"></script>
        <script src="<?= $config->forum_js ?>"></script>
        <script src="<?= $config->colorbox_js ?>"></script>
        <script src="<?= $config->boostrap_js ?>"></script>
        <script src="<?= $config->jquery_ui_js; ?>"></script>
        <script src="<?= $config->custom_js ?>"></script>
        <script>
            $(document).ready(function () {
                $.post( "<?=$config->domain?>logic/ajaxController.php?kit=<?=$_GET['name']?>", {function: 'getKitAmount'},
                    function( data ) {
                        $("#remaining").val( data );
                        $(".inv").html(data);
                    });

                $.post( "<?=$config->domain?>logic/ajaxController.php", {function: 'getKitGridValue', kit: '<?=$_GET["name"]?>'},
                    function( data ) {
                        $("#grid").val( data );
                    });

                (function downloadJSAtOnload() {
                    var products = '<?php
                        $prodArray = $product->getKitRecommended($_GET["name"]);
                        foreach($prodArray as $row){
                            echo $row;
                        }
                    ?>';
                    $('.recommended-products')
                        .append(products)
                        .trigger('data-loaded.recommended');

                    $('.cart-recommended-products')
                        .append(products)
                        .trigger('data-loaded.recommended');
                })();
            });
        </script>


        <script>
             var _gaq = _gaq || [];
            _gaq.push(['_setAccount', 'UA-36049628-1']);
            _gaq.push(['_trackPageview']);
        </script>



        <script>
            (function ($) {
                $('#shareHlp').tooltip({
                    hide: {duration: 1500}
                });
                if( $( window).width() > 600 ) {
                    $(".imgPop").colorbox({rel: 'imgPop', transition: "none", width: "75%", height: "75%", opacity: .7});
                    $(".vidPop").colorbox({
                        iframe: true,
                        rel: 'vidPop',
                        transition: "none",
                        width: "75%",
                        height: "75%",
                        opacity: .7
                    });

                }
                else{
                    $(".imgPop").colorbox({rel: 'imgPop', transition: "none", width: "90%", height: "60%", opacity: .7});
                    $(".vidPop").colorbox({
                        iframe: true,
                        rel: 'vidPop',
                        transition: "none",
                        width: "95%",
                        height: "60%",
                        opacity: .7
                    });

                }

                $(".imgThumb").mouseover(function () {
                    var imgUrl = $(this).attr('data-src') + '370x280.jpg';
                    $('#primaryImg img').attr('src', imgUrl);
                    $('#primaryImg a').attr('href', imgUrl);
                });

                $.post("<?=$config->domain?>logic/ajaxController.php", {function: 'setCatClick'});
                $.post("<?=$config->domain?>logic/ajaxController.php", {function: 'setBounce'});

            })(jQuery);
        </script>
</body>
</html>


<?php
$path = str_replace(' ','-',$_GET['name']);
$cachefile = "../cache/kit/".strtolower($path).".html";
// open the cache file "cache/home.html" for writing
$fp = fopen($cachefile, 'w');
// save the contents of output buffer to the file
fwrite($fp, Minify_HTML::minify(ob_get_contents()));
// close the file
fclose($fp);
// Send the output to the browser
ob_end_flush();
?>
