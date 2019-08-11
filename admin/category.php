<?php
require_once '../lib/loader.php';
$loader->load_admin();
$session->is_logged_in("login.php");


if(!checkpermission('CATEGORY'))
	redirect_to('index.php');

$page_title = 'مدیریت دسته ها';

if ($_GET['delete'])
{
	$sql = $database->prepare("DELETE FROM `category` WHERE id = ?");
	
	if ($sql->execute(array (
			$_GET['delete']
	)))
		$status = '<img src="theme/img/icons/tick.png"/> <font color="green" face="Tahoma" style="font-size: 15pt">دسته مورد نظر با موفقیت حذف گردید</font>';
	else
		$status = '<img src="theme/img/icons/exclamation-red.png"/> <font color="#FF0000" face="Tahoma" style="font-size: 15pt">حذف دسته مورد نظر با مشکل روبرو شد!</font>';
}
?>
<?php require_once $path.'theme/header.php';?>
<h1>مدیریت دسته ها</h1>
<?php if($status) echo $status;?>
<table style="width: 100%">
	<tr>
		<th>شناسه دسته
			<hr />
		</th>
		<th>عنوان دسته
			<hr />
		</th>
		<th>مدیریت دسته
			<hr />
		</th>
	</tr>
	<?php
	$_GET['page'] = $_GET['page'] ? (int)$_GET['page'] : 1;
	$count = "SELECT COUNT(*) as count FROM `category`";
	
	$count = $database->query($count)->fetch();
	
	$page = new Pagination($_GET['page'], $setting['pagelimit'], $count['count']);
	
	$category = $database->query("SELECT * FROM `category` LIMIT $setting[pagelimit] OFFSET {$page->offset()}");
	while ( $cat = $category->fetch() )
	{
		echo '<tr>';
		echo '<td>' . $cat['id'] . '</td>';
		echo '<td>' . $cat['title'] . '</td>';
		echo '<td id="ajax"><a href="category_send.php?edit='.$cat['id'].'"><img src="../theme/img/page_edit.png" title="ویرایش دسته" />
					<a onclick="if (confirm(\'پایا از حذف دسته انتخاب شده مطمئن هستید؟\')) window.location = \'category.php?delete='.$cat['id'].'\';" href="#"><img src="../theme/img/page_delete.png" border="0" title="حذف دسته" /></a></td>';
		echo '</tr>';
	}
	?>
</table>
<br/>
<div style="direction: ltr">
<?php echo $page->get_pagination();?>
</div>
<?php require_once $path.'theme/footer.php';?>