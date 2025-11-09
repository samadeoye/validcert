<?php
require_once 'inc/utils.php';
$pageTitle = 'Verify Certificate';

$certificateHash = isset($_GET['hash']) ? $_GET['hash'] : '';
$certificateQrCodeAndResult = $customStyles = '';
if ($certificateHash != '')
{
  //verify the certificate with the hash
  $userId = getUserSessionValueByKey('id', 'verifier');
  $verificationResult = ValidCert\Certificate\CertificateVerification::verifyCertificateByHash(
    $certificateHash, $userId
  );
  $verificationStatus = $verificationResult['status'];
  $certificateQrCodeAndResult = $verificationResult['msg'];
  
  $customStyles = 'margin-bottom:180px;';
}

$arAdditionalCSS[] = <<<EOQ
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<link href="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css" rel="stylesheet" />
EOQ;

require_once 'inc/head.php';
?>

  <main class="main">

    <!-- Page Title -->
    <div class="page-title" data-aos="fade">
      <nav class="breadcrumbs">
        <div class="container">
          <ol>
            <li><a href="">Home</a></li>
            <li class="current"><?php echo $pageTitle; ?></li>
          </ol>
        </div>
      </nav>
    </div><!-- End Page Title -->

    <!-- Starter Section Section -->
    <section id="starter-section" class="starter-section section">

      <div class="container section-title" data-aos="fade-up">
        <h2>Verify certificate details</h2>
      </div>

      <div class="container appointment" style="<?php echo $customStyles; ?>">
        <div class="row justify-content-center">
        
          <div class="col-md-6">

            <div class="card">
              <div class="card-body">
                <div id="certVerificationAlert"></div>
                <?php
                if ($certificateHash == '')
                { ?>
                  <form id="verifyForm" method="post" action="inc/actions" onsubmit="return false;">
                    <input type="hidden" name="action" id="action" value="verifycertificate">

                    <div class="form-group mb-2">
                      <label class="form-label">Certificate ID <div><small>Note that you must enter the certificate ID if provided by the issuer</small></div></label>
                      <input type="text" class="form-control" name="certificateId" id="certificateId">
                    </div>

                    <div class="form-group mb-2">
                      <label class="form-label">Holder's First Name</label>
                      <input type="text" class="form-control" name="holderFirstName" id="holderFirstName">
                    </div>

                    <div class="form-group mb-2">
                      <label class="form-label">Holder's Last Name</label>
                      <input type="text" class="form-control" name="holderLastName" id="holderLastName">
                    </div>

                    <div class="form-group mb-2">
                      <label class="form-label">Program / Course of Study</small></label>
                      <input type="text" class="form-control" name="program" id="program">
                    </div>

                    <div class="form-group mb-2">
                      <label class="form-label">Issuer</label>
                      <select class="form-select" name="issuerId" id="issuerId">
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

                    <div class="form-group mb-2">
                      <label class="form-label">Issue Date</label>
                      <!-- <input type="date" class="form-control" name="issueDate" id="issueDate"> -->
                      <input type="text" class="form-control" name="issueDate" id="issueDate">
                    </div>

                    <div class="form-group">
                      <button type="button" class="btn btn-block theme-block-btn" id="btnSubmit">
                        VERIFY
                      </button>
                      <div class="text-center mt-4">
                        <a href="verifier/app/login">Login</a><br><small>for more features</small>
                      </div>
                    </div>
                  </form> <?php
                }
                else
                {
                  echo $certificateQrCodeAndResult;
                }
                ?>
              </div>
            </div>

          </div>

        </div>
      </div>

    </section>

  </main>


<?php
$arAdditionalJsScripts[] = <<<EOQ
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.min.js"></script>
EOQ;

$arAdditionalJsOnLoad[] = <<<EOQ

flatpickr("#issueDate", {
  dateFormat: "Y-m-d"
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
      url: 'inc/actions',
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
          $('#issuerId').val('').trigger('change');
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

$arAdditionalJs[] = <<<EOQ
EOQ;

require_once 'inc/foot.php';
?>