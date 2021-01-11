<?php
  include('../../common/auth.php');
  include('../../controller/inventory/getdata.php');
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

<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">

  <!-- Page Wrapper -->
  <div id="wrapper">

    <?php include('../layout/'.$_SESSION['group'].'.php');?>
    <!-- Content Wrapper -->
    <div id="content-wrapper" class="d-flex flex-column">

      <!-- Main Content -->
      <div id="content">

        <!-- Topbar -->
        <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">

          <!-- Sidebar Toggle (Topbar) -->
          <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
            <i class="fa fa-bars"></i>
          </button>

          <!-- Topbar Search -->
          <form class="d-none d-sm-inline-block form-inline mr-auto ml-md-3 my-2 my-md-0 mw-100 navbar-search">
            <div class="input-group">
              <input type="text" class="form-control bg-light border-0 small" placeholder="Search for..." aria-label="Search" aria-describedby="basic-addon2">
              <div class="input-group-append">
                <button class="btn btn-primary" type="button">
                  <i class="fas fa-search fa-sm"></i>
                </button>
              </div>
            </div>
          </form>

          <!-- Topbar Navbar -->
          <ul class="navbar-nav ml-auto">

            <!-- Nav Item - Search Dropdown (Visible Only XS) -->
            <li class="nav-item dropdown no-arrow d-sm-none">
              <a class="nav-link dropdown-toggle" href="#" id="searchDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <i class="fas fa-search fa-fw"></i>
              </a>
              <!-- Dropdown - Messages -->
              <div class="dropdown-menu dropdown-menu-right p-3 shadow animated--grow-in" aria-labelledby="searchDropdown">
                <form class="form-inline mr-auto w-100 navbar-search">
                  <div class="input-group">
                    <input type="text" class="form-control bg-light border-0 small" placeholder="Search for..." aria-label="Search" aria-describedby="basic-addon2">
                    <div class="input-group-append">
                      <button class="btn btn-primary" type="button">
                        <i class="fas fa-search fa-sm"></i>
                      </button>
                    </div>
                  </div>
                </form>
              </div>
            </li>

            <!-- Nav Item - Alerts -->
            <li class="nav-item dropdown no-arrow mx-1">
              <a class="nav-link dropdown-toggle" href="#" id="alertsDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <i class="fas fa-bell fa-fw"></i>
                <!-- Counter - Alerts -->
                <span class="badge badge-danger badge-counter">3+</span>
              </a>
              <!-- Dropdown - Alerts -->
              <div class="dropdown-list dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="alertsDropdown">
                <h6 class="dropdown-header">
                  Alerts Center
                </h6>
                <a class="dropdown-item d-flex align-items-center" href="#">
                  <div class="mr-3">
                    <div class="icon-circle bg-primary">
                      <i class="fas fa-file-alt text-white"></i>
                    </div>
                  </div>
                  <div>
                    <div class="small text-gray-500">December 12, 2019</div>
                    <span class="font-weight-bold">A new monthly report is ready to download!</span>
                  </div>
                </a>
                <a class="dropdown-item d-flex align-items-center" href="#">
                  <div class="mr-3">
                    <div class="icon-circle bg-success">
                      <i class="fas fa-donate text-white"></i>
                    </div>
                  </div>
                  <div>
                    <div class="small text-gray-500">December 7, 2019</div>
                    $290.29 has been deposited into your account!
                  </div>
                </a>
                <a class="dropdown-item d-flex align-items-center" href="#">
                  <div class="mr-3">
                    <div class="icon-circle bg-warning">
                      <i class="fas fa-exclamation-triangle text-white"></i>
                    </div>
                  </div>
                  <div>
                    <div class="small text-gray-500">December 2, 2019</div>
                    Spending Alert: We've noticed unusually high spending for your account.
                  </div>
                </a>
                <a class="dropdown-item text-center small text-gray-500" href="#">Show All Alerts</a>
              </div>
            </li>

            <!-- Nav Item - Messages -->
            <li class="nav-item dropdown no-arrow mx-1">
              <a class="nav-link dropdown-toggle" href="#" id="messagesDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <i class="fas fa-envelope fa-fw"></i>
                <!-- Counter - Messages -->
                <span class="badge badge-danger badge-counter">7</span>
              </a>
              <!-- Dropdown - Messages -->
              <div class="dropdown-list dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="messagesDropdown">
                <h6 class="dropdown-header">
                  Message Center
                </h6>
                <a class="dropdown-item d-flex align-items-center" href="#">
                  <div class="dropdown-list-image mr-3">
                    <img class="rounded-circle" src="https://source.unsplash.com/fn_BT9fwg_E/60x60" alt="">
                    <div class="status-indicator bg-success"></div>
                  </div>
                  <div class="font-weight-bold">
                    <div class="text-truncate">Hi there! I am wondering if you can help me with a problem I've been having.</div>
                    <div class="small text-gray-500">Emily Fowler · 58m</div>
                  </div>
                </a>
                <a class="dropdown-item d-flex align-items-center" href="#">
                  <div class="dropdown-list-image mr-3">
                    <img class="rounded-circle" src="https://source.unsplash.com/AU4VPcFN4LE/60x60" alt="">
                    <div class="status-indicator"></div>
                  </div>
                  <div>
                    <div class="text-truncate">I have the photos that you ordered last month, how would you like them sent to you?</div>
                    <div class="small text-gray-500">Jae Chun · 1d</div>
                  </div>
                </a>
                <a class="dropdown-item d-flex align-items-center" href="#">
                  <div class="dropdown-list-image mr-3">
                    <img class="rounded-circle" src="https://source.unsplash.com/CS2uCrpNzJY/60x60" alt="">
                    <div class="status-indicator bg-warning"></div>
                  </div>
                  <div>
                    <div class="text-truncate">Last month's report looks great, I am very happy with the progress so far, keep up the good work!</div>
                    <div class="small text-gray-500">Morgan Alvarez · 2d</div>
                  </div>
                </a>
                <a class="dropdown-item d-flex align-items-center" href="#">
                  <div class="dropdown-list-image mr-3">
                    <img class="rounded-circle" src="https://source.unsplash.com/Mv9hjnEUHR4/60x60" alt="">
                    <div class="status-indicator bg-success"></div>
                  </div>
                  <div>
                    <div class="text-truncate">Am I a good boy? The reason I ask is because someone told me that people say this to all dogs, even if they aren't good...</div>
                    <div class="small text-gray-500">Chicken the Dog · 2w</div>
                  </div>
                </a>
                <a class="dropdown-item text-center small text-gray-500" href="#">Read More Messages</a>
              </div>
            </li>

            <div class="topbar-divider d-none d-sm-block"></div>

            <!-- Nav Item - User Information -->
            <li class="nav-item dropdown no-arrow">
              <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <span class="mr-2 d-none d-lg-inline text-gray-600 small"><?php $_SESSION['username']?></span>
                <img class="img-profile rounded-circle" src="https://source.unsplash.com/QAB-WJcbgJk/60x60">
              </a>
              <!-- Dropdown - User Information -->
              <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="userDropdown">
                <a class="dropdown-item" href="#">
                  <i class="fas fa-user fa-sm fa-fw mr-2 text-gray-400"></i>
                  Profile
                </a>
                <a class="dropdown-item" href="#">
                  <i class="fas fa-cogs fa-sm fa-fw mr-2 text-gray-400"></i>
                  Settings
                </a>
                <a class="dropdown-item" href="#">
                  <i class="fas fa-list fa-sm fa-fw mr-2 text-gray-400"></i>
                  Activity Log
                </a>
                <div class="dropdown-divider"></div>
                <a class="dropdown-item" href="#" data-toggle="modal" data-target="#logoutModal">
                  <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                  Logout
                </a>
              </div>
            </li>

          </ul>

        </nav>
        <!-- End of Topbar -->

        <!-- Begin Page Content -->
        <div class="container-fluid">

          <!-- Page Heading -->
          <h1><span id="span_label_add" style="display: none" class="h3 mb-4 text-gray-800">Add products</span><span id="span_label_edit" style="display: none" class="h3 mb-4 text-gray-800">Edit products</span> </h1>

          <!-- <input type="hidden" id="reload_invid" name="reload_invid" value="">
          <div class="overlay" id="overlay"></div>

          <div class="selector" id="result">
            <h2><?php //echo $status ?></h2>
            <p><?php //echo $error ?></p>
            <p><a href="javascript:showHide('prodType','result');">Close Window to List Again</a></p>
          </div> -->

          <input type="hidden" id="reload_invid" name="reload_invid" value="">
    
          <div class="card shadow mb-4" id="prodType">
            <div class="card-header py-3" >
              <h6 class="m-0 font-weight-bold text-primary" style="text-align:center; font-size: 2rem;">Step 1 of 3:</h6>
            </div>
            <div class="card-body" style="text-align:center">
              <p style="margin-bottom:2rem">Select the type of upload:</p>
              <a class="typ" href="javascript:setType('new');">New Product</a>
			        <a class="typ" href="javascript:setType('edit');">Edit Product</a>
            </div>
          </div>

          <div class="card shadow mb-4" id="getProd" style="display:none">
              <div class="card-header py-3 m-0 font-weight-bold text-primary" style="text-align:center; font-size: 2rem;">
                <a class="leftBut" href="javascript:showHide('prodType','getProd');"><svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-arrow-left-circle" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
  <path fill-rule="evenodd" d="M8 15A7 7 0 1 0 8 1a7 7 0 0 0 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"/>
  <path fill-rule="evenodd" d="M8.354 11.354a.5.5 0 0 0 0-.708L5.707 8l2.647-2.646a.5.5 0 1 0-.708-.708l-3 3a.5.5 0 0 0 0 .708l3 3a.5.5 0 0 0 .708 0z"/>
  <path fill-rule="evenodd" d="M11.5 8a.5.5 0 0 0-.5-.5H6a.5.5 0 0 0 0 1h5a.5.5 0 0 0 .5-.5z"/>
</svg></a>
                  Step 2 of 3:
                <a id="show-product" class="rightBut" href="javascript:setProd( document.getElementById('products-list').value, true );" ><svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-arrow-right-circle" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
  <path fill-rule="evenodd" d="M8 15A7 7 0 1 0 8 1a7 7 0 0 0 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"/>
  <path fill-rule="evenodd" d="M7.646 11.354a.5.5 0 0 1 0-.708L10.293 8 7.646 5.354a.5.5 0 1 1 .708-.708l3 3a.5.5 0 0 1 0 .708l-3 3a.5.5 0 0 1-.708 0z"/>
  <path fill-rule="evenodd" d="M4.5 8a.5.5 0 0 1 .5-.5h5a.5.5 0 0 1 0 1H5a.5.5 0 0 1-.5-.5z"/>
</svg></a> 
              </div>
              <div class="card-body" style="text-align:center;">
                <h5>Type Selected: <span class="type_sel" id="modType1"></span></h5>
                <h6>Select a similar product:</h6>

                <select  name="products-list" id="products-list" class="step2-style" style="margin: 0 30px 30px">  <!-- onchange="javascript:setProd( this.value, true );" -->
                  <option value="">  --Select Product--  </option>
                  <?php $i = 0; foreach($phpObject as $val):?>
                    <option value="<?php echo $i?>"><?php echo  $val['invid']." ".$val['cat']." ".$val['type']." ".$val['color']?></option>
                  <?php $i++; endforeach;?>
                </select>
                <h6>Or find by product name:</h6>
                <p style="margin-bottom: 10px;">
                  <input type="text" name="suggest" id="suggest" autocomplete="off" class="ac_input step2-style" style="width: 300px"/>
                </p>
              </div>         
          </div>

          <div class="card shadow mb-4" id="setProd" style="display:none">
              <div class="card-header py-3 m-0 font-weight-bold text-primary" style="text-align:center; font-size: 2rem;">
                <a class="leftBut" href="javascript:showHide('getProd,overlay,result','setProd');"><svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-arrow-left-circle" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
  <path fill-rule="evenodd" d="M8 15A7 7 0 1 0 8 1a7 7 0 0 0 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"/>
  <path fill-rule="evenodd" d="M8.354 11.354a.5.5 0 0 0 0-.708L5.707 8l2.647-2.646a.5.5 0 1 0-.708-.708l-3 3a.5.5 0 0 0 0 .708l3 3a.5.5 0 0 0 .708 0z"/>
  <path fill-rule="evenodd" d="M11.5 8a.5.5 0 0 0-.5-.5H6a.5.5 0 0 0 0 1h5a.5.5 0 0 0 .5-.5z"/>
</svg></a>
                  Step 3 of 3:
              </div>
              <div class="card-body" >
                <h5>Type Selected: <span class="type_sel" id="modType2"></span></h5>
                <div id="pendingPagination" style="display: none">
                  <div class="pagination">
                    <a href="#" id="previousPending" class="" >
                    <svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-arrow-left-circle" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
  <path fill-rule="evenodd" d="M8 15A7 7 0 1 0 8 1a7 7 0 0 0 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"/>
  <path fill-rule="evenodd" d="M8.354 11.354a.5.5 0 0 0 0-.708L5.707 8l2.647-2.646a.5.5 0 1 0-.708-.708l-3 3a.5.5 0 0 0 0 .708l3 3a.5.5 0 0 0 .708 0z"/>
  <path fill-rule="evenodd" d="M11.5 8a.5.5 0 0 0-.5-.5H6a.5.5 0 0 0 0 1h5a.5.5 0 0 0 .5-.5z"/>
</svg>
                      <span class="paginationArrow">Prev</span>
                    </a>
                    <span>/</span>
                    <a href="#" id="nextPending" class="">
                      <span class="paginationArrow">Next</span>
                      <svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-arrow-right-circle" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
  <path fill-rule="evenodd" d="M8 15A7 7 0 1 0 8 1a7 7 0 0 0 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"/>
  <path fill-rule="evenodd" d="M7.646 11.354a.5.5 0 0 1 0-.708L10.293 8 7.646 5.354a.5.5 0 1 1 .708-.708l3 3a.5.5 0 0 1 0 .708l-3 3a.5.5 0 0 1-.708 0z"/>
  <path fill-rule="evenodd" d="M4.5 8a.5.5 0 0 1 .5-.5h5a.5.5 0 0 1 0 1H5a.5.5 0 0 1-.5-.5z"/>
</svg>
                    </a>
                    <input type="hidden" value="" id="currentPage" />
                    <input type="hidden" value="" id="maxPage" />
                  </div>
                </div>
                <div style="overflow-x: scroll">
                  <table class="table table-bordered product-table" cellpadding="2" cellspacing="2">
                    <tr>
                      <th class="sku">SKU #</th>
                      <th>Category</th>
                      <th>Type</th>
                      <th>Color</th>
                      <th>Retail Price</th>
                      <th>Sale Price</th>
                      <th>Item Cost</th>
                      <th>Wholesale</th>
                      <th>Quantity</th>
                      <th>Sort Order</th>
                    </tr>
                    <tr>
                      <td class="sku"><input type="invid" name="invid" id="invid" size="5"></td>
                      <td><input type="text" name="cat" id="cat" size="10"/></td>
                      <td><input type="text" name="type" id="type" size="8"/></td>
                      <td><input type="text" name="color" id="color" size="12"/></td>
                      <td><input type="text" name="retail" id="retail" size="7"/></td>
                      <td><input type="text" name="saleprice" id="saleprice" size="7"/></td>
                      <td><input type="text" name="itemcost" id="itemcost" size="10"/></td>
                      <td><input type="text" name="wholesale" id="wholesale" size="7"/></td>
                      <td><input type="text" name="invamount" id="invamount" size="3"/></td>
                      <td><input type="text" name="sort_order" id="sort_order" size="3"/></td>
                    </tr>
                    <tr>
                      <th>Weight</th>
                      <th>Volume</th>
                      <th>Min Width</th>
                      <th>Min Length</th>
                      <th>Min Height</th>
                      <th>Ship Group</th>
                      <th>Purch Date</th>
                      <th>Status</th>
                      <th class="order">New Order</th>
                    </tr>
                    <tr>
                      <td><input type="text" name="weight" id="weight" size="5"/></td>
                      <td><input type="text" name="volume" id="volume" size="10"/></td>
                      <td><input type="text" name="minwidth" id="minwidth" size="8"/></td>
                      <td><input type="text" name="minlength" id="minlength" size="12"/></td>
                      <td><input type="text" name="minheight" id="minheight" size="7"/></td>
                      <td><input type="text" name="shipgroup" id="shipgroup" size="7"/></td>
                      <td><input type="text" name="purch" id="purch" value="<?php echo date("Y-m-d");?>" size="10"/></td>
                      <td style="vertical-align: top;">
                        <select name="active" id="active">
                          <option value="0">Inactive</option>
                          <option value="1">Active</option>
                          <option value="2">Pending</option>
                        </select>
                      </td>
                      <td><input class="order" type="checkbox" name="neworder" id="neworder"></td>
                    </tr>
                    <tr>
                      <th colspan="4">Title</th>
                      <th colspan="2">Video</th>
                      <th colspan="3">Tutorials</th>
                    </tr>
                    <tr>
                      <td colspan="4"><input type="text" name="title" id="title" size="50"/></td>
                      <td colspan="2"><input type="text" name="video" id="video" size="20" /></td>
                      <td colspan="3"><input type="text" name="tutorials" id="tutorials" size="24" /></td>
                    </tr>
                    <tr>

                      <th colspan="4">Description</th>

                      <th colspan="2">Meta Key</th>

                      <th colspan="3">Meta Desc</th>

                      <th colspan="2">Features</th>
                    </tr>
                    <tr>
                      <td colspan="4"><textarea id="descr" name="descr" class="mceAdvanced" rows="9" cols="50"></textarea></td>
                      <td colspan="2"><textarea id="meta_key" name="meta_key" rows="9" cols="20"></textarea></td>
                      <td colspan="3"><textarea id="meta_desc" name="meta_desc" rows="9" cols="28"></textarea></td>
                      <td colspan="2"><div id="features" name="features" style="width:300px; height:160px; overflow-y:auto; border:1px solid #ccc;" contentEditable="true"></textarea></td>
                    </tr>
                  </table>
                </div>
                
                <input type="button" value="Save Product" class="btn btn-success" id="submit" onclick=" javascript:SaveProduct(); " /> <!--checkClear(); -->
                <!-- <input type="button" value="Save Product" id="submitPending" onclick=" javascript:SaveProductPending(); " style="display: none"/> -->
                <input type="hidden" name="subType" value="" id="subType" />
                <input type="hidden" name="clearance" value="<?php echo $clearNum?>"/>
                <input type="hidden" name="invid" id="invid"/>
                <input type="hidden" name="new" value="<?php echo $newNum?>" />

                <table class="table table-bordered product-table">
                  <tr>
                    <td>
                      <div id="div_loadedImagesList"></div>
                      <br />
                    </td>
                  </tr>
                  <tr>
                    <td>&nbsp;
                      <ul id="filelist"></ul>
                    </td>
                  </tr>
                  <tr>
                    <td>
                      <div id="container">
                        <a id="browse" href="javascript:;">[Add more images]</a>
                        <a id="start-upload" href="javascript:;">[Start Upload]</a>
                      </div>
                      <div id="div_uploading_status" title="Saving details"></div>
                    </td>
                  </tr>
                </table>
              </div>              
          </div>
        </div>
        <!-- /.container-fluid -->
      </div>
      <!-- End of Main Content -->

<!-- Footer -->
<?php include('../layout/footer.php'); ?>
<!-- End of Footer -->

<script type="text/javascript" src="../../assets/js/plupload/plupload.full.min.js"></script>
<script type="text/javascript" src="../../assets/js/tiny_mce/tiny_mce.js"></script>
<!-- <script src="../../assets/js/jquery.autocomplete.min.js"></script> -->
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

<script>
  var newproduct_images_list = [];
  var old_invid;
  var save_newproduct_images_list = [];
  var global_pro_type;

  var pro_type = "";
  var current_is_insert= false;
  var current_inv_id= '';
  var number_ofAddedFiles= 0;
  var number_ofUploadedFiles= 0;
  var uploaded_filesList= Array();
  var purch2_insert = false;
  var pending = false;

  tinyMCE.init({
    mode : "textareas",
    theme : "advanced",
    theme_advanced_buttons1 : "bold,italic,underline,strikethrough,bullist,numlist,code,fontsizeselect,forecolor,undo,redo",
    theme_advanced_buttons2 : "",
    theme_advanced_buttons3 : "" ,
    editor_selector : "mceAdvanced"
  });

  <?php echo $jsObject ?>
  var $suggest = <?php echo json_encode($suggest) ?>;

  function ge(el){
    return document.getElementById(el);
  }

  function setType(type) {
    console.log(type);
    pro_type = type;
    showHide('getProd','prodType');
    if ( type == "new" || type == "clearance" ) {
      current_is_insert= true;
    } else{
      current_is_insert= false;
    }

    if (type == "new") {
      purch2_insert = true;
    }

    if (type == "pending") {
      $("#selectPending").css("display", "block");
    }
    else {
      $("#selectProduct").css("display", "block");
    }

    ge('modType1').innerHTML = type;

    $('select[name=products-list]').change();

    if(type == 'edit' || type =='pending'){
      $('.sku').remove();
    }
    else {
      $(".order").hide();
    }
  }

  function showHide(show, hide){
    showArr = new Array();
    hideArr = new Array();
    showArr = show.split(',');
    hideArr = hide.split(',');
    for(i=0; i<hideArr.length; i++){
      if(hideArr[i] != ""){
        document.getElementById(hideArr[i]).style.display = 'none';
      }
    }
    for(j=0; j<showArr.length; j++){
      if(showArr[j] != ""){
        document.getElementById(showArr[j]).style.display = 'block';
      }
    }
  }

  $(document).ready(function() {
		//	InitializePlupload();
		$("#suggest").autocomplete(
      {
        minLength: 0,
        source: $suggest,
        mustMatch: false,
			  matchContains: true,
        // autoFocus:true,
        focus: function( event, ui ) {
          $( "#suggest" ).val( ui.item.label );
          return false;
        },
        select: function( event, ui ) {
          $( "#suggest" ).val( ui.item.label );  
          $( "#products-list" ).val( ui.item.id );  
          return false;
        }
      }     
    )
    .autocomplete( "instance" )._renderItem = function( ul, item ) {
      return $( "<li>" )
        .append( "<div>" + item.name +"</div>" )
        .appendTo( ul );
    };

  });

  function SaveProduct() {
		var cat = $('#cat').val();
		var type = $('#type').val();
		var color = $('#color').val();
		var invid = current_inv_id;

		if(current_inv_id == "new"){
			var invid = $('#invid').val();
		}

		if(current_is_insert == 1){
      console.log(current_is_insert);
			for(var i = 0; i < data.length; i++){
				if(cat == data[i].cat && type == data[i].type && color == data[i].color ){
					alert('Product already exists.  Please change name');
					return false;
				}
				if(invid == data[i].invid){
					alert('SKU already exists.  Please change SKU.');
					return false;
				}
			}
		}

		if ( $( "#neworder" ).prop( "checked" ) ) {
			purch2_insert = true;
		}

		var v_current_inv_id= invid ;
		var v_current_is_insert= current_is_insert;
		var v_session_id=  '<?php echo session_id() ?>' ;
		var v_cat=  document.getElementById('cat').value ;
		var v_type=  document.getElementById('type').value ;
		var v_color=  document.getElementById('color').value ;
		var v_retail=  document.getElementById('retail').value ;
		var v_saleprice=  document.getElementById('saleprice').value ;
		var v_invamount=  document.getElementById('invamount').value ;
		var v_weight=  document.getElementById('weight').value ;
		var v_volume=  document.getElementById('volume').value ;
		var v_wholesale=  document.getElementById('wholesale').value ;
		var v_purch_date=  document.getElementById('purch').value ;
		var v_title=  document.getElementById('title').value ;
		var v_features=  $('#features').html() ;
		var v_video=  document.getElementById('video').value ;
		var v_tutorials=  document.getElementById('tutorials').value ;
		var v_descr=  tinyMCE.get('descr').getContent() ;

		var v_minwidth =  document.getElementById('minwidth').value ;
		var v_minheight=  document.getElementById('minheight').value ;
		var v_minlength =  document.getElementById('minlength').value ;
		var v_sort_order =  document.getElementById('sort_order').value ;
		var v_active = document.getElementById('active').value ;

		var v_meta_key=  document.getElementById('meta_key').value ;
		var v_meta_desc=  document.getElementById('meta_desc').value ;

		//alert(HRef)
		var DataArray = {
			"invid": v_current_inv_id,
			"current_is_insert": ( v_current_is_insert ? "1" : "" ),
			"session_id": v_session_id,
			"cat": v_cat,
			"type": v_type,
			"color": v_color,
			"retail": v_retail,
			"saleprice": v_saleprice,
			"invamount": v_invamount,
			"weight": v_weight,
			"volume": v_volume,
			"wholesale": v_wholesale,
			"purch": v_purch_date,
			"title": v_title,
			"features": v_features,
			"video": v_video,
			"tutorials": v_tutorials,
			"descr": v_descr,
			"minwidth": v_minwidth,
			"minheight": v_minheight,
			"minlength": v_minlength,
			"sort_order": v_sort_order,
			"active": v_active,
			"meta_key": v_meta_key,
			"meta_desc": v_meta_desc
    };

		$('#div_uploading_status').append('<p>Saving data - no kittens being harmed.  Sending images to FTP can take a couple minutes - patience is a virtue.</p>');

		 $( "#div_uploading_status" ).dialog({
			height: 640,
			modal: true
    });
    console.log(DataArray);
    $.ajax({
        type: 'POST',
        url: "../../controller/inventory/product_ajax.php",
        data: {
            data : DataArray,
            old_invid : old_invid,
            save_newproduct_images_list : save_newproduct_images_list,
            newproduct_images_list : newproduct_images_list,
            type : "update_product"
        },
        dataType: "json",
        success: function(resultData) {
          onProductSaved(resultData);
          $('#div_uploading_status').append('<button class="btn btn-success" onclick=" javascript:close_dialog(); " style="float: right;">Success</button>');

        }
    });

		if (purch2_insert == true){

			var v_ship_group= encodeURIComponent( document.getElementById('shipgroup').value );
			var v_itemcost= encodeURIComponent( document.getElementById('itemcost').value );

			var DataArray = {
				"ship_group": v_ship_group,
				"invid": v_current_inv_id,
				"purch_date": v_purch_date,
				"quantity": v_invamount,
				"wholesale": v_itemcost,
				"wholesale_total": v_wholesale
			}

			$('#div_uploading_status').append('<p>Saving data to inven_punch2</p>');
      
      $.ajax({
        type: 'POST',
        url: "../../controller/inventory/product_ajax.php",
        data: {
            data : DataArray,
            type : "save_purch2"
        },
        dataType: "json",
        success: function(resultData) {
          onProductSaved(resultData);
        }
    });
		}
  }

  function close_dialog(){
    $( "#div_uploading_status" ).dialog("close");
    $( "#div_uploading_status" ).html("");
  }
  
  function onProductSaved(data) {
		// alert( "onProductSaved::"+var_dump(data) )
		if (parseInt(data.ErrorCode) == 0) {
			$('#div_uploading_status').append('<p>Product is updated</p>');

			if ( data.current_is_insert ) {
				current_inv_id= data.id;
				current_is_insert= false;

				document.getElementById('span_label_edit').style.display= "inline";
				document.getElementById('span_label_add').style.display= "none";
				ge('modType1').innerHTML = "edit";
				ge('modType2').innerHTML = "edit";
				var cat = encodeURIComponent( document.getElementById('cat').value );
				if (cat != "Clearance"){
					$(".order").show();
				}
				ImagesLoad([]);
			}
		} else {
			alert((data.ErrorMessage))
		}
  }
  
  function onPurch2saved(data) {
    if (parseInt(data.ErrorCode) == 0) {
      $('#div_uploading_status').append('<p>Data is saved to inven_punch2</p>');

    } else {
      alert((data.ErrorMessage))
    }
	}

  function setProd(num, show_product_editor){
    showHide('setProd','getProd');
    if(pro_type == 'new'){
      global_pro_type = 'new';
      showHide('span_label_add','span_label_edit');
    } else if(pro_type == 'edit'){
      showHide('span_label_edit','span_label_add');
    }
    ge('modType2').innerHTML = pro_type;

    ge('invid').value = data[num].invid;
    old_invid = data[num].invid;
		(ge('subType').value == 'clearance') ? ge('cat').value = 'Clearance' : ge('cat').value = data[num].cat;
		ge('type').value = data[num].type;
		ge('color').value = data[num].color;
		ge('retail').value = data[num].retail;
		ge('saleprice').value = data[num].saleprice;
		ge('invamount').value = data[num].invamount;
		ge('wholesale').value = data[num].wholesale;
		ge('meta_key').value = data[num].meta_key;
		ge('meta_desc').value = data[num].meta_desc;
		$('#features').html(data[num].features);
		ge('video').value = data[num].video;
    ge('tutorials').value = data[num].tutorials;

		tinyMCE.get('descr').setContent(data[num].descr);
		ge('title').value = data[num].title;
		ge('weight').value = data[num].weight;
		ge('volume').value = data[num].volume;
		ge('minwidth').value = data[num].minwidth;
		ge('minheight').value = data[num].minheight;
		ge('minlength').value = data[num].minlength;
		ge('sort_order').value = data[num].sort_order;
		var value = data[num].active;
		var $selected = $("#active").find("option[value = " + value + "]");
		$selected.attr("selected", true);

    var prod_invid = data[num].invid;

    $.ajax({
        type: 'POST',
        url: "../../controller/inventory/product_ajax.php",
        data: {
            id : prod_invid,
            type : "setProd"
        },
        dataType: "text",
        success: function(resultData) {
            var res_data = JSON.parse(resultData);
            ge('shipgroup').value = res_data[0].ship_group;
			      ge('itemcost').value = res_data[0].wholesale;
        }
    });

    if ( current_is_insert ) {
      current_inv_id= "NEW";
			CopyImagesFromSrcProductToNewProduct( data[num].invid )
		}
		InitImagesList( data[num].invid )

  }

  function CopyImagesFromSrcProductToNewProduct( src_invid ) {
    var session_id = <?php echo json_encode($session_id); ?>;
    //session_id = oflkl1veqbrrqkmvonq272vgg1
    $.ajax({
        type: 'POST',
        url: "../../controller/inventory/product_ajax.php",
        data: {
          src_invid : src_invid,
          session_id : session_id,
          type : "CopyImagesFromSrcProductToNewProduct"
        },
        dataType: "text",
        success: function(resultData) {
          var json_newproduct_images_list = JSON.parse(resultData);
          newproduct_images_list = json_newproduct_images_list.product_images_array;
          console.log('newproduct_images_list', newproduct_images_list);
          newproduct_images_list.forEach(element => save_newproduct_images_list.push(element.path));
          ImagesLoad(newproduct_images_list); /* product image array */
        }
    });

  }

  function InitImagesList(prod_id) {
		if ( current_is_insert ) {
			current_inv_id= 'new'
			document.getElementById('span_label_edit').style.display= "none"
			document.getElementById('span_label_add').style.display= "inline"
			document.getElementById('div_loadedImagesList').innerHTML= '';
		} else {
			document.getElementById('span_label_edit').style.display= "inline"
			document.getElementById('span_label_add').style.display= "none"
			current_inv_id= prod_id
			document.getElementById('div_loadedImagesList').innerHTML= '';
		}
		ImagesLoad([])
		InitializePlupload()
  }
  
  function ImagesLoad(newproduct_images_list) {
    var new_reload = 'edit';
    $('#div_uploading_status').html("<p>"+current_inv_id +"  Images reloading...</p>");
    var session_id = <?php echo json_encode($session_id); ?>;
    if(global_pro_type == 'new'){
      newproduct_images_list = save_newproduct_images_list;
      new_reload = 'new';
    }
    $.ajax({
        type: 'POST',
        url: "../../controller/inventory/product_ajax.php",
        data: {
          current_inv_id : current_inv_id,
          session_id : session_id,
          new_reload : new_reload,
          newproduct_images_list: newproduct_images_list,
          type : "image_load"
        },
        dataType: "text",
        success: function(resultData) {
          var res_data = JSON.parse(resultData);
          var config_images_base_url = res_data['config_images_base_url'];
          var config_images_ext = res_data['config_images_ext'];
          var config_uploads_products_dir = res_data['config_uploads_products_dir'];
          var product_images_array = res_data['product_images_array'];
          var rows_count = res_data['rows_count'];          

          var html = "";

          if(rows_count == 0){
            html += "<b>No Images uploaded</b>";
          }

          html += '<ul id="sortable" style="max-width:3000px;">';

          for(let i = 0; i <rows_count; i++){
            html += '<li class="ui-state-default" id="li_image_row_' + product_images_array[i]["path"] + '">'
                  + '<input readonly class="form-control" id="input_path_' + i + '" name="input_path_' + product_images_array[i]["path"] + '" value=\'' + product_images_array[i]["path"] + '\' size="30" >&nbsp;&nbsp;&nbsp;&nbsp;' 
                  + '<span id="span_edit_link_' + i + '">' 
                  + '<a style="cursor:pointer;" class="btn btn-primary"  onclick="javascript:EditImage(' + i + ')" >Edit</a>&nbsp; </span>'
                  + '<span id="span_view_link_' + i + '" style="display:none" >'
                  + '<a style="cursor:pointer;" class="btn btn-success"  onclick="javascript:PathAltSave(' + i + ',\'' + product_images_array[i]["path"] + '\')" >Save</a>&nbsp;&nbsp;&nbsp;'
                  + '<a style="cursor:pointer;" class="btn btn-info"  onclick="javascript:HideEditImage(' + i + ')" >Cancel</a>&nbsp; </span> <br>'
                  + '<img src="'+ config_images_base_url + config_uploads_products_dir + '/' + product_images_array[i]["path"] + '_130x98.' + config_images_ext + '" width="130" height="98">'
                  + '&nbsp;<span id="span_DeleteImage_link_'+ i +'"  style="display:none" ><a style="cursor:pointer;" class="btn btn-danger"  onclick="javascript:DeleteImage('+ i +',\'' + product_images_array[i]["path"] + '\')">x</a></span><br>'
                  + '<input readonly class="form-control" id="input_alt_' + i + '" name="input_alt_' + i + '" value="'+ product_images_array[i]["alt"] +'" size="30" style="margin-top:10px" > ';
            if(rows_count > 1){
              html += '<span style="cursor:pointer; padding-top:15px; padding-bottom: 5px; height: 25px; color: navy;" class="span_images_sortable" >Drag me</span>';
            }

            html += '</li>';
          }
          html += '</ul>';
          document.getElementById('div_loadedImagesList').innerHTML= html;
			    $('#div_uploading_status').html("<p>"+current_inv_id +"  Images reloaded.</p>");
        }
    });
  }

  function DeleteImage( row, file_name ) {
    if ( !confirm("Do you want to delete '"+file_name+"' image ? ") ) return;
    
    if(global_pro_type == 'new') {
      // remove from save_newproduct_images_list
      save_newproduct_images_list = arrayRemove(save_newproduct_images_list, file_name);
      console.log('new', save_newproduct_images_list);
    }

		$("#div_uploading_status").text('');
		$("#div_uploading_status").append('<p>Deleting image <img src="http://mjtrends.com/admin/assets/images/indicator.gif"></p>');

		$( "#div_uploading_status" ).dialog({
			height: 140,
			modal: true
    });

    var session_id = <?php echo json_encode($session_id); ?>;
    
    $.ajax({
        type: 'POST',
        url: "../../controller/inventory/product_ajax.php",
        data: {
          _id : current_inv_id,
          newproduct_images_list : newproduct_images_list,
          file_name : encodeURIComponent(file_name),
          session_id : session_id,
          type : "delete_image"
        },
        dataType: "text",
        success: function(resultData) {
          console.log(resultData)
          document.getElementById('div_uploading_status').innerHTML= current_inv_id + ' Image deleted...'
          ImagesLoad([]);
          $( "#div_uploading_status" ).dialog("close");
        }
    });

		// $.getJSON(HRef, function (data) {
		// 	document.getElementById('div_uploading_status').innerHTML= current_inv_id + ' Image deleted...'
		// 	// alert( "data::" + var_dump(data))
		// 	ImagesLoad([])
		// 	$( "#div_uploading_status" ).dialog("close");
		// });
  }
  
  function arrayRemove(arr, value) { 
    return arr.filter(function(ele){ 
        return ele != value; 
    });
  }

  function EditImage( row) {
    $('#input_path_'+row).prop('readonly', false);
    $('#input_alt_'+row).prop('readonly', false);

    $('#span_DeleteImage_link_'+row).css('display', 'inline');
    $('#span_edit_link_'+row).css('display', 'none');
    $('#span_view_link_'+row).css('display', 'inline');
	}

	function HideEditImage( row) {
		$('#input_path_'+row).prop('readonly', true);
    $('#input_alt_'+row).prop('readonly', true);

    $('#span_DeleteImage_link_'+row).css('display', 'none');
    $('#span_edit_link_'+row).css('display', 'inline');
    $('#span_view_link_'+row).css('display', 'none');
  }

  function PathAltSave( row, original_file_name ) {
		var pathValue= jQuery.trim(document.getElementById( 'input_path_'+row ).value)
		if ( pathValue == "" ) {
			alert("Enter File Name !");
			document.getElementById( 'input_path_'+row ).focus();
			return;
		}
		var altValue= jQuery.trim(document.getElementById( 'input_alt_'+row ).value)
		if ( altValue == "" ) {
			alert("Enter Alt !");
			document.getElementById( 'input_alt_'+row ).focus()
			return;
		}
    var session_id = <?php echo json_encode($session_id); ?>;
    console.log(pathValue, altValue, current_inv_id)
    $("#div_uploading_status").text('');
    $("#div_uploading_status").append('<p>Saving image <img src="http://mjtrends.com/admin/assets/images/indicator.gif"></p>');

    $( "#div_uploading_status" ).dialog({
      height: 140,
      modal: true
    });
    $.ajax({
        type: 'POST',
        url: "../../controller/inventory/product_ajax.php",
        data: {
          current_id : current_inv_id,
          session_id : session_id,
          original_file_name : encodeURIComponent(original_file_name),
          pathValue : encodeURIComponent(pathValue),
          altValue : altValue,
          type : "save_image_path_alt"
        },
        dataType: "text",
        success: function(resultData) {
          console.log(resultData);
          $( "#div_uploading_status" ).dialog("close");
          ImagesLoad([]);
        }
    });

	}

  function InitializePlupload() {
		document.getElementById('filelist').innerHTML= "";
		document.getElementById('div_loadedImagesList').innerHTML= "";

		if(window.global_uploader != true){			
			
			window.global_uploader = true;//set global variable so that we don't keep rebinding the upload button
			// uploader.queue = [];
			var uploader = new plupload.Uploader({
				browse_button: 'browse', // this can be an id of a DOM element or the DOM element itself
				// Rename files by clicking on their titles
				url: '../../controller/inventory/upload_product_images.php?invid='+current_inv_id+"&session_id=<?php echo session_id() ?>&DocumentRoot=<?php echo urldecode($DocumentRoot) ?>"
			});

			window.testing_uploader = uploader;
      console.log("InitializePlupload -> uploader", uploader)

			uploader.bind('FilesAdded', function(up, files) {
				number_ofAddedFiles= files.length
				var html = '';
				plupload.each(files, function(file) {
					html += '<li id="' + file.id + '">' + file.name + ' (' + plupload.formatSize(file.size) + ') <b></b></li>';
				});
				document.getElementById('filelist').innerHTML += html;
				document.getElementById('div_uploading_status').innerHTML= current_inv_id + ' Files selected.'
			});

			uploader.bind('UploadProgress', function(up, file) {
				if ( document.getElementById(file.id) ) {
					document.getElementById(file.id).getElementsByTagName('b')[0].innerHTML = '<span>' + file.percent + "%</span>";
				}
			});

			uploader.bind('Error', function(up, err) {
				
				alert( "Error #" + err.code + ": " + err.message )
				document.getElementById('console').innerHTML += "\nError #" + err.code + ": " + err.message;
      });

			document.getElementById('start-upload').onclick = function() {
        document.getElementById('filelist').innerHTML= "";
        uploaded_filesList = Array();
        $("#div_uploading_status").text('');
        $("#div_uploading_status").append('<p>Uploading image <img src="http://mjtrends.com/admin/assets/images/indicator.gif"></p>');

        $( "#div_uploading_status" ).dialog({
          height: 140,
          modal: true
        });
				uploader.start();
			};

      uploader.bind('FileUploaded', function(up, file, info) {  // Called when a file has finished uploading
        
        console.log('file', file);
        console.log('info', info);
        var session_id = <?php echo json_encode($session_id); ?>;
        uploaded_filesList[ uploaded_filesList.length ]= file.name
        var file_name = file.name;
        var split_file_name = file_name.split(/[.]/);
        split_file_name.pop();
        save_newproduct_images_list.push(split_file_name.join('-'));
				number_ofUploadedFiles++
        // alert( HRef )
        $.ajax({
            type: 'POST',
            url: "../../controller/inventory/product_ajax.php",
            data: {
              current_id : current_inv_id,
              session_id : session_id,
              old_invid : old_invid,
              newproduct_images_list : newproduct_images_list,
              file_name : encodeURIComponent(file.name),
              type : "save_uploaded_image_path"
            },
            dataType: "text",
            success: function(resultData) {
              console.log("save_uploaded_image_path:", resultData);
              // document.getElementById('div_uploading_status').innerHTML= current_inv_id + '  ' + number_ofUploadedFiles + ' images uploaded.'
              if ( number_ofAddedFiles <= number_ofUploadedFiles ) {
                // document.getElementById('div_uploading_status').innerHTML= current_inv_id + '  ' + number_ofUploadedFiles + ' images uploaded and coping file(s) to ftp server started...'
                $.ajax({
                    type: 'POST',
                    url: "../../controller/inventory/product_ajax.php",
                    data: {
                      current_id : current_inv_id,
                      session_id : session_id,
                      uploaded_filesList : JSON.stringify(uploaded_filesList),
                      type : "create_images_thumbnails"
                    },
                    dataType: "text",
                    success: function(resultData) {
                      var data = JSON.parse(resultData);
                      // console.log("create_images_thumbnails: ",data);
                      $( "#div_uploading_status" ).dialog("close");
                      document.getElementById('div_uploading_status').innerHTML= current_inv_id + '  ' + number_ofUploadedFiles + " Images uploaded and "+ data.CreatedthumbnailsListLength + " thumbnails created and copied to ftp server!"
                      console.log( number_ofUploadedFiles + " Images uploaded and "+ data.CreatedthumbnailsListLength + " thumbnails created and copied to ftp server!")
                      number_ofAddedFiles= 0;
                      number_ofUploadedFiles= 0;
                      document.getElementById('reload_invid').value= current_inv_id;
                        ImagesLoad([]);
                    }
                });
              }
            }
        });

			});

			uploader.init();
		}

  }
  
  function var_dump(oElem) {
		var sStr = '';
		if (typeof(oElem) == 'string' || typeof(oElem) == 'number')     {
			sStr = oElem;
		} else {
			var sValue = '';
			for (var oItem in oElem) {
				sValue = oElem[oItem];
				if (typeof(oElem) == 'innerHTML' || typeof(oElem) == 'outerHTML') {
					sValue = sValue.replace(/</g, '&lt;').replace(/>/g, '&gt;');
				}
				sStr += 'obj.' + oItem + ' = ' + sValue + '\n';
			}
		}
		return sStr;
	}
</script>