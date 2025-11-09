<?php
require_once '../../../inc/utils.php';

$modalId = isset($_REQUEST['modalId']) ? trim($_REQUEST['modalId']) : 'defaultModal';

$title = 'Education / Professional Levels';
$formId = 'importCertificatesForm';
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
        <div class="card-header bg-primary text-white">
        <h5 class="mb-0">Expected Levels Format</h5>
        </div>
        <div class="card-body">
            <p>Below is the format your certificate import levels should follow:</p>

            <div class="table-responsive">
                <table class="table table-bordered table-striped align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Abbreviation</th>
                            <th>Description</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $levels = ValidCert\EducationLevel\EducationLevelFunctions::getEducationLevels([
                            'id', 'title', 'abbr'
                        ]);
                        foreach ($levels as $level)
                        {
                            echo <<<EOQ
                            <tr>
                                <td>{$level['abbr']}</td>
                                <td>{$level['title']}</td>
                            </tr>
EOQ;
                        }
                        ?>
                    </tbody>
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

});

</script>
