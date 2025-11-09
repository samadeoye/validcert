<?php
require_once '../../inc/utils.php';
$pageTitle = 'Reset Password';
require_once DEF_DOC_ROOT_VERIFIER.'inc/head-guest.php';

use ValidCert\User\Views\ResetPasswordView;

ResetPasswordView::$userType = 'verifier';
echo ResetPasswordView::getUserResetPasswordView();

$arAdditionalJsOnLoad[] = ResetPasswordView::getUserResetPasswordViewJsOnLoad();

require_once DEF_DOC_ROOT_VERIFIER.'inc/foot-guest.php';