<?php
require_once '../lib/loader.php';
$loader->load_admin();
$session->is_logged_in("login.php");


if(!checkpermission('EMAIL'))
	redirect_to('index.php');

$page_title = 'مدیریت لیست خبرنامه ها';

if ($_GET['delete'])
{
	$sql = $database->prepare("DELETE FROM `newslist` WHERE id = ?");
	
	if ($sql->execute(array ($_GET['delete'])))
	{
		$sql = $database->prepare("DELETE FROM `job` WHERE newslistid = ?");
		$sql->execute(array ($_GET['delete']));
		$status = '<img src="theme/img/icons/tick.png"/> <font color="green" face="Tahoma" style="font-size: 15pt">خبرنامه مورد نظر با موفقیت حذف گردید</font>';
	}
	else
		$status = '<img src="theme/img/icons/exclamation-red.png"/> <font color="#FF0000" face="Tahoma" style="font-size: 15pt">حذف خبرنامه مورد نظر با مشکل روبرو شد!</font>';
}
?>
<?php require_once $path.'theme/header.php';?>
<h1>مدیریت دسته ها</h1>
<?php if($status) echo $status;?>
<table style="width: 100%">
	<tr>
		<th>شناسه خبرنامه
			<hr />
		</th>
		<th>موضوع
			<hr />
		</th>
		<th>تعداد
			<hr />
		</th>
		<th>ارسال شده
			<hr />
		</th>
		<th>مدیریت
			<hr />
		</th>
	</tr>
	<?php
	$_GET['page'] = $_GET['page'] ? (int)$_GET['page'] : 1;
	$count = "SELECT COUNT(*) as count FROM `newslist`";
	
	$count = $database->query($count)->fetch();
	
	$page = new Pagination($_GET['page'], $setting['pagelimit'], $count['count']);
	
	$category = $database->query("SELECT * FROM `newslist` LIMIT $setting[pagelimit] OFFSET {$page->offset()}");
	while ( $cat = $category->fetch() )
	{
		echo '<tr>';
		echo '<td>' . $cat['id'] . '</td>';
		echo '<td>' . $cat['subject'] . '</td>';
		echo '<td>' . $cat['number'] . '</td>';
		echo '<td>' . $cat['sent'] . '</td>';
		echo '<td id="ajax">
					<a onclick="if (confirm(\'پایا از حذف خبرنامه انتخاب شده مطمئن هستید؟\')) window.location = \'newslist.php?delete='.$cat['id'].'\';" href="#"><img src="../theme/img/page_delete.png" border="0" title="حذف دسته" /></a></td>';
		echo '</tr>';
	}
	?>
</table>
<br/>
<div style="direction: ltr">
<?php echo $page->get_pagination();?>
</div>
<?php require_once $path.'theme/footer.php';?>