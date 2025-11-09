<?php
require_once '../../inc/utils.php';
$pageTitle = 'Dashboard';

require_once DEF_DOC_ROOT_ADMIN.'inc/head.php';

$arData = ValidCert\Admin\Dashboard\Dashboard::getDashboardData();
?>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
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
      <div class="container-fluid">
        <div class="row">
          <div class="col-md-3">
            <!-- small box -->
            <div class="small-box bg-primary">
              <div class="inner">
                <h3><?php echo doTypeCastInt($arData['numCertificatesAdded']);?></h3>
                <p>Certificates Added</p>
              </div>
              <a href="<?php echo DEF_BASE_URL; ?>app/certificates" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
            </div>
          </div>
          <!-- ./col -->

          <div class="col-md-3">
            <!-- small box -->
            <div class="small-box bg-secondary">
              <div class="inner">
                <h3><?php echo doTypeCastInt($arData['numCertificatesImported']);?></h3>
                <p>Certificates Imported</p>
              </div>
              <a href="<?php echo DEF_BASE_URL; ?>app/certificates" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
            </div>
          </div>
          
          <div class="col-md-3">
            <!-- small box -->
            <div class="small-box bg-info">
              <div class="inner">
                <h3><?php echo doTypeCastInt($arData['nuCertificatesTotal']);?></h3>
                <p>Certificates Total</p>
              </div>
              <a href="<?php echo DEF_BASE_URL; ?>app/certificates" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
            </div>
          </div>

          <div class="col-md-3">
            <!-- small box -->
            <div class="small-box bg-warning">
              <div class="inner">
                <h3><?php echo doTypeCastInt($arData['numVerifications']);?></h3>
                <p>Verifications</p>
              </div>
              <a href="<?php echo DEF_BASE_URL; ?>app/verification-history" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
            </div>
          </div>

        </div>
        <!-- /.row -->

        <div class="row">
          <div class="col-md-12">
              <div class="card card-primary card-tabs">
                  <div class="card-header">
                    <h3>Recent Certificates</h3>
                  </div>
                  <div class="m-3">
                    <button class="btn btn-primary btn-sm" id="btnReloadCertificatesTable"><i class="fas fa-redo"></i> Reload</button>
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
      <!-- /.Left col -->
    </div>
    <!-- /.row (main row) -->

    </div><!-- /.container-fluid -->
</section>
<!-- /.content -->
</div>
  
<?php
$arAdditionalJs[] = <<<EOQ

EOQ;

$arAdditionalJsOnLoad[] = <<<EOQ

var certificatesTable = $('#certificatesTable').DataTable({
  processing: true,
  autoWidth: false,
  responsive: true,
  paging: false,
  info: false,
  lengthChange: false,
  ajax: baseUrl+'inc/actions?action=getcertificates&actionType=recent',
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

EOQ;

require_once DEF_DOC_ROOT_ADMIN.'inc/foot.php';
?>