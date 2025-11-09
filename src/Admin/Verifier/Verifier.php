<?php
namespace ValidCert\Admin\Verifier;

use ValidCert\User\User;

class Verifier
{
    /**
     * Disable a verifier's account
     * @param array $requestData
     * @return void
     */
    public static function disableVerifier($requestData)
    {
        $userId = $requestData['id'];
        $remarks = $requestData['remarks'];

        User::disableUserAccount(
            $userId, $remarks
        );
    }

    /**
     * Enable a verifier's account
     * @param array $requestData
     * @return void
     */
    public static function enableVerifier($requestData)
    {
        $userId = $requestData['id'];

        User::enableUserAccount(
            $userId
        );
    }
}