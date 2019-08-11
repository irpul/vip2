<?php
require_once '../lib/loader.php';
$loader->load_admin();
$session->is_logged_in("login.php");



$page_title = 'پاک کردن کش سیستم';

foreach (glob("../temp/*") as $filename) {
	unlink($filename);
}
?>
<?php require_once $path.'theme/header.php';?>
<h1>پاک کردن کش سیستم</h1>
<span id="error">
<img src="theme/img/icons/tick.png"/> <font color="green" face="Tahoma" style="font-size: 12pt">عملیات با موفقیت انجام شد.</font>
</span>
<?php require_once $path.'theme/footer.php';?>