<?php
    include('../../common/auth.php');
    include('../../config/config.php');
    $auth = new Auth;
    $auth->get_auth();
    $auth->get_warehouse_access();
    $session_id = session_id();
    $config_admin_base_url = $config['config_admin_base_url'];
    $DocumentRoot= $config['DocumentRoot'];
?>

<!DOCTYPE html>
<html lang="en">

    <head>
        <meta>
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <meta name="description" content="">
        <meta name="author" content="">
        <meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests">
        <title>MJTrends Etsy</title>
        <link rel="icon" href="<?php $_SERVER['HTTP_HOST'] ?>/admin3/assets/img/favicon.ico" />

        <!-- Custom fonts for this template-->
        <link href="../../assets/vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
        <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

        <!-- Custom styles for this template-->
        <link href="../../assets/css/sb-admin-2.min.css" rel="stylesheet">
        <link href="//cdn.datatables.net/1.10.19/css/jquery.dataTables.min.css" rel="stylesheet">
        <link href="../../assets/css/custom.css" rel="stylesheet">
        <link rel="stylesheet" type="text/css" href="../../assets/jquery-ui/jquery-ui.min.css" />
        <style>
           .btn-default {
                color: #333;
                background-color: #fff;
                border-color: #ccc;
            }
            .text-danger {
                color: #a94442!important;
                font-family: "Trebuchet MS",Verdana,Arial,sans-serif;
            }
            .span.text-danger, .span.text-success {
                font-size: 18px!important;
            }
            .div.text-danger, .div.text-success {
                font-size: 12px!important;
            }
            .text-success {
                color: #3c763d!important;
                font-family: "Trebuchet MS",Verdana,Arial,sans-serif;
            }
            .hide {
                display: none;
            }
        </style>
        
    </head>

    <body id="page-top">
        <div id="wrapper">
            <?php include('../layout/'.$_SESSION['group'].'.php');?>
            <div id="content-wrapper" class="d-flex flex-column">
            <div id="content">
                <?php include('../layout/topbar.php');?>

                <!-- Begin Page Content -->
                <div class="container-fluid">
                    <h1 class="h3 mb-4 text-gray-800">Etsy » </h1>

                    <div class="card shadow mb-4">
                        <div class="card-body">
                            <div class="homepage_body">
                                <div class="row">
                                    <div class="col-sm-12 col-md-12" id="etsyCat">
                                        <div id="etsyProd_prev">
                                            <h2 class="sub-header h3 text-gray-800">Add All Products From Category</h2>
                                            <div class="panel panel-primary">
                                                <div class="panel-heading" style="position:relative;">
                                                    Step 1 of 2: Select Category
                                                    <button id="showProducts" class="btn btn-success" style="position:absolute;right:10px;top:1px;">NEXT <i class="fa fa-arrow-right" aria-hidden="true"></i></button>
                                                </div>
                                                <div class="panel-body">
                                                    <form method="post" action="../../controller/inventory/etsy_cat.php" id="category_form" class="form-inline">
                                                        <input type="hidden" name="category" id="category" />
                                                        <div class="col-md-6 offset-md-3">
                                                            <input type="text" class="form-control" style="width:100%;" id="suggest" name="suggest" autocomplete="off" placeholder="Category title" />
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                        <div id="etsyProd_next">
                                        </div>
                                    </div>

                                    <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                                        <div class="modal-dialog" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h4 class="modal-title" id="myModalLabel">Modal title</h4>
                                                </div>
                                                <div class="modal-body"></div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
            <!-- End of Main Content -->
            <!-- Footer -->
            <footer class="sticky-footer bg-white">
                <div class="container my-auto">
                    <div class="copyright text-center my-auto">
                        <span>Copyright &copy; MJTrends 2019</span>
                    </div>
                </div>
            </footer>
            <!-- End of Footer -->
            </div>
        </div>
        <!-- End of Page Wrapper -->

        <!-- Scroll to Top Button-->
        <a class="scroll-to-top rounded" href="#page-top">
            <i class="fas fa-angle-up"></i>
        </a>

        <!-- Logout Modal-->
        <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Ready to Leave?</h5>
                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
                </div>
                <div class="modal-body">Select "Logout" below if you are ready to end your current session.</div>
                <div class="modal-footer">
                <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                <a class="btn btn-primary" href="login.html">Logout</a>
                </div>
            </div>
            </div>
        </div>

        <!-- Bootstrap core JavaScript-->
        <script type="text/javascript" src="../../assets/js/tiny_mce/tiny_mce.js"></script>

          <!-- Bootstrap core JavaScript-->
        <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
        <script src="../../assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

        <!-- Core plugin JavaScript-->
        <script src="../../assets/vendor/jquery-easing/jquery.easing.min.js"></script>

        <!-- Custom scripts for all pages-->
        <script src="../../assets/js/sb-admin-2.min.js"></script>
        <script src="//cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
        <script src="../../assets/js/demo/datatables-demo.js"></script>
        <script type="text/javascript" src="../../assets/js/plupload/plupload.full.min.js"></script>
        
        <!-- <script src="../../assets/js/jquery.autocomplete.min.js"></script> -->
        <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

        <script type="text/javascript">
            function tinyMCEInit() {
                tinyMCE.init({
                    mode : "textareas",
                    theme : "advanced",
                    theme_advanced_buttons1 : "bold,italic,underline,strikethrough,bullist,numlist,code,fontsizeselect,forecolor,undo,redo",
                    theme_advanced_buttons2 : "",
                    theme_advanced_buttons3 : "" ,
                    editor_selector : "mceAdvanced"
                });
            }

            function loadCategoryForm() {
                $("#suggest").autocomplete({
                    minLength: 3,
                    source: function(request, response) {
                        $.ajax({
                            type: "POST",
                            url: '../../controller/inventory/etsy_cat.php',
                            dataType: "json",
                            data: {
                                title: $("#suggest").val(),
                                type: 'getcatslist'
                            },
                            success: function (data) {
                                response( $.map( data.suggest, function(item) {
                                    return {
                                        label: item.name,
                                        value: item.id,
                                        option: this
                                    };
                                }));
                            },
                        });
                    }, 
                    select: function (event, ui) {
                        $("#suggest").val(ui.item.label);
                        $("#category").val(ui.item.value);
                        return false;
                    }
                });
            }
        </script>
        <script type="text/javascript">
            var etsy_cats = '';
            $(function() {
                tinyMCEInit();
                loadCategoryForm();

                $('#etsyCat').on('click', '#showProducts', function(){
                    $.ajax({
                        type: "POST",
                        url: '../../controller/inventory/etsy_cat.php',
                        dataType: 'html',
                        data: {
                            category: $('#category').val(),
                            type: 'products'
                        },
                        success: function (data) {
                            // $('#etsyCat').html(data);
                            categories = JSON.parse(data).categories;
                            products = JSON.parse(data).products;
                            etsy_cats = JSON.parse(data).etsy_cats;

                            console.log(products);

                            // Data Getting
                            document.getElementById("etsyProd_prev").style.display = "none";
                            var html = '';
                            html += '<h2 class="sub-header h3 text-gray-800">Add All Products From Category</h2>';
                            html += '<div class="panel panel-primary">';
                            html += '<div class="panel-heading" style="position:relative;">';
                            html += 'Step 2 of 2: Manage Products';
                            html += '<button id="showCats" class="btn btn-success pull-right" style="position:absolute;right:10px;top:1px;"><i class="fa fa-arrow-left" aria-hidden="true"></i> BACK</button>';
                            html += '</div>';
                            html += '<div class="panel-body">';
                            html += '<h3 style="font-size: 14px; font-weight: 700;">Category: <strong class="text-info" style="color: #31708f; font-size: 14px; font-weight: 700;">' + categories + '</strong> <small style="font-size: 14px; font-weight: 700;">(' + products.length + ' products total found)</small></h3>';
                            html += '<form method="post" action="../../controller/inventory/etsy_cat.php" id="product_form">';
                            
                            html += '<input type="hidden" name="category" id="category" value="' + categories + '" >';
                            html += '<input type="hidden" name="category_id" id="category_id" value="0" >';
                            
                            html += '<div class="form-group col-sm-12">';
                            html += '<label class="control-label" for="Category">Etsy Category(Taxanomy)</label>';
                            html += '<div class="form-inline" id="etsy_cat_wraper">';
                            html += '<div class="form-group">';
                            html += '<select name="etsy_cat" rel="0" class="etsy_cat form-control">';
                            html += '<option value="0">Select Category</option>';
                            for(key in etsy_cats) {
                                if(etsy_cats.hasOwnProperty(key)) {
                                    html += '<option value="' + etsy_cats[key].id + '">' + etsy_cats[key].name + '</option>';
                                }
                            }
                            html += '</select>';
                            html += '</div>';
                            html += '</div>';
                            html += '</div>';

                            html += '<table class="table">';
                            html += '<tr>';
                            html += '<td></td>';
                            html += '<td>Color</td>';
                            html += '<td>Retails</td>';
                            html += '<td>Sale Price</td>';
                            html += '<td>Wholesale</td>';
                            html += '<td>Quantity</td>';
                            html += '<td></td>';
                            html += '</tr>';

                            for(key in products) {
                                if(products.hasOwnProperty(key)) {
                                    html += '<tbody id="product_' + products[key].invid + '">';
                                    html += '<tr class="product_short">';
                                    html += '<td><a href="#" rel="' + products[key].invid + '" class="show_details"><i class="fa fa-plus" aria-hidden="true"></i></a></td>';
                                    html += '<td>' + products[key].color + '</td>';
                                    html += '<td><input type="text" name="listings[' + products[key].invid + '][retail]" value="' + products[key].retail + '" size="5"></td>';
                                    html += '<td><input type="text" name="listings[' + products[key].invid + '][saleprice]" value="' + products[key].saleprice + '" size="5"></td>';
                                    html += '<td><input type="text" name="listings[' + products[key].invid + '][wholesale]" value="' + products[key].wholesale + '" size="5"></td>';
                                    html += '<td><input type="text" name="listings[' + products[key].invid + '][invamount]" value="' + products[key].invamount + '" size="3"></td>';
                                    html += '<td class="text-right"><a href="#" rel="' + products[key].invid + '" class="remove_product"><i class="fa fa-times" aria-hidden="true"></i></a></td>';
                                    html += '</tr>';

                                    html += '<tr class="product_details hide">';
                                    html += '<td  colspan="40"> <div class = "row">';

                                    html += '<div class="col-sm-2"> <div class = "form-group clearfix">';
                                    html += '<label class="control-label" for="Weight">Weight</label>';
                                    html += '<input type="text" name="listings[' + products[key].invid + '][weight]" class="form-control" value="' + products[key].weight + '">';
                                    html += '</div></div>';

                                    html += '<div class="col-sm-2"> <div class = "form-group clearfix">';
                                    html += '<label class="control-label" for="Volume">Volume</label>';
                                    html += '<input type="text" name="listings[' + products[key].invid + '][volume]" class="form-control" value="' + products[key].volume + '">';
                                    html += '</div></div>';

                                    html += '<div class="col-sm-2"> <div class = "form-group clearfix">';
                                    html += '<label class="control-label" for="Width">Min Width</label>';
                                    html += '<input type="text" name="listings[' + products[key].invid + '][minwidth]" class="form-control" value="' + products[key].minwidth + '">';
                                    html += '</div></div>';
                                    

                                    html += '<div class="col-sm-2"> <div class = "form-group clearfix">';
                                    html += '<label class="control-label" for="Length">Min Length</label>';
                                    html += '<input type="text" name="listings[' + products[key].invid + '][minlength]" class="form-control" value="' + products[key].minlength + '">';
                                    html += '</div></div>';

                                    html += '<div class="col-sm-2"> <div class = "form-group clearfix">';
                                    html += '<label class="control-label" for="Height">Min Height</label>';
                                    html += '<input type="text" name="listings[' + products[key].invid + '][minheight]" class="form-control" value="' + products[key].minheight + '">';
                                    html += '</div></div>';

                                    html += '<div class="col-sm-2"> <div class = "form-group clearfix">';
                                    html += '<label class="control-label" for="Ship">Ship Group</label>';
                                    html += '<input type="text" name="listings[' + products[key].invid + '][ship]" class="form-control" value="">';
                                    html += '</div></div>';

                                    html += '<div class="col-sm-2"> <div class = "form-group clearfix">';
                                    html += '<label class="control-label" for="Purch">Purch Date</label>';
                                    html += '<input type="text" name="listings[' + products[key].invid + '][purch]" class="form-control" value="' + products[key].purch + '">';
                                    html += '</div></div>';

                                    html += '<div class="col-sm-2"> <div class = "form-group clearfix">';
                                    html += '<label class="control-label" for="Status">Status</label>';
                                    html += '<select name="listings[' + products[key].invid + '][status]" class="form-control">';
                                    html += '<option value="active">Active</option>';
                                    html += '<option value="disabled">Disabled</option>';
                                    html += '</select>';
                                    html += '</div></div>';

                                    html += '<div class="col-sm-6"> <div class = "form-group clearfix">';
                                    html += '<label class="control-label" for="Title">Title</label>';
                                    html += '<input type="text" name="listings[' + products[key].invid + '][title]" class="form-control" value="' + products[key].title + '">';
                                    html += '</div></div>';

                                    html += '<div class="col-sm-6"> <div class = "form-group clearfix">';
                                    html += '<label class="control-label" for="Description">Description</label>';
                                    html += '<textarea name="listings[' + products[key].invid + '][descr]" id="mce_' + products[key].invid + '" class="form-control mceAdvanced" rows="9">' + products[key].descr + '</textarea>';
                                    html += '</div></div>';

                                    html += '<div class="col-sm-6"> <div class = "form-group clearfix">';
                                    html += '<label class="control-label" for="Features">Features</label>';
                                    featureArray = JSON.parse(products[key].features)
                                    feature_text = '';
                                    if(Array.isArray(featureArray)){
                                        featureArray.forEach((v, i) => {
                                            feature_text += i + ': ' + v + '\n';
                                        });
                                    } else {
                                        for(fKey in featureArray) {
                                            feature_text += fKey + ': ' + featureArray[fKey] + '\n';
                                        }
                                    }

                                    html += '<textarea name="listings[' + products[key].invid + '][features]" class="form-control" rows="9">' + feature_text + '</textarea>';
                                    html += '</div></div>';

                                    html += '<div class="col-sm-12"> <div class = "form-group clearfix">';
                                    html += '<h3 style="font-size: 1rem;">Images</h3>';

                                    product_img = products[key].img;
                                    product_images = (product_img != 'undefined') ? JSON.parse(product_img) : [];
                                    if(product_images.length) {
                                        k = 0;
                                        for (inkey in product_images) {
                                            in_url = 'http://mjtrends.b-cdn.net/images/product/' + products[key].invid + '/' + product_images[inkey].path + '_115x87.jpg';
                                            in_alt = product_images[inkey].alt;
                                            if(product_images[inkey] && product_images[inkey].path){
                                                in_path = 'images/product/' + products[key].invid + '/' + product_images[inkey].path;
                                            } else {
                                                in_path = '';
                                            }

                                            k++;
                                            html += '<img src="' + in_url + '" alt="' + in_alt + '" class="img-thumbnail">';
                                            html += '<input type="hidden" value="' + in_path + '" name="listings[' + products[key].invid + '][img][]" >';
                                            if(k > 4) {
                                                break;
                                            }
                                        }
                                    } else {
                                        html += '<h3 class="text-warning text-center">No images</h3>';
                                    }
                                
                                    html += '</div></div>';
                                    html += '</div>';
                                    html += '</td>';
                                    html += '</tr>';
                                    html += '</tbody>';
                                }
                            }
                            html += '</table>';
                            html += '<div class="form-group col-sm-12 col-sm-offset-6 text-right">';
                            html += '<button class="btn btn-success pull-right" data-loading-text="Please wait..." id="importButton"><i class="fa fa-plus" aria-hidden="true"></i> Post to Etsy</button>';
                            html += '</div>';
                            html += '</form>';
                            html += '</div>';
                            html += '</div>';
                            $('#etsyProd_next').html('');
                            $('#etsyProd_next').append(html);
                            document.getElementById("etsyProd_next").style.display = "block";
                        },
                        error: function(result) {
                            
                        }
                    });
                    return false;
                });

                $('#etsyCat').on('click', '#showCats', function(){
                    $('.mceAdvanced').each(function() {
                        var $editor = $(this);
                        var $showLink = $(this).parents('tbody').find('a.show_details').find('i');
                        if($showLink.hasClass('fa-minus')) {
                            tinyMCE.EditorManager.execCommand('mceRemoveEditor',true, $editor.attr('id'));
                        }
                    });

                    $.ajax({
                        type: "POST",
                        url: '../../controller/inventory/etsy_cat.php',
                        dataType: 'html',
                        data: {
                            type: 'category',
                            category: $('#category').val()
                        },
                        success: function (data) {
                            // $('#etsyCat').html(data);
                            document.getElementById("etsyProd_prev").style.display = "block";
                            document.getElementById("etsyProd_next").style.display = "none";
                            loadCategoryForm();
                        },
                        error: function(result) {
                            
                        }
                    });
                    return false;
                });

                $('#etsyCat').on('click', '.show_details', function(){
                    var invid = $(this).attr('rel');
                    if($(this).find('i').hasClass('fa-plus')) {
                        $(this).find('i').removeClass('fa-plus').addClass('fa-minus');
                        $('#product_'+invid).find('tr.product_details').removeClass('hide').addClass('active');
                        $('#product_'+invid).find('tr.product_short').addClass('active');

                        tinyMCE.EditorManager.execCommand('mceAddControl',true, 'mce_'+invid);
                    } else {
                        $(this).find('i').removeClass('fa-minus').addClass('fa-plus');
                        $('#product_'+invid).find('tr.product_details').addClass('hide').removeClass('active');
                        $('#product_'+invid).find('tr.product_short').removeClass('active');

                        tinyMCE.EditorManager.execCommand('mceRemoveEditor',true, 'mce_'+invid);
                    }
                    $(this).blur();
                    return false;
                });

                $('#etsyCat').on('click', '.remove_product', function(){
                    var invid = $(this).attr('rel');
                    tinyMCE.EditorManager.execCommand('mceRemoveEditor',true, 'mce_'+invid);
                    $('#product_'+invid).remove();
                    return false;
                });

                $('#etsyCat').on('submit', '#product_form', function() {
                    var action = $(this).attr('action');
                    $.ajax({
                        type: "POST",
                        url: action,
                        dataType: 'json',
                        data: $(this).serialize() + "&type=import",
                        success: function (data) {
                            if(data.access == 'success') {
                                var done = true;
                                $.each(data.info, function(key, val){
                                    var $row = $('#product_'+key).find('.product_short');
                                    var k = 0;
                                    $row.removeClass('danger').removeClass('success');
                                    $row.find('i.fa-question-circle').remove();
                                    if(val.data == 'success') {
                                        $row.find('td').each(function(){
                                            k++;
                                            if(k == 3){
                                                $(this).attr('colspan', 4).html(val.msg);
                                            }
                                            if(k > 3 && k < 7) {
                                                $(this).remove();
                                            }
                                        });
                                        $row.addClass('success');
                                    } else {
                                        done = false;
                                        $row.find('td').each(function(){
                                            k++;
                                            if(k == 2){
                                                $(this).prepend('<i class="fa fa-question-circle fa-2x" title="Error: '+val.msg+'"></i> ');
                                            }
                                        });
                                        $row.addClass('danger');
                                    }

                                    //var $row_im = $('#product_'+key).find('.product_details');
                                    //$row_im.find('.form-group').remove();
                                });
                                if(done) {
                                    $('#importButton').replaceWith('<a class="btn btn-success pull-right" href="">Add other category <i class="fa fa-arrow-right" aria-hidden="true"></i></a>');
                                    $('#showCats').remove();
                                }
                            } else {
                                $('#myModal').find('.modal-title').html('<span class="text-danger"><i class="fa fa-exclamation-triangle" aria-hidden="true"></i> Error</div>');
                                $('#myModal').find('.modal-body').html('<div class="text-danger">'+data.msg+'</div>');
                                $('#myModal').modal('show');
                            }
                        }
                    });
                    return false;
                });
                
                /* category dropdown selects */
                
                var parents = new Array();
                
                $('body').on('change', '.etsy_cat', function(){
                    var $select = $(this);
                    var slected = $select.val();
                    var curent = parseInt($select.attr('rel'));
                    var level = curent + 1;

                    $('#category_id').val(slected);

                    // Remove all daughter selects if it exists
                    $('.etsy_cat').each(function(){
                        if( $(this).attr('rel') > curent ) {
                            $(this).parent('div.form-group').remove();
                        }
                    });
                    parents = parents.slice(0, curent);
                    
                    var child = etsy_cats;
                    if(parents) {
                        $.each(parents, function(key, pid){
                            child = child[pid].child;
                        });
                    }

                    parents.push(slected);

                    if(child[slected].child != undefined) {
                        var html = '<option value="0">Select Sub-Category</option>';
                        $.each(child[slected].child, function(key, value){
                            html = html + '<option value="'+key+'">'+value.name+'</option>'
                        });
                        $('#etsy_cat_wraper').append('<div class="form-group"><select name="etsy_cat" rel="'+level+'" class="etsy_cat form-control">'+html+'</select></div>');
                    }
                });
                /* End category dropdown selects*/

            });
        </script>
    </body>
</html>
