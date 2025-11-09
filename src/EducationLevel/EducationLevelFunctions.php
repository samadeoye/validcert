<?php
namespace ValidCert\EducationLevel;

use ValidCert\Crud\Crud;

class EducationLevelFunctions
{
    private static $table = DEF_TBL_EDUCATION_LEVELS;
    public static $dataJson = [];
    
    /**
     * Get education levels records
     * @param array $arFields
     * @return array
     */
    public static function getEducationLevels($arFields=['*'])
    {
        $fields = is_array($arFields) ? implode(',', $arFields) : $arFields;
        
        return Crud::select(
            self::$table,
            [
                'columns' => $fields,
                'return_type' => 'all',
                'order' => 'cdate DESC'
            ]
        );
    }

    /**
     * Get education levels for a dropdown
     * @return array
     */
    public static function getEducationLevelsDropdownArray()
    {
        $rs = self::getEducationLevels([
            'id'
            , 'title'
            , 'abbr'
        ]);
        if (count($rs) > 0)
        {
            $ar = [];
            foreach ($rs as $r)
            {
                $ar[$r['id']] = $r['abbr'];
            }
            return $ar;
        }
        return [];
    }

    /**
     * Summary of getEducationLevel
     * @param string $id
     * @param array $arFields
     */
    public static function getEducationLevel($id, $arFields=['*'])
    {
        $fields = is_array($arFields) ? implode(',', $arFields) : $arFields;
        
        return Crud::select(
            self::$table,
            [
                'columns' => $fields,
                'where' => ['id' => $id],
                'return_type' => 'row'
            ]
        );
    }

    /**
     * Get education level abbreviation
     * @param string $id
     * @return string
     */
    public static function getEducationLevelAbbr($id)
    {
        $rs = self::getEducationLevel(
            $id, ['abbr']
        );
        if ($rs)
        {
            return $rs['abbr'];
        }
        return '';
    }

    /**
     * Get education level's abbreviation and title
     * @param string $id
     * @return string
     */
    public static function getEducationLevelAbbrAndTitle($id)
    {
        $rs = self::getEducationLevel(
            $id, ['abbr', 'title']
        );
        if ($rs)
        {
            return "{$rs['title']} ({$rs['abbr']})";
        }
        return '';
    }

    /**
     * Get education level id by abbreviation
     * @param mixed $abbr
     * @return string
     */
    public static function getEducationLevelIdByAbbr($abbr)
    {
        $rs = Crud::select(
            self::$table,
            [
                'columns' => 'id',
                'where' => ['abbr' => $abbr],
                'return_type' => 'row'
            ]
        );
        if ($rs)
        {
            return $rs['id'];
        }
        return '';
    }
}