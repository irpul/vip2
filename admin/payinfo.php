<?php
require_once '../lib/loader.php';
$loader->load_admin();
$session->is_logged_in("login.php");

if(!checkpermission('PAYINFO'))
	redirect_to('index.php');

$page_title = 'مدیریت پرداخت ها';

if ($_GET['delete'])
{
	$sql = $database->prepare("DELETE FROM `payment` WHERE payment_id = ?");
	
	if ($sql->execute(array (
			$_GET['delete']
	)))
		$status = '<img src="theme/img/icons/tick.png"/> <font color="green" face="Tahoma" style="font-size: 8pt">پرداخت مورد نظر با موفقیت حذف گردید</font>';
	else
		$status = '<img src="theme/img/icons/exclamation-red.png"/> <font color="#FF0000" face="Tahoma" style="font-size: 8pt">حذف پرداخت مورد نظر با مشکل روبرو شد!</font>';
}elseif ($_POST['delete'])
{
	if(!checkpermission('PAYINFO'))
		redirect_to('index.php');
	foreach($_POST['id'] as $id)
	{
		$sql = $database->prepare("DELETE FROM `payment` WHERE payment_id = ?");
		if ($sql->execute(array (
				$id
		)))
			$status = '<img src="theme/img/icons/tick.png"/> <font color="green" face="Tahoma" style="font-size: 15px">پرداخت مورد نظر با موفقیت حذف گردید</font>';
		else
			$status = '<img src="theme/img/icons/exclamation-red.png"/> <font color="#FF0000" face="Tahoma" style="font-size: 15px">حذف پرداخت مورد نظر با مشکل روبرو شد!</font>';
	}
}


?>
<?php require_once $path.'theme/header.php';?>
<h1>مدیریت پرداخت ها</h1>
<div class="fileinfo" style="text-align: center;">
<form method="get" action="payinfo.php">
<table style="width:100%">
<tr id="ajax">
	<td>لیست تیکت:</td>
	<td><input type="text" value="" name="from" placeholder="از تاریخ" style="width:100px"></td>
	<td> | </td>
	<td><input type="text" value="" name="to" placeholder="تا تاریخ" style="width:100px"></td>
	<td> | </td>
	<td><select style="width:60px" name="status"><option value=0>همه</option><option value=2>موفق</option><option value=1>نا موفق</option></select></td>
	<td> | </td>
	<td><input type="submit" value="فیلتر"></td>

</tr>
</table>
</form>
</div>
<?php if($status) echo $status;?>
<form method="post" action="payinfo.php?<?php echo http_build_query(array('from'=>$_GET['from'],'to'=>$_GET['to'],'page'=>$_GET['page'],'status'=>$_GET['status']))?>" class="form">
<table style="width: 100%;">
	<tr>
		<th>نام کاربری
			<hr />
		</th>
		<th>کلمه عبور
			<hr />
		</th>
		<th>ایمیل
			<hr />
		</th>
		<th>دسته
			<hr />
		</th>
		<th>ای پی
			<hr />
		</th>
		<th>زمان
			<hr />
		</th>
		<th>وضعیت
			<hr />
		</th>
		<th>مدیریت پرداخت
			<hr />
		</th>
		
	</tr>
	<?php
$status_query = '1=1 ';
	if (isset($_GET[from]) AND $_GET[from]!='')
	{
		$time_from = explode('/',$_GET[from]);

		$time_from = jDateTime::mktime(0,0,0,$time_from[1],$time_from[2],$time_from[0]);
		$status_query 	.= " AND `payment_time` > '$time_from'";
	}
	if (isset($_GET[to]) AND $_GET[to]!='')
	{
		$time_to = explode('/',$_GET[to]);
		$time_to = jDateTime::mktime(23,59,59,$time_to[1],$time_to[2],$time_to[0]);
		$status_query 	.= " AND `payment_time` < '$time_to'";
	}
	if (isset($_GET[status]) && $_GET[status])
	{
		$_GET[status] = (int) $_GET[status];
		$status_query 	.= " AND `payment_status` = '$_GET[status]'";
	}
	$_GET['page'] = $_GET['page'] ? (int)$_GET['page'] : 1;
	$count = "SELECT COUNT(*) as count FROM `payment` WHERE $status_query";
		
	$count = $database->query($count)->fetch();
		
	$page = new Pagination($_GET['page'], $setting['pagelimit'], $count['count']);
		
	$payment = $database->query("SELECT * FROM `payment` WHERE $status_query ORDER BY `payment_id` DESC LIMIT $setting[pagelimit] OFFSET {$page->offset()}");

$ps = $database->query("SELECT SUM(payment_amount) AS amount FROM `payment` WHERE $status_query ");
$ps = $ps ->fetch();
	while ( $p = $payment->fetch() )
	{

		if ($p['payment_status'] == 2) {
			$active = 'موفقیت آمیز';
			$activehtml = "<span style='color:#2ecc71'>$active</span>";
		} else {
			$active = 'ناموفق';
			$activehtml = "<span style='color:#E74C3C'>$active</span>";
		}
		$cat = $database->query("SELECT * FROM `category` WHERE `id` = $p[payment_categoryid]")->fetch();
		echo '<tr>';
		echo '<td>' . $p['payment_user'] . '</td>';
		echo '<td>' . $p['payment_password'] . '</td>';
		echo '<td>' . $p['payment_email'] . '</td>';
		echo '<td>' . $cat['title'] . '</td>';
		echo '<td>' . $p['payment_ip'] . '</td>';
		echo '<td>' . getTime($p['payment_time']) . '</td>';
		echo '<td>' . $activehtml . '</td>';
		echo '<td><a onclick="if (confirm(\'آایا از حذف پرداخت انتخاب شده مطمئن هستید؟\')) window.location = \'payinfo.php?delete='.$p['payment_id'].'\';" href="#"><img src="../theme/img/page_delete.png" border="0" title="حذف پرداخت" /></a>
				</td>';
		echo '<td><input name="id[]" value="'.$p['payment_id'].'" type="checkbox"></td>';
		echo '</tr>';
	}
	?>
</table>
<input type="submit" name="delete" value="حذف">
</form>
<br/>
<div style="direction: ltr">
<?php echo $page->get_pagination("from=$_GET[from]&to=$_GET[to]&status=$_GET[status]");?>
</div>
جمع : <?php echo $ps['amount']; ?>
<?php require_once $path.'theme/footer.php';?>