<?php
namespace ValidCert\Admin\Issuer;

use ValidCert\Crud\Crud;

class IssuersList
{
    private static $table = DEF_TBL_USERS;
    public static $dataJson = [];
    
    /**
     * Get issuers records
     * @param mixed $arFields
     * @param mixed $actionType
     */
    public static function getIssuers($arFields=['*'], $actionType='')
    {
        $fields = is_array($arFields) ? implode(',', $arFields) : $arFields;

        $arFilter = [
            'columns' => $fields,
            'where' => ['userType' => 'issuer'],
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
     * Get a list of issuers
     * @param array $requestData
     * @return void
     */
    public static function getIssuersList($requestData)
    {
        $actionType = isset($requestData['actionType']) ? $requestData['actionType'] : '';

        $rs = self::getIssuers([
            'id'
            , 'firstName'
            , 'lastName'
            , 'username'
            , 'email'
            , 'role'
            , 'organization'
            , 'organizationType'
            , 'approved'
            , 'rejected'
            , 'rejection_remarks'
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
                    , 'organizationType' => ucwords($r['organizationType'])
                    , 'status' => self::getIssuerStatusBadge($approved, $rejected, $r['rejection_remarks'])
                    , 'cdate' => getFormattedDate($r['cdate'])
                    , 'mdate' => getFormattedDate($r['mdate'])
                ];

                $approveBtn = $rejectBtn = '';
                if ($actionType != 'recent')
                {
                    if ($approved == 0 && $rejected == 0)
                    {
                        $approveBtn = <<<EOQ
<button type="button" class="btn btn-success btn-sm" data-toggle="tooltip" title="Approve" onclick="approveIssuer('{$id}')">
    <i class="fas fa-check"></i>
</button>
EOQ;

                        $rejectBtn = <<<EOQ
<button type="button" class="btn btn-danger btn-sm" data-toggle="tooltip" title="Reject" onclick="rejectIssuer('{$id}')">
    <i class="fas fa-times"></i>
</button>
EOQ;
                    }
                    elseif ($rejected == 1)
                    {
                        $approveBtn = <<<EOQ
<button type="button" class="btn btn-success btn-sm" data-toggle="tooltip" title="Approve" onclick="approveIssuer('{$id}')">
    <i class="fas fa-check"></i>
</button>
EOQ;
                    }
                }

                $row['approve'] = $approveBtn;
                $row['reject'] = $rejectBtn;

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
     * Get status badge for the issuer record
     * @param int $approved
     * @param int $rejected
     * @param string $rejectionRemarks
     * @return string
     */
    private static function getIssuerStatusBadge($approved, $rejected, $rejectionRemarks)
    {
        $badgeClass = $badgeLabel = '';
        if ($approved == 1)
        {
            $badgeClass = 'success';
            $badgeLabel = 'ACTIVE';
        }
        elseif ($rejected == 1)
        {
            $badgeClass = 'danger';
            $badgeLabel = 'REJECTED';
            $rejectionRemarks = <<<EOQ
<i class="fas fa-comment-dots" data-toggle="tooltip" data-placement="bottom" title="{$rejectionRemarks}"></i>
EOQ;
        }
        else
        {
            $badgeClass = 'warning';
            $badgeLabel = 'AWAITING APPROVAL';
        }

        return <<<EOQ
<span class="badge badge-{$badgeClass}">{$badgeLabel}</span> {$rejectionRemarks}
EOQ;
    }
}