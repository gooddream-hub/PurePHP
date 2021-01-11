<?php
include('../../controller/reports/product.php');
include('../../common/auth.php');

$auth = new Auth;
$auth->get_auth();
$auth->get_warehouse_access();

$product = product();
?>

<!-- Header -->
<?php include('../layout/header.php'); ?>
<!-- End of Header -->

<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
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
          <h1 class="h3 mb-4 text-gray-800">Product sales graph</h1>
          <div class="row">
            <div class="col-md-1">
                <label style="margin-top: 5px;">Start date : </label>
            </div>
            <div class="col-md-4">
              <input type="text" class="form-control" name="sales-report-start" value="<?php echo date('m/d/Y', strtotime('-1 years')); ?>"/>
              <input type="hidden" class="form-control" name="start_date" id="start_date" value="<?php echo date('Y-m-d', strtotime('-1 years')); ?>"/>
            </div>
            <div class="col-md-1">
                <label style="margin-top: 5px;">End date : </label>
            </div>
            <div class="col-md-4">
                <input type="text" class="form-control" name="sales-report-end" value="<?php echo date('m/d/Y'); ?>"/>
                <input type="hidden" class="form-control" name="end_date" id="end_date" value="<?php echo date('Y-m-d'); ?>"/>
            </div>
          </div></br>
           <!--  <div class="col-md-1">
                <label style="margin-top: 5px;">Select date : </label>
            </div>
            <?php
            //$start_date = $end = date('m/d/Y', strtotime('-1 years'));
            //$end_date = $end = date('m/d/Y');
            ?>
            <div class="col-md-5">
                <input type="text" class="form-control" name="sales-report-filter" value="<?php echo $start_date.' - '.$end_date; ?>" readonly/>
                <input type="hidden" class="form-control" name="start_date" id="start_date" value="<?php echo date('Y-m-d', strtotime('-1 years')); ?>"/>
                <input type="hidden" class="form-control" name="end_date" id="end_date" value="<?php echo date('Y-m-d'); ?>"/>
            </div>
          </div></br> -->
          
                    <!-- DataTales Example -->
          <div class="card shadow mb-4">
            <!-- <div class="card-header py-3">
              <h6 class="m-0 font-weight-bold text-primary">DataTables Example</h6>
            </div> -->
            <div class="card-body">
                <div class="table-responsive">
                    <table class="sales-report">
                        <tr>
                            <td colspan="4">
                                <p style="margin-top: 20px">Select up to 8 products or/and 4 categories to compare </p>
                            </td>
                        </tr>
                        <tr>
                            <th>Products</th>
                            <th>Products</th>
                            <th>Type</th>
                            <th>Category</th>
                        </tr>
                        <tr>
                            <td>
                                <select name="prod1" class="products">
                                    <option value="">Select</option>
                                    <?php foreach($product['product'] as $row):?>
                                        <option value="<?php echo $row['invid']?>"><?php echo $row['type']." ".$row['color']?></option>
                                    <?php endforeach;?>
                                </select>
                            </td>
                            <td>
                                <select name="prod2" class="products">
                                    <option value="">Select</option>
                                    <?php foreach($product['product'] as $row):?>
                                        <option value="<?php echo $row['invid']?>"><?php echo $row['type']." ".$row['color']?></option>
                                    <?php endforeach;?>
                                </select>
                            </td>
                            <td>
                                <select name="type1" class="type">
                                    <option value="">Select</option>
                                    <?php foreach($product['type'] as $row):?>
                                        <option value="<?php echo $row['type']?>"><?php echo $row['type']?></option>
                                    <?php endforeach;?>
                                </select>
                            </td>
                            <td>
                                <select name="cat1" class="cats">
                                    <option value="">Select</option>
                                    <?php foreach($product['cat1'] as $row):?>
                                        <option value="<?php echo $row['cat']?>"><?php echo $row['cat']?></option>
                                    <?php endforeach;?>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <select name="prod3" class="products">
                                    <option value="">Select</option>
                                    <?php foreach($product['product'] as $row):?>
                                        <option value="<?php echo $row['invid']?>"><?php echo $row['type']." ".$row['color']?></option>
                                    <?php endforeach;?>
                                </select>
                            </td>
                            <td>
                                <select name="prod4" class="products">
                                    <option value="">Select</option>
                                    <?php foreach($product['product'] as $row):?>
                                        <option value="<?php echo $row['invid']?>"><?php echo $row['type']." ".$row['color']?></option>
                                    <?php endforeach;?>
                                </select>
                            </td>
                            <td>
                                <select name="type2" class="type">
                                    <option value="">Select</option>
                                    <?php foreach($product['type'] as $row):?>
                                        <option value="<?php echo $row['type']?>"><?php echo $row['type']?></option>
                                    <?php endforeach;?>
                                </select>
                            </td>
                            <td>
                                <select name="cat2" class="cats">
                                    <option value="">Select</option>
                                    <?php foreach($product['cat2'] as $row):?>
                                        <option value="<?php echo $row['cat']?>"><?php echo $row['cat']?></option>
                                    <?php endforeach;?>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <select name="prod5" class="products">
                                    <option value="">Select</option>
                                    <?php foreach($product['product'] as $row):?>
                                        <option value="<?php echo $row['invid']?>"><?php echo $row['type']." ".$row['color']?></option>
                                    <?php endforeach;?>
                                </select>
                            </td>
                            <td>
                                <select name="prod6" class="products">
                                    <option value="">Select</option>
                                    <?php foreach($product['product'] as $row):?>
                                        <option value="<?php echo $row['invid']?>"><?php echo $row['type']." ".$row['color']?></option>
                                    <?php endforeach;?>
                                </select>
                            </td>
                            <td>
                                <select name="type" class="type">
                                    <option value="">Select</option>
                                    <?php foreach($product['type'] as $row):?>
                                        <option value="<?php echo $row['type']?>"><?php echo $row['type']?></option>
                                    <?php endforeach;?>
                                </select>
                            </td>
                            <td>
                                <select name="cat3" class="cats">
                                    <option value="">Select</option>
                                    <?php foreach($product['cat3'] as $row):?>
                                        <option value="<?php echo $row['cat']?>"><?php echo $row['cat']?></option>
                                    <?php endforeach;?>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <select name="prod7" class="products">
                                    <option value="">Select</option>
                                    <?php foreach($product['product'] as $row):?>
                                        <option value="<?php echo $row['invid']?>"><?php echo $row['type']." ".$row['color']?></option>
                                    <?php endforeach;?>
                                </select>
                            </td>
                            <td>
                                <select name="prod8" class="products">
                                    <option value="">Select</option>
                                    <?php foreach($product['product'] as $row):?>
                                        <option value="<?php echo $row['invid']?>"><?php echo $row['type']." ".$row['color']?></option>
                                    <?php endforeach;?>
                                </select>
                            </td>
                            <td>
                                <select name="type4" class="type">
                                    <option value="">Select</option>
                                    <?php foreach($product['type'] as $row):?>
                                        <option value="<?php echo $row['type']?>"><?php echo $row['type']?></option>
                                    <?php endforeach;?>
                                </select>
                            </td>
                            <td>
                                <select name="cat4" class="cats">
                                    <option value="">Select</option>
                                    <?php foreach($product['cat4'] as $row):?>
                                        <option value="<?php echo $row['cat']?>"><?php echo $row['cat']?></option>
                                    <?php endforeach;?>
                                </select>
                            </td>
                        </tr>
                    </table>
                </div>

                <p style="margin-top: 10px;">
                    <input id="build-graph" name="build-graph" type="button" value="Compare"/>
                </p>
                <div class="chart-error" style="color:red"></div>
                  <canvas id="line-chart"></canvas>
              </div>
          </div>
        </div>
        <!-- /.container-fluid -->


      </div>
      <!-- End of Main Content -->

<!-- Footer -->
<?php include('../layout/footer.php'); ?>
<!-- End of Footer -->

<!-- Date picker-->
<script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
<script src='https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.24.0/moment.min.js'></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.4.0/Chart.bundle.js"></script>
<script src="../../assets/js/reports/product.js"></script>