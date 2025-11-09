<?php

namespace ValidCert\User\Views;

class ForgotPasswordView
{
    public static $userType;

    /**
     * Forgot Password form
     * @return string
     */
    public static function getUserForgotPasswordView()
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
            <p class="login-box-msg">Enter your registered email</p>
            <form method="post" onsubmit="return false;" id="forgotPassForm">
                <input type="hidden" name="action" id="action" value="forgotpassverifyemail">
                <div class="input-group mb-3">
                    <input type="email" class="form-control" id="email" name="email" placeholder="Email">
                    <div class="input-group-append">
                        <div class="input-group-text">
                            <span class="fas fa-envelope"></span>
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

    public static function getUserForgotPasswordViewJsOnLoad()
    {
        return <<<EOQ

var formId = '#forgotPassForm';
$(formId+' #btnSubmit').click(function()
{
    var email = $(formId+' #email').val();

    if (email.length < 13)
    {
        throwError('Please enter a valid email');
    }
    else
    {
        var form = $('#forgotPassForm');
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
                if(data.status)
                {
                    throwSuccess('Password reset link has been sent to your email: '+ email);
                    form[0].reset();
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