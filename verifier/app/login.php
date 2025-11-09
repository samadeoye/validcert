<?php
require_once '../../inc/utils.php';
$pageTitle = 'Login';
require_once DEF_DOC_ROOT_VERIFIER.'inc/head-guest.php';

use ValidCert\User\Views\LoginView;

LoginView::$userType = 'verifier';
echo LoginView::getUserLoginView();

$arAdditionalJsOnLoad[] = LoginView::getUserLoginViewJsOnLoad();

require_once DEF_DOC_ROOT_VERIFIER.'inc/foot-guest.php';