<?php
namespace ValidCert\Admin\Issuer;

use ValidCert\User\User;

class Issuer
{
    /**
     * Reject a user's account (aplicable to only issuers)
     * @param array $requestData
     * @return void
     */
    public static function rejectIssuer($requestData)
    {
        $userId = $requestData['id'];
        $remarks = $requestData['remarks'];

        User::rejectUserAccount(
            $userId, $remarks
        );
    }

    /**
     * Approve a user's account (aplicable to only issuers)
     * @param array $requestData
     * @return void
     */
    public static function approveIssuer($requestData)
    {
        $userId = $requestData['id'];

        User::approveUserAccount(
            $userId
        );
    }
}