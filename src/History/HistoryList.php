<?php
namespace ValidCert\History;

use ValidCert\Crud\Crud;
use ValidCert\User\User;

class HistoryList
{
    protected static $table = DEF_TBL_HISTORY;
    public static $dataJson = [];

    /**
     * Get audit logs list
     * @param array $requestData
     * @return void
     */
    public static function getHistoryList($requestData)
    {
        $category = isset($requestData['category']) ? trim($requestData['category']) : '';
        $date = isset($requestData['date']) ? trim($requestData['date']) : '';
        $recordId = isset($requestData['recordId']) ? trim($requestData['recordId']) : '';

        $where = [];
        if (!empty($category))
        {
            $where = [
                'category' => $category
            ];
        }
        if (!empty($date))
        {
            $date = date('Y-m-d', strtotime($date));
            $where = [
                'cdate' => $date
            ];
        }
        if (!empty($recordId) && strlen($recordId) == 36)
        {
            $where['recordId'] = $recordId;
        }

        $arFilter = [
            'columns' => 'category, action, field, oldValue, newValue, cdate, userId',
            'return_type' => 'all',
            'order' => 'cdate DESC'
        ];
        if (count($where) > 0)
        {
            $arFilter['where'] = $where;
        }

        $rs = Crud::select(
            self::$table
            , $arFilter
        );
        if (count($rs) > 0)
        {
            $rows = [];
            $sn = 1;
            foreach ($rs as $r)
            {
                $action = $r['action'];
                $category = $r['category'];

                $label = '';
                $ar = $r;
                if ($action != 'delete')
                {
                    $ar = HistoryMetadata::getHistoryMetadataFieldValue(
                        $category, $r['field'], $r['oldValue'], $r['newValue']
                    );
                    $label = $ar['label'];
                }

                $row = [
                    'sn' => $sn
                    , 'action' => self::getHistoryActionBtn($action)
                    , 'field' => $label
                    , 'oldValue' => $ar['oldValue']
                    , 'newValue' => $ar['newValue']
                    , 'date' => $r['cdate']
                    , 'user' => User::getUserName($r['userId'])
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
     * Get audit log action button by action
     * @param string $action
     * @return string
     */
    private static function getHistoryActionBtn($action)
    {
        $btnClass = '';
        $action = strtolower($action);
        switch ($action)
        {
            case 'new':
                $btnClass = 'primary';
            break;

            case 'update':
                $btnClass = 'success';
            break;

            case 'delete':
                $btnClass = 'danger';
            break;

            default:
                $btnClass = 'primary';
        }

        $action = stringToUpper($action);

        return <<<EOQ
<a class="badge badge-{$btnClass}">{$action}</a>
EOQ;
    }
}