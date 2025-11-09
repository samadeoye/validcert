<?php
namespace ValidCert\Issuer;

use ValidCert\Crud\Crud;

class IssuerFunctions
{
    protected static $table = DEF_TBL_USERS;
    
    /**
     * Get issuers records
     * @param array $arFields
     */
    public static function getIssuers($arFields=['*'])
    {
        $fields = is_array($arFields) ? implode(',', $arFields) : $arFields;
        
        return Crud::select(
            self::$table,
            [
                'columns' => $fields,
                'where' => [
                    'userType' => 'issuer'
                ],
                'return_type' => 'all',
                'order' => 'cdate DESC'
            ]
        );
    }

    /**
     * Get an issuer's record
     * @param string $id
     * @param array $arFields
     */
    public static function getIssuer($id, $arFields=['*'])
    {
        $fields = is_array($arFields) ? implode(',', $arFields) : $arFields;
        
        return Crud::select(
            self::$table,
            [
                'columns' => $fields,
                'where' => [
                    'id' => $id
                ],
                'return_type' => 'row',
                'order' => 'cdate DESC'
            ]
        );
    }

    /**
     * Get an issuer's name
     * @param string $id
     */
    public static function getIssuerName($id)
    {
        $rs = self::getIssuer(
            $id, ['username']
        );
        if ($rs)
        {
            return $rs['username'];
        }
        return '';
    }
    
    /**
     * Get an issuer's organization name
     * @param string $id
     */
    public static function getIssuerOrganization($id)
    {
        $rs = self::getIssuer(
            $id, ['organization']
        );
        if ($rs)
        {
            return $rs['organization'];
        }
        return '';
    }
}