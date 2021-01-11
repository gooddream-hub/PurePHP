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
                                    <div class="col-sm-12 col-md-12" id="etsyProd">
                                        <div id="etsyProd_prev">
                                            <h2 class="sub-header h3 text-gray-800">Select Product</h2>
                                            <div class="panel panel-primary">
                                                <div class="panel-heading" style="position:relative;">
                                                    Step 1 of 2: Select Product by name
                                                    <button id="showProduct" class="btn btn-success" style="position:absolute;right:10px;top:1px;">NEXT <i class="fa fa-arrow-right" aria-hidden="true"></i></button>
                                                </div>
                                                <div class="panel-body">
                                                    <form method="post" action="../../controller/inventory/etsy.php" id="category_form" class="form-inline">
                                                        <input type="hidden" name="product" id="product" >
                                                        <div class="col-md-6 offset-md-3">
                                                            <input type="text" class="form-control" style="width:100%;" id="suggest" name="suggest" autocomplete="off" placeholder="Product title">
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

        <script>
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

            function loadProductForm() {
                $("#suggest").autocomplete({
                    minLength: 3,
                    source: function(request, response) {
                        $.ajax({
                            type: "POST",
                            url: '../../controller/inventory/etsy.php',
                            dataType: "json",
                            data: {
                                title: $("#suggest").val(),
                                type: 'getproductlist'
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
                        $("#product").val(ui.item.value);
                        return false;
                    }
                });
            }

            var categories = '';
            
            $(function() {
                tinyMCEInit();
                loadProductForm();

                /* Next button */
                $('#etsyProd').on('click', '#showProduct', function(){
                    $.ajax({
                        type: "POST",
                        url: '../../controller/inventory/etsy.php',
                        dataType: 'html',
                        data: {
                            id: $('#product').val(),
                            type: 'getproductbyId'
                        },
                        success: function (data) {
                            // $('#etsyProd').html(data);
                            categories = JSON.parse(data).categories;
                            product = JSON.parse(data).product;
                            shipping_temlates = JSON.parse(data).shipping_temlates;

                            // Data Getting
                            document.getElementById("etsyProd_prev").style.display = "none";
                            var html = '';
                            html += '<h2 class="sub-header">Add Product</h2>';
                            html += '<div class="panel panel-primary">';
                            html += '<div class="panel-heading" style="position:relative;">';
                            html += 'Step 2 of 2: Post to Etsy';
                            html += '<button id="showForm" class="btn btn-success pull-right" style="position:absolute;right:10px;top:1px;"><i class="fa fa-arrow-left" aria-hidden="true"></i> BACK</button>';
                            html += '</div>';
                            html += '<div class="panel-body">';
                            html += '<form method="post" action="../../controller/inventory/etsy.php" id="product_form">';
                            html += '<input type="hidden" name="product" id="product" value="' + product[0].cat + ' ' + product[0].type + ' ' + product[0].color + '">';
                            html += '<input type="hidden" name="product_id" id="product_id" value="' + product[0].invid + '">';
                            html += '<input type="hidden" name="category_id" id="category_id" value="0" >';
                            html += '<table class="table">';

                            html += '<tr>';
                            html += '<div class="form-group col-sm-10" style="float: left;">';
                            html += '<label class="control-label" for="Category">Etsy Category(Taxanomy)</label>';
                            html += '<div class="form-inline" id="etsy_cat_wraper">';
                            html += '<div class="form-group">';
                            html += '<select name="etsy_cat" rel="0" class="etsy_cat form-control">';
                            html += '<option value="0">Select Category</option>';
                            for(key in categories) {
                                if(categories.hasOwnProperty(key)) {
                                    html += '<option value="' + categories[key].id + '">' + categories[key].name + '</option>';
                                }
                            }

                            html += '</select>';
                            html += '</div>';
                            html += '</div>';
                            html += '</div>';
                            html += '<div class="form-group col-sm-2" style="float: left;">';
                            html += '<label class="control-label" for="Purch_date">Purch Date</label>';
                            
                            var today = new Date();
                            var dd = today.getDate();
                            var mm = today.getMonth()+1; 
                            var yyyy = today.getFullYear();
                            if(dd<10) 
                            {
                                dd='0'+dd;
                            } 
                            if(mm<10) 
                            {
                                mm='0'+mm;
                            } 
                            today = yyyy + '-'+ mm + '-' + dd;
                            
                            html += '<input type="text" name="purch_date" class="form-control" value="' + today + '" size="8">';
                            html += '</div>';
                            html += '</tr>';
                            //
                            html += '<tr>';
                            html += '<td colspan="12">';
                            html += '<div class="form-group col-sm-2" style="float: left;">';
                            html += '<label class="control-label" for="Weight">Type</label>';
                            html += '<input type="text" name="type" class="form-control" value="' + product[0].type + '">';
                            html += '</div>';
                            html += '<div class="form-group col-sm-2" style="float: left;">';
                            html += '<label class="control-label" for="Color">Color</label>';
                            html += '<input type="text" name="color" class="form-control" value="' + product[0].color + '">';
                            html += '</div>';
                            html += '<div class="form-group col-sm-2" style="float: left;">';
                            html += '<label class="control-label" for="Retail_Price">Retail Price</label>';
                            html += '<input type="text" name="retail" class="form-control" value="' + product[0].retail + '">';
                            html += '</div>';
                            html += '<div class="form-group col-sm-2" style="float: left;">';
                            html += '<label class="control-label" for="Sale">Sale Price</label>';
                            html += '<input type="text" name="saleprice" class="form-control" value="' + product[0].saleprice + '">';
                            html += '</div>';
                            html += '<div class="form-group col-sm-2" style="float: left;">';
                            html += '<label class="control-label" for="Quantity">Quantity</label>';
                            html += '<input type="text" name="invamount" class="form-control" value="' + product[0].invamount + '">';
                            html += '</div>';
                            html += '<div class="form-group col-sm-2" style="float: left;">';
                            html += '<label class="control-label" for="Shipping">Shipping temlate</label>';
                            html += '<select name="shipping_templates" id="shipping_templates" class="form-control">';

                            for(key in shipping_temlates) {
                                if(shipping_temlates.hasOwnProperty(key)) {
                                    html += '<option value="' + shipping_temlates[key].id + '">' + shipping_temlates[key].title + '</option>';
                                }
                            }

                            html += '</select>';
                            html += '</div>';
                            html += '</td>';
                            html += '</tr>';
                            //
                            html += '<tr>';
                            html += '<td colspan="12">';
                            html += '<div class="form-group col-sm-2" style="float: left;">';
                            html += '<label class="control-label" for="Weight">Weight</label>';
                            html += '<input type="text" name="weight" class="form-control" value="' + product[0].weight + '">';
                            html += '</div>';
                            html += '<div class="form-group col-sm-2" style="float: left;">';
                            html += '<label class="control-label" for="Volume">Volume</label>';
                            html += '<input type="text" name="volume" class="form-control" value="' + product[0].volume + '">';
                            html += '</div>';
                            html += '<div class="form-group col-sm-2" style="float: left;">';
                            html += '<label class="control-label" for="Length">Length</label>';
                            html += '<input type="text" name="minlength" class="form-control" value="' + product[0].minlength + '">';
                            html += '</div>';
                            html += '<div class="form-group col-sm-2" style="float: left;">';
                            html += '<label class="control-label" for="Width">Width</label>';
                            html += '<input type="text" name="minwidth" class="form-control" value="' + product[0].minwidth + '">';
                            html += '</div>';
                            html += '<div class="form-group col-sm-2" style="float: left;">';
                            html += '<label class="control-label" for="Height">Height</label>';
                            html += '<input type="text" name="minheight" class="form-control" value="' + product[0].minheight + '">';
                            html += '</div>';
                            html += '<div class="form-group col-sm-2" style="float: left;">';
                            html += '<label class="control-label" for="Wholesale">Wholesale</label>';
                            html += '<input type="text" name="wholesale" class="form-control" value="' + product[0].wholesale + '">';
                            html += '</div>';
                            html += '</td>';
                            html += '</tr>';
                            //
                            html += '<tr>';
                            html += '<td colspan="12">';
                            html += '<div class="form-group col-sm-6" style="float: left;">';
                            html += '<label class="control-label" for="Title">Title</label>';
                            html += '<input type="text" name="title" class="form-control" value="' + product[0].title + '" size="50">';
                            html += '</div>';
                            html += '<div class="form-group col-sm-3" style="float: left;">';
                            html += '<label class="control-label" for="Video">Video</label>';
                            html += '<input type="text" name="video" class="form-control" value="' + product[0].video + '" size="28">';
                            html += '</div>';
                            html += '<div class="form-group col-sm-3" style="float: left;">';
                            html += '<label class="control-label" for="Tutorials">Tutorials</label>';
                            html += '<input type="text" name="tutorials" class="form-control" value="' + product[0].tutorials + '" size="24">';
                            html += '</div>';
                            html += '</td>';
                            html += '</tr>';
                            //
                            html += '<tr>';
                            html += '<td colspan="12">';
                            html += '<div class="form-group col-sm-6" style="float: left;">';
                            html += '<label class="control-label" for="Description">Description</label>';
                            html += '<textarea id="descr" name="descr" class="mceAdvanced form-control" rows="9" cols="50">' + product[0].descr + '</textarea>';
                            html += '</div>';
                            html += '<div class="form-group col-sm-3" style="float: left;">';
                            html += '<label class="control-label" for="Meta_key">Meta Key</label>';
                            html += '<textarea id="meta_key" name="meta_key" class="form-control" rows="9" cols="28">' + product[0].meta_key + '</textarea>';
                            html += '</div>';
                            html += '<div class="form-group col-sm-3" style="float: left;">';
                            html += '<label class="control-label" for="Meta_desc">Meta Desc</label>';
                            html += '<textarea id="meta_desc" name="meta_desc" class="form-control" rows="9" cols="28">' + product[0].meta_desc + '</textarea>';
                            html += '</div>';
                            html += '</td>';
                            html += '</tr>';
                            //
                            var featureArray = product[0].features;
                            var feature_text = '';
                            if(Array.isArray(featureArray)){
                                featureArray.forEach((v, i) => {
                                    feature_text += i + ': ' + v + '\n';
                                });
                            } else {
                                var regex = /<br\s*[\/]?>/gi;
                                feature_text = featureArray.replace(regex, '\n');
                            }

                            html += '<tr>';
                            html += '<td colspan="12">';
                            html += '<div class="form-group col-sm-6" style="float: left;">';
                            html += '<label class="control-label" for="Features">Features</label>';
                            html += '<textarea id="features" name="features" class="form-control" rows="9" cols="50">' + feature_text + '</textarea>';
                            html += '</div>';
                            html += '<div class="form-group col-sm-6" style="float: left;">';
                            html += '<label class="control-label" for="Features">Images</label>';
                            html += '<div class="product-images">';
                            
                            if(product[0].img) {
                                var product_image = product[0].img;
                                for (i = 0; i <product_image.length; i++) {
                                    html += '<img src="http://mjtrends.b-cdn.net/images/product/' + product[0].invid + '/' + product_image[i].path + '_115x87.jpg" alt="' + product_image[i].alt + '" data-path="images/product/' + product[0].invid + '/ '+ product_image[i].path  + '" class="img-thumbnail">';
                                    html += '<input type="hidden" value="images/product/' + product[0].invid + '/' + product_image[i].path + '" name="img[]">';
                                }
                            } else {
                                html += '<h3 class="text-warning text-center">No images</h3>';
                            }
                            
                            html += '</div>';
                            html += '</div>';
                            html += '</td>';
                            html += '</tr>';
                            //
                            html += '<tr>';
                            html += '<td colspan="12">';
                            html += '<div class="form-group col-sm-12 col-sm-offset-6 text-right">';
                            html += '<button class="btn btn-success pull-right" data-loading-text="Please wait..." id="importButton"><i class="fa fa-plus" aria-hidden="true"></i> Post to Etsy</button>';
                            html += '</div>';
                            html += '</td>';
                            html += '</tr>';
                            html += '</table>';
                            html += '</form>';
                            html += '</div>';
                            html += '</div>';
                            $('#etsyProd_next').html('');
                            $('#etsyProd_next').append(html);
                            document.getElementById("etsyProd_next").style.display = "block";

                            tinyMCE.EditorManager.execCommand('mceAddControl',true, 'descr');
                        },
                        error: function(result) {
                        }
                    });
                    return false;
                });

                /* Back button */
                $('#etsyProd').on('click', '#showForm', function(){
                    tinyMCE.EditorManager.execCommand('mceRemoveEditor',true, 'descr');
                    
                    $.ajax({
                        type: "POST",
                        url: '../../controller/inventory/etsy.php',
                        dataType: 'html',
                        data: {
                            product: $('#product').val(),
                            product_id: $('#product_id').val(),
                            type: 'product_form'
                        },
                        success: function (data) {
                            // $('#etsyProd').html(data);
                            document.getElementById("etsyProd_prev").style.display = "block";
                            document.getElementById("etsyProd_next").style.display = "none";
                            loadProductForm();
                        },
                        error: function(result) {
                            
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
                    
                    var child = categories;
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

                /* Product form submit to etsy */
                $('#etsyProd').on('submit', '#product_form', function() {
                    var action = $(this).attr('action');
                    $.ajax({
                        type: "POST",
                        url: action,
                        dataType: 'json',
                        data: $(this).serialize() + "&type=product_listing",
                        success: function (data) {
                            if(data.access == 'success') {
                                var done = true;
                                if(done) {
                                    if(data.info.data == 'error'){
                                        $('#myModal').find('.modal-title').html('<span class="text-danger"><i class="fa fa-exclamation-triangle" aria-hidden="true"></i> Error</div>');
                                        $('#myModal').find('.modal-body').html('<div class="text-danger">'+data.info.msg+'</div>');
                                        $('#myModal').modal('show');
                                    } else {
                                        $('#myModal').find('.modal-title').html('<span class="text-'+data.info.data+'"><i class="fa fa-exclamation-triangle" aria-hidden="true"></i> '+data.info.data+'</div>');
                                        $('#myModal').find('.modal-body').html('<div class="text-'+data.info.data+'">'+data.info.msg+'</div>');
                                        $('#myModal').find('.modal-body').append('<div class="text-center"><a class="btn btn-success" href="">Add other product <i class="fa fa-arrow-right" aria-hidden="true"></i></a></div>');
                                        $('#myModal').modal('show');
                                        $('#importButton').replaceWith('<a class="btn btn-success pull-right" href="">Add other product <i class="fa fa-arrow-right" aria-hidden="true"></i></a>');
                                    }
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
            });
        </script>
    </body>
</html>
