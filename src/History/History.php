<?php
namespace ValidCert\History;

use ValidCert\Crud\Crud;

class History
{
    protected static $table = DEF_TBL_HISTORY;
    public static $updateCount = 0;

    /**
     * Update history logs
     * @param string $table
     * @param string $record
     * @param array $data
     * @param string $category
     * @param string $action
     * @return void
     */
    public static function updateHistoryLogs($table, $record, $data, $category, $action = 'update')
    {
        //prepare the data history based on the action
        $dataHistory = self::getDataHistory(
            $table, $record, $data, $action, $category
        );

        if (!empty($dataHistory) || in_array($action, ['delete']))
        {
            self::insertLogsData([
                'dataHistory' => $dataHistory,
                'category' => $category,
                'action' => $action,
                'record' => $record
            ]);
        }
    }

    /**
     * Get data history
     * @param string $table
     * @param string $record
     * @param array $data
     * @param string $action
     * @param string $category
     * @return array<array{field: mixed, newValue: mixed, oldValue: mixed|array{field: mixed, newValue: mixed}|array{field: null}>}
     */
    private static function getDataHistory($table, $record, $data, $action, $category)
    {
        $dataHistory = [];
        switch ($action)
        {
            case 'update':
                $rs = Crud::getRecordInfo($table, $record);
                if ($rs)
                {
                    foreach ($data as $key => $value)
                    {
                        if (array_key_exists($key, $rs) && $rs[$key] != $value)
                        {
                            $oldValue = $rs[$key];
                            $newValue = $value;
                            if ($category == 'users' && $key == 'password')
                            {
                                //do not log actual password data
                                $oldValue = $newValue = '****';
                            }
                            $dataHistory[] = [
                                'field' => $key,
                                'oldValue' => $oldValue,
                                'newValue' => $newValue
                            ];
                        }
                    }
                }
                break;

            case 'new':
            case 'delete':
                if (count($data) > 0)
                {
                    foreach ($data as $key => $value)
                    {
                        $dataHistory[] = [
                            'field' => $key,
                            'newValue' => $value
                        ];
                    }
                }
                else
                {
                    //simply log the delete action
                    $dataHistory[] = [
                        'field' => null
                    ];
                }
                break;
        }

        return $dataHistory;
    }

    /**
     * @param array $params
     * @return void
     */
    private static function insertLogsData($params)
    {
        $dataHistory = $params['dataHistory'];
        $category = $params['category'];
        $action = $params['action'];
        $record = $params['record'];

        $cdate = getCurrentDate();
        $userId = getUserSessionValueByKey('id');

        foreach ($dataHistory as $row)
        {
            $row['category'] = $category;
            $row['action'] = $action;
            $row['recordId'] = $record;
            $row['userId'] = $userId;
            $row['cdate'] = $cdate;
            Crud::insert(
                self::$table
                , $row
            );
            self::$updateCount++;
        }
    }
}