<?php
  include('../../controller/customer/search.php');
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
          <h1 class="h3 mb-4 text-gray-800">Find customer by</h1>
                    <!-- DataTales Example -->
          <div class="card shadow mb-4">
            <div class="card-header py-3" id="error">
              <!-- <h6 class="m-0 font-weight-bold text-primary"> Search Form</h6> -->
            </div>
            <div class="card-body">
                <form action="search.php" method="post" id="search-form">
                    <div class="filter-box">
                        <label for="email">Email:</label>
                        <input type="text" class="form-control bg-light border-0 small" placeholder="Email" aria-label="Email" aria-describedby="basic-addon2" name="email" id = "email">
                        <label for="phone">Phone:</label>
                        <input type="text" class="form-control bg-light border-0 small" placeholder="Phone" aria-label="phone" aria-describedby="basic-addon2" name="phone" id = "phone">
                    </div> <br>
                    <!-- <div class="filter-box">
                        <label for="start_date">Start Date:</label>
                        <input type="date" class="form-control bg-light border-0 small" placeholder="Start Date" aria-label="start_date" aria-describedby="basic-addon2" name="start_date" id = "start_date">
                        <label for="end_date">End Date:</label>
                        <input type="date" class="form-control bg-light border-0 small" placeholder="End Date" aria-label="end_date" aria-describedby="basic-addon2" name="end_date" id = "end_date">
                    </div> <br> -->
                    <div class="filter-box">
                        <label for="firstname">First Name:</label>
                        <input type="text" class="form-control bg-light border-0 small" placeholder="First Name" aria-label="firstname" aria-describedby="basic-addon2" name="firstname" id = "firstname">
                        <label for="lastname">Last Name:</label>
                        <input type="text" class="form-control bg-light border-0 small" placeholder="Last Name" aria-label="lastname" aria-describedby="basic-addon2" name="lastname" id = "lastname">
                    </div> <br>
                    <div class="filter-box">
                        <label for="bill_city">Billing City:</label>
                        <input type="text" class="form-control bg-light border-0 small" placeholder="City" aria-label="bill_city" aria-describedby="basic-addon2" name="bill_city" id = "bill_city">
                        <label for="bill_state">Billing State:</label>
                        <input type="text" class="form-control bg-light border-0 small" placeholder="State" aria-label="bill_state" aria-describedby="basic-addon2" name="bill_state" id = "bill_state">
                    </div> <br>
                    <div class="filter-box">
                        <label for="ship_city">Shipping City:</label>
                        <input type="text" class="form-control bg-light border-0 small" placeholder="City" aria-label="ship_city" aria-describedby="basic-addon2" name="ship_city" id = "ship_city">
                        <label for="ship_state">Shipping State:</label>
                        <input type="text" class="form-control bg-light border-0 small" placeholder="State" aria-label="ship_state" aria-describedby="basic-addon2" name="ship_state" id = "ship_state">
                    </div> <br>
                    <div style="text-align:center">
                        <button type="button" id="search" class="btn btn-success"> Search </button>
                    </div>
                </form>                
            </div>
          </div>

          <div class="card shadow mb-4" id="search-result-table" style="display:none">
            <div class="card-header py-3">
              <h6 class="m-0 font-weight-bold text-primary">Search Result</h6>
            </div>
            <div class="card-body">
              <div class="table-responsive">
                <table class="table table-bordered" id="" width="100%" cellspacing="0">
                  <thead>
                    <tr>
                      <th>Name</th>
                      <th>Email</th>
                      <th>Phone</th>
                      <th>City</th>
                      <th>State</th>
                    </tr>
                  </thead>
                  <tbody id="search-result-table-body">
                  	
                  </tbody>
                </table>
              </div>
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
  $( document ).ready(function() {
      $("#search").click(function(){
          var email = $("#email").val();
          var phone = $("#phone").val();
          var firstname = $("#firstname").val();
          var lastname = $("#lastname").val();
          var bill_city = $("#bill_city").val();
          var bill_state = $("#bill_state").val();
          var ship_city = $("#ship_city").val();
          var ship_state = $("#ship_state").val();
          $("#error").html("");
          if(!email && !phone && !firstname && !lastname && !bill_city && !bill_state && !ship_city && !ship_state ){
              $("#error").append('<p class="danger"> Please add search data! </p>')
          }else{
              // $("#search-form").submit();
              $.ajax({
                  type: 'POST',
                  url: "../../controller/customer/search.php",
                  data: {
                      email: email,
                      phone: phone,
                      firstname: firstname,
                      lastname: lastname,
                      bill_city: bill_city,
                      bill_state: bill_state,
                      ship_city: ship_city,
                      ship_state: ship_state
                  },
                  dataType: "text",
                  success: function(resultData) { 
                      var res_data = JSON.parse(resultData);
                      console.log(res_data)
                      if(res_data.length == 1){
                          window.location = "search-result.php?email="+res_data[0].email+"&firstname="+res_data[0].shipfirst+"&lastname="+res_data[0].shiplast;
                      }else{
                          $("#search-result-table").css("display", "block");
                          $("#search-result-table-body").html("");
                          for(let i = 0; i < res_data.length; i++){
                              let html = "<tr>"+
                              '<td><a href="search-result.php?email='+res_data[i].email+'&firstname='+res_data[i].shipfirst+'&lastname='+res_data[i].shiplast+'">'+res_data[i].shipfirst+' '+res_data[i].shiplast+'</a></td>'+
                              '<td>'+res_data[i].email+'</td>'+
                              '<td>'+res_data[i].phone+'</td>'+
                              '<td>'+res_data[i].shipcity+'</td>'+
                              '<td>'+res_data[i].shipstate+'</td>'+
                              '</tr>';
                              $("#search-result-table-body").append(html);
                          }
                      }
                      
                  }
              });
          }
      })
  });
</script>