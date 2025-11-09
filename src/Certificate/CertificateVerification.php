<?php
namespace ValidCert\Certificate;

use Exception;
use ValidCert\Crud\Crud;
use ValidCert\EducationLevel\EducationLevelFunctions;
use ValidCert\Certificate\CertificateQr;
use ValidCert\User\User;

class CertificateVerification
{
    private static $table = DEF_TBL_CERTIFICATES;
    private static $tableVerification = DEF_TBL_CERTIFICATES_VERIFICATIONS;
    protected static $historyCategory = 'certificates';
    public static $dataJson = [];
    
    /**
     * Verify a certificate by form data
     * @param array $requestData
     * @throws \Exception
     * @return void
     */
    public static function verifyCertificate($requestData)
    {
        $certificateId = trim($requestData['certificateId']); //optional
        $holderFirstName = stringToUpper(trim($requestData['holderFirstName']));
        $holderLastName = stringToUpper(trim($requestData['holderLastName']));
        $program = stringToUpper(trim($requestData['program']));
        $issuerId = trim($requestData['issuerId']);
        $issueDate = trim($requestData['issueDate']);

        $userId = isset($requestData['userId']) ? $requestData['userId'] : '';

        //convert issue date to appropriate PHP date format
        if (!empty($issueDate))
        {
            $issueDate = date('Y-m-d', strtotime($issueDate));
        }

        //validate
        if (empty($holderFirstName) || strlen($holderFirstName) < 3)
        {
            throw new Exception('Please enter a valid holder\'s first name');
        }
        elseif (empty($holderLastName) || strlen($holderLastName) < 3)
        {
            throw new Exception('Please enter a valid holder\'s last name');
        }
        elseif (empty($issuerId) || strlen($issuerId) != 36)
        {
            throw new Exception('Please select the certificate issuer');
        }
        elseif (empty($issueDate) || strlen($issueDate) != 10)
        {
            throw new Exception('Please select the issue date');
        }
        else
        {
            /*
            VERIFY CERTIFICATE:
            Compute hash using this data and check against stored hashes.
            */
            $data = [
                'certificateId' => $certificateId
                , 'issuerId' => $issuerId
                , 'holderFirstName' => $holderFirstName
                , 'holderLastName' => $holderLastName
                , 'program' => $program
                , 'issueDate' => $issueDate
            ];

            //compute certifcate metadata hash
            $obj = new CertificateHash([
                'data' => $data
            ]);
            $certificateHash = $obj->computeCertificateHash();

            //verify the certificate
            $verificationData = [
                'holderFullName' => "{$holderFirstName} {$holderLastName}"
                , 'issuerId' => $issuerId
                , 'issueDate' => $issueDate
            ];
            $verificationResult = self::getCertificateVerificationResultByHash(
                $certificateHash, $userId, 'form', $verificationData
            );
            $status = $verificationResult['status'];
            $msg = $verificationResult['msg'];

            if ($status == 1)
            {
                self::$dataJson['msg'] = $msg;
            }
            else
            {
                self::$dataJson['status'] = false;
                self::$dataJson['msg'] = $msg;
            }
        }
    }

    /**
     * Verify a certificate by hash (from QR)
     * @param string $certificateHash
     * @param string $userId
     * @return array{msg: string, status: bool}
     */
    public static function verifyCertificateByHash($certificateHash, $userId='')
    {
        return self::getCertificateVerificationResultByHash(
            $certificateHash, $userId
        );
    }

    /**
     * Verify a certificate by hash (QR) or form data
     * @param mixed $certificateHash
     * @param mixed $userId
     * @param mixed $verificationType
     * @param mixed $verificationData
     * @return array{msg: string, status: boolean}
     */
    private static function getCertificateVerificationResultByHash($certificateHash, $userId='', $verificationType='', $verificationData=[])
    {
        $rs = Crud::getRecordInfoWithCondition(
            self::$table
            , ['id', 'issuerId', 'certificateId', 'holderFirstName', 'holderLastName', 'holderFullName', 'program', 'issueDate', 'levelId']
            , ['certificateHash' => $certificateHash]
        );
        $level = $program = $certificateId = '';
        $status = false;
        if ($rs)
        {
            $certificateId = $rs['certificateId'];
            $holderFirstName = $rs['holderFirstName'];
            $holderLastName = $rs['holderLastName'];
            $issuerId = $rs['issuerId'];
            $issueDate = $rs['issueDate'];
            $program = $rs['program'];
            $holderFullName = $rs['holderFullName'];

            $isValid = true;
            //if verificate is done via form, the hashing is already done before calling this function
            if ($verificationType != 'form')
            {
                //recompute the hash to see if it matches
                $data = [
                    'certificateId' => $certificateId
                    , 'issuerId' => $issuerId
                    , 'holderFirstName' => $holderFirstName
                    , 'holderLastName' => $holderLastName
                    , 'program' => $program
                    , 'issueDate' => $issueDate
                ];
                //verify certifcate hash
                $obj = new CertificateHash([
                    'data' => $data
                    , 'certificateHash' => $certificateHash
                ]);
                $isValid = $obj->computeCertificateHash();
            }
            
            if ($isValid)
            {
                $status = true;
                
                $level = EducationLevelFunctions::getEducationLevelAbbrAndTitle($rs['levelId']);
                
                //show additional fields to logged-in verifier
                if (!empty($userId) && strlen($userId) == 36)
                {
                    //log verification
                    self::logCertificateVerification([
                        'holderFullName' => $holderFullName
                        , 'issuerId' => $issuerId
                        , 'issueDate' => $issueDate
                        , 'userId' => $userId
                        , 'status' => 1
                    ]);
                }
            }
        }
        
        if ($status == 0)
        {
            $issuerId = $verificationData['issuerId'];
            $issueDate = $verificationData['issueDate'];
            $holderFullName = $verificationData['holderFullName'];

            if ($verificationType == 'form')
            {
                if (!empty($userId) && strlen($userId) == 36)
                {
                    //log verification
                    self::logCertificateVerification([
                        'holderFullName' => $holderFullName
                        , 'issuerId' => $issuerId
                        , 'issueDate' => $issueDate
                        , 'userId' => $userId
                        , 'status' => 0
                    ]);
                }
            }
        }

        return self::getVerificationResult([
            'status' => $status
            , 'verificationType' => $verificationType
            , 'issuerId' => $issuerId
            , 'issueDate' => $issueDate
            , 'program' => $program
            , 'level' => $level
            , 'certificateHash' => $certificateHash
            , 'certificateId' => $certificateId
            , 'holderFullName' => $holderFullName
            , 'userId' => $userId
        ]);
    }

    /**
     * Get verification result
     * @param mixed $arParams
     * @return array{msg: string, status: boolean}
     */
    private static function getVerificationResult($arParams)
    {
        $status = $arParams['status'];
        $verificationType = $arParams['verificationType'];
        $issuerId = $arParams['issuerId'];
        $level = isset($arParams['level']) ? $arParams['level'] : '';
        $certificateHash = isset($arParams['certificateHash']) ? $arParams['certificateHash'] : '';
        $certificateId = isset($arParams['certificateId']) ? $arParams['certificateId'] : '';
        $issueDate = $arParams['issueDate'];
        $program = $arParams['program'];
        $holderFullName = $arParams['holderFullName'];
        $userId = isset($arParams['userId']) ? $arParams['userId'] : '';

        $alertClass = $alertMsg = $qrCode = '';
        if ($status)
        {
            $alertClass = 'success';
            $issuerName = User::getUserOrganization($issuerId);

            if ($verificationType != 'form')
            {
                //generate QR based on the hash
                $objQr = new CertificateQr($certificateHash);
                $qrCodeBase64 = $objQr->generateCertificateQr();

                $qrCode = <<<EOQ
<div style="text-align: center; margin: 20px auto;"><a href="{$qrCodeBase64}"><img src="{$qrCodeBase64}" alt="Cetificate QR Code"></a></div>
EOQ;
            }

            $alertMsg .= <<<EOQ
<div><i class="fas fa-check-circle pr-2"></i> The certificate is valid</div>
EOQ;

            if ($certificateId != '')
            {
                $alertMsg .= <<<EOQ
<div><b>Certificate ID:</b> {$certificateId}</div>
EOQ;
            }

            $alertMsg .= <<<EOQ
<div><b>Issuer:</b> {$issuerName}</div>
<div><b>Holder's Name:</b> {$holderFullName}</div>
<div><b>Issue Date:</b> {$issueDate}</div>
EOQ;

            //show additional fields to logged-in verifier
            if (!empty($userId) && strlen($userId) == 36)
            {
                $alertMsg .= <<<EOQ
<div><b>Program:</b> {$program}</div>
<div><b>Level:</b> {$level}</div>
EOQ;
            }
        }
        else
        {
            $alertClass = 'danger';
            $alertMsg = <<<EOQ
<div><i class="fas fa-times-circle pr-2"></i> The certificate is invalid!</div>
EOQ;
        }

        if ($verificationType != 'form')
        {
            //QR code verification - accessed directly via the url: return the message
            $alertMsg = <<<EOQ
{$qrCode}
<div class="alert alert-{$alertClass} alert-dismissible fade show" style="line-height:1.8rem;" role="alert">
    {$alertMsg}
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
EOQ;
        }

        return [
            'status' => $status
            , 'msg' => $alertMsg
        ];
    }

    /**
     * Log certificate verification
     * @param array $params
     * @return void
     */
    private static function logCertificateVerification($params)
    {
        $data = $params;
        $data['id'] = getNewId();
        $data['cdate'] = getCurrentDate();

        Crud::insert(
            self::$tableVerification
            , $data
        );
    }
}