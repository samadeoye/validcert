<?php
namespace ValidCert\Issuer;

use ValidCert\Crud\Crud;

class IssuerDashboard
{
    protected static $tableCertificates = DEF_TBL_CERTIFICATES;

    /**
     * Get issuer dashboard data
     * @param string $userId
     * @return array{nuCertificatesTotal: mixed, numCertificatesAdded: mixed, numCertificatesImported: mixed}
     */
    public static function getDashboardData($userId)
    {
        //certificates added manually
        $numCertificatesAdded = self::getDashboardCommonCount(
            self::$tableCertificates, [
                'action' => 'manual'
                , 'issuerId' => $userId
            ]
        );
        //certificates imported
        $numCertificatesImported = self::getDashboardCommonCount(
            self::$tableCertificates, [
                'action' => 'import'
                , 'issuerId' => $userId
            ]
        );

        $nuCertificatesTotal = $numCertificatesAdded + $numCertificatesImported;

        return [
            'numCertificatesAdded' => $numCertificatesAdded
            , 'numCertificatesImported' => $numCertificatesImported
            , 'nuCertificatesTotal' => $nuCertificatesTotal
        ];
    }

    /**
     * Get issuer dashboard data count
     * @param string $table
     * @param array $arWhere
     */
    protected static function getDashboardCommonCount($table, $arWhere=[])
    {
        $rs = Crud::select(
            $table,
            [
                'columns' => 'COUNT(id) AS num',
                'where' => $arWhere
            ]
        );
        if ($rs)
        {
            return $rs['num'];
        }
        return 0;
    }
}