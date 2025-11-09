<?php
require_once '../../inc/utils.php';
$pageTitle = 'Education Levels';
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
                        <h3>Education Levels</h3>
                    </div>
                    <div class="m-3">
                        <button class="btn btn-primary" id="btnAddNewEducationLevel"><i class="fas fa-plus"></i> Add New</button>
                        <button class="btn btn-primary btn-sm float-right" id="btnReloadEducationLevelsTable"><i class="fas fa-redo"></i> Reload</button>
                    </div>
                    <div class="card-body table-responsive">
                        <table id="educationLevelsTable" class="table table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Abbr.</th>
                                    <th>Title</th>
                                    <th>Created Date</th>
                                    <th>Modified Date</th>
                                    <th></th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tfoot>
                                <tr>
                                    <th>#</th>
                                    <th>Abbr.</th>
                                    <th>Title</th>
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
function editEducationLevel(id)
{
    showModal(baseUrl+'inc/popup/educationlevels?id='+id+'&action=updateeducationlevel', 'defaultModal');
}

function deleteEducationLevel(id)
{
    Swal.fire({
        title: '',
        text: 'Are you sure you want to delete this record?',
        icon: 'error',
        showCancelButton: true,
        reverseButtons: true,
        confirmButtonText: 'Delete',
        confirmButtonColor: '#d33'
    }).then((result) => {
        if (result.isConfirmed)
        {
            $.ajax({
                url: baseUrl+'inc/actions',
                type: 'POST',
                dataType: 'json',
                data: {
                    'id': id,
                    'action': 'deleteeducationlevel'
                },
                success: function(data) {
                    if (data.status == true) {
                        throwSuccess('Deleted successfully');
                        reloadTable('educationLevelsTable');
                    }
                    else {
                        throwError(data.msg);
                    }
                }
            });
        }
    });
}

EOQ;

$arAdditionalJsOnLoad[] = <<<EOQ

var educationLevelsTable = $('#educationLevelsTable').DataTable({
    processing: true,
    autoWidth: false,
    responsive: true,
    ajax: baseUrl+'inc/actions?action=geteducationlevels',
    columns: [
        { data: 'sn' },
        { data: 'abbr' },
        { data: 'title' },
        { data: 'cdate' },
        { data: 'mdate' },
        { data: 'edit' },
        { data: 'delete' }
    ],
    columnDefs: [
        {"orderable": false, "targets": [5,6]}
    ],
    pageLength: 50
});

$('#btnReloadEducationLevelsTable').on('click', function() {
    reloadTable('educationLevelsTable');
});

$('#btnAddNewEducationLevel').on('click', function() {
    showModal(baseUrl+'inc/popup/educationlevels?action=addeducationlevel', 'defaultModal');
});

EOQ;

require_once DEF_DOC_ROOT_ADMIN.'inc/foot.php';
?>