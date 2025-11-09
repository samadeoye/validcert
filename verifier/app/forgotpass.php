<?php
require_once '../../inc/utils.php';
$pageTitle = 'Forgot Password';
require_once DEF_DOC_ROOT_VERIFIER.'inc/head-guest.php';

use ValidCert\User\Views\ForgotPasswordView;

ForgotPasswordView::$userType = 'verifier';
echo ForgotPasswordView::getUserForgotPasswordView();

$arAdditionalJsOnLoad[] = ForgotPasswordView::getUserForgotPasswordViewJsOnLoad();

require_once DEF_DOC_ROOT_VERIFIER.'inc/foot-guest.php';