<?php
namespace ValidCert\Admin\Verifier;

use ValidCert\Crud\Crud;

class VerifiersList
{
    private static $table = DEF_TBL_USERS;
    public static $dataJson = [];
    
    /**
     * Get verifiers records
     * @param array $arFields
     * @param string $actionType
     */
    public static function getVerifiers($arFields=['*'], $actionType='')
    {
        $fields = is_array($arFields) ? implode(',', $arFields) : $arFields;

        $arFilter = [
            'columns' => $fields,
            'where' => ['userType' => 'verifier'],
            'return_type' => 'all',
            'order' => 'cdate DESC'
        ];

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
     * Get a list of verifiers
     * @param array $requestData
     * @return void
     */
    public static function getVerifiersList($requestData)
    {
        $actionType = isset($requestData['actionType']) ? $requestData['actionType'] : '';

        $rs = self::getVerifiers([
            'id'
            , 'firstName'
            , 'lastName'
            , 'username'
            , 'email'
            , 'role'
            , 'organization'
            , 'status'
            , 'approved'
            , 'rejected'
            , 'rejection_remarks'
            , 'disable_remarks'
            , 'cdate'
            , 'mdate'
        ], $actionType
        );
        if (count($rs) > 0)
        {
            $rows = [];
            $sn = 1;
            foreach ($rs as $r)
            {
                $id = $r['id'];
                $status = $r['status'];
                $approved = $r['approved'];
                $rejected = $r['rejected'];

                $row = [
                    'sn' => $sn
                    , 'firstName' => $r['firstName']
                    , 'lastName' => $r['lastName']
                    , 'username' => $r['username']
                    , 'email' => $r['email']
                    , 'role' => $r['role']
                    , 'organization' => $r['organization']
                    , 'status' => self::getIssuerStatusBadge($approved, $status, $rejected, $r['rejection_remarks'], $r['disable_remarks'])
                    , 'cdate' => getFormattedDate($r['cdate'])
                    , 'mdate' => getFormattedDate($r['mdate'])
                ];

                $statusBtn = '';
                if ($actionType != 'recent')
                {
                    if ($approved == 1)
                    {
                        if ($status == 1)
                        {
                            $statusBtn = <<<EOQ
<button type="button" class="btn btn-danger btn-sm" data-toggle="tooltip" title="Disable" onclick="disableVerifier('{$id}')">
    <i class="fas fa-times"></i>
</button>
EOQ;
                        }
                        else
                        {
                            $statusBtn = <<<EOQ
<button type="button" class="btn btn-success btn-sm" data-toggle="tooltip" title="Enable" onclick="enableVerifier('{$id}')">
    <i class="fas fa-check"></i>
</button>
EOQ;
                        }
                    }
                }

                $row['statusBtn'] = $statusBtn;

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
     * Get status badge for the issuer
     * @param int $approved
     * @param int $status
     * @param int $rejected
     * @param string $rejectionRemarks
     * @param string $disableRemarks
     * @return string
     */
    private static function getIssuerStatusBadge($approved, $status, $rejected, $rejectionRemarks, $disableRemarks)
    {
        $badgeClass = $badgeLabel = $remarks = '';
        if ($approved == 1)
        {
            if ($status == 1)
            {
                $badgeClass = 'success';
                $badgeLabel = 'ACTIVE';
            }
            else
            {
                $badgeClass = 'secondary';
                $badgeLabel = 'INACTIVE';
                $remarks = <<<EOQ
<i class="fas fa-comment-dots" data-toggle="tooltip" data-placement="bottom" title="{$disableRemarks}"></i>
EOQ;
            }
        }
        elseif ($rejected == 1)
        {
            $badgeClass = 'danger';
            $badgeLabel = 'REJECTED';
            $remarks = <<<EOQ
<i class="fas fa-comment-dots" data-toggle="tooltip" data-placement="bottom" title="{$rejectionRemarks}"></i>
EOQ;
        }
        else
        {
            $badgeClass = 'warning';
            $badgeLabel = 'AWAITING APPROVAL';
        }

        return <<<EOQ
<span class="badge badge-{$badgeClass}">{$badgeLabel}</span> {$remarks}
EOQ;
    }
}