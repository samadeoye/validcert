<?php
namespace ValidCert\Auth;

use Exception;
use ValidCert\Crud\Crud;

class Login
{
    protected static $table = DEF_TBL_USERS;
    protected static $tableLogs = DEF_TBL_USERS_LOGS;
    public static $dataJson = [];

    /**
     * User login process
     * @param array $arParams
     * @throws \Exception
     * @return void
     */
    public static function loginUser($arParams)
    {
        $email = trim($arParams['email']);
        $password = trim($arParams['password']);
        $userType = trim($arParams['userType']);

        //check if a user exists with the email
        $rs = Crud::select(
            self::$table,
            [
                'columns' => 'id, firstName, lastName, role, userType, organization, organizationType, email, password, status, approved, rejected, rejection_remarks, disable_remarks',
                'where' => [
                    'email' => $email,
                    'userType' => $userType
                ]
            ]
        );
        if ($rs)
        {
            if ($rs['rejected'] == 1)
            {
                $errorMsg = <<<EOQ
<div>Your account has been rejected.</div>
<div><b>Reason:</b> {$rs['rejection_remarks']}</div>
EOQ;

                throw new Exception($errorMsg);
            }
            elseif ($rs['approved'] == 0)
            {
                throw new Exception('Your account is still awaiting approval. We will let you know once your account has been reviewed.');
            }
            elseif ($rs['status'] == 0)
            {
                $errorMsg = <<<EOQ
<div>Your account has been disabled.</div>
<div><b>Reason:</b> {$rs['disable_remarks']}</div>
EOQ;

                throw new Exception($errorMsg);
            }
            elseif (md5($password) != $rs['password'])
            {
                throw new Exception('Email or Password is incorrect');
            }
            else
            {
                //login
                unset($rs['password']);

                $sessionId = getNewId();
                $rs['sessionId'] = $sessionId;

                $_SESSION[$userType] = $rs;
                //update login logs for non-admin users
                if (!in_array($userType, ['admin']))
                {
                    self::logUserLogin($rs);
                }

                self::$dataJson['msg'] = 'Login successful';
            }
        }
        else
        {
            throw new Exception('User with this email does not exist');
        }
    }

    /**
     * Push a log entry for the user login action
     * @param array $arUser
     * @return void
     */
    private static function logUserLogin($arUser)
    {
        $cdate = getCurrentDate();
        $data = [
            'id' => getNewId()
            , 'userId' => $arUser['id']
            , 'dateLogin' => $cdate
            , 'sessionId' => $arUser['sessionId']
            , 'cdate' => $cdate
        ];
        Crud::insert(
            self::$tableLogs
            , $data
        );
    }

    /**
     * Push a log entry for the user logout action
     * @param string $sessionId
     * @return void
     */
    public static function logUserLogout($sessionId)
    {
        if (!empty($sessionId) && strlen($sessionId) == 36)
        {
            $rs = Crud::getRecordInfoWithCondition(
                self::$tableLogs
                , ['id']
                , ['sessionId' => $sessionId]
            );
            if ($rs)
            {
                $cdate = getCurrentDate();
                $data = [
                    'dateLogout' => $cdate
                    , 'mdate' => $cdate
                ];
                Crud::update(
                    self::$tableLogs
                    , $data
                    , ['id' => $rs['id']]
                );
            }
        }
    }
}