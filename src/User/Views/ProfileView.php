<?php

namespace ValidCert\User\Views;

class ProfileView
{
    /**
     * User profile update form
     * @param mixed $params
     * @return string
     */
    public static function getUserProfileView($params)
    {
        $arUser = $params['arUser'];
        $userType = $arUser['userType'];
        $lblUserType = ucwords($userType);

        $theme = 'theme';
        switch ($arUser['userType'])
        {
            case 'admin':
                $theme = 'primary';
            break;
            case 'issuer':
                $theme = 'info';
            break;
        }

        $pageTitle = 'Profile';

        $roleInput = '';
        if ($userType == 'issuer')
        {
            $roleInput = <<<EOQ
            <div class="form-group">
                <label for="organization">Organization Type</label>
                <input type="text" id="organizationType" name="organizationType" class="form-control" value="{$arUser['organizationType']}" readonly>
            </div>
EOQ;
        }

return <<<EOQ

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">{$pageTitle}</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="<?php echo DEF_ROOT_PATH_VERIFIER;?>/app/">Home</a></li>
              <li class="breadcrumb-item active">{$pageTitle}</li>
            </ol>
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-md-6">
                <div class="card card-{$theme}">
                    <div class="card-header">
                        <h3 class="card-title"><i class="fas fa-user"></i> Update Profile</h3>
                    </div>
                    <div class="card-body">
                        <form method="post" onsubmit="return false;" id="profileForm">
                            <input type="hidden" name="action" id="action" value="updateprofile">
                            <div class="form-group">
                                <label for="firstName">First Name</label>
                                <input type="text" id="firstName" name="firstName" class="form-control" value="{$arUser['firstName']}">
                            </div>
                            
                            <div class="form-group">
                                <label for="lastName">Last Name</label>
                                <input type="text" id="lastName" name="lastName" class="form-control" value="{$arUser['lastName']}">
                            </div>

                            <div class="form-group">
                                <label for="organization">User Type</label>
                                <input type="text" id="userType" name="userType" class="form-control" value="{$lblUserType}" readonly>
                            </div>

                            <div class="form-group">
                                <label for="role">Role</label>
                                <input type="text" id="role" name="role" class="form-control" value="{$arUser['role']}">
                            </div>

                            <div class="form-group">
                                <label for="organization">Organization</label>
                                <input type="text" id="organization" name="organization" class="form-control" value="{$arUser['organization']}" readonly>
                            </div>

                            {$roleInput}

                            <div class="form-group">
                                <label for="email">Email</label>
                                <input type="text" id="email" name="email" class="form-control" value="{$arUser['email']}" readonly>
                            </div>

                            <div class="form-group">
                                <input type="submit" value="Save Changes" class="btn btn-success float-right" id="btnSubmit">
                            </div>
                        </form>
                    </div>
                    <!-- /.card-body -->
                </div>
                <!-- /.card -->
            </div>

            <div class="col-md-6">
                <div class="card card-{$theme}">
                    <div class="card-header">
                        <h3 class="card-title"><i class="fas fa-lock"></i> Change Password</h3>
                    </div>
                    <div class="card-body">
                        <form method="post" onsubmit="return false;" id="changePasswordForm">
                            <input type="hidden" name="action" value="changepassword">
                            <div class="form-group">
                                <label for="currentPassword">Current Password</label>
                                <input type="password" id="currentPassword" name="currentPassword" class="form-control">
                            </div>
                            <div class="form-group">
                                <label for="newPassword">New Password</label>
                                <input type="password" id="newPassword" name="newPassword" class="form-control">
                            </div>
                            <div class="form-group">
                                <label for="confirmPassword">Confirm New Password</label>
                                <input type="password" id="confirmPassword" name="confirmPassword" class="form-control">
                            </div>
                            <div class="form-group">
                                <input type="submit" value="Save Changes" class="btn btn-success float-right" id="btnSubmitChangePass">
                            </div>
                        </form>
                    </div>
                    <!-- /.card-body -->
                </div>
                <!-- /.card -->
            </div>

        </div>
    </section>
    
<!-- /.content -->
</div>

EOQ;
    }

    public static function getUserProfileViewJsOnLoad()
    {
        return <<<EOQ

var formId = '#profileForm';

$('#profileForm #btnSubmit').click(function ()
{
    var firstName = $(formId+' #firstName').val();
    var lastName = $(formId+' #lastName').val();
    var organization = $(formId+' #organization').val();
    var role = $(formId+' #role').val();

    if (firstName.length < 3 || lastName.length < 3)
    {
        throwError('Please enter your name');
    }
    else if (organization.length > 0 && role.length < 3)
    {
        throwError('Please enter a valid role');
    }
    else
    {
        var form = $("#profileForm");
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
                if(data.status == true)
                {
                    throwSuccess('Profile updated successfully!');
                    $(formId+' #firstName').val(data.data['firstName']);
                    $(formId+' #lastName').val(data.data['lastName']);
                }
                else
                {
                    throwError(data.msg);
                }
            }
        });
    }
});

$('#changePasswordForm #btnSubmitChangePass').click(function ()
{
    var formId = '#changePasswordForm';
    var currentPassword = $(formId+' #currentPassword').val();
    var newPassword = $(formId+' #newPassword').val();
    var confirmPassword = $(formId+' #confirmPassword').val();

    if (currentPassword.length < 8 || newPassword.length < 8 || confirmPassword.length < 8)
    {
        throwError('Password must contain at least 8 characters');
    }
    else if (newPassword != confirmPassword)
    {
        throwError('Passwords do not match');
    }
    else
    {
        var form = $("#changePasswordForm");
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
                    throwSuccess('Password changed successfully!');
                    form[0].reset();
                }
                else
                {
                    if (data.info !== undefined)
                    {
                        throwInfo(data.msg);
                    }
                    else
                    {
                        throwError(data.msg);
                    }
                }
            }
        });
    }
});

EOQ;
    }
}