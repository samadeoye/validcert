<?php

namespace ValidCert\User\Views;

class RegisterView
{
    public static $userType;
    
    /**
     * User registration form
     * @return string
     */
    public static function getUserRegisterView()
    {
        $siteName = SITE_NAME;
        $baseUrl = DEF_BASE_URL;
        $rootPath = DEF_ROOT_PATH;

        $userType = self::$userType;
        $arUserType = [
            'verifier' => ''
            , 'issuer' => ''
            , 'admin' => ''
        ];
        $arUserType[$userType] = ' selected';

        $theme = 'theme';
        switch ($userType)
        {
            case 'admin':
                $theme = 'primary';
            break;
            case 'issuer':
                $theme = 'info';
            break;
        }

        $organizationTypeStyles = '';
        if ($userType == 'verifier')
        {
            //hide organization type input
            $organizationTypeStyles = 'display:none;';
        }

return <<<EOQ

<body class="hold-transition login-page mt-4 mb-4">
<div class="login-box">
    <div class="card card-outline card-{$theme}">
        <div class="card-header text-center">
            <a class="h2">{$siteName}</a>
        </div>
        <div class="card-body">
            <p class="login-box-msg">Sign up to start your session</p>
            <form method="post" onsubmit="return false;" id="registerForm">
                <input type="hidden" name="action" id="action" value="register">
                <input type="hidden" name="userType" id="userType" value="{$userType}">
                <div class="input-group mb-3">
                    <input type="firstName" class="form-control" id="firstName" name="firstName" placeholder="First Name">
                    <div class="input-group-append">
                        <div class="input-group-text">
                            <span class="fas fa-user"></span>
                        </div>
                    </div>
                </div>

                <div class="input-group mb-3">
                    <input type="lastName" class="form-control" id="lastName" name="lastName" placeholder="Last Name">
                    <div class="input-group-append">
                        <div class="input-group-text">
                            <span class="fas fa-user"></span>
                        </div>
                    </div>
                </div>

                <div class="input-group mb-3">
                    <select class="form-control" id="userTypex" name="userTypex" disabled>
                        <option value="">Select User Type</option>
                        <option value="verifier"{$arUserType['verifier']}>Verifier</option>
                        <option value="issuer"{$arUserType['issuer']}>Issuer</option>
                        <option value="admin"{$arUserType['admin']}>Admin</option>
                    </select>
                    <div class="input-group-append">
                        <div class="input-group-text">
                            <span class="fas fa-id-card"></span>
                        </div>
                    </div>
                </div>

                <div class="input-group mb-3" id="divOrganizationType" style="{$organizationTypeStyles}">
                    <select class="form-control" id="organizationType" name="organizationType">
                        <option value="">Select Organization Type</option>
                        <option value="academic">Academic</option>
                        <option value="professional">Professional</option>
                    </select>
                    <div class="input-group-append">
                        <div class="input-group-text">
                            <span class="fas fa-chalkboard-teacher"></span>
                        </div>
                    </div>
                </div>

                <div class="input-group mb-3">
                    <input type="organization" class="form-control" id="organization" name="organization" placeholder="Organization">
                    <div class="input-group-append">
                        <div class="input-group-text">
                            <span class="fas fa-home"></span>
                        </div>
                    </div>
                </div>

                <div class="input-group mb-3">
                    <input type="role" class="form-control" id="role" name="role" placeholder="Role">
                    <div class="input-group-append">
                        <div class="input-group-text">
                            <span class="fas fa-user"></span>
                        </div>
                    </div>
                </div>

                <div class="input-group mb-3">
                    <input type="email" class="form-control" id="email" name="email" placeholder="Email">
                    <div class="input-group-append">
                        <div class="input-group-text">
                            <span class="fas fa-envelope"></span>
                        </div>
                    </div>
                </div>
                <div class="input-group mb-3">
                    <input type="password" class="form-control" id="password" name="password" placeholder="Password">
                    <div class="input-group-append">
                        <div class="input-group-text">
                            <span class="fas fa-lock"></span>
                        </div>
                    </div>
                </div>

                <div class="input-group mb-3">
                    <input type="password" class="form-control" id="passwordConfirm" name="passwordConfirm" placeholder="Confirm Password">
                    <div class="input-group-append">
                        <div class="input-group-text">
                            <span class="fas fa-lock"></span>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-12">
                        <button type="submit" class="btn btn-{$theme} btn-block" id="btnSubmit">Sign Up</button>
                    </div>
                </div>
            </form>

            <p class="my-2">
                <a href="{$baseUrl}app/login">Already registered?</a>
            </p>
            <p class="my-2">
                <a href="{$rootPath}">Go to Home</a>
            </p>
        </div>
        <!-- /.card-body -->
    </div>
    <!-- /.card -->
</div>

EOQ;
    }

    public static function getUserRegisterViewJsOnLoad()
    {
        return <<<EOQ

var formId = '#registerForm';

toggleOrganizationType();
$(formId+' #userType').on('change', function() {
    toggleOrganizationType();
});

$(formId+' #btnSubmit').click(function()
{
    var firstName = $(formId+' #firstName').val();
    var lastName = $(formId+' #lastName').val();
    var userType = $(formId+' #userType').val();
    var role = $(formId+' #role').val();
    var organization = $(formId+' #organization').val();
    var organizationType = $(formId+' #organizationType').val();
    var email = $(formId+' #email').val();
    var password = $(formId+' #password').val();
    var passwordConfirm = $(formId+' #passwordConfirm').val();

    if ((firstName.length < 3 || lastName.length < 3) || (firstName.length > 50 || lastName.length > 50))
    {
        throwError('Name is invalid');
    }
    else if (userType.length < 4)
    {
        throwError('User Type is required');
    }
    else if (role.length < 3)
    {
        throwError('Please enter your role in the organization');
    }
    else if (userType == 'issuer' && organization.length < 5)
    {
        throwError('Please provide your organization name');
    }
    else if (userType == 'issuer' && organizationType.length < 5)
    {
        throwError('Please select your organization type');
    }
    else if (email.length < 13 || email.length > 150)
    {
        throwError('Email is incorrect');
    }
    else if (password.length < 8)
    {
        throwError('Password must contain at least 8 characters');
    }
    else if (password != passwordConfirm)
    {
        throwError('Passwords do not match');
    }
    else
    {
        var registerForm = $("#registerForm");
        $.ajax({
            url: baseUrl+'inc/actions',
            type: 'POST',
            dataType: 'json',
            data: registerForm.serialize(),
            beforeSend: function() {
                enableDisableBtn(formId+' #btnSubmit', 0);
            },
            complete: function() {
                enableDisableBtn(formId+' #btnSubmit', 1);
            },
            success: function(data)
            {
                if (data.status == true)
                {
                    throwSuccess(data.msg);
                    registerForm[0].reset();

                    if (data.loggedIn == 1)
                    {
                        //redirect to dashboard
                        window.location.href = baseUrl+'app/login';
                    }
                }
                else
                {
                    throwError(data.msg);
                }
            }
        });
    }
});

EOQ;
    }

    public static function getUserRegisterViewJs()
    {
        return <<<EOQ
var formId = '#registerForm';
function toggleOrganizationType()
{
    if ($(formId+' #userType').val() == 'issuer')
    {
        $('#divOrganizationType').show();
    }
    else
    {
        $('#organizationType').val('');
        $('#divOrganizationType').hide();
    }
}

EOQ;
    }
}