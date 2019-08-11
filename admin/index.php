<?php
require_once '../lib/loader.php';
$loader->load_admin();
$session->is_logged_in("login.php");
?>
<?php require_once $path.'theme/header.php';?>

<?php

$time = time();
$user_active = $database->query("SELECT * FROM `users` WHERE `active` = 1")->rowCount();
$user_deactive = $database->query("SELECT * FROM `users` WHERE  `active` != 1")->rowCount();
$total = $user_active + $user_deactive;

$ticket = $database->query("SELECT * FROM `ticket`")->rowCount();
$ticket_open = $database->query("SELECT * FROM `ticket` WHERE `status` = 1")->rowCount();
$ticket_answer = $database->query("SELECT * FROM `ticket` WHERE `status` = 3")->rowCount();

$spayment = $database->query("SELECT * FROM `payment` WHERE `payment_status` =2")->rowCount();
$fpayment = $database->query("SELECT * FROM `payment` WHERE `payment_status` !=2")->rowCount();
$totalpayment = $spayment + $fpayment;


$servers = $database->query("SELECT * FROM `servers`");
$user_online = '';
while($server = $servers->fetch())
{
	$users = file_get_contents($server['url']."fileinfo.php?online&secret=$secret");
	$users = json_decode($users,true);
	$user_online .= "کاربران آنلاین سرور $server[id] : ".count($users) . "<br/>";
}

$guest_online = $database->query("SELECT * FROM `dlinfo_guest` WHERE `time`>$time")->rowCount();

echo <<<HTML
<div align="right" style="font-family:BYekan">
<div class="legend">اطلاعات کلی</div>
	تعداد کل کاربران : $total <br/>
	تعداد کاربران فعال : $user_active<br/>
	تعداد کاربران غیر فعال : $user_deactive<br/>
	<br>
	$user_online
	مهمان های آنلاین : $guest_online<br/>
	<br>
	تعداد کل تیکت ها : $ticket<br/>
	<a href="admin_ticket.php?action=1">تعداد تیکت های باز : $ticket_open</a><br/>
	<a href="admin_ticket.php?action=3">تعداد تیکت های منتظر پاسخ : $ticket_answer</a><br/>
	<br>
	تعداد کل پرداخت ها : $totalpayment<br/>
	پرداخت های موفق : $spayment<br/>
	پرداخت های نا موفق : $fpayment<br/>
</div>
HTML

?>
<?php require_once $path.'theme/footer.php';?>