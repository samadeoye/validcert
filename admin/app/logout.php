<?php
require_once '../../inc/utils.php';

unset($_SESSION['admin']);

header('Location: '.DEF_ROOT_PATH_ADMIN.'/app/login');