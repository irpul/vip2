<?php
require_once '../lib/loader.php';
$loader->load_admin();
$session->is_logged_in("login.php");

if(!checkpermission('ONLINE'))
	redirect_to('index.php');

$page_title = 'گزارش کاربران آنلاین';

if (!isset($_POST['server']))
{
	
?>
<?php require_once $path.'theme/header.php';?>
<h1>شماره سرور را وارد نمایید</h1>
<form id="" method="post" action="online.php" >
<table>
	<tr>
		<td><label for="server">شماره سرور</label></td>
		<td><input type="text" dir="ltr" id="server" name="server"></td>
	</tr>
	<tr>
		<td></td>
		<td>
			<button type="submit" name="submit" class="button gray">ذخیره</button>
			<button type="reset" name="reset" class="button red">انصراف</button>
		</td>
	</tr>
</table>
</form>
<?php require_once $path.'theme/footer.php';exit;

}

$server = (int)$_POST['server'];
$server = $database->query("SELECT * FROM `servers` WHERE `id`='$server'")->fetch();
if(!$server) redirect_to('online.php');

$users = file_get_contents($server['url']."fileinfo.php?online&secret=$secret");
$users = json_decode($users,true);
	

?>
<?php require_once $path.'theme/header.php';?>
<h1>گزارش کاربران آنلاین</h1>
<table style="width: 100%">
	<tr>
		<th>نام فایل
			<hr />
		</th>
		<th>نام کاربر
			<hr />
		</th>
		<th>ای پی کاربر
			<hr />
		</th>
	</tr>
	<?php
	
	foreach ($users as $id=>$user)
	{
		$username = $database->query("SELECT * FROM `users` WHERE `id` = $id")->fetch();
		foreach ($user as $file)
		{
			echo '<tr>';
			echo '<td class="ltr">' . $file['file'] . '</td>';
			echo '<td>' . $username['username'] . '</td>';
			echo '<td>' . $file['ip'] . '</td>';
			echo '</tr>';
		}
	}
	?>
</table>

<?php require_once $path.'theme/footer.php';?>