<?php
namespace ValidCert\History;

use ValidCert\Crud\Crud;

class HistoryMetadata
{
    protected static $table = DEF_TBL_HISTORY;
    public static $updateCount = 0;

    /**
     * Get audit logs metadata
     * @param string $category
     * @return array|array{certificates: array, users: array}
     */
    public static function getHistoryMetadata($category='')
    {
        $arData = [
            'certificates' => [
                'certificateId' => [
                    'label' => 'Certificate ID'
                ]
                , 'issuerId' => [
                    'label' => 'Issuer'
                    , 'function' => 'ValidCert\User\User::getUserOrganization'
                    //, 'functionParams' => ['dbfield' => 'issuerId']
                ]
                , 'holderFirstName' => [
                    'label' => 'Holder\'s First Name'
                ]
                , 'holderLastName' => [
                    'label' => 'Holder\'s Last Name'
                ]
                , 'program' => [
                    'label' => 'Program'
                ]
                , 'issueDate' => [
                    'label' => 'Issue Date'
                ]
                , 'levelId' => [
                    'label' => 'Level'
                    , 'function' => 'ValidCert\EducationLevel\EducationLevelFunctions::getEducationLevelAbbr'
                ]
            ]
            , 'users' => [
                'firstName' => [
                    'label' => 'First Name'
                ]
                , 'lastName' => [
                    'label' => 'Last Name'
                ]
                , 'role' => [
                    'label' => 'Role'
                ]
                , 'password' => [
                    'label' => 'Password'
                ]
            ]
        ];

        if (!empty($category))
        {
            return isset($arData[$category]) ? $arData[$category] : [];
        }
        return $arData;
    }
    
    /**
     * Get audit logs metadata categories
     * @return array
     */
    public static function getHistoryMetadataCategories()
    {
        $ar = self::getHistoryMetadata();
        return array_keys($ar);
    }

    /**
     * Get audit logs metadata field value
     * @param string $category
     * @param string $fieldKey
     * @param mixed $oldValue
     * @param mixed $newValue
     * @return array|array{label: mixed, newValue: mixed, oldValue: mixed}
     */
    public static function getHistoryMetadataFieldValue($category, $fieldKey, $oldValue, $newValue)
    {
        $rs = self::getHistoryMetadata(
            $category
        );
        $fieldMeta = isset($rs[$fieldKey]) ? $rs[$fieldKey] : [];
        if (!empty($fieldMeta))
        {
            if (isset($fieldMeta['function']))
            {
                $functionName = $fieldMeta['function'];

                $params = [$oldValue];
                $oldValue = call_user_func_array($functionName, $params);

                //new value
                $params = [$newValue];
                $newValue = call_user_func_array($functionName, $params);
            }

            return [
                'label' => $fieldMeta['label']
                , 'oldValue' => $oldValue
                , 'newValue' => $newValue
            ];
        }
        return [];
    }
}