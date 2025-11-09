<footer id="footer" class="footer light-background">

    <!-- <div class="container copyright text-center mt-4"> -->
    <div class="container copyright text-center">
      <p>© <?php echo date('Y'); ?> <span>Copyright</span> <strong class="px-1 sitename"><?php echo SITE_NAME; ?></strong> <span>All Rights Reserved</span></p>
      <div class="credits">
        Developed by <a href="https://wa.me/<?php echo SITE_PHONE; ?>"><strong><?php echo SITE_AUTHOR; ?></strong></a>
      </div>
    </div>

  </footer>

  <!-- Scroll Top -->
  <a href="#" id="scroll-top" class="scroll-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>

  <!-- Preloader -->
  <div id="preloader"></div>

  <!-- Vendor JS Files -->
  <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
  <script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="assets/vendor/php-email-form/validate.js"></script>
  <script src="assets/vendor/aos/aos.js"></script>
  <script src="assets/vendor/glightbox/js/glightbox.min.js"></script>
  <script src="assets/vendor/purecounter/purecounter_vanilla.js"></script>
  <script src="assets/vendor/swiper/swiper-bundle.min.js"></script>
  <!-- Bootstrap Datepicker -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.10.0/js/bootstrap-datepicker.min.js"></script>
  <!-- Toast Alert -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
  <!-- Sweet Alert -->
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.blockUI/2.70/jquery.blockUI.min.js"></script>

  <!-- Main JS File -->
  <script src="assets/js/custom.js"></script>
  <script src="assets/js/main.js"></script>

</body>

</html>

<?php
if (isset($arAdditionalJsScripts) && count($arAdditionalJsScripts) > 0)
{
  echo implode("\n", $arAdditionalJsScripts);
}
?>

<script>
<?php
if (isset($arAdditionalJs) && count($arAdditionalJs) > 0)
{
  echo implode("\n", $arAdditionalJs);
  echo "\n";
}
?>
</script>

<script>
$(document).ready(function() {

  $(document).ajaxStart(function () {
    $.blockUI({
      message: `
        <div id="globalLoader">
          <div class="spinner-border text-light" role="status"></div>
          <div class="mt-2 text-white">Processing...</div>
        </div>
      `,
      css: {
        border: 'none',
        backgroundColor: 'transparent',
        padding: 0
      },
      overlayCSS: {
        backgroundColor: '#000',
        opacity: 0.5,
        cursor: 'wait'
      },
      baseZ: 2000
    });

    $('.blockUI.blockMsg.blockPage').css({
      top: 0,
      left: 0,
      width: '100vw',
      height: '100vh',
      display: 'flex',
      alignItems: 'center',
      justifyContent: 'center',
      background: 'none'
    });
  });

  $(document).ajaxStop(function () {
    $.unblockUI();
  });

  <?php
  if (isset($arAdditionalJsOnLoad) && count($arAdditionalJsOnLoad) > 0)
  {
    echo implode("\n", $arAdditionalJsOnLoad);
  }
  ?>
});
</script>
</body>
</html>