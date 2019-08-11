<?php
require_once '../lib/loader.php';
$loader->load_admin();
$session->is_logged_in("login.php");

if(!checkpermission('ADMIN'))
	redirect_to('index.php');

$page_title = 'مدیریت ای پی';

if ($_GET['delete'])
{	
	$sql = $database->prepare("DELETE FROM `blockip` WHERE ip = ?");
		
	if ($sql->execute(array (
			$_GET['delete']
	)))
		$status = '<img src="theme/img/icons/tick.png"/> <font color="green" face="Tahoma" style="font-size: 12pt">IP مورد نظر با موفقیت حذف گردید</font>';
	else
		$status = '<img src="theme/img/icons/exclamation-red.png"/> <font color="#FF0000" face="Tahoma" style="font-size: 15px">حذف IP مورد نظر با مشکل روبرو شد!</font>';
}
	
if( $setting['ajax']==1 && $_GET['ajax']==2 )
{
	echo $status;
	die();
}
?>
<?php require_once $path.'theme/header.php';?>
<h1>مدیریت ای پی</h1>
<span id="error">
<?php if($status) echo $status;?>
</span>
<table style="width: 100%">
	<tr>
		<th>ای پی
			<hr />
		</th>
		<th>نوع
			<hr />
		</th>
		<th>مدیریت
			<hr />
		</th>
	</tr>
	<?php
	$_GET['page'] = $_GET['page'] ? (int)$_GET['page'] : 1;
	$count = "SELECT COUNT(*) as count FROM `admin`";
	
	$count = $database->query($count)->fetch();
	
	$page = new Pagination($_GET['page'], $setting['pagelimit'], $count['count']);
	
	
	$users = $database->query("SELECT * FROM `blockip` LIMIT $setting[pagelimit] OFFSET {$page->offset()}");
	
	while ( $user = $users->fetch() )
	{
		$type = $user['allow'] == "1" ? 'allow' : 'block';
		echo '<tr>';
		echo '<td>' . $user['ip'] . '</td>';
		echo '<td>' . $type . '</td>';
		echo '<td id="ajax">
					<a onclick="if (confirm(\'پایا از حذف مدیر انتخاب شده مطمئن هستید؟\')) window.location = \'block.php?delete='.$user['ip'].'\';" href="#"><img src="../theme/img/news_delete.png" border="0" title="حذف ای پی" /></a>
				</td>';
		echo '</tr>';
	}
	?>
</table>
<br/>
<div style="direction: ltr">
<?php echo $page->get_pagination();?>
</div>
<?php require_once $path.'theme/footer.php';?>