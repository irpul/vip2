<?php
require_once '../lib/loader.php';
$loader->load_admin();
$session->is_logged_in("login.php");

if(!checkpermission('SERVER'))
	redirect_to('index.php');

$page_title = 'مدیریت سرور ها';

if ($_GET['delete'])
{
	$sql = $database->prepare("DELETE FROM `servers` WHERE id = ?");
	
	if ($sql->execute(array (
			$_GET['delete']
	)))
		$status = '<img src="theme/img/icons/tick.png"/> <font color="green" face="Tahoma" style="font-size: 15px">سرور مورد نظر با موفقیت حذف گردید</font>';
	else
		$status = '<img src="theme/img/icons/exclamation-red.png"/> <font color="#FF0000" face="Tahoma" style="font-size: 15px">حذف سرور مورد نظر با مشکل روبرو شد!</font>';
}
if( $setting['ajax']==1 && $_GET['ajax']==2 )
{
	echo $status;
	die();
}
?>
<?php require_once $path.'theme/header.php';?>
<h1>مدیریت سرور ها</h1>
<span id="error">
<?php if($status) echo $status;?>
</span>
<table style="width: 100%">
	<tr>
		<th>شناسه سرور
			<hr />
		</th>
		<th>لینک سرور
			<hr />
		</th>
		<th>مدیریت سرور
			<hr />
		</th>
	</tr>
	<?php
	$_GET['page'] = $_GET['page'] ? (int)$_GET['page'] : 1;
	$count = "SELECT COUNT(*) as count FROM `servers`";
	
	$count = $database->query($count)->fetch();
	
	$page = new Pagination($_GET['page'], $setting['pagelimit'], $count['count']);
	
	
	$servers = $database->query("SELECT * FROM `servers` LIMIT $setting[pagelimit] OFFSET {$page->offset()}");
	while ( $server = $servers->fetch() )
	{
		echo '<tr>';
		echo '<td>' . $server['id'] . '</td>';
		echo '<td class="ltr">' . $server['url'] . '</td>';
		echo '<td id="ajax"><a href="server_send.php?edit='.$server['id'].'"><img src="../theme/img/link_edit.png" title="ویرایش سرور" />
					<a onclick="if (confirm(\'پایا از حذف دسته انتخاب شده مطمئن هستید؟\')) window.location = \'server.php?delete='.$server['id'].'\';" href="#"><img src="../theme/img/link_delete.png" border="0" title="حذف سرور" /></a></td>';
		echo '</tr>';
	}
	?>
</table>
<br/>
<div style="direction: ltr">
<?php echo $page->get_pagination();?>
</div>
<?php require_once $path.'theme/footer.php';?>