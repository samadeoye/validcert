<?php
require_once '../../inc/utils.php';

//update user login logs
ValidCert\Auth\Login::logUserLogout(
    $_SESSION['issuer']['sessionId']
);

unset($_SESSION['issuer']);

header('Location: '.DEF_ROOT_PATH_ISSUER.'/app/login');