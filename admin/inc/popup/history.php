<?php
require_once '../../../inc/utils.php';

$modalId = isset($_REQUEST['modalId']) ? trim($_REQUEST['modalId']) : 'largeModal';
$category = $_REQUEST['category'];

$title = 'History';
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
        <div>
            <button class="btn btn-primary btn-sm" id="btnReloadHistoryTable"><i class="fas fa-redo"></i> Reload</button>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table id="historyTable" class="table table-bordered table-hover">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>Action</th>
                            <th>Field</th>
                            <th>Old Value</th>
                            <th>New Value</th>
                            <th>Date</th>
                            <th>User</th>
                        </tr>
                    </thead>
                    <tfoot>
                        <tr>
                            <th>#</th>
                            <th>Action</th>
                            <th>Field</th>
                            <th>Old Value</th>
                            <th>New Value</th>
                            <th>Date</th>
                            <th>User</th>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
</div>
<div class="modal-footer">
    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
</div>

<script>
$(document).ready(function() {

    var historyTable = $('#historyTable').DataTable({
        processing: true,
        autoWidth: false,
        responsive: true,
        ajax: baseUrl+'inc/actions?action=gethistory&category=<?php echo $category; ?>',
        columns: [
            { data: 'sn' },
            { data: 'action' },
            { data: 'field' },
            { data: 'oldValue' },
            { data: 'newValue' },
            { data: 'date' },
            { data: 'user' }
        ],
        columnDefs: [
            {"orderable": false}
        ],
        pageLength: 50
    });

    $('#btnReloadHistoryTable').on('click', function() {
        reloadTable('historyTable');
    });

});

</script>