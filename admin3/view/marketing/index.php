<?php
    include('../../common/auth.php');
    include('../../controller/marketing/index.php');
    include('../../config/config.php');
    $auth = new Auth;
    $auth->get_auth();
    $auth->get_warehouse_access();
    $session_id = session_id();
    $config_admin_base_url = $config['config_admin_base_url'];
    $DocumentRoot= $config['DocumentRoot'];

?>
<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/css/bootstrap.min.css">
        <link rel="stylesheet" type="text/css" href="../../cache/css/forum-css-forum.min.css">
        <link href='http://fonts.googleapis.com/css?family=Lato&subset=latin,latin-ext' rel='stylesheet' type='text/css'>
        <title>MJTrends: Your online store for vinyl fabrics, latex sheeting, snakeskin, faux leather, veggie leather, sewing supplies, and more.</title>
    </head>
    <body class="homepage">
        <header>
            <div class="header">
                <?php include('./inner-header.php'); ?>

        <!--Slider-->
        <div class="c-wrapper">
            <div id="myCarousel" class="carousel slide" data-ride="carousel">
                <!-- Wrapper for slides -->
                <div class="carousel-inner" role="listbox">
                    <?php foreach($block1 as $index=>$slide): ?>
                        <div class="slide item<?=($index==0)?' active':''?>">
                            <div class="image-bg" style="background-image: url('<?=$slide['src']?>')">
                                <img src="<?=$slide['src']?>">
                            </div>
                            <div class="info-wrapper">
                                <div class="info-block">
                                    <div class="slide-info" style="color: <?=$slide['color'] ? $slide['color'] : '#555' ;?>">
                                        <?php if ($slide['top_text']) : ?>
                                            <h3 class="dancing"><?=$slide['top_text'];?></h3>
                                            <div class="slide-line" style="background-color: <?=$slide['color'] ? $slide['color'] : '#555' ;?>"></div>
                                        <?php endif; ?>
                                        <?=$slide['caption'] ? $slide['caption'] : '' ;?>
                                        <?php if ($slide['button']) : ?>
                                            <div class="slide-line" style="background-color: <?=$slide['color'] ? $slide['color'] : '#555' ;?>"></div>
                                            <a href="<?=$slide['url']?>" class="btn btn-slide <?=$slide['button_transparent'] ? 'btn-transparent' : 'btn-blue' ;?>">
                                                <?=$slide['button'];?>
                                            </a>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>

                <?php if( count($block1) > 1 ) : ?>
                    <!-- Indicators -->
                    <ol class="carousel-indicators" style="display: none;">
                        <?php foreach($block1 as $index=>$slide): ?>
                            <li data-target="#myCarousel" data-slide-to="<?=$index?>"<?=($index==0)?' class="active"':''?>></li>
                        <?php endforeach; ?>
                    </ol>
                    <!-- Left and right controls -->
                    <a class="carousel-control left" href="#myCarousel" role="button" data-slide="prev">
                        <svg viewBox="0 0 100 100">
                            <path d="M 10,50 L 60,100 L 70,90 L 30,50  L 70,10 L 60,0 Z" class="arrow"></path>
                        </svg>
                    </a>
                    <a class="carousel-control right" href="#myCarousel" role="button" data-slide="next">
                        <svg viewBox="0 0 100 100">
                            <path d="M 10,50 L 60,100 L 70,90 L 30,50  L 70,10 L 60,0 Z" class="arrow" transform="translate(100, 100) rotate(180) "></path>
                        </svg>
                    </a>
                <?php endif; ?>
            </div>
        </div>
        <!--/slider-->

        <!--featured-->
        <div class="container text-center featured">
            <h2 class="with_line"><span>WEEKLY FEATURED PRODUCTS</span></h2>
            <ul class="bxslider-featured">
                <?php foreach($block2 as $block): ?>
                    <li class="featured-product one-fourth">
                        <?php if(isset($block['is_out_of_stock']) && $block['is_out_of_stock']): ?>
                            <span class="out_of_stock">OUT OF STOCK</span>
                        <?php elseif(isset($block['saleprice']) && $block['saleprice'] > 0): ?>
                            <span class="sale_sign">SALE!</span>
                        <?php endif; ?>
                        <a href="<?=$block['link']?>" class="featured-products-link">
                            <img class="featured-products-img" data-pin-nopin="true" title="<?=$block['title']?>" src="<?=$block['src']?>">
                        </a>
                        <span class="featured-products-cat"><?php if(isset($block['cat']))echo $block['cat']?></span>
                        <hr/>
                        <span class="featured-products-title">
                            <a href="<?=$block['link']?>"><?=$block['title']?></a>
                        </span>
                        <span class="price">
                            <?php if(isset($block['saleprice']) && $block['saleprice'] > 0): ?>
                                <span class="old_price">$<?php if(isset($block['retail']))echo $block['retail']?></span>
                                <span class="sale_price">$<?=$block['saleprice']?></span>
                            <?php else : ?>
                                <span>$<?php if(isset($block['retail']))echo $block['retail']?></span>
                            <?php endif; ?>
                        </span>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>
        <!--/featured-->

        <!--blog-->
        <div class="container text-center blog">
            <h2 class="with_line"><span>LATEST FROM OUR BLOG</span></h2>
            <ul class="bxslider-blog">
                <?php foreach($block3 as $block): ?>
                    <li class="latest-blog-post one-fourth">
                        <div class="content-wrapper">
                            <div class="image-text-wrapper">
                                <a href="<?php if(isset($block['post_url']))echo $block['post_url']?>">
                                    <div class="image-wrapper">
                                        <img class="latest-blog-post-img" width="200px" title="<?php if(isset($block['post_title']))echo $block['post_title']?>" src="<?=$block['src']?>">
                                    </div>
                                    <div class=" text-wrapper latest-blog-post-link">
                                        <h3 class="latest-blog-post-title"><?php if(isset($block['post_title']))echo$block['post_title']?></h3>
                                        <hr/>
                                        <div class="short-text"><?php if(isset($block['post_preview']))echo $block['post_preview']?></div>
                                        <?php if(isset($block['comments_cnt']) && $block['comments_cnt']) : ?>
                                            <div class="comments-cnt"><?=$block['comments_cnt']?> COMMENT<?= ($block['comments_cnt'] > 1)?'S':'' ?></div>
                                        <?php endif ; ?>
                                    </div>
                                </a>
                            </div>
                            <div class="post-date">
                                <span class="post-date-day">
                                    <?php if(isset($block['post_date_day'])){
                                        echo $block['post_date_day'];
                                    }
                                    ?>
                                </span>
                                <span class="post-date-month"><?php if(isset($block['post_date_month']))echo $block['post_date_month']?></span>
                            </div>
                        </div>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>
        <!--/blog-->

        <!--shop-->
        <div class="container text-center shop">
            <h2 class="with_line"><span>SHOP THE LOOK</span></h2>
            <ul class="bxslider-shop">
                <?php foreach($block4 as $block): ?>
                    <li class="latest-blog-post one-fourth">
                        <a href="<?=$block['link']?>">
                            <div class="shop-wrapper">
                                <div class="shop-image-wrapper">
                                    <img class="shop-the-look-img" data-pin-nopin="true" title="<?=$block['title']?>" src="<?=$block['src']?>">
                                </div>
                                <div class="shop-image-text">
                                    <span><?=substr($block['title'],0,31)?></span>
                                </div>
                            </div>
                        </a>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>
        <!--/shop-->

        <!--sliderBottom-->
        <div class="c-wrapper sliderBottom">
            <div id="myCarouselBottom" class="carousel slide" data-ride="carousel">
                <!-- Indicators -->
                <ol class="carousel-indicators">
                    <?php foreach($block5 as $index=>$slide): ?>
                        <li data-target="#myCarouselBottom" data-slide-to="<?=$index?>"<?=($index==0)?' class="active"':''?>></li>
                    <?php endforeach; ?>
                </ol>

                <!-- Wrapper for slides -->
                <div class="carousel-inner" role="listbox">
                    <?php foreach($block5 as $index=>$slide): ?>
                        <div class="item<?=($index==0)?' active':''?>">
                            <a href="<?=$slide['url']?>"><img class="one-fourth" src="<?=$slide['src']?>" title=""/>
                                <div class="carousel-caption">
                                    <h3><?=$slide['caption']?></h3>
                                    <p><?=$slide['short_desc']?></p>
                                </div>
                            </a>
                        </div>
                    <?php endforeach; ?>
                </div>

                <!-- Left and right controls -->
                <a class="left carousel-control" href="#myCarouselBottom" role="button" data-slide="prev">
                    <span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
                    <span class="sr-only">Previous</span>
                </a>
                <a class="right carousel-control" href="#myCarouselBottom" role="button" data-slide="next">
                    <span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>
                    <span class="sr-only">Next</span>
                </a>
            </div>
        </div>
        <!--/sliderBottom-->

        <!--participate-->
        <div class="container text-center participate">
            <h2 class="with_line"><span>PARTICIPATE/LEARN</span></h2>
            <div class="row6">
                <? foreach($block6 as $block): ?>
                    <div class="participate one-fourth">
                        <a href="<?=$block['url']?>"><img class="participate-img" title="" src="<?=$block['src']?>"></a>
                    </div>
                <? endforeach; ?>
            </div>
        </div>
        <!--/participate-->

        <!--footer -->
        <?php include('./inner-footer.php'); ?>
        <!--/footer-->

        <script>(function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){(i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)})(window,document,'script','//www.google-analytics.com/analytics.js','ga');ga('create', 'UA-36049628-1', 'auto');ga('send', 'pageview');</script>

        <script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
        <script src="//mjtrends.b-cdn.net/cache/js/jquery.bxslider.min.js"></script>
        <script src="//mjtrends.b-cdn.net/cache/js/logic-functions.min.js"></script>
        <script src="//mjtrends.b-cdn.net/cache/js/bootstrap.min.js"></script>
        <script src="//ajax.googleapis.com/ajax/libs/jqueryui/1.11.4/jquery-ui.min.js"></script>
        <script src="//mjtrends.b-cdn.net/cache/js/jquery-colorbox-jquery.colorbox-min.js"></script>
        <script src="//mjtrends.b-cdn.net/cache/js/js-custom.min.js"></script>
        
        <script>
            $(document).ready(function () {
                $('.bxslider-featured').bxSlider({
                    minSlides: 1,
                    maxSlides: 8,
                    slideWidth: 247,
                    slideMargin: 20,
                    homePage: '.featured'
                });
                $('.bxslider-blog').bxSlider({
                    minSlides: 1,
                    maxSlides: 8,
                    slideWidth: 339,
                    slideMargin: 22,
                    homePage: '.blog'
                });
                $('.bxslider-shop').bxSlider({
                    minSlides: 1,
                    maxSlides: 20,
                    slideWidth: 247,
                    slideMargin: 20,
                    homePage: '.shop'
                });
            });
        </script>
    </body>
</html>
