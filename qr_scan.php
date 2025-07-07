<?php
require_once 'includes/header.php';
?>
<h2>Сканирование QR</h2>
<div id="reader" style="width:300px"></div>
<script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>
<script>
function onScanSuccess(decodedText) {
  window.location.href = decodedText;
}
const html5QrCode = new Html5Qrcode("reader");
Html5Qrcode.getCameras().then(cameras => {
  if (cameras && cameras.length) {
    html5QrCode.start({ facingMode: "environment" }, { fps: 10, qrbox: 250 }, onScanSuccess);
  }
}).catch(err => {
  console.error(err);
});
</script>
<?php
require_once 'includes/footer.php';
?>
