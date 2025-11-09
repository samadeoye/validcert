<?php
require_once '../../inc/utils.php';
$pageTitle = 'Register';
require_once DEF_DOC_ROOT_VERIFIER.'inc/head-guest.php';

use ValidCert\User\Views\RegisterView;

RegisterView::$userType = 'verifier';
echo RegisterView::getUserRegisterView();

$arAdditionalJsOnLoad[] = RegisterView::getUserRegisterViewJsOnLoad();
$arAdditionalJs[] = RegisterView::getUserRegisterViewJs();

require_once DEF_DOC_ROOT_VERIFIER.'inc/foot-guest.php';