<?php
namespace ValidCert\Certificate;

use ValidCert\Crud\Crud;

class CertificateFunctions
{
    private static $table = DEF_TBL_CERTIFICATES;
    protected static $historyCategory = 'certificates';
    public static $dataJson = [];

    /**
     * Get a certificate record data
     * @param mixed $id
     * @param mixed $arFields
     */
    public static function getCertificate($id, $arFields=['*'])
    {
        $fields = is_array($arFields) ? implode(',', $arFields) : $arFields;

        return Crud::select(
            self::$table,
            [
                'columns' => $fields,
                'where' => [
                    'id' => $id
                ]
            ]
        );
    }
}