<?
$section = $_GET['section'];

include("../logic/Minify_HTML.class.php");
include("../forum/logic/forum.class.php");
include("../config/config.php");

ob_start();

$site = false;
$forum = false;
$blog = false;
if ($section == "site") {
    $site = true;
    $classmenu = "cartmenu";
}
if ($section == "forum") {
    $forum = true;
    $classmenu = "cartmenu";
}
if ($section == "blog") {
    $blog = true;
}

?>

<!-- COMMON -->

<div class='modal_bg'></div>
<div style="display:block; width:100%; clear:both; height: 56px;"></div>
<section class="header-black" id="header-black">

    <div class="container">
        <div class="row">

            <div class="searchAndCart">
                <div id="icon-cart" class="closed">
                    <div id="cart-background"></div>
                    <span class="icon"></span>
                    <span id="cart-amm">0</span>

                    <div class="cart">
                        <div class="items" id="cartitems">
                            <span id="noCartItems">There no items in your cart.</span>
                        </div>
                        <!--items-->
                        <a id="checkout" class="checkout" href="<?= $config->domain ?>cart.php">Checkout</a>
                    </div>
                    <!--cart -->
                </div>
                <button id="open_search" class="btn visible-xs-block btn-default btn-search-open">
                    <span class="glyphicon glyphicon-search" type="button"></span>
                </button>
            </div>
            <!-- searchAndCart -->

            <div class="col-lg-3 col-xs-12 col-sm-4 logo-holder col-xs-offset-56px" id="logotype">
                <a href="http://www.MJTrends.com"><img class="" src='//mjtrends.b-cdn.net/images/blog/site/logo.png' alt='MJTRENDS'></a>
            </div>
            <!-- menu START -->
            <div class="col-sm-8 col-lg-9 <?= $classmenu ?>" id="menus">
                <!-- menu START -->

                <div id="secondarymenu"></div>

                <div class="mob_nav" id="mainmenu"><!--main_nav-->

                    <div class="nav"> <!--nav-justified-->

                        <div class="modal_menu"></div>
                        <div class="menu-item show-md">
                            <h6 id="nav4" class=" text-center">Fabrics</h6>

                            <div class="subnav" id="fabrics">
                                <ul>
                                    <li><a href="<?= $config->domain ?>categories-Clear-Vinyl-Material,Fabric">Clear Vinyl Material</a></li>
                                    <li><a href="<?= $config->domain ?>categories-Faux-Leather,Fabric">Faux Leather</a></li>
                                    <li><a href="<?= $config->domain ?>categories-Fleece,Fabric">Fleece</a></li>
                                    <li><a href="<?= $config->domain ?>categories-Interfacing,Fabric">Interfacing</a></li>
                                    <li><a href="<?= $config->domain ?>categories-Foil-Spandex,Fabric">Metallic Spandex</a></li>
                                    <li><a href="<?= $config->domain ?>categories-Patent-Vinyl,Fabric">Patent Vinyl</a></li>
                                    <li><a href="<?= $config->domain ?>categories-Stretch-PVC,Fabric">Stretch Vinyl</a></li>
                                    <li><a href="<?= $config->domain ?>categories-Snakeskin,Fabric">Snakeskin</a></li>
                                    <li><a href="<?= $config->domain ?>categories-PVC,Fabric">Vinyl</a></li>
                                    <li><a href="<?= $config->domain ?>categories-Tulle,Fabric">Tulle / Mesh</a></li>
                                    <li><a href="<?= $config->domain ?>categories-upholstery,fabric">Upholstery</a></li>
                                </ul>
                            </div>
                        </div>

                        <div class="menu-item show-md">
                            <h6 id="nav5" class=" text-center">Latex <span class="show430"> Sheeting</span></h6>

                            <div class="subnav cols-3 accordian-mobile">
                                <ul>
                                    <li class="bold">Gauges</li>
                                    <li><a href="<?= $config->domain ?>categories-.20mm,Latex-Sheeting">.20mm</a></li>
                                    <li><a href="<?= $config->domain ?>categories-.30mm,Latex-Sheeting">.35mm</a></li>
                                    <li><a href="<?= $config->domain ?>categories-.40mm,Latex-Sheeting">.40mm</a></li>
                                    <li><a href="<?= $config->domain ?>categories-.50mm,Latex-Sheeting">.50mm</a></li>
                                    <li><a href="<?= $config->domain ?>categories-.60mm,Latex-Sheeting">.60mm</a></li>
                                    <li><a href="<?= $config->domain ?>categories-.80mm,Latex-Sheeting">.80mm</a></li>
                                    <li><a href="<?= $config->domain ?>gridview-Latex-Sheeting">Grid View</a></li>
                                </ul>
                                <ul>
                                    <li class="bold">Accessories</li>
                                    <li><a href="<?= $config->domain ?>categories-Adhesive,Notions">Adhesives</a></li>
                                    <li><a href="<?= $config->domain ?>categories-Adhesive-Cleaner,Notions">Adhesive Cleaner</a></li>
                                    <li><a href="<?= $config->domain ?>categories-Cutting-Mat,Notions">Cutting Matts</a></li>
                                    <li><a href="<?= $config->domain ?>products.Clear,Fabric-tape,Notions">Double Sided Tape</a></li>
                                    <li><a href="<?= $config->domain ?>categories-Gloves,Notions">Gloves</a></li>
                                    <li><a href="<?= $config->domain ?>categories-Latex-Shine,Notions">Latex Shine</a></li>
                                    <li><a href="<?= $config->domain ?>categories-Rotary-Cutter,Notions">Rotary Cutters</a></li>
                                    <li><a href="<?= $config->domain ?>categories-Rotary-Cutter,Notions">Rotary Blades</a></li>
                                    <li><a href="<?= $config->domain ?>categories-Latex-Seam-Roller,Notions">Seam Roller</a></li>
                                </ul>
                                <ul>
                                    <li class="bold">Kits</li>
                                    <li><a href="<?= $config->domain ?>kit.Latex-Starter">Beginner</a></li>
                                    <li><a href="<?= $config->domain ?>kit.Latex-Advanced">Advanced</a></li>
                                    <li><a href="<?= $config->domain ?>kit.Latex-Pro">Expert</a></li>
                                </ul>
                            </div>
                        </div>


                        <div class="menu-item show-md">
                            <h6 id="nav6" class=" text-center">Notions</h6>

                            <div class="subnav cols-3 accordian accordian-mobile" id="notions">
                                <ul>
                                    <li class="bold">Corsetry / Lingerie</li>
                                    <li><a href="<?= $config->domain ?>categories-Bra-cups,Notions">Bra Cups / Shaping</a></li>
                                    <li><a href="<?= $config->domain ?>categories-Bra-hooks,Notions">Bra Hooks</a></li>
                                    <li><a href="<?= $config->domain ?>categories-Bra-slides,Notions">Bra Slides</a></li>
                                    <li><a href="<?= $config->domain ?>categories-Bra-straps,Notions">Bra Straps</a></li>
                                    <li><a href="<?= $config->domain ?>categories-Bra-wire,Notions">Bra Wire</a></li>
                                    <li><a href="<?= $config->domain ?>categories-Bra-separator-wire,Notions">Bra Separator Wire (V-Wire)</a></li>
                                    <li><a href="<?= $config->domain ?>categories-Busks,Notions">Corset Busks</a></li>
                                    <li><a href="<?= $config->domain ?>categories-Eyelets-and-Grommets,Notions">Eyelets &amp; Grommets</a></li>
                                    <li><a href="<?= $config->domain ?>categories-Garter-clip,Notions">Garter Clips</a></li>
                                    <li><a href="<?= $config->domain ?>categories-Hook-Eye-Tape,Notions">Hook &amp; Eye Tape</a></li>
                                    <li><a href="<?= $config->domain ?>categories-Flat-boning,Notions">Flat Boning</a></li>
                                    <li><a href="<?= $config->domain ?>categories-Spiral-boning,Notions">Spiral Boning</a></li>
                                    <li><a href="<?= $config->domain ?>categories-Polyester-boning,Notions">Polyester Boning</a></li>
                                    <li><a href="<?= $config->domain ?>categories-Polyester-boning-precut,Notions">Polyester Boning Precut</a></li>
                                    <li><a href="<?= $config->domain ?>categories-Snaps,Notions">Snaps</a></li>

                                </ul>
                                <ul>
                                    <li class="bold">Cutting</li>
                                    <li><a href="<?= $config->domain ?>categories-Cutting-Mat,Notions">Cutting Mats</a></li>
                                    <li><a href="<?= $config->domain ?>categories-Rotary-Cutter,Notions">Rotary Cutters</a></li>
                                    <li><a href="<?= $config->domain ?>categories-Rotary-Cutter-Blade,Notions">Rotary Blades</a></li>
                                    <li><a href="<?= $config->domain ?>categories-Seam-ripper,Notions">Seam Rippers</a></li>
                                    <li><a href="<?= $config->domain ?>categories-Scissors,Notions">Scissors</a></li>
                                </ul>
                                <ul>

                                    <li class="bold">Measuring / Pattern Making</li>
                                    <li><a href="<?= $config->domain ?>categories-French-Curve,Notions">French Curves</a></li>
                                    <li><a href="<?= $config->domain ?>categories-Tape-measure,Notions">Measuring Tape</a></li>
                                    <li><a href="<?= $config->domain ?>categories-Tailors-Chalk,Notions">Tailors Chalk</a></li>
                                    <li><a href="<?= $config->domain ?>categories-Marking-Tools,Notions">Tracing Wheel</a></li>
                                </ul>
                                <ul>
                                    <li class="bold">Latex Accessories</li>
                                    <li><a href="<?= $config->domain ?>categories-Adhesive,Notions">Adhesives</a></li>
                                    <li><a href="<?= $config->domain ?>categories-Adhesive-Cleaner,Notions">Adhesive Cleaner</a></li>
                                    <li><a href="<?= $config->domain ?>products.Clear,Fabric-tape,Notions">Double Sided Tape</a></li>
                                    <li><a href="<?= $config->domain ?>categories-Gloves,Notions">Gloves</a></li>
                                    <li><a href="<?= $config->domain ?>categories-Latex-Shine,Notions">Latex Shine</a></li>
                                    <li><a href="<?= $config->domain ?>categories-Latex-Seam-Roller,Notions">Seam Roller</a></li>
                                </ul>
                                <ul>

                                    <li class="bold">Vinyl Accessories</li>
                                    <li><a href="<?= $config->domain ?>products.Vinyl,Adhesive,Notions">Vinyl Adhesive</a></li>
                                    <li><a href="<?= $config->domain ?>products.Roller,Presser-Foot,Notions">Roller Pressor Foot</a></li>
                                    <li><a href="<?= $config->domain ?>categories-Presser-Foot,Notions">Teflon Pressor Foot</a></li>
                                </ul>
                                <ul>

                                    <li class="bold">Kits</li>
                                    <li><a href="<?= $config->domain ?>kit.Vinyl-Starter">Vinyl Starter Kit</a></li>
                                    <li><a href="<?= $config->domain ?>kit.Vinyl-Advanced">Vinyl Advanced Kit</a></li>
                                    <li><a href="<?= $config->domain ?>kit.Vinyl-Pro">Vinyl Pro Kit</a></li>
                                    <li><a href="<?= $config->domain ?>kit.Latex-Starter">Latex Starter Kit</a></li>
                                    <li><a href="<?= $config->domain ?>kit.Latex-Advanced">Latex Advanced Kit</a></li>
                                    <li><a href="<?= $config->domain ?>kit.Latex-Pro">Latex Pro Kit</a></li>
                                    <li><a href="<?= $config->domain ?>kit.Lingerie">Lingerie Kit</a></li>
                                    <li><a href="<?= $config->domain ?>kit.French-Curve-Pro-Pack">French Curve Kit</a></li>
                                    <li><a href="<?= $config->domain ?>categories-kits,DIY">View All</a></li>
                                </ul>
                                <ul>
                                    <li class="bold">Closures / Velcro</li>
                                    <li><a href="<?= $config->domain ?>categories-buttons,Notions">Buttons</a></li>
                                    <li><a href="<?= $config->domain ?>categories-Buckle,Notions">Buckles (for straps)</a></li>
                                    <li><a href="<?= $config->domain ?>categories-Clasps,Notions">Clasps &amp; D-Rings</a></li>
                                    <li><a href="<?= $config->domain ?>categories-Cord-lock,Notions">Cord Locks</a></li>
                                    <li><a href="<?= $config->domain ?>categories-Cord-Ends,Notions">Cord Ends</a></li>
                                    <li><a href="<?= $config->domain ?>categories-Drawstring,Notions">Drawstring</a></li>
                                    <li><a href="<?= $config->domain ?>categories-Elastic,Notions">Elastic</a></li>
                                    <li><a href="<?= $config->domain ?>categories-Eyelets-and-Grommets,Notions">Eyelets &amp; Grommets</a></li>
                                    <li><a href="<?= $config->domain ?>categories-Frog-closure,Notions">Frog Closures</a></li>
                                    <li><a href="<?= $config->domain ?>categories-Snaps,Notions">Snaps</a></li>
                                    <li><a href="<?= $config->domain ?>categories-Velcro,Notions">Velcro</a></li>
                                    <li><a href="<?= $config->domain ?>categories-Webbing,Notions">Webbing</a></li>
                                </ul>

                                <ul>
                                    <li class="bold">Sewing Supplies</li>
                                    <li><a href="<?= $config->domain ?>categories-bias-tape-maker,Notions">Bias Tape Maker</a></li>
                                    <li><a href="<?= $config->domain ?>categories-Bobbins,Notions">Bobbins</a></li>
                                    <li><a href="<?= $config->domain ?>categories-Presser-Foot,Notions">Pressor Foot</a></li>
                                    <li><a href="<?= $config->domain ?>categories-Seam-ripper,Notions">Seam Rippers</a></li>
                                    <li><a href="<?= $config->domain ?>categories-Sewing-Needles,Notions">Sewing Needles</a></li>
                                    <li><a href="<?= $config->domain ?>categories-Tweezers,Notions">Sewing Tweezers</a></li>
                                    <li><a href="<?= $config->domain ?>categories-Thread,Notions">Thread</a></li>
                                    <li><a href="<?= $config->domain ?>categories-Fabric-Glue,Notions">Fabric Glue</a></li>
                                    <li><a href="<?= $config->domain ?>categories-Fray-Check,Notions">Fray Check</a></li>
                                </ul>

                                <ul class="second-level">
                                    <li class="bold second-level-child">Zippers</li>
                                    <li class="second-level-opened">
                                        <div class="second-level-title">Separating</div>
                                        <ul class="third-level">
                                            <li><a  href="<?= $config->domain ?>categories-aluminum-separating,Zippers">Aluminum</a></li>
                                            <li><a href="<?= $config->domain ?>categories-brass-separating,Zippers">Brass</a></li>
                                            <li><a href="<?= $config->domain ?>categories-nylon-separating,Zippers">Nylon</a></li>
                                            <li><a href="<?= $config->domain ?>categories-plastic-separating,Zippers">Plastic</a></li>
                                        </ul>
                                    </li>
                                    <li class="second-level-opened">
                                        <div class="second-level-title">Non-Separating</div>
                                        <ul class="third-level">
                                            <li><a href="<?= $config->domain ?>categories-aluminum-non-separating,Zippers">Aluminum</a></li>
                                            <li><a href="<?= $config->domain ?>categories-brass-non-separating,Zippers">Brass</a></li>
                                            <li><a href="<?= $config->domain ?>categories-hidden,Zippers">Hidden / Concealed</a></li>
                                            <li><a href="<?= $config->domain ?>categories-nylon-non-separating,Zippers">Nylon</a></li>
                                            <li><a href="<?= $config->domain ?>categories-plastic-non-separating,Zippers">Plastic</a></li>
                                            <li><a href="<?= $config->domain ?>categories-3-way,Zippers">3-way</a></li>
                                        </ul>
                                    </li>
                                    <li class="second-level-link">
                                        <a href="<?= $config->domain ?>categories-zipper-repair,Notions">Zipper Repair</a>
                                    </li>
                                   <li class="second-level-link">
                                        <a href="<?= $config->domain ?>categories-Zipper-pulls,Notions">Pulls</a>
                                    </li>
                                    <li class="second-level-link">
                                        <a href="<?= $config->domain ?>categories-custom-length-zipper,Notions">Custom length</a>
                                    </li>
                                    <li class="second-level-link">
                                        <a href="<?= $config->domain ?>cache/cat/zipper-types.html">View All</a>
                                    </li>
                                </ul>

                                <ul>
                                    <li class="bold">Miscellaneous</li>
                                    <li><a href="<?= $config->domain ?>categories-Awl,Notions">Awl</a></li>
                                    <li><a href="<?= $config->domain ?>categories-cuffs,Notions">Cuffs</a></li>
                                    <li><a href="<?= $config->domain ?>products.Clear,Fabric-tape,Notions">Double Sided Tape</a></li>
                                    <li><a href="<?= $config->domain ?>categories-Fabric-Glue,Notions">Fabric Glue</a></li>
                                    <li><a href="<?= $config->domain ?>categories-Fabric-Dye,Notions">Fabric Dye</a></li>
                                    <li><a href="<?= $config->domain ?>categories-Fray-Check,Notions">Fray Check</a></li>
                                    <li><a href="<?= $config->domain ?>categories-Spikes,Notions">Spikes</a></li>
                                    <li><a href="<?= $config->domain ?>categories-Studs,Notions">Studs</a></li>
                                    <li><a href="<?= $config->domain ?>categories-Sewing-Needles,Notions">Twin needles</a></li>
                                    <li><a href="<?= $config->domain ?>categories-Tweezers,Notions">Tweezers</a></li>
                                </ul>
                            </div>
                        </div>

                        <div class="menu-item md-hide">
                            <h6 id="nav7" class=" text-center">Sale<span class="hidden-lg hidden-xs hidden-sm">  / Clearance</span>
                            </h6>

                            <div class="subnav" id="sale">
                                <ul>
                                    <li><a href="<?= $config->domain ?>categories-sale,sale">Sale Items</a></li>
                                    <li><a href="<?= $config->domain ?>categories-clearance,sale">Clearance Items (limited quantity)</a></li>
                                </ul>
                            </div>
                        </div>

                        <div class="menu-item md-hide">
                            <h6 id="nav8" class=" text-center">Patterns</h6>

                            <div class="subnav cols-2" id="upholstery">
                                <ul>
                                    <li class="bold">Womens</li>
                                    <li><a href="<?= $config->domain ?>pattern/womens/bandeau-dress">Bandeau Dress</a></li>
                                    <li><a href="<?= $config->domain ?>pattern/womens/leggings">Leggings</a></li>
                                    <li><a href="<?= $config->domain ?>pattern/womens/bodysuit">Bodysuit</a></li>
                                    <li><a href="<?= $config->domain ?>pattern/womens/mini-skirt">Mini Skirt</a></li>
                                    <li><a href="<?= $config->domain ?>pattern/womens/ruffle-skirt">Ruffle Skirt</a></li>
                                    <li><a href="<?= $config->domain ?>pattern/womens/pencil-skirt">Pencil Skirt</a></li>
                                    <li><a href="<?= $config->domain ?>pattern/womens/short-shorts">Short Shorts</a></li>
                                    <li><a href="<?= $config->domain ?>pattern/womens/tank-top">Tank Top</a></li>
                                </ul>
                                <ul>
                                    <li class="bold">Mens</li>
                                    <li><a href="<?= $config->domain ?>pattern/mens/bike-short">Bike Short</a></li>
                                    <li><a href="<?= $config->domain ?>pattern/mens/boxer-brief">Boxer Brief</a></li>
                                    <li><a href="<?= $config->domain ?>pattern/mens/ybrief">Y-Front Brief</a></li>
                                    <li><a href="<?= $config->domain ?>pattern/mens/short-sleeve-shirt">Short Sleeve Shirt</a></li>
                                    <li><a href="<?= $config->domain ?>pattern/mens/long-sleeve-shirt">Long Sleeve Shirt</a></li>
                                    <li><a href="<?= $config->domain ?>pattern/mens/tank-top">Tank top</a></li>
                                    <li><a href="<?= $config->domain ?>pattern/mens/Mens-wrestling-tank-suit">Wrestling tank suit</a></li>
                                    <li><a href="<?= $config->domain ?>pattern/mens/athletic-fit-hoodie">Athletic fit hoodie</a></li>
                                </ul>
                                
                                <ul class="subnavCenter">
                                    <li><a href="<?=$config->domain?>patterns">View All</a></li>
                                </ul>
                            </div>
                        </div>

                        <div class="menu-item md-hide">
                            <h6 id="nav9" class=" text-center">Swatches</h6>

                            <div class="subnav cols-2" id="swatch">
                                <ul>
                                    <li><a href="<?= $config->domain ?>products.Latex-sheeting,Swatches,Swatches">Latex Sheeting</a></li>
                                    <li><a href="<?= $config->domain ?>products.Faux-Leather,Swatches,Swatches">Matte Vinyl / Faux Leather</a></li>
                                    <li><a href="<?= $config->domain ?>products.Patent-Vinyl,Swatches,Swatches">Patent Vinyl</a></li>
                                    <li><a href="<?= $config->domain ?>products.clear-vinyl-material,Swatches,Swatches">Clear Vinyl Material</a></li>
                                </ul>
                                <ul>
                                    <li><a href="<?= $config->domain ?>products.PVC,Swatches,Swatches">PVC / PU vinyl</a></li>
                                    <li><a href="<?= $config->domain ?>products.Snakeskin,Swatches,Swatches">Snakeskin</a></li>
                                    <li><a href="<?= $config->domain ?>products.Stretch-PVC,Swatches,Swatches">Stretch PVC Vinyl</a></li>
                                </ul>
                            </div>
                        </div>

                        <div class="menu-item md-hide">
                            <h6 id="nav10">DIY Jewelry</h6>

                            <div class="subnav subnav_end" id="wholesale">
                                <ul>
                                    <li><a href="<?= $config->domain ?>categories-Belts,DIY-jewelry">Womens Metal Belts</a></li>
                                    <li><a href="<?= $config->domain ?>categories-Body-Adhesive,DIY-jewelry">Body Adhesive</a></li>
                                    <li><a href="<?= $config->domain ?>categories-Chain-Maille,DIY-jewelry">Chain Maille</a></li>
                                    <li><a href="<?= $config->domain ?>categories-Bracelets-Necklaces,DIY-jewelry">Bracelets &amp; Necklaces</a></li>
                                    <li><a href="<?= $config->domain ?>categories-Earrings,DIY-jewelry">Earring Supplies</a></li>
                                    <li><a href="<?= $config->domain ?>categories-Spikes,Notions">Spikes</a></li>
                                    <li><a href="<?= $config->domain ?>categories-Studs,Notions">Studs</a></li>
                                </ul>
                            </div>
                        </div>

                    </div>
                </div>


                <!--menu END-->

            </div>
            <!--menu END-->
            <!--mobile nav-->
            <!-- Brand and toggle get grouped for better mobile display -->
            <div class="navbar-header custom-nav-header">
                <button type="button" id="menuopen" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#mainmenu">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="glyphicon glyphicon-remove" aria-hidden="true"></span>
                </button>
            </div>

            <!-- Collect the nav links, forms, and other content for toggling -->
            <!--mobile nav-->
        </div>
    </div>

</section>

<?php if($forum || $site): ?>
<!-- header -->

<!-- COMMON END -->
        
            <!--grey div -->
<div class="greyline">
    <div class="container">
        <div class="row">
            <div class="col-md-3 col-lg-3 col-sm-4 hidden-xs">
                <div class="helpcreate">
                    <a href="<?=$config->domain?>forum"><img src="//mjtrends.b-cdn.net/images/we-help-create.png" alt="We help create"></a>
                </div>
            </div>
            <div class="col-xs-12 col-md-offset-1 col-md-5 col-sm-6">
                <? if($forum): //forum tabs?> 
                    <ul class="tabs">
                        <li id="tab_recent"><a href="<?=$config->domain?>forum?listType=recent">Recent</a></li>
                        <li id="tab_latex"><a href="<?=$config->domain?>forum?listType=latex">Latex</a></li>
                        <li id="tab_vinyl"><a href="<?=$config->domain?>forum?listType=vinyl">Vinyl</a></li>
                        <li id="tab_selfies"><a href="<?=$config->domain?>forum?listType=selfies">Selfies</a></li>
                    </ul>
                <? endif; ?>

                <? if($site): //site tabs?>
                    <ul class="sitetabs">
                        <li><a href="<?=$config->domain?>blog/">Blog</a></li>
                        <li><a href="<?=$config->domain?>articles.php">Tutorials</a></li>
                        <li><a href="<?=$config->domain?>forum/">Forum</a></li>
                    </ul>
                <? endif; ?>
            </div>


            <div class="col-xs-3 col-sm-2 col-md-3 searchholder">
                <?if($forum):?>
                <form method="get" id="smartSearchFrm" action="<?=$config->domain.'search.php';?>">
                    <input name="category" class="category" type="hidden" value="Forum">
                    <div class="input-group">
                        <input name="search" type="text" class="form-control" placeholder="Search" id="smart_search" autocomplete="off">
                        <div class="dropdown">
                            <div class="current"><span id="searchVal">Forum</span> <span class="glyphicon glyphicon-chevron-down"></span> </div>
                            <ul class="drop-menu">
                                <li><a href="<?= $config->domain.'search.php'?>">Forum</a></li>
                                <li><a href="<?= $config->domain.'search.php'?>">Website</a></li>
                            </ul>
                        </div>
                        <span class="input-group-btn">
                            <button class="btn btn-default" type="submit">
                                <span class="glyphicon glyphicon-search" aria-hidden="true"></span>
                            </button>
                        </span>
                    </div>
                </form>
                <?endif;?>

                <?if($site):?>
                <form method="get" id="smartSearchFrm" action="<?php echo $config->domain.'search.php'; ?>">
                    <div class="input-group">
                        <input name="search" type="text" class="form-control" placeholder="Search" id="smart_search" autocomplete="off">
                        <div class="input-group-btn">
                            <button class="btn btn-default" type="submit">
                                <span class="glyphicon glyphicon-search" aria-hidden="true"></span>
                            </button>
                        </div>
                    </div>
                </form>
                <?endif;?>
            </div>
        </div>
    </div>
</div>
            <!-- grey div END-->
</header>
        <!-- header END -->
 <? endif; ?>

<?php
$html_blog = '<section class="header-white hidden-xs">
        <div class="container">
            <div class="row">
                <div class="col-lg-3 col-md-3 col-sm-3 inspiration text-center">
                    <a href="blog"><img src="//mjtrends.b-cdn.net/images/blog/site/inspiration.png"></a>
                </div>
                <div class="col-lg-9 col-md-9 col-sm-9">
                <div class="row header-hl">
                <div class="subscribe-div">
                    <button type="button" class="prevent-search-close btn btn-warning subscribe-popup">Subscribe</button>
                </div>
                <form method="get" id="smartSearchFrm" action="'. $config->domain.'search.php" class="col-lg-4 col-sm-4 hidden-xs searchform">
                    <input name="category" class="category" type="hidden" value="Blog">
                    <div class="input-group">
                        <input name="search" type="text" class="search-input form-control closed" placeholder="Search" id="smart_search" autocomplete="off">
                            <div class="dropdown">
                                <div class="current"><span id="searchVal">Blog</span> <span class="glyphicon glyphicon-chevron-down"></span> </div>
                                <ul class="drop-menu">
                                    <li><a href="'. $config->domain .'forum/search.php">Blog</a></li>
                                    <li><a href="'. $config->domain .'search.php">Website</a></li>
                                </ul>
                            </div><!-- dropdown-->
                        
                        <span class="input-group-btn">
                            <button class="btn btn-default" type="submit" id="searchsubmit">
                                <span class="glyphicon glyphicon-search" aria-hidden="true"></span>
                            </button>
                        </span>
                    </div>
                </form>
                
                <ul class="additional-menu">
                    <li><a href="/forum">Forums</a></li>
                    <li><a href="/articles.php">Tutorials</a></li>
                    <li><a href="/blog">Blog</a></li></ul>
                </div>              
            </div>
        </div>
    </div>
</section>
        <div class="container">';
/* <form role="search" method="get" id="searchform" class="col-lg-4 col-sm-4 hidden-xs searchform" action="/blog/">
                    <div class="input-group">
                        <input name="s" id="s" type="text" class="search-input form-control closed" placeholder="Search">
                        <span class="input-group-btn">
                            <button class="btn btn-default" id="searchsubmit" type="submit"><span class="glyphicon glyphicon-search"></span></button>
                        </span>
                    </div>
                </form> */
?>

<?php

if ($section == 'blog') {
    echo $html_blog;
    $cachefile = "../cache/header-responsive-blog.html";
} elseif ($section == 'forum') {
    $cachefile = "../cache/header-responsive-forum.html";
} elseif ($section == 'site') {
    $cachefile = "../cache/header-responsive.html";
} else {
    echo(' <script> alert( "error" ) </script> ');
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