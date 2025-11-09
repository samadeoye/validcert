<?php
require_once '../../inc/utils.php';

set_error_handler(function ($severity, $message, $file, $line)
{
    if (!(error_reporting() & $severity))
    {
        return false;
    }
    throw new \ErrorException($message, 0, $severity, $file, $line);
});

use ValidCert\Auth\Register;
use ValidCert\Auth\Login;
use ValidCert\User\User;
use ValidCert\Certificate\CertificateVerification;
use ValidCert\Certificate\CertificateVerificationList;

$action = isset($_REQUEST['action']) ? trim($_REQUEST['action']) : '';
if ($action == '')
{
    getJsonRow(false, 'Invalid request!');
}

$nonTransactional = [];
$transactionStarted = false;

try
{
    if (!in_array($action, $nonTransactional))
    {
        $db->beginTransaction();
        $transactionStarted = true;
    }

    $userId = getUserSessionValueByKey('id');
    //pass into the request
    if (!in_array($action, ['register', 'login', 'forgotpassverifyemail', 'resetpassword']))
    {
        $_REQUEST['userId'] = $userId;
        $_REQUEST['userType'] = $userType;
    }

    $data = $arExtraData = [];
    switch ($action)
    {
        case 'register':
            Register::registerUser($_REQUEST);
            $arExtraData = Register::$dataJson;
        break;

        case 'login':
            Login::loginUser($_REQUEST);
            $arExtraData = Login::$dataJson;
        break;

        case 'updateprofile':
            User::updateUser(
                $_REQUEST, $userId
            );
            $arUser = User::$data;
            if (count($arUser) > 0)
            {
                $data = $arUser;
            }
        break;

        case 'changepassword':
            User::changePassword(
                $_REQUEST, $userId
            );
        break;

        case 'forgotpassverifyemail':
            User::verifyEmailForPasswordReset($_REQUEST);
        break;

        case 'resetpassword':
            User::resetPassword($_REQUEST);
        break;

        case 'verifycertificate':
            CertificateVerification::verifyCertificate(
                $_REQUEST
            );
            $arExtraData = CertificateVerification::$dataJson;
        break;

        case 'getcertificatesverifications':
            CertificateVerificationList::getCertificatesVerificationsList($_REQUEST);
            $dataJson = CertificateVerificationList::$dataJson;
            if (count($dataJson) > 0)
            {
                $data = $dataJson;
            }
        break;

        default:
            $arExtraData['status'] = false;
            $arExtraData['msg'] = 'Unknown action';
    }

    if ($transactionStarted)
    {
        $db->commit();
    }
    
    if (count($data) > 0)
    {
        getJsonList($data);
    }
    getJsonRow(
        true
        , 'Operation successful!'
        , $arExtraData
    );
}
catch (Throwable $ex)
{
    if ($transactionStarted)
    {
        $db->rollBack();
    }
    getJsonRow(false, $ex->getMessage());
}