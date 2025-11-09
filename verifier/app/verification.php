<?php
require_once '../../inc/utils.php';
$pageTitle = 'Verification';

$arAdditionalCSS[] = <<<EOQ
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<link href="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css" rel="stylesheet" />
EOQ;

require_once DEF_DOC_ROOT_VERIFIER.'inc/head.php';
?>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0"><?php echo $pageTitle;?></h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="<?php echo DEF_ROOT_PATH_VERIFIER;?>/app/">Home</a></li>
              <li class="breadcrumb-item active"><?php echo $pageTitle;?></li>
            </ol>
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-md-8 mx-auto">
                <div class="card card-theme">
                    <div class="card-header">
                        <h3 class="card-title"><i class="fas fa-certificate"></i> Verify Certificate</h3>
                    </div>
                    <div class="card-body">
                        <div id="certVerificationAlert"></div>
                        <form method="post" onsubmit="return false;" id="verifyForm">
                            <input type="hidden" name="action" id="action" value="verifycertificate">
                            <div class="form-group">
                                <label for="certificateId">Certificate ID <div><small>Note that you must enter the certificate ID if provided by the issuer</small></div></label>
                                <input type="text" id="certificateId" name="certificateId" class="form-control">
                            </div>
                            
                            <div class="form-group">
                                <label for="holderFirstName">Holder's First Name</label>
                                <input type="text" id="holderFirstName" name="holderFirstName" class="form-control">
                            </div>

                            <div class="form-group">
                                <label for="holderLastName">Holder's Last Name</label>
                                <input type="text" id="holderLastName" name="holderLastName" class="form-control">
                            </div>

                            <div class="form-group">
                                <label for="certificateType">Program / Course of Study</label>
                                <input type="text" id="program" name="program" class="form-control">
                            </div>

                            <div class="form-group">
                                <label for="issuerId">Issuer</label>
                                <select id="issuerId" name="issuerId" class="form-control">
                                    <option value=""></option>
                                    <?php
                                        $issuers = ValidCert\Issuer\IssuerFunctions::getIssuers(['id', 'organization']);
                                        foreach ($issuers as $issuer)
                                        {
                                        echo <<<EOQ
                                        <option value="{$issuer['id']}">{$issuer['organization']}</option>
EOQ;
                                        }
                                    ?>
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="issueDate">Issue Date</label>
                                <input type="text" id="issueDate" name="issueDate" class="form-control">
                            </div>

                            <div class="form-group">
                                <input type="submit" value="VERIFY" class="btn btn-success" id="btnSubmit">
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
  
<?php
$arAdditionalJsScripts[] = <<<EOQ
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script src="https://code.jquery.com/jquery-3.x.x.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.min.js"></script>
EOQ;

$arAdditionalJsOnLoad[] = <<<EOQ

flatpickr("#issueDate", {
  dateFormat: "Y/m/d"
});

$('#issuerId').select2({
  placeholder: 'Select an issuer',
  width: '100%',
  height: '40px'
});

var formId = '#verifyForm';

$(formId+' #btnSubmit').click(function ()
{
    var holderFirstName = $(formId+' #holderFirstName').val();
    var holderLastName = $(formId+' #holderLastName').val();
    var program = $(formId+' #program').val();
    var issuerId = $(formId+' #issuerId').val();
    var issueDate = $(formId+' #issueDate').val();

    if (holderFirstName.length < 3)
    {
        throwError('Please enter a valid holder\'s first name');
    }
    else if (holderLastName.length < 3)
    {
        throwError('Please enter a valid holder\'s last name');
    }
    else if (program.length < 3)
    {
        throwError('Please enter the program');
    }
    else if (issuerId.length != 36)
    {
        throwError('Please select the issuer');
    }
    else if (issueDate.length != 10)
    {
        throwError('Please select the issue date');
    }
    else
    {
        var form = $(formId);
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
                if (data.status == true)
                {
                    form[0].reset();
                    showAlert(data.msg, 'certVerificationAlert', 'success');
                }
                else
                {
                    showAlert(data.msg, 'certVerificationAlert', 'danger');
                }
            }
        });
    }
});

EOQ;

require_once DEF_DOC_ROOT_VERIFIER.'inc/foot.php';
?>
