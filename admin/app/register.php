<?php
require_once '../../inc/utils.php';
$pageTitle = 'Register';
require_once DEF_DOC_ROOT_ADMIN.'inc/head-guest.php';

use ValidCert\User\Views\RegisterView;

RegisterView::$userType = 'admin';
echo RegisterView::getUserRegisterView();

$arAdditionalJsOnLoad[] = RegisterView::getUserRegisterViewJsOnLoad();
$arAdditionalJs[] = RegisterView::getUserRegisterViewJs();

require_once DEF_DOC_ROOT_ADMIN.'inc/foot-guest.php';