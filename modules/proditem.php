<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
include("../config/config.php");
include("../logic/global.php");
include("../logic/product.class.php");
include("../logic/cart.class.php");
include("../logic/Minify_HTML.class.php");

$product = new Product;
$product->mysqli = mysqliConn();
$product->getItemDetails();
$product->getGroupTraits();
$product->getTutorials();
$product->getPosts();
$product->getBreadcrumbs();
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
<body itemscope itemtype="https://schema.org/Product">
<header>
    <div class="header">
        <?php include("../cache/header-responsive.html"); ?>
        <div class="mainbody">
            <div class="container">
                <div class="row to-home-page">
                    <a href="<?=$config->domain?>">Homepage</a> &raquo; <a title="<?php echo $product->breadcrumbs['title']; ?>"
                                                        href="<?php echo $product->breadcrumbs['link']; ?>"><?php echo $product->breadcrumbs['text']; ?></a> <?php echo $product->breadcrumbs['current_page']; ?>
                </div>
                <div id="prodNarrow">
                    <h2 class="hidden-md hidden-lg"><?= $product->details['title'] ?></h2>
                </div>
                <div class="row main-product_myclass">
                    <div class="product-gallery col-md-6 col-sm-6 col-xs-12">
                        <div class="prod">
                            <meta itemprop="color" content="<?=$product->details['color']?>"/>
                            <meta itemprop="category" content="<?=$product->details['cat']?> > <?=$product->details['type']?>"/>
                            <div class="prodLeft prodLeft_myclass" id="prodImages">
                                <?php $style_string = "marginright_myclass"; ?>
                                <?php $countProductArr = count($product->details['img']); ?>
                                
                                <? $i = 0; foreach ($product->details['img'] as $row): ?>
                                    <? if ($i == 0): ?>
                                        <div id="primaryImg">
                                            <a class="imgPop"
                                               href="https://mjtrends.b-cdn.net/images/product/<?= $product->details['invid'] ?>/<?= $row['path'] ?>_924x699.jpg"
                                               title="<?= $row['alt'] ?>">
                                                <img
                                                    src="https://mjtrends.b-cdn.net/images/product/<?= $product->details['invid'] ?>/<?= $row['path'] ?>_370x280.jpg"
                                                    alt="<?= $row['alt'] ?>" itemprop="image">
                                            </a>
                                        </div>
                                    <? endif;
                                    $i++ ?>
                                    <?php $countProductArr ?>
                                    <div class="imgPop_mystyle <?php echo $style_string; ?>">
                                        <a class="imgPop1"
                                           href="https://mjtrends.b-cdn.net/images/product/<?= $product->details['invid'] ?>/<?= $row['path'] ?>_924x699.jpg"
                                           title="<?= $row['alt'] ?>">
                                            <img class="imgThumb"
                                                 src="https://mjtrends.b-cdn.net/images/product/<?= $product->details['invid'] ?>/<?= $row['path'] ?>_115x87.jpg"
                                                 data-src="https://mjtrends.b-cdn.net/images/product/<?= $product->details['invid'] ?>/<?= $row['path'] ?>_"
                                                 width="115" height="87" alt="<?= $row['alt'] ?> thumbnail image.">
                                        </a>
                                    </div>
                                <? endforeach; ?>
                                <div class="imgPop_mystyle <?php echo $style_string; ?>">
                                    <? foreach ($product->details['video'] as $row): ?>
                                        <a class="vPop" href="<?=$row['url']?>" title="<?=$row['alt']?>">
                                            <img class="vThumb"
                                                 src="https://mjtrends.b-cdn.net/images/product/<?=$product->details['invid']?>/<?= $row['thumb'] ?>_115x87.jpg"
                                                 width="115" height="87" alt="<?=$row['alt']?>">
                                        </a>
                                    <? endforeach; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!--product-gallery-->

                    <div class="product-descript col-md-6 col-sm-6 col-xs-12">

                        <div class="prodRight">
                            <h2 itemprop="name"
                                id="prodTitle"><?= $product->details['title'] ?>
                            </h2>
                            <!-- price and add to cart -->
                            <div class="price_addtocart_myclass" itemprop="offers" itemscope itemtype="https://schema.org/Offer">
                                <div class="productPrice">
                                    <? if ($product->details['saleprice'] > 0): ?>
                                        <span class="strike" id="prodRetail">$<?= $product->details['retail'] ?></span>
                                        <span itemprop="priceCurrency" content="USD">$<span
                                                itemprop="price"
                                                id="prodSale"><?= $product->details['saleprice'] ?></span></span>
                                    <? else: ?>
                                        <span itemprop="priceCurrency" content="USD">$<span
                                                itemprop="price"
                                                id="prodRetail"><?= $product->details['retail'] ?></span></span>
                                    <? endif; ?>
                                    <? if ($product->details['cat'] == "Fabric" || $product->details['cat'] == 'Latex-Sheeting'): ?>
                                        <span> / yard</span>
                                    <? endif; ?>
                                </div>

                                <div class="formWrapper">
                                    <form class="prod-form" action="cart.php" method="post"
                                          onSubmit="return checkProd();" id="prodFrm">
                                        <p class="quant">Quantity: <input id="quant" name="quant" <?= $product->details['clearance'] ?> type="text" size="3"><span><b>In
                                                    Stock: </b><span
                                                    class="inv"> <?= $product->details['unit'] ?></span></span></p>
                                        <?php
                                        if (!empty($product->traits)) {
                                            echo '<select name="length" id="product-trait">';
                                            foreach ($product->traits as $trait) {
                                                if (empty($product->details['teeth_type'])) {
                                                    echo '<option ' . (($trait['invid'] == $product->details['invid']) ? 'selected="selected"' : '') . ' value="' . $trait['invid'] . '">' . $trait['length'] . ' ' . $product->details['color'] . ' ' . $product->details['type'] . '</option>';
                                                } else {
                                                    echo '<option ' . (($trait['invid'] == $product->details['invid']) ? 'selected="selected"' : '') . ' value="' . $trait['invid'] . '">' . $trait['length'] . ': $' . ($trait['saleprice'] > 0 ? $trait['saleprice'] : $trait['retail']) . '</option>';
                                                }
                                            }
                                            echo '</select>';
                                        }
                                        ?>
                                        <div id="err" class="red" style="display:none">ERROR: whole numbers only</div>
                                        <div class="btnWrap">
                                            <button id="add" class="btn redBtn" type="submit">Add to
                                                Cart
                                            </button>
                                            <? if ($product->details['cat'] == 'Fabric' || $product->details['cat'] == 'Latex-Sheeting'): ?>
                                                <a class="whtBtn whtBtn_myclass btn btn-default" href="products.<?= $product->details['swatch'] . ',Swatches,Swatches'; ?>">Order Swatch</a>
                                                <a id="swatchHlp" href="#swatch_help" title="this is alt"><img src="https://mjtrends.b-cdn.net/images/helpQ.gif" alt="About our swatches"></a>
                                            <? endif; ?>
                                        </div>

                                        <!-- End: price and add to cart -->
                                        <p class="quant quant2_myclass"><span><b>In Stock:</b> <span class="inv"></span></span>
                                        </p>
                                        <input name="invid" type="hidden" id="invid" value="<?= $product->details['invid'] ?>">
                                        <input name="type" type="hidden" value="<?= $product->details['type'] ?>">
                                        <input name="color" type="hidden" value="<?= $product->details['length'] ?> <?= $product->details['color'] ?>">
                                        <input name="amt" type="hidden" id="remaining" value="<?= $product->details['invamount'] ?>">
                                        <input name="price" type="hidden" id="price" value="<?= $product->details['retail'] ?>">
                                        <input name="sale" type="hidden" id="saleprice" value="<?= $product->details['saleprice'] ?>">
                                        <input name="img" type="hidden" value="<?= $product->details['img'][0]['path'] ?>">
                                        <input name="cat" type="hidden" value="<?= $_GET['cat'] ?>">
                                        <input name="weight" type="hidden" value="<?=$product->details['weight'] ?>">
                                        <input name="volume" type="hidden" value="<?=$product->details['volume'] ?>">
                                        <input name="minLenth" type="hidden" value="<?=$product->details['minlength'] ?>">
                                        <input name="minWidth" type="hidden" value="<?=$product->details['minwidth'] ?>">
                                        <input name="minHeight" type="hidden" value="<?=$product->details['minheight'] ?>">
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
                                <h3 class="features-head features-head-my_class">Features:</h3>
                                <ul id="prodFeatures">
                                    <? foreach ($product->details['features'] as $key => $val): ?>
                                        <li><b><?= $key ?>: </b><?= $val ?></li>
                                    <? endforeach; ?>
                                </ul>
                            </div>
                            <div class="separator_myclass">
                                <h3 class="descript-head descript-head-my_class">Description:</h3>

                                <div class="descrip-text descrip-text_myclass" itemprop="description">
                                    <?= $product->details['descr'] ?>
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
                                                        src="https://mjtrends.b-cdn.net/images/video/<?= $row['thumb'] ?>_115x87.jpg"
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

                        <img src="https://mjtrends.b-cdn.net/images/paypal-verification.gif"
                             alt="Official PayPal Seal">
                        <img src="https://mjtrends.b-cdn.net/images/authorize.gif"
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
                                            <img src="https://mjtrends.b-cdn.net/images/product/<?=$product->details['invid']?>/<?=$product->details['img'][0]['path']?>_115x87.jpg" class="cartItemImg" width="115" height="87">
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
                $.post("<?=$config->domain?>logic/ajaxController.php?type=<?=$product->details['type']?>&color=<?=$product->details['color']?>&cat=<?=$product->details['cat']?>", {function: 'getInvamount'},
                    function (data) {
                        $("#remaining").val(data);
                        $(".inv").html(data);
                    });

                (function downloadJSAtOnload() {
                    var products = '<?php
                        $prodArray = $product->getRecommended($product->details['type'], $product->details['invid']);
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


        <script type="text/javascript">
            <?if(!empty($product->traits)):?>
            var ajax_version = <?=time();?>;
            <?endif;?>
        </script>

        <script> window.jQuery || document.write('<script src="jquery/jquery-ui-1.10.0.custom/js/jquery-ui-1.10.0.custom.min.js"><script>')</script>

        <script>
            $(document).ready(function () {
                $('#swatchHlp').tooltip({
                    content: $('#swatch_help').html(),
                    position: {my: "left+15 center", at: "left center"},
                    hide: {duration: 1500}
                });

                $('#shareHlp').tooltip({
                    hide: {duration: 1500}
                });
                if( $( window).width() > 600 ) {
                    var $gall = $(".imgPop1").colorbox({rel: 'imgPop1', transition: "none", width: "75%", height: "75%", opacity: .7});
                    $("a.imgPop").click(function(e){
                        e.preventDefault();
                        $gall.eq(0).click();
                        return false;
                    });
                     $("a.Pop").click(function(e){
                        e.preventDefault();
                        $gall.eq(0).click();
                        return false;
                    });
                    $(".vidPop").colorbox({
                        iframe: true,
                        rel: 'vidPop',
                        transition: "none",
                        width: "75%",
                        height: "75%",
                        opacity: .7
                    });
                    $(".vPop").colorbox({
                        iframe: true,
                        rel: 'imgPop1',
                        transition: "none",
                        width: "75%",
                        height: "75%",
                        opacity: .7
                    });

                }
                else{
                    var $gall = $(".imgPop1").colorbox({rel: 'imgPop1', transition: "none", width: "90%", height: "60%", opacity: .7});
                    $("a.imgPop").click(function(e){
                        e.preventDefault();
                        $gall.eq(0).click();
                        return false;
                    });
                    
                    $(".vidPop").colorbox({
                        iframe: true,
                        rel: 'vidPop',
                        transition: "none",
                        width: "95%",
                        height: "60%",
                        opacity: .7
                    });
                    $(".vPop").colorbox({
                        iframe: true,
                        rel: 'imgPop1',
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

                $("#product-trait").change(function () {
                    loadProduct($(this));
                });
            });
        </script>
</body>
</html>

<?php
if(strpos($product->details['color'],"/")){
    $color = str_replace("/", "_", $product->details['color']);
} else{
    $color = $product->details['color'];
}

if ($product->details['length'] != "") {
    if (!empty($product->details['teeth_type'])) {
        $separating = $product->details['seperating'] ? 'separating' : 'non-separating';
        $path = $color . '-' . str_replace(' ', '', $product->details['length']) . '-' . $separating . '-' .
            str_replace(' ', '-', $product->details['teeth_type']) . '-' . str_replace(' ', '', $product->details['type']) . '-' . str_replace(' ', '', $product->details['invid']);
    } else {
        $path = $product->details['cat'] . '-' . str_replace(' ', '', $product->details['type']) . '-' . str_replace(' ', '', $color) . '-' . str_replace(' ', '-', $product->details['length']);
    }
} else {
    $path = $product->details['cat'] . '-' . str_replace(' ', '', $product->details['type']) . '-' . str_replace(' ', '', $color);
}

$cachefile = "../cache/prod/" . strtolower($path) . ".html";
// open the cache file "cache/home.html" for writing
$fp = fopen($cachefile, 'w');
// save the contents of output buffer to the file
fwrite($fp, Minify_HTML::minify(ob_get_contents()));
// close the file
fclose($fp);
// Send the output to the browser
ob_end_flush();
?>
