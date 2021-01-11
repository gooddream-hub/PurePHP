<?php
  error_reporting(0);
  ini_set('display_errors', 0);
  include('../../controller/customer/invoice.php');
  include('../../common/auth.php');

  $auth = new Auth;
  $auth->get_auth();
  $auth->get_warehouse_access();

  $custid = (int)$_GET['custid'];
  $invoice = get_invoice($custid);

  init($invoice);
  $bxs = get_bx($invoice);
  $packing = get_packing($invoice);
  $labels = get_labels($invoice);
?>


<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="">

  <title>MJTrends Invoices</title>
  <link rel="icon" href="<?php $_SERVER['HTTP_HOST'] ?>/admin3/assets/img/favicon.ico" />

  <!-- Custom fonts for this template-->
  <link href="../../assets/vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
  <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

  <!-- Custom styles for this template-->
  <link href="../../assets/css/sb-admin-2.min.css" rel="stylesheet">
  <link href="../../assets/css/mjtrends.css" rel="stylesheet">
  <link href="../../assets/css/invoice-print.css" media="print" rel="stylesheet">
  <link href="//cdn.datatables.net/1.10.19/css/jquery.dataTables.min.css" rel="stylesheet">
</head>

<body id="page-top">

  <!-- Page Wrapper -->
  <div id="wrapper">

    <?php include('../layout/'.$_SESSION['group'].'.php');?>
    <!-- Content Wrapper -->
    <div id="content-wrapper" class="d-flex flex-column">

      <!-- Main Content -->
      <div id="content" >

        <!-- Topbar -->
        <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow" style="height:auto;">

          <!-- Sidebar Toggle (Topbar) -->
          <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
            <i class="fa fa-bars"></i>
          </button>

          <!-- Topbar Get Label -->
          <div style='display:grid;'>
          <label for="ship_size" style="padding:.375rem .75rem; white-space:nowrap;">Get USPS Label:</label>

          <input type="hidden" name="shipType" id="shipType" value="<?php echo $invoice['details'][0]['ship_type']?>">
          <?php if(count($packing) < 1):?>
            <div class="form-group" style="display:inline-flex">
              <label for="ship_size" style="padding:.375rem .75rem; white-space:nowrap;">Width:</label>
              <input type="text" name="ship_width" id="ship_widthFirstClass" placeholder="Width" class="form-control" style="width:100px; margin: 0 10px;" value="<?php echo $bxs['FirstClass']['size']['width'];?>">
              <label for="ship_size" style="padding:.375rem .75rem; white-space:nowrap;">Height:</label>
              <input type="text" name="ship_height" id="ship_heightFirstClass" placeholder="Height" class="form-control" style="width:100px; margin: 0 10px;" value="<?php echo $bxs['FirstClass']['size']['height'];?>">
              <label for="ship_size" style="padding:.375rem .75rem; white-space:nowrap;">Length:</label>
              <input type="text" name="ship_length" id="ship_lengthFirstClass" placeholder="Length" class="form-control" style="width:100px; margin: 0 10px;" value="<?php echo $bxs['FirstClass']['size']['length'];?>">
              <label for="ship_size" style="padding:.375rem .75rem; white-space:nowrap;">Weight:</label>
              <input type="text" name="ship_weight" id="ship_weightFirstClass" placeholder="Weight" class="form-control" style="width:100px; margin: 0 10px;" value="<?php echo $bxs['FirstClass']['weight'];?>">
              <button class="btn btn-primary" id="get_label"  style="white-space:nowrap" onclick="getLabel('FirstClass')" <?php if($bxs['FirstClass']['label'] != "" ){echo "disabled";}?>>Get Label</button>
            </div>
          <?php else:?>
          <?php foreach($packing as $key=>$item):?>
            <!-- <form class="d-none d-sm-inline-block form-inline mr-auto ml-md-3 my-2 my-md-0 mw-100 navbar-search"> -->
              <div class="form-group" style="display:inline-flex">
                  <label for="ship_size" style="padding:.375rem .75rem; white-space:nowrap;">Width:</label>
                  <input type="text" name="ship_width" id="ship_width<?php echo $key?>" placeholder="Width" class="form-control" style="width:100px; margin: 0 10px;" value="<?php echo $bxs[$item['box']]['size']['width'];?>">
                  <label for="ship_size" style="padding:.375rem .75rem; white-space:nowrap;">Height:</label>
                  <input type="text" name="ship_height" id="ship_height<?php echo $key?>" placeholder="Height" class="form-control" style="width:100px; margin: 0 10px;" value="<?php echo $bxs[$item['box']]['size']['height'];?>">
                  <label for="ship_size" style="padding:.375rem .75rem; white-space:nowrap;">Length:</label>
                  <input type="text" name="ship_length" id="ship_length<?php echo $key?>" placeholder="Length" class="form-control" style="width:100px; margin: 0 10px;" value="<?php echo $bxs[$item['box']]['size']['length'];?>">
                  <label for="ship_size" style="padding:.375rem .75rem; white-space:nowrap;">Weight:</label>
                  <input type="text" name="ship_weight" id="ship_weight<?php echo $key?>" placeholder="Weight" class="form-control" style="width:100px; margin: 0 10px;" value="<?php echo $bxs[$item['box']]['weight'];?>">
                  <button class="btn btn-primary" id="get_label"  style="white-space:nowrap" onclick="getLabel('<?php echo $key?>')" <?php if($item['label'] != "" ){echo "disabled";}?>>Get Label</button>
              </div>
            <!-- </form> -->
          <?php endforeach;?>

          <?php endif?>
            
          </div>
          

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


            <li class="nav-item dropdown no-arrow">
            	<button class="btn btn-primary btn-print" id="print" <?php if( strpos($invoice['details'][0]['ship_date'], 'pending') === false){echo "disabled";}?> >Print</button>
            	<button class="btn btn-danger btn-print" data-toggle="modal" data-target="#del_conf" id="delete" <?php if($invoice['details'][0]['ship_date'] == "failed"){echo "disabled";}?> >Delete</button>
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
                <span class="mr-2 d-none d-lg-inline text-gray-600 small"><?php echo $_SESSION['username']?></span>
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
          <div class="invoice_info">

          </div>

          <div class="print">
          	<div class="phead">
	          	<h1 class="h3 mb-4 text-gray-800">Invoice <span>www.MJTrends.com</span></h1>
	          	<p class="invoice_num">NO. <?php echo $_GET['custid']?></p>
          	</div>
          	
          	<hr>
          	
          	<table class="shipmethod">
          		<tr>
          			<td>Shipping Method</td>
          			<td>Order Date</td>
          			<td>Payment Method</td>
          		</tr>
          		<tr>
              <td><i><?php echo $invoice['details'][0]['ship_type']?></i></td>
              <td><i><?php echo $invoice['details'][0]['order_date']?></i></td>
              <td><i><?php echo $invoice['payment']?></i></td>
          		</tr>

          	</table>

          	<table class="details">
          		<thead>
          			<tr>
	          			<th>Sku</th>
	          			<th>Item</th>
	          			<th>Quantity</th>
	          			<th>Price</th>
	          			<th>Subtotal</th>
          			</tr>
          		</thead>
          		<tbody>
          			<?php foreach($invoice['items'] as $row):?>
          			<tr>
          				<td><?php echo $row['invid']?></td>
          				<td><?php echo $row['color']?> <?php echo $row['type']?> <?php echo $row['cat']?></td>
          				<td><?php echo $row['quant']?></td>
          				<td>$<?php echo number_format($row['price'], 2, '.', '')?></td>
          				<td>$<?php echo number_format($row['quant']*$row['price'], 2, '.', '')?></td>
          			</tr>
          			<?php endforeach;?>
          		</tbody>
          	</table>
          	
          	<table class="subtotal">
      			<tr>
      				<td>Subtotal: </td>
      				<td>$<?php echo number_format($invoice['subtotal'], 2, '.', '')?></td>
      			</tr>
      			<tr>
      				<td>Shipping:</td>
      				<td>$<?php echo number_format($invoice['details'][0]['shipping'], 2, '.', '')?></td>
      			</tr>
      			<tr>
      				<td>Tax:</td>
      				<td>$<?php echo number_format($invoice['details'][0]['tax'], 2, '.', '')?></td>
      			</tr>
      			<tr>
      				<td>Total:</td>
      				<td>$<?php echo number_format($invoice['subtotal']+$invoice['details'][0]['shipping']+$invoice['details'][0]['tax'], 2, '.', '')?></td>
      			</tr>
          	</table>

          	<div class="shipto">
          		<h3>Shipped to</h3>
          		<p><?php echo $invoice['details'][0]['shipfirst']?> <?php echo $invoice['details'][0]['shiplast']?></p>
          		<p><?php echo $invoice['details'][0]['shipadone']?></p>
          		<?php if($invoice['details'][0]['shipadtwo'] != ""):?>
          			<p><?php echo $invoice['details'][0]['shipadtwo']?></p>
          		<?php endif;?>
          		<p><?php echo $invoice['details'][0]['shipcity']?>, <?php echo $invoice['details'][0]['shipstate']?> <?php echo $invoice['details'][0]['shipzip']?></p>
          		<?php if($invoice['details'][0]['shipco'] != "US"):?>
          			<p><?php echo $invoice['details'][0]['shipco']?></p>
          		<?php endif;?>
          	</div>


          	<div class="invoice_bottom">
          		<div class="left">
          			<div class="address">
          				<p>MJTrends</p>
          				<p>2611 E Meredith</p>
          				<p>Vienna, VA 22181</p>
          			</div>
          			<div>
          				<p>sales@MJTrends.com</p>
          				<p>1-571-285-0000</p>
          				<p>www.MJTrends.com</p>
          			</div>
          		</div>

          		<div class="right">
          			<h5>Our Mission:</h5>
          			<i>To help create  -  </i>
          			<p>Tutorials, forums, and products to bring your imagination to life.</p>
          		</div>
          	</div>

      	</div>

        <?php foreach($labels as $row):?>
          <div class="print_label">
            <img src=<?php echo ltrim($row, '[');?>>
          </div>
        <?php endforeach;?>

        <div id="usps_now">

        </div>
      	<!-- end print -->

      </div>
      <!-- End of Main Content -->

      <!-- Footer -->

      <!-- End of Footer -->

    </div>
    <!-- End of Content Wrapper -->

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

<!-- delete confirmation modal -->
  <div class="modal fade bd-example-modal-sm" id="del_conf" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-sm">
    <div class="modal-content">
      Invoice has been set to "Failed"
    </div>
  </div>
  </div>
<!-- end modal -->

<!-- shipping label confirmation modal -->
  <div class="modal fade bd-example-modal-sm" id="label_conf" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-sm">
    <div class="modal-content">
      Another label has been created
    </div>
  </div>
  </div>
<!-- end modal -->

  <div class="modal fade bd-example-modal-sm" id="label_getting" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-sm">
      <div class="modal-content loading-content">
        <p style="margin-top:-1px; padding:30px 20px 0 0;">Loading the shipping label<p>
        <img class="loading" src="http://mjtrends.com/~mjtren5/admin/assets/images/loading.gif" alt="">
      </div>
    </div>
  </div>

  <div class="modal fade" id="labelmodal" role="dialog" style="overflow-y: scroll;">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-footer" style="justify-content: space-between;">
        <div>
          <p style="display:content;">Postage Balance: <span id="balance"></span></p>
        </div>
        <div>
          <button type="button" class="btn btn-primary" id="labelmodelprint">Print</button>
          <button type="button" class="btn btn-default" id="labelmodelclose">Close</button>
        </div>
          
          
        </div>
        <div class="modal-body" >
          
          <div id="label_image">
            <img src="" alt="" class="label-img" style="width:100%;">
          </div>
        </div>
        
      </div>
    </div>
  </div>

  <!-- Bootstrap core JavaScript-->
  <script src="../../assets/vendor/jquery/jquery.min.js"></script>
  <script src="../../assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

  <!-- Core plugin JavaScript-->
  <script src="../../assets/vendor/jquery-easing/jquery.easing.min.js"></script>

  <!-- Custom scripts for all pages-->
  <script src="../../assets/js/sb-admin-2.min.js"></script>
  <script src="../../assets/js/invoice.js"></script>
  
  <script>
    $('#labelmodelclose').click(function(){
      $('#labelmodal').modal('hide');
      window.location.reload();
    });

    $('#labelmodelprint').click(function(){
      var printContents = document.getElementById("label_image").innerHTML;
      console.log(printContents);
      var originalContents = document.body.innerHTML;
      document.body.innerHTML = printContents;
      setTimeout(function(){ 
        window.print(); 
        document.body.innerHTML = originalContents;
        window.location.reload();
      }, 1000);

      // document.body.innerHTML = originalContents;
    });

    function getLabel(box){
    var width = $("#ship_width"+box).val()
    var height = $("#ship_height"+box).val()
    var length = $("#ship_length"+box).val()
    var weight = $("#ship_weight"+box).val()
    var shipType = $("#shipType").val()
    var custid = <?php echo json_encode($custid); ?>;
    console.log(box, width, height, length, weight);
    $('#label_getting').modal('toggle');
    $.ajax({
        type: 'POST',
        url: "../../controller/customer/usps.php",
        data: {
          box: box,
          custid: custid,
          width: width,
          height: height,
          length: length,
          weight: weight,
          shipType: shipType,
        },
        dataType: "text",
        success: function(resultData) { 
            console.log(resultData);
            var data = JSON.parse(resultData);
            $('#label_getting').modal('hide');
            $('#balance').text(data.balance);
            $('.label-img').attr('src', data.label);
            $('#labelmodal').modal('show');
            
        }
    });
    }
    </script>
</body>

</html>