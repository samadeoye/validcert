<?php
namespace ValidCert\Auth;

use Exception;
use ValidCert\Crud\Crud;
use ValidCert\User\User;

class Register
{
    protected static $table = DEF_TBL_USERS;
    private static $data = [];
    public static $dataJson = [];

    /**
     * Validate user entry
     * @param array $arParams
     * @throws \Exception
     * @return void
     */
    protected static function validateUserData($arParams)
    {
        $firstName = stringToUpper(trim($arParams['firstName']));
        $lastName = stringToUpper(trim($arParams['lastName']));
        $email = strtolower(trim($arParams['email']));
        $userType = $arParams['userType'];
        $role = isset($arParams['role']) ? trim($arParams['role']) : '';
        $organization = isset($arParams['organization']) ? trim($arParams['organization']) : '';
        $organizationType = isset($arParams['organizationType']) ? $arParams['organizationType'] : '';
        $password = trim($arParams['password']);
        $passwordConfirm = trim($arParams['passwordConfirm']);

        if (!filter_var($email, FILTER_VALIDATE_EMAIL))
        {
            throw new Exception('Please enter a valid email');
        }
        elseif (strlen($password) < 8)
        {
            throw new Exception('Password must contain at least 8 characters');
        }
        elseif ($password != $passwordConfirm)
        {
            throw new Exception('Passwords do not match');
        }
        elseif ($userType == 'issuer' && $role == '')
        {
            throw new Exception('Please provide your role in the institution');
        }
        elseif ($userType == 'issuer' && $organization == '')
        {
            throw new Exception('Please provide your organization name');
        }
        elseif ($userType == 'issuer' && $organizationType == '')
        {
            throw new Exception('Please select your organization type');
        }
        //check if a user exists with the same email
        elseif (User::checkIfUserExists('email', $email))
        {
            throw new Exception('A user already exists with this email');
        }
        elseif (Crud::checkDuplicate(self::$table, 'UCASE(organization)', stringToUpper($organization)))
        {
            throw new Exception('A user already exists from this organization');
        }
        else
        {
            //get user name
            $username = strtolower($firstName) . '.' . strtolower($lastName);
            $username = self::getNewUserName($username);
        }

        //proceed to register
        self::$data = [
            'firstName' => $firstName
            , 'lastName' => $lastName
            , 'fullName' => "{$firstName} {$lastName}"
            , 'username' => $username
            , 'email' => $email
            , 'userType' => $userType
            , 'role' => $role
            , 'organization' => $organization
            , 'organizationType' => $organizationType
        ];
    }

    /**
     * Get new username
     * @param string $username
     */
    private static function getNewUsername($username)
    {
        //check if there are existing users with this username
        $rs = Crud::getRecordInfoWithCondition(
            self::$table
            , ['COUNT(id) AS num']
            , ['username' => $username]
        );
        $num = 0;
        if ($rs)
        {
            $num = $rs['num'];
        }
        if ($num > 0)
        {
            //add suffix num
            $suffixNum = $num + 1;
            $username = $username . $suffixNum;
            //check if this one already exists
            $rs = Crud::getRecordInfoWithCondition(
                self::$table
                , ['id']
                , ['username' => $username]
            );
            if ($rs)
            {
                self::getNewUsername($username);
            }
        }
        return $username;
    }

    /**
     * User's registration process
     * @param array $arParams
     * @throws \Exception
     * @return void
     */
    public static function registerUser($arParams)
    {
        self::validateUserData($arParams);

        $password = trim($arParams['password']);
        $data = self::$data;
        $userType = $data['userType'];
        //If user is an issuer, they need to be manually reviewed by admin
        $approved = 1;
        if ($userType == 'issuer')
        {
            $approved = 0;
        }
        $data['approved'] = $approved;

        //proceed to register
        $id = getNewId();

        $data['id'] = $id;
        $data['password'] = md5($password);
        $data['cdate'] = getCurrentDate();
        if (Crud::insert(self::$table, $data))
        {
            if ($approved == 1)
            {
                Login::loginUser([
                    'email' => $data['email']
                    , 'password' => $password
                    , 'userType' => $userType
                ]);
                $msg = 'Registration successful! Redirecting to login...';
            }
            else
            {
                $msg = 'Registration successful! An admin will review your account.';
            }

            $loggedIn = $approved;
            self::$dataJson['loggedIn'] = $loggedIn;
            self::$dataJson['msg'] = $msg;
        }
        else
        {
            throw new Exception('An error occured saving your data');
        }
    }
}