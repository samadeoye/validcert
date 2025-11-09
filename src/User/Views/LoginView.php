<?php

namespace ValidCert\User\Views;

class LoginView
{
    public static $userType;
    
    /**
     * Login form
     * @return string
     */
    public static function getUserLoginView()
    {
        $siteName = SITE_NAME;
        $baseUrl = DEF_BASE_URL;
        $userType = self::$userType;
        $lblUserType = ucwords($userType);

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
            <h3>{$lblUserType} Login</h3>
        </div>
        <div class="card-body">
            <p class="login-box-msg">Sign in to start your session</p>
            <div id="loginAlert"></div>
            <form method="post" onsubmit="return false;" id="loginForm">
                <input type="hidden" name="action" id="action" value="login">
                <input type="hidden" name="userType" id="userType" value="{$userType}">
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
                <div class="row">
                    <div class="col-12">
                        <button type="submit" class="btn btn-{$theme} btn-block" id="btnSubmit">Sign In</button>
                    </div>
                </div>
            </form>

            <p class="my-2">
                <a href="{$baseUrl}app/forgotpass">I forgot my password</a>
            </p>

            <p class="my-2">
                <a href="{$baseUrl}app/register">Not registered?</a>
            </p>
        </div>
        <!-- /.card-body -->
    </div>
    <!-- /.card -->
</div>
<!-- /.login-box -->

EOQ;
    }

    public static function getUserLoginViewJsOnLoad()
    {
        return <<<EOQ

var formId = '#loginForm';
$(formId+' #btnSubmit').click(function ()
{
    var email = $(formId+' #email').val();
    var password = $(formId+' #password').val();

    if (email.length < 13 || email.length > 100)
    {
        throwError('Email is invalid');
    }
    else if (password.length < 6)
    {
        throwError('Password is invalid');
    }
    else
    {
        var loginForm = $("#loginForm");
        $.ajax({
            url: baseUrl+'inc/actions',
            type: 'POST',
            dataType: 'json',
            data: loginForm.serialize(),
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
                    throwSuccess('Login successful! Logging you in...');
                    loginForm[0].reset();
                    //redirect to dashboard
                    window.location.href = baseUrl+'app/';
                }
                else
                {
                    if (data.info !== undefined)
                    {
                        throwError(data.msg);
                    }
                    else
                    {
                        showAlert(data.msg, 'loginAlert', 'danger');
                    }
                }
            }
        });
    }
});

EOQ;
    }
}