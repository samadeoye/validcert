<?php
namespace ValidCert\Admin\Dashboard;

use ValidCert\Crud\Crud;

class Dashboard
{
    protected static $tableCertificates = DEF_TBL_CERTIFICATES;
    protected static $tableVerifications = DEF_TBL_CERTIFICATES_VERIFICATIONS;

    /**
     * Get admin dashboard data
     * @return array{nuCertificatesTotal: int, numCertificatesAdded: int, numCertificatesImported: int, numVerifications: int}
     */
    public static function getDashboardData()
    {
        //certificates added manually
        $numCertificatesAdded = self::getDashboardCommonCount(
            self::$tableCertificates, ['action' => 'manual']
        );
        //certificates imported
        $numCertificatesImported = self::getDashboardCommonCount(
            self::$tableCertificates, ['action' => 'import']
        );
        //verifications
        $numVerifications = self::getDashboardCommonCount(
            self::$tableVerifications
        );

        $nuCertificatesTotal = $numCertificatesAdded + $numCertificatesImported;

        return [
            'numCertificatesAdded' => $numCertificatesAdded
            , 'numCertificatesImported' => $numCertificatesImported
            , 'nuCertificatesTotal' => $nuCertificatesTotal
            , 'numVerifications' => $numVerifications
        ];
    }

    /**
     * Get dashboard data count
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