<?php
require_once '../../inc/utils.php';
$pageTitle = 'Register';
require_once DEF_DOC_ROOT_ISSUER.'inc/head-guest.php';

use ValidCert\User\Views\RegisterView;

RegisterView::$userType = 'issuer';
echo RegisterView::getUserRegisterView();

$arAdditionalJsOnLoad[] = RegisterView::getUserRegisterViewJsOnLoad();
$arAdditionalJs[] = RegisterView::getUserRegisterViewJs();

require_once DEF_DOC_ROOT_ISSUER.'inc/foot-guest.php';