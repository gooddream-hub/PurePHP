<?php
    include('../../common/auth.php');
    include('../../controller/marketing/homepage.php');
    include('../../config/config.php');
    $auth = new Auth;
    $auth->get_auth();
    $auth->get_warehouse_access();
    $session_id = session_id();
    $config_admin_base_url = $config['config_admin_base_url'];
    $DocumentRoot= $config['DocumentRoot'];
?>


<!-- Header -->
<?php include('../layout/header.php'); ?>
<!-- End of Header -->

<div id="wrapper">

<!-- Layout -->
<?php include('../layout/'.$_SESSION['group'].'.php');?>
<!-- End of Layout -->

<style>
    .hp-row-mt{margin: 0 30px 30px 30px;}
    .hp-row-mt input, .hp-row-mt textarea{width: 80%; margin-top: 0.125rem;}
    .portlet-handler{background-color: #ddd; border: 1px solid #ccc; margin: 10px auto; text-align: center; width: 100px;}
    .btn-portlet-handler{padding: 0.175rem 0.35rem; border: 1px solid #858796;}
    .btn-mr{padding: 0.175rem 0.5rem; border: 1px solid #858796; margin-right: 10px;}
    .one-fourth{margin-bottom: 30px;}
    .shop-the-look-title{color: #858796!important;}

</style>

    <div id="content-wrapper" class="d-flex flex-column">
        <!-- Main Content -->
        <div id="content">
            <?php include('../layout/topbar.php');?>
            <link href="<?php $_SERVER['HTTP_HOST']; ?>/admin3/cache/css/jquery-ui-1.9.2.custom.min.css" rel="stylesheet">

            <!-- Begin Page Content -->
            <div class="container-fluid">
                <h1 class="h3 mb-4 text-gray-800">Update homepage</h1>
                <div class="card shadow mb-4">
                    <div class="card-body">
                        <div class="homepage_body">

                            <div class="row row1 hp-row-mt">
                                <div class="sortable">
                                    <?php if (!empty($block1)): ?>
                                        <?php foreach($block1 as $block): ?>
                                            <div class="col-md-3 slider one-fourth portlet" style="float: left;">
                                                <div class="portlet-handler"><- Drag -></div>
                                                <img class="" src="<?=$block['src']?>" style="width: 100%;" title="" />
                                                <input type="text" name="color" value="<?=$block['color']?>" placeholder="Color..." />
                                                <input type="text" name="top_text" value="<?=$block['top_text']?>" placeholder="Top text..." />
                                                <textarea name="caption" rows="5" cols="33" placeholder="Caption. Use h1, h2 and h3 tags. Tags h1 and h2 will be transformed to uppercase"><?=$block['caption']?></textarea><br>
                                                <input type="checkbox" style="width: initial;" class="transparent" name="button_transparent" value="<?=$block['button_transparent']?>" <?=$block['button_transparent'] ? 'checked' : ''?> /> Transparent button<br>
                                                <input type="text" name="button" value="<?=$block['button']?>" placeholder="Button text..." />
                                                <input type="text" name="url" value="<?=$block['url']?>" placeholder="URL..." />
                                                <button type="button" class="btn btn-portlet-handler">Delete</button>
                                            </div>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <div class="col-md-3 slider one-fourth portlet">
                                            <div class="portlet-handler"><- Drag -></div>
                                            <img src="../../assets/img/marketing/image/source_06.png" style="width: 100%;" title="" />
                                            <input type="text" name="color" value="" placeholder="Color..."/>
                                            <input type="text" name="top_text" value="" placeholder="Top text..."/>
                                            <textarea name="caption" rows="5" cols="33" placeholder="Caption. Use h1, h2 and h3 tags and <h1 class='xl'> for extra large font. Tags H1 and H2 will be transformed to uppercase"></textarea><br>
                                            <input type="checkbox"  class="transparent" name="button_transparent" value="" /> Transparent button<br>
                                            <input type="text" name="button" value="" placeholder="Button text..."/>
                                            <input type="text" name="url" value="" placeholder="URL..."/>
                                            <button type="button" class="btn btn-portlet-handler">Delete</button>
                                        </div>
                                    <?php endif; ?>
                                </div>
                                <div class="one-fourth add-new" style="font-size: 15em;">+</div>
                            </div>

                            <div class="row row2 hp-row-mt">
                                <?php if (!empty($block2)): ?>
                                    <?php foreach($block2 as $block): ?>
                                        <div class="col-md-3 slider one-fourth portlet" style="float: left;">
                                            <div class="featured-product one-fourth" item_id="<?=$block['item_id']?>">
                                                <img class="featured-products-img" src="<?=$block['src']?>" title="" style="width: 100%;" />
                                                <span class="featured-products-title"><?=$block['title']?></span>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <?php for($i=1;$i<=8;$i++): ?>
                                        <div class="col-md-3" style="float: left;">
                                            <div class="featured-product one-fourth" item_id="">
                                                <img class="featured-products-img" style="width: 100%;" src="../../assets/img/marketing/email_template/fashion-news/source_06.png" title="" />
                                                <span class="featured-products-title"></span>
                                            </div>
                                        </div>
                                    <?php endfor; ?>
                                <?php endif; ?>
                            </div>

                            <div class="row row3 hp-row-mt">
                                <?php if (!empty($block3)): ?>
                                    <?php foreach($block3 as $block): ?>
                                        <div class="col-md-3" style="float: left;">
                                            <div class="latest-blog-post one-fourth" post-id="<?php echo isset($block['post_id'])?$block['post_id']:''?>">
                                                <img class="latest-blog-post-img" src="<?=$block['src']?>" title="" style="width: 100%;" />
                                                <span class="latest-blog-post-title"><?=$block['title']?></span>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <?php for($i=1;$i<=8;$i++): ?>
                                        <div class="col-md-3" style="float: left;">
                                            <div class="latest-blog-post one-fourth" post-id="">
                                                <img class="latest-blog-post-img" width="200px" style="width: 100%;" src="../../assets/img/marketing/email_template/fashion-news/source_14.png" title="" />
                                                <span class="latest-blog-post-title"></span>
                                            </div>
                                        </div>
                                    <?php endfor; ?>
                                <?php endif; ?>
                            </div>
                            
                            <div class="row row4 hp-row-mt">
                                <?php if (!empty($block4)): ?>
                                    <?php foreach($block4 as $block): ?>
                                        <div class="col-md-3" style="float: left;">
                                            <div class="shop-the-look one-fourth" item_id="<?=(isset($block['item_id']))?$block['item_id']:''?>">
                                                <img class="shop-the-look-img" style="width: 100%;" src="<?=$block['src']?>" title="" />
                                                <span contenteditable="true" class="shop-the-look-title"><?=$block['title']?></span>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <?php for($i=1;$i<=20;$i++): ?>
                                        <div class="col-md-3" style="float: left;">
                                            <div class="shop-the-look one-fourth" item_id="">
                                                <img class="shop-the-look-img" style="width: 100%;" src="../../assets/img/marketing/email_template/fashion-news/source_06.png" title="" />
                                                <span contenteditable="true" class="shop-the-look-title"></span>
                                            </div>
                                        </div>
                                    <?php endfor; ?>
                                <?php endif; ?>
                            </div>

                            <div class="row row5 hp-row-mt">
                                <?php if (!empty($block5)): ?>
                                    <?php foreach($block5 as $block): ?>
                                        <div class="col-md-3" style="float: left;">
                                            <div class="slider one-fourth">
                                                <img class="" style="width: 100%;" src="<?=$block['src']?>" title="" />
                                                <input type="text" name="caption" value="<?=$block['caption']?>" placeholder="Caption..."/>
                                                <input type="text" name="short_desc" value="<?=$block['short_desc']?>" placeholder="Short desc..."/>
                                                <input type="text" name="url" value="<?=$block['url']?>" placeholder="URL..."/>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <?php for($i=1;$i<=4;$i++): ?>
                                        <div class="col-md-3" style="float: left;">
                                            <div class="slider one-fourth">
                                                <img class="one-fourth" style="width: 100%;" src="../../assets/img/marketing/email_template/fashion-news/source_06.png" title="" />
                                                <input type="text" name="caption" value="" placeholder="Caption..."/>
                                                <input type="text" name="short_desc" value="" placeholder="Short desc..."/>
                                                <input type="text" name="url" value="" placeholder="URL..."/>
                                            </div>
                                        </div>
                                    <?php endfor; ?>
                                <?php endif; ?>
                            </div>

                            <div class="row row6 hp-row-mt">
                                <?php if (!empty($block6)): ?>
                                    <?php foreach($block6 as $block): ?>
                                        <div class="col-md-3" style="float: left;">
                                            <div class="participate one-fourth">
                                                <img class="participate-img" style="width: 100%;" src="<?=$block['src']?>" title="" />
                                                <input type="text" value="<?=$block['url']?>" placeholder="URL..."/>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <?php for($i=1;$i<=3;$i++): ?>
                                        <div class="col-md-3" style="float: left;">
                                            <div class="participate one-fourth">
                                                <img class="participate-img" style="width: 100%;" src="../../assets/img/marketing/email_template/fashion-news/source_06.png" title="" />
                                                <input type="text" value="" placeholder="URL..."/>
                                            </div>
                                        </div>
                                    <?php endfor; ?>
                                <?php endif; ?>
                            </div>

                            <div class="row hp-row-mt" style="margin-left: 0;">
                                <button class="btn btn-mr" id="preview">Preview</button>
                                <button class="btn btn-mr" id="push_live">Push Live</button>
                                <button class="btn btn-mr" id="clear">Clear</button>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- End of Main Content -->

<!-- Footer -->
<?php include('../layout/footer.php'); ?>
<!-- End of Footer -->
<script type="text/javascript" src="../../assets/jquery-ui/jquery-ui.min.js"></script>
<script type="text/javascript" src="<?php $_SERVER['HTTP_HOST']; ?>/admin3/cache/js/jquery.blockUI.js"></script>
<script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jquery/1.12.1/jquery.min.js"></script>
<script type="text/javascript" src="../../assets/js/jquery.ui.widget.js"></script>
<script type="text/javascript" src="../../assets/js/jquery.fileupload.js"></script>

<script type="text/javascript">
    var jQuery_1_9_1 = $.noConflict(true);
</script>

<script type="text/javascript">
    var el = '';
    var uploader_class = 'uploader_block';
    var uploader_html  = ''+
        '<div class="'+uploader_class+'">'+
            '<input id="fileupload" class="btn" type="file" name="homepage_img"/>'+
            '<button id="fileupload_cancel" class="btn fileupload_cancel btn-portlet-handler" onclick="remove_upload(); return false;">Cancel</button>'+
            '<div id="progress">'+
                '<div class="bar" style="width: 0%;"></div>'+
            '</div>'+
        '</div>'+
    '';

    var uploader_options = {
        url: '../../controller/marketing/homepage_ajax.php',
        dataType: 'json',
        progressall: function (e, data) {
            var progress = parseInt(data.loaded / data.total * 100, 10);
            jQuery_1_9_1('#progress .bar').css(
                'width',
                progress + '%'
            ).css('height', 5)
            .css('background', '#4e73df');
        },
        done: function (e, data) {
            jQuery_1_9_1.each(data.result.files, function (index, file) {
                jQuery_1_9_1(el).attr('src', file.url);
                jQuery_1_9_1('.'+uploader_class).remove();
            });
        }
    };
    
    var product_suggestion = '<div class="autocomplete-container"><input type="text" name="product_suggest" id="product_suggest" autocomplete="off" class="ac_input ui-autocomplete-input" style="width: 99%"><span role="status" aria-live="polite" class="ui-helper-hidden-accessible"></span></div>';

    $(document).ready(function() {
        prepare_page();
        
        $(".row1[class!='uploader_block'] .add-new").click(function(e) {
            el = this;
            add_row1_fileuploader(el);
            
            return false;
        });

        $(".row1 img").click(function() {

            el = this;
            add_fileuploader(el);
            return false;
        });
        
        $('body').on('click', '.row1 button', function() {
            $(this).parent().remove();
        });
        
        $(".row2 .featured-product").mouseenter(function(e) {
            $('.autocomplete-container').remove();
            $(this).append(product_suggestion);
            make_autocomplete();
        }).mouseleave(function(e) {
            if ($(e.target).attr('id')  != 'product_suggest') {
                $('.autocomplete-container').remove();
            }
        });
        
        $(".row3 .latest-blog-post").mouseenter(function(e) {
            $('.latest-blog-post-selector').remove();
            var block = this;
            $.ajax(
            {
                type: "POST",
                url: '../../controller/marketing/homepage_ajax.php',
                dataType: "json",
                data: {
                    type : "block3"
                },
                success: function (data)
                {
                    var options = '<option value=""></option>';
                    for (var i in data) {
                        options += '<option value="'+data[i]['ID']+'">'+data[i]['post_title']+'</option>';
                    }
                    var html = ''+
                        '<div class="latest-blog-post-selector">'+
                        '<select class="current-latest-post">'+
                        options+
                        '</select>'+
                        '</div>';
                    $(block).append(html);
                },
                error: function(result) {

                }
            });
        }).mouseleave(function(e) {
            $('.latest-blog-post-selector').remove();
        });
        
        $(".row4 .shop-the-look").mouseenter(function(e) {
            $('.autocomplete-container').remove();
            $(this).append(product_suggestion);
            make_shop_autocomplete();
        }).mouseleave(function(e) {
            if ($(e.target).attr('id')  != 'product_suggest') {
                $('.autocomplete-container').remove();
            }
        });
        
        $(".row5 img").click(function() {
            el = this;
            add_fileuploader(el);
            return false;
        });

        $(".row6 img").click(function() {
            el = this;
            add_fileuploader(el);
            return false;
        });
        
        $('body').on('change', '.latest-blog-post-selector', function() {
            var post = $(this).parent();
            var post_id = $('select', this).val();
            $.ajax(
            {
                type: "POST",
                url: '../../controller/marketing/homepage_ajax.php',
                dataType: "json",
                data: {
                    id: post_id,
                    type: 'getpostimg'
                },
                success: function (data)
                {
                    if (data) {
                        $('.latest-blog-post-img', post).attr('src', data['img']);
                        $('.latest-blog-post-title', post).html(data['title']);
                        post.attr('post-id', data['post_id']);
                    }
                },
                error: function(result) {

                }
            });
        });
        
        $('#push_live').click(function() {
            $('.add-new').remove();
            remove_fileuploaders();
            $.ajax(
            {
                type: "POST",
                url: '../../controller/marketing/homepage_ajax.php',
                dataType: "json",
                data: {
                    data: get_homepage_html(),
                    type: 'pushlive'
                },
                success: function (data)
                {
                    if (data) {
                        alert('sucess');
                        window.open("<?php echo $config['homepage_creation_frontend_url'].'marketing/index.php';?>");
                    }
                    else {
                        alert('error');
                    }
                },
                error: function(result) {
                    alert('error');
                }
            });
        });
        
        $('#preview').click(function() {
            $('.add-new').remove();
            remove_fileuploaders();
            $.ajax(
            {
                type: "POST",
                url: '../../controller/marketing/homepage_ajax.php',
                dataType: "json",
                data: {
                    data: get_homepage_html(),
                    type: 'preview'
                },
                success: function (data)
                {
                    if (data) {
                        window.open("<?php echo $config['homepage_creation_frontend_url'].'marketing/index.php?';?>" + Math.random());

                    }
                    else {
                        alert('error');
                    }
                },
                error: function(result) {
                    alert('error');
                }
            });
        });
        
        $('#clear').click(function() {
            $.ajax(
            {
                type: "POST",
                url: '../../controller/marketing/homepage_ajax.php',
                data: {
                    type: 'clear'
                },
                dataType: "json",
                success: function (data)
                {
                    if (data) {
                        window.location.reload();
                    }
                    else {
                        alert('error');
                    }
                },
                error: function(result) {
                    alert('error');
                }
            });
        });

        $('.transparent').change(function() {
            if(this.checked){
                $(this).prop('checked', true);
                $(this).prop('value', 'transparent_button');
            }
            else {
                $(this).prop('checked', false);
                $(this).prop('value', "");
            }
        });

        // Drag'n'drop for top hero
        $( ".sortable" ).sortable({
            handle: ".portlet-handler",
            cancel: ".portlet-toggle",
            placeholder: "portlet-placeholder ui-corner-all one-fourth"
        });

        $( ".portlet" ).addClass( "ui-widget ui-widget-content ui-helper-clearfix ui-corner-all" );

        // refresh buttons
        $('.refresh-posts').click(function(){
            $.ajax(
            {
                type: "POST",
                dataType: "json",
                url: '../../controller/marketing/homepage_ajax.php',
                data: {
                    type : "getlatestpostswithimg"
                },
                success: function (data)
                {
                   
                    $('.latest-blog-post').each(function(i,v) {
                        if(data[i]) {
                            $('.latest-blog-post-img', $(this)).attr('src', data[i]['guid']);
                            $('.latest-blog-post-title', $(this)).html(data[i]['post_title']);
                            $(this).attr('post-id', data[i]['post_parent']);
                        }
                    });
                }
            });
        });

        $('.refresh-pins').click(function(){
            $.ajax(
            {
                type: "POST",
                url: '../../controller/marketing/homepage_ajax.php',
                dataType: "json",
                data: {
                    type : "getlatestpins"
                },
                success: function (data)
                {
                    $('.shop-the-look').each(function(i,v) {
                        if(data[i]) {
                            console.log(data[i]['img']);
                            $('.shop-the-look-img', $(this)).attr('src', data[i]['img']);
                            $('.shop-the-look-title', $(this)).html(data[i]['title']).css('color', 'red');
                            $(this).attr('item_id', data[i]['id']);
                        }
                    });
                }
            });
        });
    });
    
    function remove_upload() {
        jQuery_1_9_1('.'+uploader_class).remove();
    }
    
    function make_autocomplete() {
        $("#product_suggest").autocomplete({
            minLength: 3,
            source: function(request, response) 
            {
                $.ajax(
                {
                    type: "POST",
                    url: '../../controller/marketing/homepage_ajax.php',
                    dataType: "json",
                    data: {
                        title: $("#product_suggest").val(),
                        type : "block2"
                    },
                    success: function (data)
                    {
                        response( $.map( data.suggest, function(item) {
                            if (typeof this.data == 'undefined') {
                                this.data = {};
                            }
                            this.data[item.id] = {
                                img: item.img,
                                title: item.title,
                            };
                            return {
                                label: item.name,
                                value: item.id,
                                option: this
                            };
                        }));

                    },
                    error: function(result) {

                    }
                });
            }, 
            select: function (event, ui)
            {
                $("#product_suggest").val(ui.item.label);
                var img_block = $("#product_suggest").parents('.featured-product');
                img_block.attr('item_id', ui.item.value);
                $('.featured-products-img', img_block).attr('src', ui.item.option.data[ui.item.value].img);
                $('.featured-products-title', img_block).html(ui.item.option.data[ui.item.value].title);
                return false;
            }
        });
    }

    function make_shop_autocomplete() {
        $("#product_suggest").autocomplete({
            minLength: 3,
            source: function(request, response)
            {
                $.ajax(
                        {
                            type: "POST",
                            url: '../../controller/marketing/homepage_ajax.php',
                            dataType: "json",
                            data: {
                                title: $('#product_suggest').val(),
                                type: 'block4'
                            },
                            success: function (data)
                            {
                                response( $.map( data.suggest, function(item) {
                                    if (typeof this.data == 'undefined') {
                                        this.data = {};
                                    }
                                    this.data[item.id] = {
                                        img: item.img,
                                        title: item.title
                                    };
                                    return {
                                        label: item.name,
                                        value: item.id,
                                        option: this
                                    };
                                }));

                            },
                            error: function(result) {

                            }
                        });
            },
            select: function (event, ui)
            {
                $("#product_suggest").val(ui.item.label);
                var img_block = $("#product_suggest").parents('.shop-the-look');
                img_block.attr('item_id', ui.item.value);
                $('.shop-the-look-img', img_block).attr('src', ui.item.option.data[ui.item.value].img);
                $('.shop-the-look-title', img_block).html(ui.item.option.data[ui.item.value].title);
                return false;
            }
        });
    }
    
    function get_homepage_html() {
        var blocks = '';
        
        $('.homepage_body .row-title').remove();
        $('.homepage_body > br').remove();
        $('.homepage_body > hr').remove();


        
        blocks = {
            block1: $('.homepage_body .row1 .one-fourth').map(function(){
                        return {
                                src: $(this).find('img').attr('src'),
                                color:$(this).find('input[name="color"]').val(),
                                top_text:$(this).find('input[name="top_text"]').val(),
                                caption:$(this).find('textarea[name="caption"]').val(),
                                button_transparent:$(this).find('input[name="button_transparent"]').val(),
                                button:$(this).find('input[name="button"]').val(),
                                url:$(this).find('input[name="url"]').val()
                            }
                    }).get(),
            block2: $('.homepage_body .row2 .one-fourth').map(function(){
                        return {
                                src: $(this).find('img').attr('src'),
                                title:$(this).find('.featured-products-title').html(),
                                item_id:$(this).attr("item_id")
                            }
                    }).get(),
            block3: $('.homepage_body .row3 .one-fourth').map(function(){
                        return {
                                src: $(this).find('img').attr('src'),
                                title:$(this).find('.latest-blog-post-title').html(),
                                post_id:$(this).attr('post-id')
                            }
                        }).get(),
            block4: $('.homepage_body .row4 .one-fourth').map(function(){
                        return {
                                src: $(this).find('img').attr('src'),
                                title:$(this).find('.shop-the-look-title').html(),
                                item_id:$(this).attr("item_id")
                            }
                        }).get(),
            block5: $('.homepage_body .row5 .one-fourth').map(function(){
                        return {
                                src: $(this).find('img').attr('src'),
                                caption:$(this).find('input[name="caption"]').val(),
                                short_desc:$(this).find('input[name="short_desc"]').val(),
                                url:$(this).find('input[name="url"]').val()
                            }
                        }).get(),
            block6: $('.homepage_body .row6 .one-fourth').map(function(){
                        return {
                                src: $(this).find('img').attr('src'),
                                url:$(this).find('input').val()
                            }
                        }).get()
        };
        
        prepare_page();
        
        return blocks;
    }
    
    function prepare_page() {
        remove_fileuploaders();
        $('.row1').before('<hr>', '<br>', '<div class="row-title" style="padding-bottom: 10px;font-weight: bold;">Top Hero:</div>');
        $('.row2').before('<hr>', '<br>', '<div class="row-title" style="padding-bottom: 10px;font-weight: bold;">Weekly Featured:</div>');
        $('.row3').before('<hr>', '<br>', '<button class="btn refresh-posts refresh-btn btn-portlet-handler" style="float: right;">Refresh</button><div class="row-title" style="padding-bottom: 10px;font-weight: bold;">Latest from Blog:</div>');
        $('.row4').before('<hr>', '<br>', '<button class="btn refresh-pins refresh-btn btn-portlet-handler" style="float: right;">Refresh</button><div class="row-title" style="padding-bottom: 10px;font-weight: bold;">Shop the look:</div>');
        $('.row5').before('<hr>', '<br>', '<div class="row-title" style="padding-bottom: 10px;font-weight: bold;">Bottom slides:</div>');
        $('.row6').before('<hr>', '<br>', '<div class="row-title" style="padding-bottom: 10px;font-weight: bold;">Participate/Learn:</div>');
    }
    
    function remove_fileuploaders() {
        jQuery_1_9_1('.'+uploader_class).remove();
    }
    
    function add_row1_fileuploader(el) {
        if (!jQuery_1_9_1('.'+uploader_class+' #fileupload').length) {
            jQuery_1_9_1(el).after(uploader_html);
        }
        var options = $.extend( true, {}, uploader_options );
        options.done = function (e, data) {
            jQuery_1_9_1.each(data.result.files, function (index, file) {
                if (typeof file.error == 'undefined') {
                    jQuery_1_9_1(el).before('<div class="col-md-3 slider one-fourth portlet" style="float: left;"><img class="one-fourth" style="width: 100%;" src="'+file.url+'" title="" />' +
                        '<input style="width: 80%;" type="text" name="color" value="" placeholder="Color..."/>' +
                        '<input style="width: 80%;" type="text" name="top_text" value="" placeholder="Top text..."/>' +
                        '<textarea style="width: 80%;" name="caption" rows="5" cols="33" placeholder="Caption. Use h1, h2 and h3 tags and h1 class=xl for extra large font. Tags h1 and h2 will be transformed to uppercase"></textarea><br>' +
                        '<input style="width: auto;" type="checkbox" class="transparent" name="button_transparent" value="" /> Transparent button<br>' +
                        '<input style="width: 80%;" type="text" name="button" value="" placeholder="Button text..."/>' +
                        '<input style="width: 80%; margin-right: 3px; float: left;" type="text" name="url" value="" placeholder="URL..."/><br/>' +
                        '<button type="button" class="btn btn-portlet-handler">Delete</button></div>');
                }
                else {
                    alert(file.error);
                }
            });
            jQuery_1_9_1('.'+uploader_class).remove();
        };
        jQuery_1_9_1('#fileupload').attr('multiple', true);
        jQuery_1_9_1('#fileupload').fileupload(options);
    }
    
    function add_fileuploader(el) {
        if (!jQuery_1_9_1('.'+uploader_class+' #fileupload').length) {
            jQuery_1_9_1(el).after(uploader_html);
        }
        jQuery_1_9_1('#fileupload').fileupload(uploader_options);
    }
</script>