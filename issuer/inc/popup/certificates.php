<?php
require_once '../../../inc/utils.php';
use ValidCert\Certificate\CertificateFunctions;

$id = isset($_REQUEST['id']) ? trim($_REQUEST['id']) : '';
$modalId = isset($_REQUEST['modalId']) ? trim($_REQUEST['modalId']) : 'defaultModal';
$action = trim($_REQUEST['action']);

$certificateId = $holderFirstName = $holderLastName = $program = $issueDate = $levelId = '';

$title = 'Add New Certificate';
$formId = 'saveCertificateForm';

if ($action == 'updatecertificate')
{
    $title = 'Update Certificate';

    $rs = CertificateFunctions::getCertificate(
        $id
        , ['certificateId', 'holderFirstName', 'holderLastName', 'program', 'issueDate', 'levelId']
    );
    if ($rs)
    {
        $certificateId = $rs['certificateId'];
        $holderFirstName = $rs['holderFirstName'];
        $holderLastName = $rs['holderLastName'];
        $program = $rs['program'];
        $issueDate = $rs['issueDate'];
        $levelId = $rs['levelId'];
    }
    else
    {
        //throw error and exit
        echo '<p class="p-3">An erorr occurred while loading details. Please refresh page and try again.</p>';
        exit;
    }
}
?>

<form id="<?php echo $formId; ?>" method="post" onsubmit="return false;">
    <div class="modal-header">
        <h5 class="modal-title"><?php echo $title; ?></h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <i class="fas fa-times"></i>
        </button>
    </div>
    <div class="modal-body">
        <input type="hidden" name="action" id="action" value="<?php echo $action; ?>">
        <input type="hidden" name="id" id="id" value="<?php echo $id; ?>">
        
        <div class="row">
            <div class="form-group col-md-12">
                <label>Certificate ID</label>
                <input type="text" class="form-control" name="certificateId" id="certificateId" value="<?php echo $certificateId; ?>">
            </div>
        </div>

        <div class="row">
            <div class="form-group col-md-12">
                <label>Holder's First Name</label>
                <input type="text" class="form-control" name="holderFirstName" id="holderFirstName" value="<?php echo $holderFirstName; ?>">
            </div>
        </div>

        <div class="row">
            <div class="form-group col-md-12">
                <label>Holder's Last Name</label>
                <input type="text" class="form-control" name="holderLastName" id="holderLastName" value="<?php echo $holderLastName; ?>">
            </div>
        </div>

        <div class="row">
            <div class="form-group col-md-12">
                <label>Program / Course of Study</label>
                <input type="text" class="form-control" name="program" id="program" value="<?php echo $program; ?>">
            </div>
        </div>

        <div class="row">
            <div class="form-group col-md-12">
                <label>Issue Date</label>
                <input type="text" class="form-control" name="issueDate" id="issueDate" value="<?php echo $issueDate; ?>">
            </div>
        </div>

        <div class="row">
            <div class="form-group col-md-12">
                <label>Level</label>
                <select class="form-control" name="levelId" id="levelId">
                <?php
                $levels = ValidCert\EducationLevel\EducationLevelFunctions::getEducationLevelsDropdownArray();
                foreach ($levels as $eduLevelId => $abbr)
                {
                    $selected = '';
                    if ($levelId == $eduLevelId)
                    {
                        $selected = ' selected';
                    }
                    echo <<<EOQ
                    <option value="{$eduLevelId}"{$selected}>{$abbr}</option>
EOQ;
                }
                ?>
                </select>
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-info" id="btnSubmit">Submit</button>
    </div>
</form>

<script>
var formId = '<?php echo $formId; ?>';
var modalId = '<?php echo $modalId; ?>';
$(document).ready(function() {

    flatpickr("#issueDate", {
        dateFormat: "Y-m-d",
        maxDate: '<?php echo date('Y-m-d'); ?>'
    });

    $('#'+formId+' #btnSubmit').click(function(){
        //var certificateId = $('#'+formId+' #certificateId').val();
        var holderFirstName = $('#'+formId+' #holderFirstName').val();
        var holderLastName = $('#'+formId+' #holderLastName').val();
        var program = $('#'+formId+' #program').val();
        var issueDate = $('#'+formId+' #issueDate').val();
        var levelId = $('#'+formId+' #levelId').val();
        
        if (holderFirstName.length < 3 || holderFirstName.length > 100)
        {
            getErrorMessage('first name!');
        }
        if (holderLastName.length < 3 || holderLastName.length > 100)
        {
            getErrorMessage('last name!');
        }
        else if (program.length < 3)
        {
            getErrorMessage('program!');
        }
        else if (issueDate.length != 10)
        {
            getErrorMessage('issue date!');
        }
        else if (levelId.length != 36)
        {
            getErrorMessage('level!');
        }
        else
        {
            var form = $('#'+formId);
            $.ajax({
                url: baseUrl+'inc/actions',
                type: 'POST',
                dataType: 'json',
                data: form.serialize(),
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

});
</script>