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
		<th>
			ردیف
			<hr>
		</th>
		<th>نام فایل
			<hr />
		</th>
		<th>نام کاربر
			<hr />
		</th>
		<th>ای پی کاربر
			<hr />
		</th>
		<th>زمان دانلود
			<hr />
		</th>
	</tr>
	<?php
	$_GET['page'] = $_GET['page'] ? (int)$_GET['page'] : 1;
	
	if (isset($_GET['user']))
	{
		$_GET['user'] = (int)$_GET['user'];
		$where = 'userid = '.$_GET['user'];
	}
	else
		$where = '1=1';
	
	$count = "SELECT COUNT(*) as count FROM `dlinfo` where $where";
	
	$count = $database->query($count)->fetch();
	
	$page = new Pagination($_GET['page'], $setting['pagelimit'], $count['count']);
	
	$users = $database->query("SELECT * FROM `dlinfo` where $where LIMIT $setting[pagelimit] OFFSET {$page->offset()}");
	$i=1;
	while ( $user = $users->fetch() )
	{
		$username = $database->query("SELECT * FROM `users` WHERE `id` = $user[userid]")->fetch();
		echo '<tr>';
		echo '<td>' . $i++ . '</td>';
		echo '<td class="ltr">' . $user['file'] . '</td>';
		echo '<td>' . $username['username'] . '</td>';
		echo '<td>' . $user['ip'] . '</td>';
		echo '<td>' . getTime($user['time']) . '</td>';
		echo '</tr>';
	}
	?>
</table>
<br/>
<div style="direction: ltr">
<?php echo $page->get_pagination($_GET['user'] ? 'user='.$_GET['user'] : null);?>
</div>
<?php require_once $path.'theme/footer.php';?>