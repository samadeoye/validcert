<?php
require_once '../../inc/utils.php';
$pageTitle = 'Reset Password';
require_once DEF_DOC_ROOT_ADMIN.'inc/head-guest.php';

use ValidCert\User\Views\ResetPasswordView;

ResetPasswordView::$userType = 'admin';
echo ResetPasswordView::getUserResetPasswordView();

$arAdditionalJsOnLoad[] = ResetPasswordView::getUserResetPasswordViewJsOnLoad();

require_once DEF_DOC_ROOT_ADMIN.'inc/foot-guest.php';