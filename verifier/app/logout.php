<?php
require_once '../../inc/utils.php';

//update user login logs
ValidCert\Auth\Login::logUserLogout(
    $_SESSION['verifier']['sessionId']
);

unset($_SESSION['verifier']);

header('Location: '.DEF_ROOT_PATH_VERIFIER.'/app/login');