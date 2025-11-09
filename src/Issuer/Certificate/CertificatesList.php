<?php
namespace ValidCert\Issuer\Certificate;

use ValidCert\Crud\Crud;
use ValidCert\EducationLevel\EducationLevelFunctions;
use ValidCert\Issuer\IssuerFunctions;
use ValidCert\Certificate\CertificateQr;

class CertificatesList
{
    private static $table = DEF_TBL_CERTIFICATES;
    public static $dataJson = [];
    
    /**
     * Get certificates records
     * @param array $arFields
     * @param string $userId
     * @param string $userType
     * @param string $actionType
     */
    public static function getCertificates($arFields=['*'], $userId='', $userType='', $actionType='')
    {
        $fields = is_array($arFields) ? implode(',', $arFields) : $arFields;

        $arFilter = [
            'columns' => $fields,
            'return_type' => 'all',
            'order' => 'cdate DESC'
        ];

        if ($userType == 'issuer')
        {
            //retrieve only certificates which were issued by them
            $arFilter['where'] = [
                'issuerId' => $userId
            ];
        }

        if ($actionType == 'recent')
        {
            //get only last 10
            $arFilter['limit'] = 10;
        }
        
        return Crud::select(
            self::$table,
            $arFilter
        );
    }

    /**
     * Get a list of existing certificates
     * @param array $requestData
     * @return void
     */
    public static function getCertificatesList($requestData)
    {
        $actionType = isset($requestData['actionType']) ? $requestData['actionType'] : '';
        $userId = isset($requestData['userId']) ? $requestData['userId'] : '';
        $userType = isset($requestData['userType']) ? $requestData['userType'] : '';

        $rs = self::getCertificates([
            'id'
            , 'issuerId'
            , 'certificateId'
            , 'holderFirstName'
            , 'holderLastName'
            , 'program'
            , 'issueDate'
            , 'levelId'
            , 'certificateHash'
            , 'cdate'
            , 'mdate'
        ], $userId, $userType, $actionType
        );
        if (count($rs) > 0)
        {
            $rows = [];
            $sn = 1;
            foreach ($rs as $r)
            {
                $id = $r['id'];

                $row = [
                    'sn' => $sn
                    , 'issuer' => IssuerFunctions::getIssuerOrganization($r['issuerId'])
                    , 'certificateId' => $r['certificateId']
                    , 'holderFirstName' => $r['holderFirstName']
                    , 'holderLastName' => $r['holderLastName']
                    , 'program' => $r['program']
                    , 'issueDate' => $r['issueDate']
                    , 'level' => EducationLevelFunctions::getEducationLevelAbbr($r['levelId'])
                    , 'cdate' => getFormattedDate($r['cdate'])
                    , 'mdate' => getFormattedDate($r['mdate'])
                ];

                //admin can only view - they cannot modify or see QR
                if ($userType == 'issuer')
                {
                    //generate QR
                    $objQr = new CertificateQr($r['certificateHash']);
                    $qrCodeBase64 = $objQr->generateCertificateQr();

                    $qrCodeFileName = 'certificateQrCode_'.date('YmdHis').'.png';
                    $row['qrcode'] = <<<EOQ
    <a href="{$qrCodeBase64}" download="{$qrCodeFileName}"><img class="datatable-qrcode-preview" src="{$qrCodeBase64}" alt="Cetificate QR Code"></a>
    EOQ;

                    if ($actionType != 'recent')
                    {
                        $row['edit'] = <<<EOQ
    <button type="button" class="btn btn-primary btn-rounded btn-icon" onclick="editCertificate('{$id}')">
        Edit
    </button>
    EOQ;

                        $row['delete'] = <<<EOQ
    <button type="button" class="btn btn-danger btn-rounded btn-icon" onclick="deleteCertificate('{$id}')">
        Delete
    </button>
    EOQ;
                    }
                }

                $rows[] = $row;
                $sn++;
            }
            $data = [
                'status' => true,
                'msg' => 'Records fetched successfully!',
                'data' => $rows
            ];
        }
        else
        {
            $data = [
                'status' => false,
                'msg' => 'No record found!',
                'data' => []
            ];
        }
        self::$dataJson = $data;
    }
}