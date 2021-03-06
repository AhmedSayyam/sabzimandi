<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="<?php echo base_url();?>public/dist/img/favicon.png" rel="apple-touch-icon-precomposed">
  <link href="<?php echo base_url();?>public/dist/img/favicon.png" rel="shortcut icon" type="image/png">
  <title>Sabzimandi | Dashboard</title>

  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="<?php echo base_url();?>public/plugins/fontawesome-free/css/all.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
  <!-- Tempusdominus Bootstrap 4 -->
  <link rel="stylesheet" href="<?php echo base_url();?>public/plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css">
  <!-- iCheck -->
  <link rel="stylesheet" href="<?php echo base_url();?>public/plugins/icheck-bootstrap/icheck-bootstrap.min.css">
  <!-- JQVMap -->
  <link rel="stylesheet" href="<?php echo base_url();?>public/plugins/jqvmap/jqvmap.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="<?php echo base_url();?>public/dist/css/adminlte.min.css">
  <!-- overlayScrollbars -->
  <link rel="stylesheet" href="<?php echo base_url();?>public/plugins/overlayScrollbars/css/OverlayScrollbars.min.css">
  <!-- Daterange picker -->
  <link rel="stylesheet" href="<?php echo base_url();?>public/plugins/daterangepicker/daterangepicker.css">
  <!-- summernote -->
  <link rel="stylesheet" href="<?php echo base_url();?>public/plugins/summernote/summernote-bs4.min.css">
  <!-- Dropzone CSS   -->
 <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.7.2/dropzone.min.css" />
 <!-- Select 2 CSS  -->
 <link rel="stylesheet" href="<?php echo base_url();?>public/plugins/select2/css/select2.min.css">
 <link rel="stylesheet" href="<?php echo base_url();?>public/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css">
  <!-- SweetAlert2 -->
  <link rel="stylesheet" href="<?php echo base_url();?>public/plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css">
  <!-- Toastr -->
  <link rel="stylesheet" href="<?php echo base_url();?>public/plugins/toastr/toastr.min.css">
   <!-- DataTables -->
   <link rel="stylesheet" href="<?php echo base_url();?>public/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
   <link rel="stylesheet" href="<?php echo base_url();?>public/plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
   <link rel="stylesheet" href="<?php echo base_url();?>public/plugins/datatables-buttons/css/buttons.bootstrap4.min.css">

   <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Noto+Nastaliq+Urdu&display=swap" rel="stylesheet"> 
   <!-- <link rel="stylesheet" href="https://cdn.datatables.net/buttons/1.2.2/css/buttons.dataTables.min.css"> -->

<style>
  @font-face {
  font-family: "myfont";
  src: url("<?php echo base_url();?>public/fonts/NotoNastaliqUrdu-Regular.ttf") format("ttf");
  }
  body{
    /* font-family: myfont; */
    font-family: 'Noto Nastaliq Urdu', serif;
  }

  .dimmer {
      display: none;
      background: #000;
      opacity: 0.75;
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      z-index: 99999;
    }
</style>
</head>
<body class="hold-transition sidebar-mini layout-fixed"  ng-app="myApp" ng-controller="main_cont">
  <!-- dimmer starts -->
  <div class="dimmer">
    <div class="text-center">
      <div class="spinner-border " style="width: 5rem; height: 5rem; margin-top: 50vh; color: #ffffff;" role="status">
        <span class="sr-only">Loading...</span>
      </div>    
      <div class="mt-3 " style=" color: #fff;"><h4>  Loading...  </h4></div>
    </div>  
  </div>
  <!-- dimmer ends -->
  
<div class="wrapper">

  <!-- Preloader -->
  <div class="preloader flex-column justify-content-center align-items-center">
    <img class="animation__shake" src="<?php echo base_url();?>public/dist/img/sabzimandi.png" alt="sabzimandi" height="60" width="60">
  </div>

  <!-- Navbar -->
  <nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
      </li>
      <!-- <li class="nav-item d-none d-sm-inline-block">
        <a href="" class="nav-link">Home</a>
      </li>
      <li class="nav-item d-none d-sm-inline-block">
        <a href="" class="nav-link">Contact</a>
      </li> -->
    </ul>

    <!-- Right navbar links -->
    <ul class="navbar-nav ml-auto">
      <!-- Navbar Search -->
      <li class="nav-item">
        <a class="nav-link" data-widget="navbar-search" href="#" role="button">
          <i class="fas fa-search"></i>
        </a>
        <div class="navbar-search-block">
          <form class="form-inline">
            <div class="input-group input-group-sm">
              <input class="form-control form-control-navbar" type="search" placeholder="Search" aria-label="Search">
              <div class="input-group-append">
                <button class="btn btn-navbar" type="submit">
                  <i class="fas fa-search"></i>
                </button>
                <button class="btn btn-navbar" type="button" data-widget="navbar-search">
                  <i class="fas fa-times"></i>
                </button>
              </div>
            </div>
          </form>
        </div>
      </li>

      <!-- Messages Dropdown Menu -->
      <li class="nav-item dropdown">
        <a class="nav-link" data-toggle="dropdown" href="#">
          <i class="far fa-comments"></i>
          <span class="badge badge-danger navbar-badge">3</span>
        </a>
        <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
          <a href="#" class="dropdown-item">
            <!-- Message Start -->
            <div class="media">
              <img src="<?php echo base_url();?>public/dist/img/user1-128x128.jpg" alt="User Avatar" class="img-size-50 mr-3 img-circle">
              <div class="media-body">
                <h3 class="dropdown-item-title">
                  Brad Diesel
                  <span class="float-right text-sm text-danger"><i class="fas fa-star"></i></span>
                </h3>
                <p class="text-sm">Call me whenever you can...</p>
                <p class="text-sm text-muted"><i class="far fa-clock mr-1"></i> 4 Hours Ago</p>
              </div>
            </div>
            <!-- Message End -->
          </a>
          <div class="dropdown-divider"></div>
          <a href="#" class="dropdown-item">
            <!-- Message Start -->
            <div class="media">
              <img src="<?php echo base_url();?>public/dist/img/user8-128x128.jpg" alt="User Avatar" class="img-size-50 img-circle mr-3">
              <div class="media-body">
                <h3 class="dropdown-item-title">
                  John Pierce
                  <span class="float-right text-sm text-muted"><i class="fas fa-star"></i></span>
                </h3>
                <p class="text-sm">I got your message bro</p>
                <p class="text-sm text-muted"><i class="far fa-clock mr-1"></i> 4 Hours Ago</p>
              </div>
            </div>
            <!-- Message End -->
          </a>
          <div class="dropdown-divider"></div>
          <a href="#" class="dropdown-item">
            <!-- Message Start -->
            <div class="media">
              <img src="<?php echo base_url();?>public/dist/img/user3-128x128.jpg" alt="User Avatar" class="img-size-50 img-circle mr-3">
              <div class="media-body">
                <h3 class="dropdown-item-title">
                  Nora Silvester
                  <span class="float-right text-sm text-warning"><i class="fas fa-star"></i></span>
                </h3>
                <p class="text-sm">The subject goes here</p>
                <p class="text-sm text-muted"><i class="far fa-clock mr-1"></i> 4 Hours Ago</p>
              </div>
            </div>
            <!-- Message End -->
          </a>
          <div class="dropdown-divider"></div>
          <a href="#" class="dropdown-item dropdown-footer">See All Messages</a>
        </div>
      </li>
      <!-- Notifications Dropdown Menu -->
      <li class="nav-item dropdown">
        <a class="nav-link" data-toggle="dropdown" href="#">
          <i class="far fa-bell"></i>
          <span class="badge badge-warning navbar-badge">15</span>
        </a>
        <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
          <span class="dropdown-item dropdown-header">15 Notifications</span>
          <div class="dropdown-divider"></div>
          <a href="#" class="dropdown-item">
            <i class="fas fa-envelope mr-2"></i> 4 new messages
            <span class="float-right text-muted text-sm">3 mins</span>
          </a>
          <div class="dropdown-divider"></div>
          <a href="#" class="dropdown-item">
            <i class="fas fa-users mr-2"></i> 8 friend requests
            <span class="float-right text-muted text-sm">12 hours</span>
          </a>
          <div class="dropdown-divider"></div>
          <a href="#" class="dropdown-item">
            <i class="fas fa-file mr-2"></i> 3 new reports
            <span class="float-right text-muted text-sm">2 days</span>
          </a>
          <div class="dropdown-divider"></div>
          <a href="#" class="dropdown-item dropdown-footer">See All Notifications</a>
        </div>
      </li>
      <li class="nav-item">
        <a class="nav-link" data-widget="fullscreen" href="#" role="button">
          <i class="fas fa-expand-arrows-alt"></i>
        </a>
      </li>
      <!-- Profile Dropdown Menu -->
      <li class="nav-item dropdown">
        <a class="nav-link" data-toggle="dropdown" role="button">
          <i class="fa fa-user-circle 2x"></i>
          <!-- <span class="badge badge-danger navbar-badge">3</span> -->
        </a>
        <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
            <div class="text-center pt-2">
              <img src="<?php echo base_url();?>public/dist/img/user1-128x128.jpg" alt="User Avatar" width="100px" height="100px" class=" img-circle">
              <h5>Ahmed Sayyam</h5>
            </div>
            <ul class="p-0">
              <li class="p-2 bg-light border border-light"><a class="d-block text-center" href="">User Profile</a></li>
              <li class="p-2 bg-light border border-light"><a class="d-block text-center" href="">Change Password</a></li>
              <li class="p-2 bg-light border border-light"><a class="d-block text-center" href="">Settings</a></li>
              <li class=" border border-light">
                <a href="<?php echo base_url()?>admin/logout" class="d-block text-center btn-info btn-block p-2">
                  Logout
                </a>
              </li>
            </ul>
        </div>
      </li>
      <li class="nav-item">
        <a class="nav-link" data-widget="control-sidebar" data-slide="true" role="button">
          <i class="fas fa-th-large"></i>
        </a>
      </li>
    </ul>
  </nav>
  <!-- /.navbar -->

  <!-- Main Sidebar Container -->
  <aside class="main-sidebar sidebar-light-info elevation-4">
    <!-- Brand Logo -->
    <a href="#" class="brand-link bg-info">
      <img src="<?php echo base_url();?>public/dist/img/sabzimandi.png" alt="AdminLTE Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
      <span class="brand-text font-weight-light">sabzimandi</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
      <!-- Sidebar user panel (optional) -->
      <div class="user-panel mt-3 pb-3 mb-3 d-flex">
        <div class="image">
          <img src="<?php echo base_url();?>public/dist/img/user2-160x160.jpg" class="img-circle elevation-2" alt="User Image">
        </div>
        <div class="info">
          <a href="#" class="d-block">Admin Name</a>
        </div>
      </div>

      <!-- Sidebar Menu -->
      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
          <!-- Add icons to the links using the .nav-icon class
               with font-awesome or any other icon font library -->
          <li class="nav-item menu-open">
            <a href="#!dashboard" class="nav-link active">
              <i class="nav-icon fas fa-tachometer-alt"></i>
              <p>
                Dashboard
                <!-- <i class="right fas fa-angle-left"></i> -->
              </p>
            </a>
          </li>
          <li class="nav-item">
            <a href="#!customers" class="nav-link">
              <i class="nav-icon fas fa-users"></i>
              <p>Customers </p>
            </a>
          </li>
          <li class="nav-item">
            <a href="#!package" class="nav-link">
              <i class="nav-icon fab fa-codiepie"></i>
              <p>Today print List</p>
            </a>
          </li>
          <li class="nav-item">
            <a href="#!pos" class="nav-link">
              <i class="nav-icon fab fa-codiepie"></i>
              <p>POS</p>
            </a>
          </li>
          <li class="nav-item">
            <a href="#!products" class="nav-link">
              <i class="nav-icon fas fa-user-tie"></i>
              <p>Add product</p>
            </a>
          </li>
          
          <li class="nav-item">
            <a href="#!reports" class="nav-link">
              <i class="nav-icon fa fa-money-bill-wave"></i>
              <p>Reports</p>
            </a>
          </li>
          
          
        </ul>
      </nav>
      <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
  </aside>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <section class="content">
      <div ng-view></div>
    </section>
    
    

    <!-- Main content -->
    
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
  <footer class="main-footer">
    <strong>Copyright &copy; 2014-2021 .</strong>
    <!-- <a href="">AdminLTE.io</a> -->
    All rights reserved.
    <div class="float-right d-none d-sm-inline-block">
      <b>Version</b> 3.1.0
    </div>
  </footer>

  <!-- Control Sidebar -->
  <aside class="control-sidebar control-sidebar-dark">
    <!-- Control sidebar content goes here -->
  </aside>
  <!-- /.control-sidebar -->
</div>
<!-- ./wrapper -->
<!-- jQuery -->
<script src="<?php echo base_url();?>public/plugins/jquery/jquery.min.js"></script>
<!-- jQuery UI 1.11.4 -->
<script src="<?php echo base_url();?>public/plugins/jquery-ui/jquery-ui.min.js"></script>
<!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
<script>
  $.widget.bridge('uibutton', $.ui.button)
</script>
<!-- Bootstrap 4 -->
<script src="<?php echo base_url();?>public/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- ChartJS -->
<script src="<?php echo base_url();?>public/plugins/chart.js/Chart.min.js"></script>
<!-- Sparkline -->
<!-- <script src="<?php echo base_url();?>public/plugins/sparklines/sparkline.js"></script> -->
<!-- JQVMap -->
<!-- Select 2 Plugin  -->
<script src="<?php echo base_url();?>public/plugins/select2/js/select2.full.min.js"></script>

<script src="<?php echo base_url();?>public/plugins/jqvmap/jquery.vmap.min.js"></script>
<script src="<?php echo base_url();?>public/plugins/jqvmap/maps/jquery.vmap.usa.js"></script>
<!-- jQuery Knob Chart -->
<script src="<?php echo base_url();?>public/plugins/jquery-knob/jquery.knob.min.js"></script>
<!-- daterangepicker -->
<script src="<?php echo base_url();?>public/plugins/moment/moment.min.js"></script>
<script src="<?php echo base_url();?>public/plugins/daterangepicker/daterangepicker.js"></script>
<!-- Tempusdominus Bootstrap 4 -->
<script src="<?php echo base_url();?>public/plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js"></script>
<!-- Summernote -->
<script src="<?php echo base_url();?>public/plugins/summernote/summernote-bs4.min.js"></script>
<!-- overlayScrollbars -->
<script src="<?php echo base_url();?>public/plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js"></script>

<!-- SweetAlert2 -->
<script src="<?php echo base_url();?>public/plugins/sweetalert2/sweetalert2.min.js"></script>
<!-- Toastr -->
<script src="<?php echo base_url();?>public/plugins/toastr/toastr.min.js"></script>


 <!-- Angular 1.8.0 -->
 <script src="<?php echo base_url();?>public/plugins/angular-1.8.0/angular.min.js"></script>
 <script src="<?php echo base_url();?>public/plugins/angular-1.8.0/angular-route.min.js"></script>

 <script src="<?php echo base_url();?>public/dist/js/pdfmake.js"></script>
 <script src="<?php echo base_url();?>public/dist/js/pdffont.js"></script>


 <!-- <script data-require="angular-translate@*" data-semver="2.5.0" src="https://cdn.rawgit.com/angular-translate/bower-angular-translate/2.5.0/angular-translate.js"></script> -->
 
 <!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/angular-translate/2.19.0/angular-translate.min.js" integrity="sha512-a/Saqh9wa0rRm8gEgTqGYgoIh1Jki7htgcbLo6R9R990l8TqdIrpx9yWuTLJ+lMsWpQeLbrkbKvkTizrOuCI9g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
 <script src="https://cdnjs.cloudflare.com/ajax/libs/angular-translate/2.19.0/angular-translate-loader-static-files/angular-translate-loader-static-files.min.js" integrity="sha512-L5AdgFYPjTX6NzrSbKLj5ZpT0bO6+Dhuv+Drw8AaY9oLKYlqElsSNtSR77o4ivyWiUDUW++nYTpt4wzG5U3GJQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
 -->
<!-- Angular Translate Plugin -->
 <script src="//cdnjs.cloudflare.com/ajax/libs/angular-translate/2.18.1/angular-translate.min.js"></script>
 <script src="//cdnjs.cloudflare.com/ajax/libs/angular-translate/2.18.1/angular-translate-loader-static-files/angular-translate-loader-static-files.min.js"></script>

 <!-- Angular Datatables -->

 

 <!-- DataTables -->
 <script src="<?php echo base_url();?>public/plugins/datatables-angularjs/datatables/jquery.dataTables.min.js"></script>
 <script src="<?php echo base_url();?>public/plugins/datatables-angularjs/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
 <script src="<?php echo base_url();?>public/plugins/datatables-angularjs/datatables-responsive/js/dataTables.responsive.min.js"></script>
 <script src="<?php echo base_url();?>public/plugins/datatables-angularjs/datatables-responsive/js/responsive.bootstrap4.min.js"></script>
 <script src="<?php echo base_url();?>public/plugins/datatables-angularjs/angular-datatables/angular-datatables.min.js"></script> 

 <!-- <script src="<?php echo base_url();?>public/plugins/angular-datatables/plugins/buttons/angular-datatables.buttons.min.js"></script> -->
 <script src="<?php echo base_url();?>public/plugins/datatables-buttons/js/dataTables.buttons.min.js"></script>
 <script src="<?php echo base_url();?>public/plugins/datatables-buttons/js/buttons.bootstrap4.min.js"></script>
 <script src="<?php echo base_url();?>public/plugins/jszip/jszip.min.js"></script>
<script src="<?php echo base_url();?>public/plugins/pdfmake/pdfmake.min.js"></script>
<script src="<?php echo base_url();?>public/plugins/pdfmake/vfs_fonts.js"></script>
<script src="<?php echo base_url();?>public/plugins/datatables-buttons/js/buttons.colVis.min.js"></script>
<script src="<?php echo base_url();?>public/plugins/datatables-buttons/js/buttons.flash.min.js"></script>
<script src="<?php echo base_url();?>public/plugins/datatables-buttons/js/buttons.html5.min.js"></script>
<script src="<?php echo base_url();?>public/plugins/datatables-buttons/js/buttons.print.min.js"></script>

<!-- AdminLTE App -->
<script src="<?php echo base_url();?>public/dist/js/adminlte.js"></script>
<!-- AdminLTE for demo purposes -->
<script src="<?php echo base_url();?>public/dist/js/demo.js"></script>
<!-- AdminLTE dashboard demo (This is only for demo purposes) -->
<script src="<?php echo base_url();?>public/dist/js/pages/dashboard.js"></script>
 <!-- Dropzone JS -->
 <script src="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.7.2/dropzone.min.js"></script>
 <!-- sweetalert2 cdn -->
 <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10.16.7/dist/sweetalert2.all.min.js"></script>
 <!-- App JS -->
<script src="<?php echo base_url();?>public/controllers/app.js"></script>
 <!-- Controller JS  -->
 <script src="<?php echo base_url();?>public/controllers/controller.js"></script>
<script>
 
</script>

</body>

</html>
