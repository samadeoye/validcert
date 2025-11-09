<?php
$arCurrentPage = getCurrentPage($pageTitle);
?>

<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">

	<base href="<?php echo DEF_ROOT_PATH; ?>/">
	<title><?php echo SITE_NAME; ?> - <?php echo $pageTitle; ?></title>
	<meta name="description" content="<?php echo SITE_DESC; ?>">
	<meta name="keywords" content="<?php echo SITE_KEYWORDS; ?>">

	<!-- Favicons -->
	<link href="assets/img/favicon.png" rel="icon">

	<!-- Fonts -->
	<link href="https://fonts.googleapis.com" rel="preconnect">
	<link href="https://fonts.gstatic.com" rel="preconnect" crossorigin>
	<link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&family=Raleway:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">

	<!-- Vendor CSS Files -->
	<link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
	<link href="assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
	<link href="assets/vendor/aos/aos.css" rel="stylesheet">
	<link href="assets/vendor/fontawesome-free/css/all.min.css" rel="stylesheet">
	<link href="assets/vendor/glightbox/css/glightbox.min.css" rel="stylesheet">
	<link href="assets/vendor/swiper/swiper-bundle.min.css" rel="stylesheet">
  <!-- Bootstrap Datepicker -->
  <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.10.0/css/bootstrap-datepicker.min.css" rel="stylesheet">
  <!-- Toast Alert -->
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.css">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">

  <!-- Main CSS File -->
  <link href="assets/css/main.css?v=<?php echo filemtime('assets/css/main.css'); ?>" rel="stylesheet">

  <?php
  if (isset($arAdditionalCSS) && count($arAdditionalCSS) > 0)
  {
    echo implode(PHP_EOL, $arAdditionalCSS);
  }
  ?>

</head>

<body class="index-page">

  <header id="header" class="header sticky-top">

    <div class="branding d-flex align-items-center">

      <div class="container position-relative d-flex align-items-center justify-content-between">
        <a href="<?php echo DEF_ROOT_PATH; ?>" class="logo d-flex align-items-center me-auto">
          <img src="assets/img/logo.png" alt="<?php echo SITE_NAME; ?>">
          <!-- <h4 class="sitename"><?php echo SITE_NAME; ?></h4> -->
        </a>

        <nav id="navmenu" class="navmenu">
          <ul>
            <li><a href="<?php echo DEF_ROOT_PATH; ?>" class="<?php echo $arCurrentPage['certificateverification'];?>">Home<br></a></li>
            <li><a href="verify" class="<?php echo $arCurrentPage['verify'];?>">Verify</a></li>
            
            <?php
              if (isset($_SESSION['verifier']))
              { ?>
                <li><a href="verifier/app/">Dashbord</a></li>
                <?php
              }
              elseif (isset($_SESSION['issuer']))
              { ?>
                <li><a href="issuer/app/">Dashbord</a></li>
                <?php
              }
              else
              { ?>
                <li><a href="verifier/app/login">Verifier Login</a></li>
                <li><a href="issuer/app/login">Issuer Login</a></li>
                <?php
              }
            ?>

          </ul>
          <i class="mobile-nav-toggle d-xl-none bi bi-list"></i>
        </nav>

        <a class="cta-btn d-none d-sm-block" href="verify">Verify</a>

      </div>

    </div>

  </header>


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

<!-- Large Modal -->
<div class="modal fade" id="largeModal" tabindex="-1" data-keyboard="false" data-backdrop="static" aria-labelledby="largeModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content"> </div>
  </div>
</div>

<!-- Extra Large Modal -->
<div class="modal fade" id="extraLargeModal" tabindex="-1" data-focus="false" data-keyboard="false" data-backdrop="static" aria-labelledby="extraLargeModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-xl">
    <div class="modal-content"> </div>
  </div>
</div>