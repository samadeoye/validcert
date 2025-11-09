<?php
require_once '../../inc/utils.php';
require_once DEF_DOC_ROOT_ISSUER.'inc/head-guest.php';

use ValidCert\User\Views\LoginView;

LoginView::$userType = 'issuer';
echo LoginView::getUserLoginView();

$arAdditionalJsOnLoad[] = LoginView::getUserLoginViewJsOnLoad();

require_once DEF_DOC_ROOT_ISSUER.'inc/foot-guest.php';