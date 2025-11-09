<?php
require_once '../../inc/utils.php';
$pageTitle = 'Certificates';
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
                        <h3>All Certificates</h3>
                    </div>
                    <div class="m-3">
                        <button class="btn btn-secondary" id="btnHistory"><i class="fas fa-history"></i> History</button>
                        <button class="btn btn-primary btn-sm float-right" id="btnReloadCertificatesTable"><i class="fas fa-redo"></i> Reload</button>
                    </div>
                    <div class="card-body table-responsive">
                        <table id="certificatesTable" class="table table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Issuer</th>
                                    <th>Certificate ID</th>
                                    <th>Holder's First Name</th>
                                    <th>Holder's Last Name</th>
                                    <th>Program</th>
                                    <th>Issue Date</th>
                                    <th>Level</th>
                                    <th>Created Date</th>
                                    <th>Modified Date</th>
                                </tr>
                            </thead>
                            <tfoot>
                                <tr>
                                    <th>#</th>
                                    <th>Issuer</th>
                                    <th>Certificate ID</th>
                                    <th>Holder's First Name</th>
                                    <th>Holder's Last Name</th>
                                    <th>Program</th>
                                    <th>Issue Date</th>
                                    <th>Level</th>
                                    <th>Created Date</th>
                                    <th>Modified Date</th>
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

$arAdditionalJsOnLoad[] = <<<EOQ

var certificatesTable = $('#certificatesTable').DataTable({
    processing: true,
    autoWidth: false,
    responsive: true,
    ajax: baseUrl+'inc/actions?action=getcertificates',
    columns: [
        { data: 'sn' },
        { data: 'issuer' },
        { data: 'certificateId' },
        { data: 'holderFirstName' },
        { data: 'holderLastName' },
        { data: 'program' },
        { data: 'issueDate' },
        { data: 'level' },
        { data: 'cdate' },
        { data: 'mdate' }
    ],
    columnDefs: [
        {"orderable": false}
    ],
    pageLength: 50
});

$('#btnReloadCertificatesTable').on('click', function() {
    reloadTable('certificatesTable');
});

$('#btnHistory').on('click', function() {
    showModal(baseUrl+'inc/popup/history?category=certificates', 'xxLargeModal');
});

EOQ;

require_once DEF_DOC_ROOT_ADMIN.'inc/foot.php';
?>