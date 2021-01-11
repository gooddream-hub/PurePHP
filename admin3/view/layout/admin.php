<!-- Sidebar -->
    <ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

      <!-- Sidebar - Brand -->
      <a class="sidebar-brand d-flex align-items-center justify-content-center" href="index.html">
        <div class="sidebar-brand-icon rotate-n-15">
          <i class="fas fa-laugh-wink"></i>
        </div>
        <div class="sidebar-brand-text mx-3">MJTrends <sup>3</sup></div>
      </a>

      <!-- Divider -->
      <hr class="sidebar-divider my-0">

      <!-- Nav Item - Dashboard -->
      <li class="nav-item">
        <a class="nav-link" href="dashboard.php">
          <i class="fas fa-fw fa-tachometer-alt"></i>
          <span>Dashboard</span></a>
      </li>

      <!-- Divider -->
      <hr class="sidebar-divider">

      <!-- Heading -->
      <div class="sidebar-heading">
        Common
      </div>

      <!-- Nav Item - Pages Collapse Menu -->
      <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="true" aria-controls="collapseTwo">
          <i class="fas fa-fw fa-cog"></i>
          <span>Customer</span>
        </a>
        <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
          <div class="bg-white py-2 collapse-inner rounded">
            <a class="collapse-item" href="<?php echo SITE_URL;?>admin3/view/customer/invoice-list.php">Invoice List</a>
            <a class="collapse-item" href="<?php echo SITE_URL;?>admin3/view/customer/search.php">Search</a>
            <a class="collapse-item" href="cards.html">Top Customers</a>
          </div>
        </div>
      </li>

      <!-- Nav Item - Utilities Collapse Menu -->
      <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseUtilities" aria-expanded="true" aria-controls="collapseUtilities">
          <i class="fas fa-fw fa-wrench"></i>
          <span>Inventory</span>
        </a>
        <div id="collapseUtilities" class="collapse" aria-labelledby="headingUtilities" data-parent="#accordionSidebar">
          <div class="bg-white py-2 collapse-inner rounded">
            <a class="collapse-item" href="<?Php echo SITE_URL; ?>admin3/view/inventory/products.php">Edit / Upload</a>
            <a class="collapse-item" href="<?php echo SITE_URL;?>admin3/view/inventory/etsy.php">Etsy Post</a>
            <a class="collapse-item" href="<?php echo SITE_URL;?>admin3/view/inventory/etsy_cat.php">Etsy Category</a>
          </div>
        </div>
      </li>

      <!-- Divider -->
      <hr class="sidebar-divider">

      <!-- Heading -->
      <div class="sidebar-heading">
        Admin
      </div>

      <!-- Nav Item - Pages Collapse Menu -->
      <li class="nav-item">
        <a class="nav-link" href="#" data-toggle="collapse" data-target="#collapsePages" aria-expanded="true" aria-controls="collapsePages">
          <i class="fas fa-fw fa-folder"></i>
          <span>Reports</span>
        </a>
        <div id="collapsePages" class="collapse" aria-labelledby="headingPages" data-parent="#accordionSidebar">
          <div class="bg-white py-2 collapse-inner rounded">
            <h6 class="collapse-header">Sales</h6>
            <a class="collapse-item" href="<?php echo SITE_URL;?>admin3/view/reports/product.php">Product</a>
            <a class="collapse-item" href="<?php echo SITE_URL;?>admin3/view/reports/profit_report.php">Profit Reports</a>
            <a class="collapse-item" href="register.html">ROI</a>
            <a class="collapse-item" href="forgot-password.html">YOY</a>
            <div class="collapse-divider"></div>
            <h6 class="collapse-header">Advertising:</h6>
            <a class="collapse-item" href="<?php echo SITE_URL;?>admin3/view/reports/newsletter.php">Newsletter</a>
            <a class="collapse-item active" href="blank.html">Social Media</a>
          </div>
        </div>
      </li>
   
      <!-- Nav Item - Pages Collapse Menu -->
      <li class="nav-item">
        <a class="nav-link" href="#" data-toggle="collapse" data-target="#collapsePages2" aria-expanded="true" aria-controls="collapsePages">
          <i class="fas fa-fw fa-folder"></i>
          <span>Marketing</span>
        </a>
        <div id="collapsePages2" class="collapse" aria-labelledby="headingPages" data-parent="#accordionSidebar">
          <div class="bg-white py-2 collapse-inner rounded">
            <a class="collapse-item" href="<?php echo SITE_URL;?>admin3/view/marketing/pinterest.php">Social Media</a>
            <a class="collapse-item" href="<?php echo SITE_URL;?>admin3/view/marketing/newsletter.php">Newsletter</a>
            <a class="collapse-item" href="<?php echo SITE_URL;?>admin3/view/marketing/homepage.php">Homepage</a>
          </div>
        </div>
      </li>    

      <!-- Nav Item - Charts -->
      <li class="nav-item">
        <a class="nav-link" href="<?php echo SITE_URL;?>admin3/view/admin/expense-input.php">
          <i class="fas fa-fw fa-chart-area"></i>
          <span>Expenses</span></a>
      </li>

      <!-- Nav Item - Tables -->
      <li class="nav-item">
        <a class="nav-link" href="tables.html">
          <i class="fas fa-fw fa-table"></i>
          <span>Tables</span></a>
      </li>

      <!-- Divider -->
      <hr class="sidebar-divider d-none d-md-block">

      <!-- Sidebar Toggler (Sidebar) -->
      <div class="text-center d-none d-md-inline">
        <button class="rounded-circle border-0" id="sidebarToggle"></button>
      </div>

    </ul>
    <!-- End of Sidebar -->