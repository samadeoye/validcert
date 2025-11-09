<?php
require_once 'utils.php';

set_error_handler(function ($severity, $message, $file, $line)
{
    if (!(error_reporting() & $severity))
    {
        return false;
    }
    throw new \ErrorException($message, 0, $severity, $file, $line);
});

use ValidCert\Certificate\CertificateVerification;

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

    $userId = getUserSessionValueByKey('id', 'verifier');
    //pass into the request
    $_REQUEST['userId'] = $userId;

    $data = $arExtraData = [];
    switch ($action)
    {
        case 'verifycertificate':
            CertificateVerification::verifyCertificate(
                $_REQUEST
            );
            $arExtraData = CertificateVerification::$dataJson;
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