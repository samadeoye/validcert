<?php
namespace ValidCert\Certificate;

use ValidCert\Crud\Crud;
use ValidCert\User\User;

class CertificateVerificationList
{
    private static $table = DEF_TBL_CERTIFICATES_VERIFICATIONS;
    public static $dataJson = [];
    
    /**
     * Get certificate verification entries
     * @param array $arFields
     * @param string $userId
     * @param string $userType
     * @param string $actionType
     */
    public static function getCertificatesVerifications($arFields=['*'], $userId='', $userType='', $actionType='')
    {
        $fields = is_array($arFields) ? implode(',', $arFields) : $arFields;

        $arFilter = [
            'columns' => $fields,
            'return_type' => 'all',
            'order' => 'cdate DESC'
        ];

        //show all verifications to admin
        if ($userType != 'admin')
        {
            $arFilter['where'] = [
                'userId' => $userId
            ];
            switch ($userType)
            {
                case 'issuer':
                    //retrieve only certificates verified which were issued by them
                    $arFilter['where'] = [
                        'issuerId' => $userId
                    ];
                break;

                case 'verifier':
                    //retrieve only certificates verified by them
                    $arFilter['where'] = [
                        'userId' => $userId
                    ];
                break;
            }
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
     * Get a list of certificate verifications
     * @param array $requestData
     * @return void
     */
    public static function getCertificatesVerificationsList($requestData)
    {
        $actionType = isset($requestData['actionType']) ? $requestData['actionType'] : '';
        $userId = isset($requestData['userId']) ? $requestData['userId'] : '';
        $userType = isset($requestData['userType']) ? $requestData['userType'] : '';

        $rs = self::getCertificatesVerifications(
            [
                'id'
                , 'issuerId'
                , 'holderFullName'
                , 'issueDate'
                , 'status'
                , 'userId'
                , 'cdate'
            ]
            , $userId
            , $userType
            , $actionType
        );
        if (count($rs) > 0)
        {
            $rows = [];
            $sn = 1;
            foreach ($rs as $r)
            {
                $row = [
                    'sn' => $sn
                    , 'issuer' => User::getUserOrganization($r['issuerId'])
                    , 'holderName' => $r['holderFullName']
                    , 'issueDate' => $r['issueDate']
                    , 'status' => self::getVerificationStatusBadge($r['status'])
                    , 'verifier' => User::getUserName($r['userId'])
                    , 'cdate' => getFormattedDate($r['cdate'])
                ];

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

    /**
     * Get certificate verification status badge
     * @param boolean $status
     * @return string
     */
    private static function getVerificationStatusBadge($status)
    {
        $badgeClass = $badgeLabel = '';
        switch ($status)
        {
            case 1:
                $badgeClass = 'success';
                $badgeLabel = 'SUCCESS';
            break;

            case 0:
                $badgeClass = 'danger';
                $badgeLabel = 'FAILURE';
            break;

            default:
                $badgeClass = 'danger';
                $badgeLabel = 'FAILURE';
        }

        return <<<EOQ
<a class="badge badge-{$badgeClass}">{$badgeLabel}</a>
EOQ;
    }
}