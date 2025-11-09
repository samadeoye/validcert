<script src="assets/appassets/plugins/jquery/jquery.min.js"></script>
<script src="assets/appassets/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="assets/appassets/assets/js/adminlte.min.js"></script>
<script src="assets/appassets/plugins/toastr/toastr.min.js"></script>
<script src="assets/appassets/assets/js/functions.js"></script>

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
});
</script>

</body>
</html>