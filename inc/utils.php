<?php
session_start();

$httpHost = $_SERVER['HTTP_HOST'];
$httpFolderPath = '';
$isProductionServer = true;
$isLocal = false;

if (in_array($httpHost, ['localhost', '127.0.0.1']))
{
    //LOCAL
    $httpFolderPath = '/validcert';
    $httpHost = 'http://'.$httpHost;
    $isProductionServer = false;
    $isLocal = true;
}
else
{
    //PRODUCTION
    $httpHost = 'https://'.$httpHost;
}
define('DEF_ROOT_PATH', $httpFolderPath);
define('DEF_FULL_ROOT_PATH', $httpHost.$httpFolderPath);
define('DEF_DOC_ROOT', $_SERVER['DOCUMENT_ROOT'] .'/'. $httpFolderPath . '/');

define('DEF_ROOT_PATH_VERIFIER', DEF_ROOT_PATH.'/verifier');
define('DEF_FULL_ROOT_PATH_VERIFIER', DEF_FULL_ROOT_PATH.'/verifier');
define('DEF_DOC_ROOT_VERIFIER', DEF_DOC_ROOT.'verifier/');

define('DEF_ROOT_PATH_ISSUER', DEF_ROOT_PATH.'/issuer');
define('DEF_FULL_ROOT_PATH_ISSUER', DEF_FULL_ROOT_PATH.'/issuer');
define('DEF_DOC_ROOT_ISSUER', DEF_DOC_ROOT.'issuer/');

define('DEF_ROOT_PATH_ADMIN', DEF_ROOT_PATH.'/admin');
define('DEF_FULL_ROOT_PATH_ADMIN', DEF_FULL_ROOT_PATH.'/admin');
define('DEF_DOC_ROOT_ADMIN', DEF_DOC_ROOT.'admin/');

define('DEF_IS_PRODUCTION', $isProductionServer);
define('DEF_IS_LOCAL', $isLocal);

error_reporting(E_ALL);
if (DEF_IS_LOCAL)
{
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    //error_reporting(E_ALL);
}

require_once DEF_DOC_ROOT.'vendor/autoload.php';
require_once DEF_DOC_ROOT.'inc/functions.php';
require_once DEF_DOC_ROOT.'inc/constants.php';
require_once DEF_DOC_ROOT.'inc/connect.php';
require_once DEF_DOC_ROOT.'inc/dropdowns.php';

$arAdditionalCSS = $arAdditionalJs = $arAdditionalJsScripts = $arAdditionalJsOnLoad = [];
$arUser = [];
$userId = '';

$userType = getCurrentLoginUserType();
if (!in_array($userType, ['issuer', 'verifier', 'admin']))
{
    $userType = 'verifier';
}
if (!empty($userType))
{
    if (isset($_SESSION[$userType]))
    {
        $arUser = getUserSessionByUserType($userType);
        $userId = $arUser['id'];
    }
}