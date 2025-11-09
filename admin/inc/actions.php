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
use ValidCert\Admin\Issuer\IssuersList;
use ValidCert\Admin\Issuer\Issuer;
use ValidCert\Admin\Verifier\VerifiersList;
use ValidCert\Admin\Verifier\Verifier;
use ValidCert\Certificate\CertificateVerificationList;
use ValidCert\Issuer\Certificate\CertificatesList;
use ValidCert\Admin\EducationLevel\EducationLevel;
use ValidCert\Admin\EducationLevel\EducationLevelsList;
use ValidCert\History\HistoryList;

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

        case 'getissuers':
            IssuersList::getIssuersList($_REQUEST);
            $dataJson = IssuersList::$dataJson;
            if (count($dataJson) > 0)
            {
                $data = $dataJson;
            }
        break;

        case 'rejectissuer':
            Issuer::rejectIssuer($_REQUEST);
        break;

        case 'approveissuer':
            Issuer::approveIssuer($_REQUEST);
        break;

        case 'getverifiers':
            VerifiersList::getVerifiersList($_REQUEST);
            $dataJson = VerifiersList::$dataJson;
            if (count($dataJson) > 0)
            {
                $data = $dataJson;
            }
        break;

        case 'disableverifier':
            Verifier::disableVerifier($_REQUEST);
        break;

        case 'enableverifier':
            Verifier::enableVerifier($_REQUEST);
        break;

        case 'getcertificates':
            CertificatesList::getCertificatesList($_REQUEST);
            $dataJson = CertificatesList::$dataJson;
            if (count($dataJson) > 0)
            {
                $data = $dataJson;
            }
        break;

        case 'getcertificatesverifications':
            CertificateVerificationList::getCertificatesVerificationsList($_REQUEST);
            $dataJson = CertificateVerificationList::$dataJson;
            if (count($dataJson) > 0)
            {
                $data = $dataJson;
            }
        break;

        case 'geteducationlevels':
            EducationLevelsList::getEducationLevelsList();
            $dataJson = EducationLevelsList::$dataJson;
            if (count($dataJson) > 0)
            {
                $data = $dataJson;
            }
        break;

        case 'addeducationlevel':
            $params = $_REQUEST;
            $params['action'] = 'add';
            $obj = new EducationLevel($params);
            $obj->processAction();
            $arExtraData = $obj->dataJson;
        break;

        case 'updateeducationlevel':
            $params = $_REQUEST;
            $params['action'] = 'update';
            $obj = new EducationLevel($params);
            $obj->processAction();
            $arExtraData = $obj->dataJson;
        break;

        case 'deleteeducationlevel':
            $params = $_REQUEST;
            $params['action'] = 'delete';
            $obj = new EducationLevel($params);
            $obj->processAction();
            $arExtraData = $obj->dataJson;
        break;

        case 'gethistory':
            HistoryList::getHistoryList($_REQUEST);
            $dataJson = HistoryList::$dataJson;
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