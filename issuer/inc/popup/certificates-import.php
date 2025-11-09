<?php
require_once '../../../inc/utils.php';

$modalId = isset($_REQUEST['modalId']) ? trim($_REQUEST['modalId']) : 'largeModal';

$title = 'Import Certificates';
$formId = 'importCertificatesForm';

$importTemplateFilePath =  DEF_FULL_ROOT_PATH.'/inc/files/importtemplates/ValidCert-CertificatesImportTemplate.xlsx';
?>

<div class="modal-header">
    <h5 class="modal-title"><?php echo $title; ?></h5>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <i class="fas fa-times"></i>
    </button>
</div>
<div class="modal-body">
    <input type="hidden" name="action" id="action" value="<?php echo $action; ?>">
    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">Import CSV / Excel File</h5>
            <a href="<?php echo $importTemplateFilePath; ?>" download class="btn btn-theme btn-sm mt-2">
                <i class="fas fa-download"></i> Download Template
            </a>
        </div>
        <div class="card-body">
            <div class="alert alert-info">
                <strong>Hint:</strong> Your file must contain column headers as shown in the legend below.  
                Accepted formats: <code>.csv</code>, <code>.xls</code>, <code>.xlsx</code>.
            </div>

            <form id="<?php echo $formId; ?>" action="inc/actions.php" method="POST" onsubmit="return false;" enctype="multipart/form-data">
                <input type="hidden" name="action" id="action" value="importcertificates">
                <div class="mb-3">
                    <label for="certificatesFile" class="form-label">Choose file</label>
                    <input type="file" name="certificatesFile" id="certificatesFile" class="form-control" accept=".csv, .xls, .xlsx" required>
                </div>
                <button type="submit" class="btn btn-success" id="btnSubmit">
                    <i class="fas fa-upload"></i> Import
                </button>
            </form>

            <hr>
            <div id="importContentTable">
                <h6 class="mt-4"> Expected File Format</h6>
                <p class="text-muted mb-2">Below is the format your CSV or Excel file should follow:</p>

                <div class="table-responsive">
                    <table class="table table-bordered table-striped align-middle">
                        <thead class="table-light">
                            <tr>
                            <th>Column Name</th>
                            <th>Description</th>
                            <th>Example Value</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td><code>Certificate ID</code></td>
                                <td>Unique identifier of the certifcate (optional)</td>
                                <td>ACE-001455</td>
                            </tr>
                            <tr>
                                <td><code>Holder's First Name</code></td>
                                <td>Registered first name of the holder</td>
                                <td>John</td>
                            </tr>
                            <tr>
                                <td><code>Holder's Last Name</code></td>
                                <td>Registered last name of the holder</td>
                                <td>Doe</td>
                            </tr>
                            <tr>
                                <td><code>Program</code></td>
                                <td>Program or course of study</td>
                                <td>Computer Science</td>
                            </tr>
                            <tr>
                                <td><code>Issue Date</code></td>
                                <td>Date of issue (YYYY-mm-dd)</td>
                                <td>2022-05-10</td>
                            </tr>
                            <tr>
                                <td><code>Level</code></td>
                                <td>Academic or professional level attained </td>
                                <td><small><a href="javascript:;" onclick="openLevelsModal();">Click to see accepted options</a></small></td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <p class="text-muted small">
                    <strong>Tip:</strong> The first row in your file must contain headers exactly as shown above (already added in template).
                </p>
            
            </div>
        </div>
    </div>
</div>
<div class="modal-footer">
    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
</div>

<script>
var formId = '<?php echo $formId; ?>';
var modalId = '<?php echo $modalId; ?>';
$(document).ready(function() {

    $('#'+formId+' #btnSubmit').click(function(){
        if ($('#certificatesFile').get(0).files.length === 0)
        {
            throwError('Please upload a valid file!');
        }
        else
        {
            var formData = new FormData(this.form);
            $.ajax({
                url: baseUrl+'inc/actions',
                type: 'POST',
                dataType: 'json',
                data: formData,
                processData: false,
                contentType: false,
                beforeSend: function() {
                    enableDisableBtn('#'+formId+' #btnSubmit', 0);
                },
                complete: function() {
                    enableDisableBtn('#'+formId+' #btnSubmit', 1);
                },
                success: function(data) {
                    if (data.status == true) {
                        $('#importContentTable').html(data.imported);
                    }
                    else {
                        toastr.error(data.msg);
                    }
                }
            });
        }
    });
});

function openLevelsModal()
{
    $('#defaultModal').css('z-index', '1060');
    showModal(baseUrl+'inc/popup/certificatesimport-levels', 'defaultModal');
}

function confirmImport(importId)
{
    Swal.fire({
        title: '',
        text: 'Are you sure you want to confirm this import?',
        icon: 'success',
        showCancelButton: true,
        reverseButtons: true,
        confirmButtonText: 'Confirm',
        confirmButtonColor: '#28a745'
    }).then((result) => {
        if (result.isConfirmed)
        {
            $.ajax({
                url: baseUrl+'inc/actions',
                type: 'POST',
                dataType: 'json',
                data: {
                    'action': 'confirmcertificatesimport'
                    , 'importId' : importId
                },
                beforeSend: function() {
                    enableDisableBtn('#'+formId+' #btnSubmit', 0);
                },
                complete: function() {
                    enableDisableBtn('#'+formId+' #btnSubmit', 1);
                },
                success: function(data) {
                    if (data.status == true) {
                        throwSuccess(data.msg);
                        closeModal(modalId, true);
                        reloadTable('certificatesTable');
                    }
                    else {
                        toastr.error(data.msg);
                    }
                }
            });
        }
    });
}
</script>