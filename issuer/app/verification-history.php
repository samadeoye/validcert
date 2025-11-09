<?php
require_once '../../inc/utils.php';
$pageTitle = 'Verification History';
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
                        <h3>Verification History</h3>
                    </div>
                    <div class="m-3">
                        <button class="btn btn-info btn-sm" id="btnReloadVerificationHistoryTable"><i class="fas fa-redo"></i> Reload</button>
                    </div>
                    <div class="card-body table-responsive">
                        <table id="verificationHistoryTable" class="table table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Holder's Name</th>
                                    <th>Issue Date</th>
                                    <th>Status</th>
                                    <th>Verifier</th>
                                    <th>Date Verified</th>
                                </tr>
                            </thead>
                            <tfoot>
                                <tr>
                                    <th>#</th>
                                    <th>Holder's Name</th>
                                    <th>Issue Date</th>
                                    <th>Status</th>
                                    <th>Verifier</th>
                                    <th>Date Verified</th>
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

var verificationHistoryTable = $('#verificationHistoryTable').DataTable({
    processing: true,
    autoWidth: false,
    responsive: true,
    ajax: baseUrl+'inc/actions?action=getcertificatesverifications',
    columns: [
        { data: 'sn' },
        { data: 'holderName' },
        { data: 'issueDate' },
        { data: 'status' },
        { data: 'verifier' },
        { data: 'cdate' },
    ],
    columnDefs: [
        {"orderable": false}
    ],
    pageLength: 50
});

$('#btnReloadVerificationHistoryTable').on('click', function() {
    reloadTable('verificationHistoryTable');
});

EOQ;

require_once DEF_DOC_ROOT_ISSUER.'inc/foot.php';
?>