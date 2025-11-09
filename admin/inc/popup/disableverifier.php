<?php
require_once '../../../inc/utils.php';

$id = $_REQUEST['id'];
$modalId = isset($_REQUEST['modalId']) ? trim($_REQUEST['modalId']) : 'defaultModal';

$title = 'Disable Verifier';
$formId = 'disableVerifierForm';
?>

<form id="<?php echo $formId; ?>" method="post" onsubmit="return false;">
    <div class="modal-header">
        <h5 class="modal-title"><?php echo $title; ?></h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <i class="fas fa-times"></i>
        </button>
    </div>
    <div class="modal-body">
        <input type="hidden" name="action" id="action" value="disableverifier">
        <input type="hidden" name="id" id="id" value="<?php echo $id; ?>">
        <div class="row">
            <div class="form-group col-md-12">
                <label>Enter remarks</label>
                <textarea class="form-control" name="disableVerifierRemarks" id="disableVerifierRemarks"></textarea>
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
        var disableVerifierRemarks = $('#'+formId+' #disableVerifierRemarks').val();
        
        if (disableVerifierRemarks.length < 3)
        {
            throwError('Please enter a valid remarks!');
        }
        else
        {
            Swal.fire({
                title: '',
                text: 'Are you sure you want to disable this user?',
                icon: 'error',
                showCancelButton: true,
                reverseButtons: true,
                confirmButtonText: 'Disable',
                confirmButtonColor: '#d33'
            }).then((result) => {
                if (result.isConfirmed)
                {
                    $.ajax({
                        url: baseUrl+'inc/actions',
                        type: 'POST',
                        dataType: 'json',
                        data: {
                            'id': '<?php echo $id; ?>',
                            'action': 'disableverifier',
                            'remarks': disableVerifierRemarks
                        },
                        success: function(data) {
                            if (data.status == true) {
                                closeModal(modalId, true);
                                throwSuccess('Disabled successfully');
                                reloadTable('verifiersTable');
                            }
                            else {
                                throwError(data.msg);
                            }
                        }
                    });
                }
            });
        }
    });

});
</script>
