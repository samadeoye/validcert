<!-- /.content-wrapper -->
<footer class="main-footer">
  <p>Copyright © <?php echo date('Y');?> All Rights Reserved. <?php echo SITE_NAME;?></p>
</footer>

  <!-- Control Sidebar -->
  <aside class="control-sidebar control-sidebar-dark">
    <!-- Control sidebar content goes here -->
  </aside>
  <!-- /.control-sidebar -->
</div>
<!-- ./wrapper -->

<!-- jQuery -->
<script src="assets/appassets/plugins/jquery/jquery.min.js"></script>
<!-- jQuery UI 1.11.4 -->
<script src="assets/appassets/plugins/jquery-ui/jquery-ui.min.js"></script>
<!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
<script>
  $.widget.bridge('uibutton', $.ui.button)
</script>
<!-- Bootstrap 4 -->
<script src="assets/appassets/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- ChartJS -->
<script src="assets/appassets/plugins/chart.js/Chart.min.js"></script>
<!-- Sparkline -->
<!-- <script src="plugins/sparklines/sparkline.js"></script> -->
<!-- JQVMap -->
<script src="assets/appassets/plugins/jqvmap/jquery.vmap.min.js"></script>
<script src="assets/appassets/plugins/jqvmap/maps/jquery.vmap.usa.js"></script>
<!-- jQuery Knob Chart -->
<script src="assets/appassets/plugins/jquery-knob/jquery.knob.min.js"></script>
<!-- daterangepicker -->
<script src="assets/appassets/plugins/moment/moment.min.js"></script>
<script src="assets/appassets/plugins/daterangepicker/daterangepicker.js"></script>
<!-- Tempusdominus Bootstrap 4 -->
<script src="assets/appassets/plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js"></script>
<!-- Summernote -->
<script src="assets/appassets/plugins/summernote/summernote-bs4.min.js"></script>
<!-- overlayScrollbars -->
<script src="assets/appassets/plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js"></script>
<!-- AdminLTE App -->
<script src="assets/appassets/assets/js/adminlte.js"></script>
<!-- <script src="assets/js/pages/dashboard.js"></script> -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="assets/appassets/plugins/toastr/toastr.min.js"></script>
<script src="assets/appassets/assets/js/functions.js"></script>

<script src="assets/appassets/plugins/datatables/jquery.dataTables.min.js"></script>
<script src="assets/appassets/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
<script src="assets/appassets/plugins/datatables-responsive/js/dataTables.responsive.min.js"></script>
<script src="assets/appassets/plugins/datatables-responsive/js/responsive.bootstrap4.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/autonumeric@4.5.4/dist/autoNumeric.min.js"></script>

<?php
if (isset($arAdditionalJsScripts) && count($arAdditionalJsScripts) > 0)
{
  echo implode(PHP_EOL, $arAdditionalJsScripts);
}
?>

<script>
var baseUrl = '<?php echo DEF_BASE_URL; ?>';
<?php
if (isset($arAdditionalJs) && count($arAdditionalJs) > 0)
{
  echo implode(PHP_EOL, $arAdditionalJs);
}
?>
</script>

<script>
$(document).ready(function() {
  <?php
  if (isset($arAdditionalJsOnLoad) && count($arAdditionalJsOnLoad) > 0)
  {
    echo implode(PHP_EOL, $arAdditionalJsOnLoad);
  }
  ?>
  $('#btnLogoutSidebar').click(function()
  {
    doOpenLogoutModal('admin');;
  });
});
</script>

</body>
</html>