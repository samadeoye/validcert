<?php

namespace ValidCert\User\Views;

class ResetPasswordView
{
    public static $userType;

    /**
     * Reset password form
     * @return string
     */
    public static function getUserResetPasswordView()
    {
        $siteName = SITE_NAME;
        $baseUrl = DEF_BASE_URL;
        $userType = self::$userType;

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

return <<<EOQ

<body class="hold-transition login-page">
<div class="login-box">
    <div class="card card-outline card-{$theme}">
        <div class="card-header text-center">
            <a class="h2 font-weight-bold">{$siteName}</a>
            <h3>Reset Password</h3>
        </div>
        <div class="card-body">
            <p class="login-box-msg">Enter new password to complete your password reset</p>
            <form method="post" onsubmit="return false;" id="resetPasswordForm">
                <input type="hidden" name="action" id="action" value="resetpassword">
                <div class="input-group mb-3">
                    <input type="password" class="form-control" id="password" name="password" placeholder="New Password">
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
                        <button type="submit" class="btn btn-{$theme} btn-block" id="btnSubmit">Proceed</button>
                    </div>
                </div>
            </form>

            <p class="my-2">
                <a href="{$baseUrl}app/login">Login</a>
            </p>

            <!-- <p class="my-2">
                <a href="{$baseUrl}app/register">Not registered?</a>
            </p> -->
        </div>
        <!-- /.card-body -->
    </div>
    <!-- /.card -->
</div>

EOQ;
    }

    public static function getUserResetPasswordViewJsOnLoad()
    {
        return <<<EOQ

var formId = '#resetPasswordForm';
$(formId+' #btnSubmit').click(function()
{
    var password = $(formId+' #password').val();
    var passwordConfirm = $(formId+' #passwordConfirm').val();

    if (password.length < 6)
    {
        throwError('Please enter a valid password');
    }
    else if (password != passwordConfirm)
    {
        throwError('Passwords do not match');
    }
    else
    {
        var form = $('#resetPasswordForm');
        $.ajax({
            url: baseUrl+'inc/actions',
            type: 'POST',
            dataType: 'json',
            data: form.serialize(),
            beforeSend: function() {
                enableDisableBtn(formId+' #btnSubmit', 0);
            },
            complete: function() {
                enableDisableBtn(formId+' #btnSubmit', 1);
            },
            success: function(data)
            {
                if (data.status)
                {
                    throwSuccess('Password reset successfully! Proceed to login.');
                    form[0].reset();
                    goToUrl('app/login');
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
}