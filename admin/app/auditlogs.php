<?php
require_once '../../inc/utils.php';
$pageTitle = 'Audit Logs';

$arAdditionalCSS[] = <<<EOQ
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
EOQ;

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
                        <h3>Audit Logs</h3>
                    </div>
                    <div class="m-3">
                        <button class="btn btn-primary btn-sm" id="btnReloadHistoryTable"><i class="fas fa-redo"></i> Reload</button>
                    </div>

                    <div class="m-3">
                        <div class="form-row">
                            <div class="row col-md-4">
                                <div class="form-group col-md-12">
                                    <select id="historyCategory" name="historyCategory" class="form-control">
                                        <option value="">All</option>
                                        <?php
                                        $categories = ValidCert\History\HistoryMetadata::getHistoryMetadataCategories();
                                        foreach ($categories as $category)
                                        {
                                            $categoryLabel = ucwords($category);
                                            echo <<<EOQ
                                            <option value="{$category}">{$categoryLabel}</option>
    EOQ;
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <div class="row col-md-4">
                                <div class="form-group col-md-12">
                                    <input type="text" id="historyDate" name="historyDate" class="form-control" placeholder="YYYY-MM-DD">
                                </div>
                            </div>
                            <div class="row col-md-4">
                                <div class="form-group col-md-12">
                                    <button class="btn btn-primary" id="btnFilterHistoryTable"><i class="fas fa-filter"></i> Filter</button>
                                    <button class="btn btn-secondary" id="btnClearHistoryFilter"><i class="fas fa-times"></i> Clear</button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card-body table-responsive">
                        <table id="historyTable" class="table table-bordered table-hover">
                            <thead>
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
    </section>

<!-- /.content -->
</div>
  
<?php

$arAdditionalJsScripts[] = <<<EOQ
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
EOQ;

$arAdditionalJs[] = <<<EOQ

var historyCategory = '';
var historyDate = '';

function getHistoryTableParams()
{
    historyCategory = $('#historyCategory').val();
    historyDate = $('#historyDate').val();
}

EOQ;

$currentDate = date('Y-m-d');
$arAdditionalJsOnLoad[] = <<<EOQ

var historyDateFilter = flatpickr("#historyDate", {
  dateFormat: "Y-m-d",
  maxDate: '{$currentDate}'
});

function loadHistoryTable()
{
    //Destroy existing table to remove old data
    if ($.fn.dataTable.isDataTable('#historyTable'))
    {
        $('#historyTable').DataTable().clear().destroy();
    }

    //Reinitialize DataTable with updated parameters
    var historyTableInit = $('#historyTable').DataTable({
        processing: true,
        autoWidth: false,
        responsive: true,
        ajax: {
            url: baseUrl+'inc/actions?action=gethistory',
            data: {
                category: historyCategory,
                date: historyDate
            }
        },
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
    return historyTableInit;
}

var historyTable = loadHistoryTable();

$('#btnReloadHistoryTable').on('click', function() {
    //reloadTable('historyTable');
    loadHistoryTable();
});

$('#btnFilterHistoryTable').on('click', function() {
    getHistoryTableParams();
    loadHistoryTable();
});

$('#btnClearHistoryFilter').on('click', function() {
    historyCategory = '';
    historyDate = '';
    historyDateFilter.clear();
    $('#historyCategory').val('');
    loadHistoryTable();
});

EOQ;

require_once DEF_DOC_ROOT_ADMIN.'inc/foot.php';
?>