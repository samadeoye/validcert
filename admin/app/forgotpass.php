<?php
require_once '../../inc/utils.php';
$pageTitle = 'Forgot Password';
require_once DEF_DOC_ROOT_ADMIN.'inc/head-guest.php';

use ValidCert\User\Views\ForgotPasswordView;

ForgotPasswordView::$userType = 'admin';
echo ForgotPasswordView::getUserForgotPasswordView();

$arAdditionalJsOnLoad[] = ForgotPasswordView::getUserForgotPasswordViewJsOnLoad();

require_once DEF_DOC_ROOT_ADMIN.'inc/foot-guest.php';