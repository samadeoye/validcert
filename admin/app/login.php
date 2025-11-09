<?php
require_once '../../inc/utils.php';
$pageTitle = 'Login';
require_once DEF_DOC_ROOT_ADMIN.'inc/head-guest.php';

use ValidCert\User\Views\LoginView;

LoginView::$userType = 'admin';
echo LoginView::getUserLoginView();

$arAdditionalJsOnLoad[] = LoginView::getUserLoginViewJsOnLoad();

require_once DEF_DOC_ROOT_ADMIN.'inc/foot-guest.php';