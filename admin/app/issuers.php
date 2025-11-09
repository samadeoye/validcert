<?php
require_once '../../inc/utils.php';
$pageTitle = 'Issuers';
require_once DEF_DOC_ROOT_ADMIN.'inc/head.php';
?>

    <div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0"><?php echo $pageTitle;?></h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="<?php echo DEF_ROOT_PATH_ADMIN;?>/app/">Home</a></li>
                    <li class="breadcrumb-item active"><?php echo $pageTitle;?></li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <section class="content">
        <!-- ./row -->
        <div class="row">
            <div class="col-md-12">
                <div class="card card-primary card-tabs">
                    <div class="card-header">
                        <h3>Issuers</h3>
                    </div>
                    <div class="m-3">
                        <button class="btn btn-primary btn-sm" id="btnReloadIssuersTable"><i class="fas fa-redo"></i> Reload</button>
                    </div>
                    <div class="card-body table-responsive">
                        <table id="issuersTable" class="table table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>First Name</th>
                                    <th>Last Name</th>
                                    <th>Username</th>
                                    <th>Email</th>
                                    <th>Role</th>
                                    <th>Organization</th>
                                    <th>Organization Type</th>
                                    <th>Status</th>
                                    <th>Created Date</th>
                                    <th>Modified Date</th>
                                    <th></th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tfoot>
                                <tr>
                                    <th>#</th>
                                    <th>First Name</th>
                                    <th>Last Name</th>
                                    <th>Username</th>
                                    <th>Email</th>
                                    <th>Role</th>
                                    <th>Organization</th>
                                    <th>Organization Type</th>
                                    <th>Status</th>
                                    <th>Created Date</th>
                                    <th>Modified Date</th>
                                    <th></th>
                                    <th></th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </section>

<!-- /.content -->
</div>
  
<?php

$arAdditionalJs[] = <<<EOQ

function approveIssuer(id)
{
    Swal.fire({
        title: '',
        text: 'Are you sure you want to approve this user?',
        icon: 'success',
        showCancelButton: true,
        reverseButtons: true,
        confirmButtonText: 'Approve',
        confirmButtonColor: '#28a745'
    }).then((result) => {
        if (result.isConfirmed)
        {
            $.ajax({
                url: baseUrl+'inc/actions',
                type: 'POST',
                dataType: 'json',
                data: {
                    'id': id,
                    'action': 'approveissuer'
                },
                success: function(data) {
                    if (data.status == true) {
                        throwSuccess('Approved successfully');
                        reloadTable('issuersTable');
                    }
                    else {
                        throwError(data.msg);
                    }
                }
            });
        }
    });
}

function rejectIssuer(id)
{
    showModal(baseUrl+'inc/popup/rejectissuer?id='+id, 'defaultModal');
}

EOQ;

$arAdditionalJsOnLoad[] = <<<EOQ

$('[data-toggle="tooltip"]').tooltip();

var issuersTable = $('#issuersTable').DataTable({
    processing: true,
    autoWidth: false,
    responsive: true,
    ajax: baseUrl+'inc/actions?action=getissuers',
    columns: [
        { data: 'sn' },
        { data: 'firstName' },
        { data: 'lastName' },
        { data: 'username' },
        { data: 'email' },
        { data: 'role' },
        { data: 'organization' },
        { data: 'organizationType' },
        { data: 'status' },
        { data: 'cdate' },
        { data: 'mdate' },
        { data: 'approve' },
        { data: 'reject' }
    ],
    columnDefs: [
        {"orderable": false, "targets": [8,11,12]}
    ],
    pageLength: 50
});

//re-initialize tooltips
issuersTable.on('draw.dt', function () {
    $('[data-toggle="tooltip"]').tooltip();
});

$('#btnReloadIssuersTable').on('click', function() {
    reloadTable('issuersTable');
});

EOQ;

require_once DEF_DOC_ROOT_ADMIN.'inc/foot.php';
?>