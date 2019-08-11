<?php
require_once '../lib/loader.php';
$loader->load_admin();
$session->is_logged_in("login.php");

if(!checkpermission('ONLINE'))
	redirect_to('index.php');

$page_title = 'گزارش کاربران آنلاین';

?>
<?php require_once $path.'theme/header.php';?>
<h1>گزارش دانلود کاربران</h1>
<table style="width: 100%">
	<tr>
		<th>نام فایل
			<hr />
		</th>
		<th>تعداد درخواست
			<hr />
		</th>
		<th>ای پی کاربر
			<hr />
		</th>
	</tr>
	<?php
	$_GET['page'] = $_GET['page'] ? (int)$_GET['page'] : 1;
	$count = "SELECT COUNT(*) as count FROM `dlinfo` GROUP BY hash";
	
	$count = $database->query($count)->fetch();
	
	$page = new Pagination($_GET['page'], $setting['pagelimit'], $count['count']);
	
	$users = $database->query("SELECT *,COUNT(hash) as count FROM `dlinfo` GROUP BY hash ORDER BY count desc LIMIT $setting[pagelimit] OFFSET {$page->offset()}");
	while ( $user = $users->fetch() )
	{
		echo '<tr>';
		echo '<td class="ltr">' . $user['file'] . '</td>';
		echo '<td>' . $user['count'] . '</td>';
		echo '<td>' . $user['ip'] . '</td>';
		echo '</tr>';
	}
	?>
</table>
<br/>
<div style="direction: ltr">
<?php echo $page->get_pagination();?>
</div>
<?php require_once $path.'theme/footer.php';?>