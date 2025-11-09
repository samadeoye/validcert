<?php
namespace ValidCert\Admin\EducationLevel;

use ValidCert\Crud\Crud;

class EducationLevelsList
{
    private static $table = DEF_TBL_EDUCATION_LEVELS;
    public static $dataJson = [];
    
    /**
     * Get education levels
     * @param array $arFields
     */
    public static function getEducationLevels($arFields=['*'])
    {
        $fields = is_array($arFields) ? implode(',', $arFields) : $arFields;

        $arFilter = [
            'columns' => $fields,
            'return_type' => 'all',
            'order' => 'cdate DESC'
        ];
        
        return Crud::select(
            self::$table,
            $arFilter
        );
    }

    /**
     * Get a list of education levels
     * @return void
     */
    public static function getEducationLevelsList()
    {
        $rs = self::getEducationLevels([
            'id'
            , 'abbr'
            , 'title'
            , 'cdate'
            , 'mdate'
        ]);
        if (count($rs) > 0)
        {
            $rows = [];
            $sn = 1;
            foreach ($rs as $r)
            {
                $id = $r['id'];

                $row = [
                    'sn' => $sn
                    , 'abbr' => $r['abbr']
                    , 'title' => $r['title']
                    , 'cdate' => getFormattedDate($r['cdate'])
                    , 'mdate' => getFormattedDate($r['mdate'])
                ];

                $row['edit'] = <<<EOQ
<button type="button" class="btn btn-primary btn-rounded btn-icon" onclick="editEducationLevel('{$id}')">
    Edit
</button>
EOQ;

                $row['delete'] = <<<EOQ
<button type="button" class="btn btn-danger btn-rounded btn-icon" onclick="deleteEducationLevel('{$id}')">
    Delete
</button>
EOQ;

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