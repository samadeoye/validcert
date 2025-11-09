<?php
require_once '../../inc/utils.php';
$pageTitle = 'Certificates';

$arAdditionalCSS[] = <<<EOQ
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
EOQ;

require_once DEF_DOC_ROOT_ISSUER.'inc/head.php';
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
                    <li class="breadcrumb-item"><a href="<?php echo DEF_ROOT_PATH_ISSUER;?>/app/">Home</a></li>
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
                <div class="card card-info card-tabs">
                    <div class="card-header">
                        <h3>All Certificates</h3>
                    </div>
                    <div class="m-3">
                        <button class="btn btn-info" id="btnAddNewCertificate"><i class="fas fa-plus"></i> Add New</button>
                        <button class="btn btn-primary" id="btnImportCertificates"><i class="fas fa-upload"></i> Import</button>
                        <button class="btn btn-info btn-sm float-right" id="btnReloadCertificatesTable"><i class="fas fa-redo"></i> Reload</button>
                    </div>
                    <div class="card-body table-responsive">
                        <table id="certificatesTable" class="table table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Certificate ID</th>
                                    <th>Holder's First Name</th>
                                    <th>Holder's Last Name</th>
                                    <th>Program</th>
                                    <th>Issue Date</th>
                                    <th>Level</th>
                                    <th>Created Date</th>
                                    <th>Modified Date</th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tfoot>
                                <tr>
                                    <th>#</th>
                                    <th>Certificate ID</th>
                                    <th>Holder's First Name</th>
                                    <th>Holder's Last Name</th>
                                    <th>Program</th>
                                    <th>Issue Date</th>
                                    <th>Level</th>
                                    <th>Created Date</th>
                                    <th>Modified Date</th>
                                    <th></th>
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

$arAdditionalJsScripts[] = <<<EOQ
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
EOQ;

$arAdditionalJs[] = <<<EOQ
function editCertificate(id)
{
    showModal(baseUrl+'inc/popup/certificates?id='+id+'&action=updatecertificate', 'defaultModal');
}

function deleteCertificate(id)
{
    Swal.fire({
        title: '',
        text: 'Are you sure you want to delete this certificate?',
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
                    'action': 'deletecertificate'
                },
                success: function(data) {
                    if(data.status == true) {
                        throwSuccess('Deleted successfully');
                        reloadTable('certificatesTable');
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

var certificatesTable = $('#certificatesTable').DataTable({
    processing: true,
    autoWidth: false,
    responsive: true,
    ajax: baseUrl+'inc/actions?action=getcertificates',
    columns: [
        { data: 'sn' },
        { data: 'certificateId' },
        { data: 'holderFirstName' },
        { data: 'holderLastName' },
        { data: 'program' },
        { data: 'issueDate' },
        { data: 'level' },
        { data: 'cdate' },
        { data: 'mdate' },
        { data: 'qrcode' },
        { data: 'edit' },
        { data: 'delete' }
    ],
    columnDefs: [
        {"orderable": false, "targets": [9,10,11]}
    ],
    pageLength: 50
});

$('#btnReloadCertificatesTable').on('click', function() {
    reloadTable('certificatesTable');
});

$('#btnAddNewCertificate').on('click', function() {
    showModal(baseUrl+'inc/popup/certificates?action=addcertificate', 'defaultModal');
});

$('#btnImportCertificates').on('click', function() {
    showModal(baseUrl+'inc/popup/certificates-import', 'largeModal');
});

EOQ;

require_once DEF_DOC_ROOT_ISSUER.'inc/foot.php';
?>