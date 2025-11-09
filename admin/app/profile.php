<?php
require_once '../../inc/utils.php';
$pageTitle = 'Profile';
require_once DEF_DOC_ROOT_ADMIN.'inc/head.php';

use ValidCert\User\Views\ProfileView;

echo ProfileView::getUserProfileView([
    'arUser' => $arUser
]);

$arAdditionalJsOnLoad[] = ProfileView::getUserProfileViewJsOnLoad();

require_once DEF_DOC_ROOT_ADMIN.'inc/foot.php';