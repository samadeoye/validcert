<?php
if (!isset($_SESSION['admin']))
{
  redirectAdminToAuth();
}
$arCurrentPage = getCurrentPageAdmin($pageTitle);
define('DEF_BASE_URL', DEF_ROOT_PATH_ADMIN.'/');
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?php echo SITE_NAME;?> - <?php echo $pageTitle;?></title>
  <base href="<?php echo DEF_ROOT_PATH; ?>/">
  <link rel="icon" type="image/png" href="<?php echo DEF_ROOT_PATH; ?>/assets/img/favicon.png"/>

  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <link rel="stylesheet" href="assets/appassets/plugins/fontawesome-free/css/all.min.css">
  <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
  <link rel="stylesheet" href="assets/appassets/plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css">
  <link rel="stylesheet" href="assets/appassets/plugins/icheck-bootstrap/icheck-bootstrap.min.css">
  <link rel="stylesheet" href="assets/appassets/plugins/jqvmap/jqvmap.min.css">
  <link rel="stylesheet" href="assets/appassets/assets/css/adminlte.min.css">
  <link rel="stylesheet" href="assets/appassets/plugins/overlayScrollbars/css/OverlayScrollbars.min.css">
  <link rel="stylesheet" href="assets/appassets/plugins/daterangepicker/daterangepicker.css">
  <link rel="stylesheet" href="assets/appassets/plugins/summernote/summernote-bs4.min.css">
  <link rel="stylesheet" href="assets/appassets/plugins/toastr/toastr.min.css">
  <link rel="stylesheet" href="assets/appassets/assets/css/adminlte.css">

  <link rel="stylesheet" href="assets/appassets/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
  <link rel="stylesheet" href="assets/appassets/plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
  
  <?php
  if (isset($arAdditionalCSS) && count($arAdditionalCSS) > 0)
  {
    echo implode(PHP_EOL, $arAdditionalCSS);
  }
  ?>

</head>
<body class="hold-transition sidebar-mini layout-fixed">
<div class="wrapper">


<!-- Default Modal -->
<div class="modal fade" id="defaultModal" tabindex="-1" data-keyboard="false" data-backdrop="static" aria-labelledby="defaultModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content"> </div>
    </div>
</div>

<!-- Small Modal -->
<div class="modal fade" id="smallModal" tabindex="-1" data-keyboard="false" data-backdrop="static" aria-labelledby="smallModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-sm">
        <div class="modal-content"> </div>
    </div>
</div>

<!-- Extra Large Modal -->
<div class="modal fade" id="extraLargeModal" tabindex="-1" data-keyboard="false" data-backdrop="static" aria-labelledby="extraLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content"> </div>
    </div>
</div>

<!-- XXL Modal -->
<div class="modal fade" id="xxLargeModal" tabindex="-1" data-focus="false" data-keyboard="false" data-backdrop="static" aria-labelledby="xxLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog xxl-modal">
        <div class="modal-content"> </div>
    </div>
</div>

<!-- Large Modal -->
<div class="modal fade" id="largeModal" tabindex="-1" data-focus="false" data-keyboard="false" data-backdrop="static" aria-labelledby="largeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content"> </div>
    </div>
</div>

  <!-- Navbar -->
  <nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
      </li>
    </ul>
  </nav>
  <!-- /.navbar -->

  <!-- Main Sidebar Container -->
  <aside class="main-sidebar sidebar-dark-primary elevation-4">
    <a href="<?php echo DEF_BASE_URL;?>app/" class="brand-link">
      <span class="brand-text font-weight-bold"><?php echo SITE_NAME;?></span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
      <div class="user-panel mt-3 pb-3 mb-3 d-flex">
        <div class="image">
          <img src="assets/appassets/assets/img/dash_avatar.png" class="img-circle elevation-2" alt="User">
        </div>
        <div class="info">
          <a href="#" class="d-block"><?php echo $arUser['firstName'] . ' ' . $arUser['lastName'];?></a>
        </div>
      </div>

      <!-- Sidebar Menu -->
      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
          <li class="nav-item py-2">
            <a href="<?php echo DEF_BASE_URL; ?>app/" class="nav-link <?php echo $arCurrentPage['dashboard'];?>">
              <i class="nav-icon fas fa-home"></i>
              <p>Dashboard</p>
            </a>
          </li>

          <li class="nav-item py-2">
            <a href="<?php echo DEF_BASE_URL; ?>app/issuers" class="nav-link <?php echo $arCurrentPage['issuers'];?>">
              <i class="nav-icon fas fa-school"></i>
              <p>Issuers</p>
            </a>
          </li>

          <li class="nav-item py-2">
            <a href="<?php echo DEF_BASE_URL; ?>app/verifiers" class="nav-link <?php echo $arCurrentPage['verifiers'];?>">
              <i class="nav-icon fas fa-users"></i>
              <p>Verifiers</p>
            </a>
          </li>

          <li class="nav-item py-2">
            <a href="<?php echo DEF_BASE_URL; ?>app/certificates" class="nav-link <?php echo $arCurrentPage['certificates'];?>">
              <i class="nav-icon fas fa-certificate"></i>
              <p>Certificates</p>
            </a>
          </li>

          <li class="nav-item py-2">
            <a href="<?php echo DEF_BASE_URL; ?>app/verification-history" class="nav-link <?php echo $arCurrentPage['verificationhistory'];?>">
              <i class="nav-icon fas fa-eye"></i>
              <p>Verification History</p>
            </a>
          </li>

          <li class="nav-item py-2">
            <a href="<?php echo DEF_BASE_URL; ?>app/educationlevels" class="nav-link <?php echo $arCurrentPage['educationlevels'];?>">
              <i class="nav-icon fas fa-graduation-cap"></i>
              <p>Education Levels</p>
            </a>
          </li>

          <li class="nav-item py-2">
            <a href="<?php echo DEF_BASE_URL; ?>app/auditlogs" class="nav-link <?php echo $arCurrentPage['auditlogs'];?>">
              <i class="nav-icon fas fa-history"></i>
              <p>Audit Logs</p>
            </a>
          </li>

          <li class="nav-item py-2">
            <a href="<?php echo DEF_BASE_URL; ?>app/profile" class="nav-link <?php echo $arCurrentPage['profile'];?>">
              <i class="nav-icon fas fa-user"></i>
              <p>Profile</p>
            </a>
          </li>

          <li class="nav-item py-2">
            <a href="javascript:;" class="nav-link" id="btnLogoutSidebar">
              <i class="nav-icon fas fa-sign-out-alt"></i>
              <p>Logout</p>
            </a>
          </li>
        </ul>
      </nav>
      <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
  </aside>