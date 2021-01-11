<?php
  include('../../controller/customer/invoice-list.php');
  include('../../common/auth.php');
  include('../../controller/marketing/getdata.php');
  include('../../config/config.php');

  $auth = new Auth;
  $auth->get_auth();
  $auth->get_warehouse_access();
  $invoice = get_invoices();

?>

<?php include('../layout/header.php'); ?>

<body id="page-top">
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
          <!-- <h1 class="h3 mb-4 text-gray-800">Pinterest &raquo; Post a pin on Pinterest</h1> -->
                    <!-- DataTales Example -->
          <div class="card shadow mb-4">
            <div class="card-header py-3" style="background: white; border: 0;">
                <ul class="nav nav-tabs">
                    <li id="newPin" class="active"><a href="javascript:pinType('new');">New Pin</a></li>
                    <li id="editPin"><a href="javascript:pinType('edit');" >Edit Pin</a></li>
                </ul>
            </div>
            <div id="new_pin" >
              <?php include('newpin.php');?>
            </div>
            <div id="edit_pin" style="display:none;">
              <?php include('editpin.php');?>
            </div>
          </div>

        </div>
        <!-- /.container-fluid -->


      </div>
      <!-- End of Main Content -->

<?php include('../layout/footer.php'); ?>

<script type="text/javascript" src="../../assets/js/plupload/plupload.full.min.js"></script>
<script type="text/javascript" src="../../assets/js/tiny_mce/tiny_mce.js"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script>
    function pinType(type){
    console.log("pinType -> type", type)
        
        if(type == 'new'){
            $( "#newPin" ).addClass( "active" );
            $( "#editPin" ).removeClass( "active" );
            $("#new_pin").css("display", "block");
            $("#edit_pin").css("display", "none");
        }
        if(type == 'edit'){
            $( "#editPin" ).addClass( "active" );
            $( "#newPin" ).removeClass( "active" );
            $("#edit_pin").css("display", "block");
            $("#new_pin").css("display", "none");
        }
    }
</script>

<script>
  $(document).ready(function() {
    var current_product = {};
    var current_product_edit = {};
    var pattern_added = false;
    var suggest = <?php echo json_encode($suggest) ?>;
    var pins_suggest = <?php echo json_encode($pins_suggest) ?>;
    console.log(pins_suggest);
      $("#product_suggest").autocomplete(
        {
          minLength: 3,
          source: suggest,
          mustMatch: false,
      matchContains: true,
          // autoFocus:true,
          focus: function( event, ui ) {
            $( "#product_suggest" ).val( ui.item.name );
            return false;
          },
          select: function( event, ui ) {
            $( "#product_suggest" ).val( ui.item.name );  
            current_product = {product: ui.item.id, is_pattern: ui.item.is_pattern};
            return false;
          }
        }     
      )
      .autocomplete( "instance" )._renderItem = function( ul, item ) {
        return $( "<li>" )
          .append( "<div>" + item.name +"</div>" )
          .appendTo( ul );
      };

      $("#product_suggest_edit").autocomplete(
        {
          minLength: 3,
          source: suggest,
          mustMatch: false,
          matchContains: true,
          // autoFocus:true,
          focus: function( event, ui ) {
            $( "#product_suggest_edit" ).val( ui.item.name );
            return false;
          },
          select: function( event, ui ) {
            $( "#product_suggest_edit" ).val( ui.item.name );  
            current_product_edit = {product: ui.item.id, is_pattern: ui.item.is_pattern};
            return false;
          }
        }     
      )
      .autocomplete( "instance" )._renderItem = function( ul, item ) {
        return $( "<li>" )
          .append( "<div>" + item.name +"</div>" )
          .appendTo( ul );
      };

      $("#search").autocomplete(
        {
          minLength: 3,
          source: pins_suggest,
          mustMatch: false,
          matchContains: true,
          // autoFocus:true,
          focus: function( event, ui ) {
            $( "#search" ).val( ui.item.title );
            return false;
          },
          select: function( event, ui ) {
            $("#search").val(ui.item.title);
            $("#title_edit").val(ui.item.title);
            $("#desc_edit").val(ui.item.desc);
            $("#name_edit").val(ui.item.name);
            $("#img_url_edit").val(ui.item.url);
            $("#pin_id_edit").val(ui.item.id);
            $('.product_list_edit').html('');
            pattern_added = false; //After we find new product we clear pattern added
            $.each(ui.item.invid, function(index, value) {
              var product = '<tr class="product_row">' +
                '<td><input type="hidden" name="product_id[]" value="' + value.product + '">' +
                '<input type="hidden" name="product_quantity[]" value="' + value.quantity + '">' +
                '<input type="hidden" class="is_pattern_flag" name="product_is_pattern[]" value="' + ((value.is_patterns == 1)?1:0) + '">' +
                '<span>' + value.name + '</span></td>' + '<td>' + '<span>' + value.quantity + '</span></td>' +
                '<td><button type="button" class="btn btn-info btn-xs remove-product"><span class="glyphicon glyphicon-remove" aria-hidden="true"></span> Remove</button></td>' +
                '</tr>';
              $('.product_list_edit').append(product);
              if(value.is_patterns == 1) {
                pattern_added = true;
              }
            });
            return false;
          }
        }     
      )
      .autocomplete( "instance" )._renderItem = function( ul, item ) {
        return $( "<li>" )
          .append( "<div>" + item.title +"</div>" )
          .appendTo( ul );
      };

      $('.add-new-edit').click(function(){
        var quantity = $('#product_quantity_edit').val();
        if (!quantity) {
          quantity = 0;
        }
        if (current_product_edit.is_pattern == 1) {
          if (!pattern_added){
            pattern_added = true;
          } else {
            alert('You cannot add more than one pattern in product list.');
            return false;
          }
        }
        var new_product = '<tr class="product_row">' +
            '<td><input type="hidden" name="product_id[]" value="' + current_product_edit.product + '">' +
            '<input type="hidden" name="product_quantity[]" value="' + quantity + '">' +
            '<input type="hidden" class="is_pattern_flag" name="product_is_pattern[]" value="' + current_product_edit.is_pattern + '">' +
            '<span>' + $("#product_suggest_edit").val() + '</span></td>' + '<td>' + '<span>' + quantity + '</span></td>' +
            '<td><button type="button" class="btn btn-info btn-xs remove-product"><span class="glyphicon glyphicon-remove" aria-hidden="true"></span> Remove</button></td>' +
            '</tr>';
        $('.product_list_edit').append(new_product);
        $('#product_suggest_edit').val('');
        $('#product_quantity_edit').val(0);
        current_product = {};
      });

      $('.add-new').click(function(){
        var quantity = $('#product_quantity').val();
        if (!quantity) {
          quantity = 0;
        }
        if (current_product.is_pattern == 1) {
          if (!pattern_added){
            pattern_added = true;
          } else {
            alert('You cannot add more than one pattern in product list.');
            return false;
          }
        }
        var new_product = '<tr class="product_row">' +
            '<td><input type="hidden" name="product_id[]" value="' + current_product.product + '">' +
            '<input type="hidden" name="product_quantity[]" value="' + quantity + '">' +
            '<input type="hidden" class="is_pattern_flag" name="product_is_pattern[]" value="' + current_product.is_pattern + '">' +
            '<span>' + $("#product_suggest").val() + '</span></td>' + '<td>' + '<span>' + quantity + '</span></td>' +
            '<td><button type="button" class="btn btn-info btn-xs remove-product"><span class="glyphicon glyphicon-remove" aria-hidden="true"></span> Remove</button></td>' +
            '</tr>';
        $('.product_list').append(new_product);
        $('#product_suggest').val('');
        $('#product_quantity').val(0);
        current_product = {};
      });

      $('body').on('click', '.remove-product', function(){
        console.log(this);
        if ($(this).parents('tr.product_row').find('.is_pattern_flag').val() == 1) {
          pattern_added = false;
        }
        $(this).parents('tr.product_row').remove();
      });

      $('body').on('click', '#new_submit', function(e) {
        e.preventDefault();
        save_pin();
      });

      $('body').on('click', '#edit_submit', function(e) {
        e.preventDefault();
        update_pin();
      });
      
      function save_pin(){
        $('#new_submit').attr('disabled','disabled');
        $.ajax({
            type: 'POST',
            url: "../../controller/marketing/save_pin.php",
            data: $('#product_pin_form').serialize(),
            dataType: "text",
            success: function(data) {
              console.log(data)
              if (typeof data == 'undefined'){
                console.log(data)
              } else if (typeof data.error != 'undefined'){
                alert(data.error)
              } else if (data){
                $('#product_pin_form input[type="text"]').val('');
                $('#product_pin_form input[type="url"]').val('');
                $('#product_pin_form textarea').val('');
                $('tbody.product_list').html('');
                alert('Saved');
              }
              $('#new_submit').removeAttr('disabled');
            },
            error: function(result){
              $('#new_submit').removeAttr('disabled');
            }
        });
        
        return false;
      }

      function update_pin(){
        $('#edit_submit').attr('disabled','disabled');
        $.ajax({
            type: 'POST',
            url: "../../controller/marketing/update_pin.php",
            data: $('#product_pin_form_edit').serialize(),
            dataType: "text",
            success: function(data) {
              console.log(data)
              if (typeof data == 'undefined'){
                console.log(data)
              } else if (typeof data.error != 'undefined'){
                alert(data.error)
              } else if (data){
                $('#product_pin_form_edit input[type="text"]').val('');
                $('#product_pin_form_edit input[type="url"]').val('');
                $('#product_pin_form_edit textarea').val('');
                $('tbody.product_list_edit').html('');
                alert('Saved');
              }
              $('#edit_submit').removeAttr('disabled');
            },
            error: function(result){
              $('#edit_submit').removeAttr('disabled');
            }
        });
        
        return false;
      }
  });

</script>