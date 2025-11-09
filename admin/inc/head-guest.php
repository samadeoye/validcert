<?php
if (isset($_SESSION['admin']))
{
  header('Location: '.DEF_ROOT_PATH_ADMIN.'/app/');
}
define('DEF_BASE_URL', DEF_ROOT_PATH_ADMIN.'/');
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <base href="<?php echo DEF_ROOT_PATH; ?>/">
  <title><?php echo SITE_NAME; ?> - <?php echo $pageTitle; ?></title>
  <link rel="icon" type="image/png" href="<?php echo DEF_ROOT_PATH; ?>/assets/img/favicon.png"/>

  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <link rel="stylesheet" href="assets/appassets/plugins/fontawesome-free/css/all.min.css">
  <link rel="stylesheet" href="assets/appassets/plugins/icheck-bootstrap/icheck-bootstrap.min.css">
  <link rel="stylesheet" href="assets/appassets/assets/css/adminlte.min.css">
  <link rel="stylesheet" href="assets/appassets/plugins/toastr/toastr.min.css">
  <link rel="stylesheet" href="assets/appassets/assets/css/adminlte.css">
</head>