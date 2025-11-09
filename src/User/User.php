<?php
namespace ValidCert\User;

use Exception;
use ValidCert\Crud\Crud;
use ValidCert\History\History;
use ValidCert\SendMail\SendMail;

class User
{
    protected static $table = DEF_TBL_USERS;
    protected static $tablePasswordReset = DEF_TBL_PASSWORD_RESET;
    protected static $historyCategory = 'users';
    public static $data = [];

    /**
     * Get a user record
     * @param string $id
     * @param array $arFields
     */
    public static function getUser($id, $arFields=['*'])
    {
        $fields = is_array($arFields) ? implode(', ', $arFields) : $arFields;
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

    /**
     * Get a user's username
     * @param string $id
     */
    public static function getUserName($id)
    {
        $rs = self::getUser(
            $id, ['username']
        );
        if ($rs)
        {
            return $rs['username'];
        }
        return '';
    }

    /**
     * Get a user's organization name
     * @param string $id
     */
    public static function getUserOrganization($id)
    {
        $rs = self::getUser(
            $id, ['organization']
        );
        if ($rs)
        {
            return $rs['organization'];
        }
        return '';
    }

    /**
     * Check if a user record exists with a specific field and its value
     * @param string $field
     * @param mixed $value
     * @return bool
     */
    public static function checkIfUserExists($field, $value)
    {
        $rs = Crud::select(
            self::$table,
            [
                'columns' => 'id',
                'where' => [
                    $field => $value
                ]
            ]
        );
        if ($rs)
        {
            return true;
        }
        return false;
    }

    /**
     * Change user's password
     * @param array $arParams
     * @param string $userId
     * @throws \Exception
     * @return void
     */
    public static function changePassword($arParams, $userId)
    {
        $currentPassword = trim($arParams['currentPassword']);
        $newPassword = trim($arParams['newPassword']);
        $confirmPassword = trim($arParams['confirmPassword']);

        $rs = self::getUser($userId, ['password', 'userType']);
        if ($rs)
        {
            $userType = $rs['userType'];

            if ($newPassword != $confirmPassword)
            {
                throw new Exception('Passwords do not match');
            }
            elseif ($rs['password'] != md5($currentPassword))
            {
                throw new Exception('Old password is incorrect');
            }
            elseif (strlen($newPassword) < 8)
            {
                throw new Exception('Password must contain at least 8 characters');
            }
            else
            {
                $newPassword = md5($newPassword);
                $data = [
                    'password' => $newPassword
                ];

                //update logs
                History::updateHistoryLogs(
                    self::$table
                    , $userId
                    , $data
                    , self::$historyCategory
                );
                
                $data['mdate'] = getCurrentDate();
                $update = Crud::update(
                    self::$table
                    , $data
                    , ['id' => $userId]
                );
                if ($update)
                {
                    $arUser = array_merge(
                        $_SESSION[$userType], ['password' => $newPassword]
                    );
                    $_SESSION[$userType] = $arUser;
                }
            }
        }
        else
        {
            throw new Exception('User not found');
        }
    }
    
    /**
     * Update a user's record
     * @param array $arParams
     * @param string $userId
     * @throws \Exception
     * @return void
     */
    public static function updateUser($arParams, $userId)
    {
        $firstName = stringToUpper(trim($arParams['firstName']));
        $lastName = stringToUpper(trim($arParams['lastName']));
        $role = trim($arParams['role']);

        $data = [
            'firstName' => $firstName,
            'lastName' => $lastName,
            'role' => $role
        ];

        //get current data
        $rs = self::getUser($userId, ['id', 'userType']);
        if ($rs)
        {
            //check if there are changes and update logs
            History::updateHistoryLogs(
                self::$table
                , $userId
                , $data
                , self::$historyCategory
            );
            if (History::$updateCount > 0)
            {
                $data['fullName'] = "{$firstName} {$lastName}";
                $data['mdate'] = getCurrentDate();

                $update = Crud::update(
                    self::$table,
                    $data,
                    ['id' => $userId]
                );
                if ($update)
                {
                    $userType = $rs['userType'];
                    $_SESSION[$userType] = array_merge(
                        $_SESSION[$userType], $data
                    );

                    $data = [
                        'status' => true,
                        'data' => $_SESSION[$userType]
                    ];
                    self::$data = $data;
                }
            }
            else
            {
                throw new Exception('No changes found!');
            }
        }
        else
        {
            throw new Exception('User not found!');
        }
    }

    /**
     * Verify if the entered email exists (for password reset)
     * @param array $arParams
     * @throws \Exception
     * @return void
     */
    public static function verifyEmailForPasswordReset($arParams)
    {
        $email = strtolower(trim($arParams['email']));

        $rs = Crud::select(
            self::$table,
            [
                'columns' => 'firstName, lastName, userType',
                'where' => [
                    'email' => $email
                ]
            ]
        );
        if ($rs)
        {
            //send password reset email
            $id = getNewId();
            $name = $rs['firstName'] .' '. $rs['lastName'];
            $userType = $rs['userType'];
            $siteName = SITE_NAME;

            $siteRootPath = '';
            switch ($userType)
            {
                case 'verifier':
                    $siteRootPath = DEF_FULL_ROOT_PATH_VERIFIER;
                break;
                case 'issuer':
                    $siteRootPath = DEF_FULL_ROOT_PATH_ISSUER;
                break;
                case 'admin':
                    $siteRootPath = DEF_FULL_ROOT_PATH_ADMIN;
                break;
            }

            $firstName = $rs['firstName'];
            $passResetLink = <<<EOQ
            <a href="{$siteRootPath}/app/resetpassword?token={$id}">Reset Password</a>
EOQ;

            $body = "Dear $firstName," . "\r\n";
            $body .= "Use the link below to complete your password reset on $siteName." . "\r\n";
            $body .= "$passResetLink" . "\r\n";

            $arParams = [
                'mailTo' => $email,
                'toName' => $name,
                'mailFrom' => SITE_EMAIL,
                'fromName' => SITE_NAME,
                'subject' => 'Password Reset on '.SITE_NAME,
                'body' => $body
            ];
            SendMail::sendDefaultMail($arParams);
            if (SendMail::$isSent)
            {
                $data = [
                    'id' => $id,
                    'email' => $email,
                    'cdate' => time()
                ];
                Crud::insert(
                    self::$tablePasswordReset
                    , $data
                );
            }
            else
            {
                throw new Exception('An error occured. Please try again.');
            }
        }
        else
        {
            throw new Exception('This email does not exist on the system');
        }
    }

    /**
     * Reset user's password with the token sent via email
     * @param array $arParams
     * @throws \Exception
     * @return void
     */
    public static function resetPassword($arParams)
    {
        $token = trim($arParams['token']);
        $password = trim($arParams['password']);
        $passwordConfirm = trim($arParams['passwordConfirm']);

        if (strlen($password) < 8)
        {
            throw new Exception('Password must contain at least 8 characters');
        }
        elseif ($password != $passwordConfirm)
        {
            throw new Exception('Passwords do not match!');
        }

        $rs = Crud::select(
            self::$tablePasswordReset,
            [
                'columns' => 'email',
                'where' => [
                    'id' => $token
                ],
                'order' => 'cdate DESC',
                'limit' => 1
            ]
        );
        if ($rs)
        {
            $email = $rs['email'];

            $userId = Crud::getRecordFieldWithCondition(
                self::$table
                , 'id'
                , ['email' => $email]
            );

            $data = ['password' => md5($password)];

            //update logs
            History::updateHistoryLogs(
                self::$table
                , $userId
                , $data
                , self::$historyCategory
            );
            
            $data['mdate'] = getCurrentDate();
            Crud::update(
                self::$table
                , $data
                , ['id' => $userId]
            );

            //delete password reset log
            Crud::delete(
                self::$tablePasswordReset
                , ['email' => $email]
            );
        }
        else
        {
            throw new Exception('Token is invalid. Please click the link from your email.');
        }
    }

    /**
     * Reject a user's account
     * @param string $id
     * @param string $remarks
     * @return void
     */
    public static function rejectUserAccount($id, $remarks='')
    {
        $data = [
            'rejected' => 1
            , 'rejection_remarks' => $remarks
        ];
        Crud::update(
            self::$table
            , $data
            , ['id' => $id]
        );
    }

    /**
     * Approve a user's account
     * @param string $id
     * @return void
     */
    public static function approveUserAccount($id)
    {
        $data = [
            'approved' => 1
            , 'rejected' => 0
            , 'rejection_remarks' => null
        ];
        Crud::update(
            self::$table
            , $data
            , ['id' => $id]
        );
    }

    /**
     * Disable a user's account
     * @param string $id
     * @param string $remarks
     * @return void
     */
    public static function disableUserAccount($id, $remarks='')
    {
        $data = [
            'status' => 0
            , 'disable_remarks' => $remarks
        ];
        Crud::update(
            self::$table
            , $data
            , ['id' => $id]
        );
    }

    /**
     * Enable a user's account (earlier disabled)
     * @param string $id
     * @return void
     */
    public static function enableUserAccount($id)
    {
        $data = [
            'status' => 1
            , 'disable_remarks' => null
        ];
        Crud::update(
            self::$table
            , $data
            , ['id' => $id]
        );
    }
}