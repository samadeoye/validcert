<?php
require_once '../../../inc/utils.php';
use ValidCert\EducationLevel\EducationLevelFunctions;

$id = isset($_REQUEST['id']) ? trim($_REQUEST['id']) : '';
$modalId = isset($_REQUEST['modalId']) ? trim($_REQUEST['modalId']) : 'defaultModal';
$action = trim($_REQUEST['action']);

$abbr = $levelTitle = '';

$title = 'Add New Education Level';
$formId = 'saveEducationLevelForm';

if ($action == 'updateeducationlevel')
{
    $title = 'Update Education Level';

    $rs = EducationLevelFunctions::getEducationLevel(
        $id
        , ['abbr', 'title']
    );
    if ($rs)
    {
        $abbr = $rs['abbr'];
        $levelTitle = $rs['title'];
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
                <label>Abbr</label>
                <input type="text" class="form-control" name="abbr" id="abbr" value="<?php echo $abbr; ?>">
            </div>
        </div>
        <div class="row">
            <div class="form-group col-md-12">
                <label>Title</label>
                <input type="text" class="form-control" name="title" id="title" value="<?php echo $levelTitle; ?>">
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary" id="btnSubmit">Submit</button>
    </div>
</form>

<script>
var formId = '<?php echo $formId; ?>';
var modalId = '<?php echo $modalId; ?>';
$(document).ready(function() {

    $('#'+formId+' #btnSubmit').click(function(){
        var abbr = $('#'+formId+' #abbr').val();
        var title = $('#'+formId+' #title').val();
        
        if (abbr.length < 2)
        {
            getErrorMessage('abbreviation!');
        }
        else if (title.length < 3)
        {
            getErrorMessage('title!');
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
                        reloadTable('educationLevelsTable');
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