<?php
  include('../../controller/admin/expense.php');
  include('../../common/auth.php');
  $auth = new Auth;
  $auth->get_auth();
  $auth->get_warehouse_access();
?>

<!-- Header -->
<?php include('../layout/header.php'); ?>
<!-- End of Header -->

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
        <h1 class="h3 mb-4 text-gray-800">Expense Input page</h1>
        <?php if($check_msg):?>
          <p class="info"> <?php echo $msg; ?> </p>
        <?php endif?>
                  <!-- DataTales Example -->
        <div class="card shadow mb-4">
          <div class="card-header py-3" style="display: inline-grid;">
            <h4 class="m-0 font-weight-bold text-primary">Expense:</h4>
            <button onClick="addRow()" class="btn btn-success" style='float:right;'>Add Row</button>
          </div>
          <div class="card-body">
            <div class="table-responsive">
              <form action="" method="post">
                  <table class="table table-bordered" id="" width="100%" cellspacing="0">
                  <thead>
                      <tr>
                          <th>Category</th>
                          <th>Description</th>
                          <th>Cost</th>
                          <th>Date</th>
                      </tr>
                  </thead>
                  <tbody id="exp_table">
                      <tr>
                          <td>
                          <select name="cat1">
                              <option value="Food and Entertain">Food/entertain</option>
                              <option value="Utilities">Utilities</option>
                              <option value="rent">Rent</option>
                              <option value="Office supplies">Office Supplies</option>
                              <option value="Bad Debt">Bad Debt</option>
                              <option value="Gas">Gas</option>
                              <option value="Bank fees">Bank fees</option>
                              <option value="Car maintenance">Car maintenance</option>
                              <option value="Travel">Travel Costs</option>
                              <option value="Contractors">Contractors</option>
                              <option value="equipment">Equipment Purchase</option>
                              <option value="inventory">Inventory Purchase</option>
                              <option value="postal">Postal Fees</option>
                              <option value="credit card">Credit Card Fees</option>
                              <option value="paypal">Paypal Fees</option>
                              <option value="VA sales tax">VA sales tax</option>
                              <option value="Webhosting">Web Hosting</option>
                              <option value="Internet">Net Access</option>
                              <option value="Phone">Phone</option>
                          </select>
                          </td>
                          <td><input name="descrip1" type="text"></td>
                          <td><input name="cost1" type="text"></td>
                          <td><input name="date1" type="date" value="<?php echo date("Y-m-d")?>"></td>
                      </tr>
                  </tbody>
                  </table>
                  <input name="id" id="id" type="hidden" value="1">
                  <input name="submit" class="btn btn-primary" style="display: flow-root; margin: auto; width: 100%;text-align: center;" type="submit" value="Submit">
              </form>
              
            </div>
          </div>
        </div>

        <div class="card shadow mb-4">
          <div class="card-header py-3">
            <h4 class="m-0 font-weight-bold text-primary">Bulk Upload via CSV file</h4>
          </div>
          <div class="card-body">
              <form name="bulkForm" method="post" action="" enctype="multipart/form-data">
          
                  <label for="csvFile" style="float:left">Select File: &nbsp;&nbsp;&nbsp;&nbsp; </label> 
                  <input name="csvFile" type="file">
                  
                  <br><br>
                  
                  <label for="month" style="float:left">Select the Month the upload is for: &nbsp;&nbsp;&nbsp;&nbsp;     </label>
                  <input type="date" name="month" id="month" class="date-pick dp-applied" value="<?php echo date('Y-m-d'); ?>" />
                  
                  <br><br>
                  <input name="submit" class="btn btn-primary" style="display: flow-root; margin: auto; width: 100%;text-align: center;" type="submit" value="Submit">
              </form>
          </div>
        </div>

      </div>
      <!-- /.container-fluid -->


    </div>
    <!-- End of Main Content -->

<!-- Footer -->
<?php include('../layout/footer.php'); ?>
<!-- End of Footer -->

<script>
  function addRow(){

    i = parseInt(document.getElementById('id').value);
    i +=1;
    document.getElementById('id').value = i;

    var tbody = document.getElementById('exp_table');
    var row = document.createElement("TR");
    var td2 = document.createElement("TD");
    var td3 = document.createElement("TD");
    var td4 = document.createElement("TD");
    var td5 = document.createElement("TD");

    var sel = document.createElement("SELECT");
    sel.setAttribute("name", "cat"+i);
    sel.options[0] = new Option('Food/Entertain', 'Food and Entertain');
    sel.options[1] = new Option('Gas', 'Gas');
    sel.options[2] = new Option('Office supplies', 'Office supplies');
    sel.options[3] = new Option('Bad Debt', 'Bad Debt');
    sel.options[4] = new Option('Car maintenance', 'Car Maintenance');
    sel.options[5] = new Option('Travel Costs', 'Travel');
    sel.options[6] = new Option('Equipment Purchase', 'equipment');
    sel.options[7] = new Option('Inventory Purchase', 'inventory');
    sel.options[8] = new Option('Postal Fees', 'postal');
    sel.options[9] = new Option('Credit Card Fees', 'credit card');
    sel.options[10] = new Option('Paypal Fees', 'paypal');
    sel.options[11] = new Option('Web Hosting', 'Webhosting');
    sel.options[12] = new Option('Net Access', 'Internet');
    sel.options[13] = new Option('Phone', 'phone');
    sel.options[14] = new Option('Rent', 'rent');
    sel.options[15] = new Option('Utilities', 'Utilities');
    sel.options[16] = new Option('Bank fees', 'Bank fees');
    sel.options[17] = new Option('Contractors', 'Contractors');
    sel.options[18] = new Option('VA sales tax', 'VA sales tax');

    var inp =  document.createElement("INPUT");
    inp.setAttribute("type","text");
    inp.setAttribute("name","descrip"+i);

    var inp2 = document.createElement("INPUT");
    inp2.setAttribute("type","text");
    inp2.setAttribute("name","cost"+i);

    currentTime = new Date();
    var month = currentTime.getMonth() + 1;
    var day = currentTime.getDate();
    if(day < 10) day = "0"+day;
    if(month < 10) month = "0"+month;
    var year = currentTime.getFullYear();

    var inp3 = document.createElement("INPUT");
    inp3.setAttribute("type","date");
    inp3.setAttribute("name","date"+i);
    inp3.setAttribute("value",year+"-"+month+"-"+day);

    td2.appendChild(sel);
    td3.appendChild(inp);
    td4.appendChild(inp2);
    td5.appendChild(inp3);
    row.appendChild(td2);
    row.appendChild(td3);
    row.appendChild(td4);
    row.appendChild(td5);
    tbody.appendChild(row);
    td2.focus();
  }
</script>