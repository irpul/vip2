<?php
require_once '../lib/loader.php';
$loader->load_admin();
$session->is_logged_in("login.php");

if(!checkpermission('TICKET'))
	redirect_to('index.php');

$page_title = 'مدیریت اکانت ها';

if ($_GET['delete'])
{
	if(!checkpermission('ACCOUNT_SEND'))
		redirect_to('index.php');
	$sql = $database->prepare("DELETE FROM `users` WHERE id = ?");
	
	if ($sql->execute(array (
			$_GET['delete']
	)))
		$status = '<img src="theme/img/icons/tick.png"/> <font color="green" face="Tahoma" style="font-size: 15px">اکانت مورد نظر با موفقیت حذف گردید</font>';
	else
		$status = '<img src="theme/img/icons/exclamation-red.png"/> <font color="#FF0000" face="Tahoma" style="font-size: 15px">حذف اکانت مورد نظر با مشکل روبرو شد!</font>';
}elseif ($_POST['delete'])
{
	if(!checkpermission('ACCOUNT_SEND'))
		redirect_to('index.php');
	foreach($_POST['id'] as $id)
	{
		$sql = $database->prepare("DELETE FROM `users` WHERE id = ?");
		if ($sql->execute(array (
				$id
		)))
			$status = '<img src="theme/img/icons/tick.png"/> <font color="green" face="Tahoma" style="font-size: 15px">اکانت مورد نظر با موفقیت حذف گردید</font>';
		else
			$status = '<img src="theme/img/icons/exclamation-red.png"/> <font color="#FF0000" face="Tahoma" style="font-size: 15px">حذف اکانت مورد نظر با مشکل روبرو شد!</font>';
	}
}
elseif($_GET['active'])
{
	$sql = $database->prepare("UPDATE `users` SET `active` = (`active`+1)%2 WHERE id = ?");
	
	if ($sql->execute(array (
			$_GET['active']
	)))
		$status = '<img src="theme/img/icons/tick.png"/> <font color="green" face="Tahoma" style="font-size: 15px">اکانت مورد نظر با موفقیت تغییر یافت</font>';
	else
		$status = '<img src="theme/img/icons/exclamation-red.png"/> <font color="#FF0000" face="Tahoma" style="font-size: 15px">تغییر اکانت مورد نظر با مشکل روبرو شد!</font>';
}elseif($_POST['active'])
{
	foreach($_POST['id'] as $id)
	{
	
		$sql = $database->prepare("UPDATE `users` SET `active` = 1 WHERE id = ?");
		
		if ($sql->execute(array (
				 $id
		)))
			$status = '<img src="theme/img/icons/tick.png"/> <font color="green" face="Tahoma" style="font-size: 15px">اکانت مورد نظر با موفقیت تغییر یافت</font>';
		else
			$status = '<img src="theme/img/icons/exclamation-red.png"/> <font color="#FF0000" face="Tahoma" style="font-size: 15px">تغییر اکانت مورد نظر با مشکل روبرو شد!</font>';
	}
}elseif($_POST['deactive'])
{
	foreach($_POST['id'] as $id)
	{
	
		$sql = $database->prepare("UPDATE `users` SET `active` = 0 WHERE id = ?");
		
		if ($sql->execute(array (
				 $id
		)))
			$status = '<img src="../theme/img/icons/tick.png"/> <font color="green" face="Tahoma" style="font-size: 15px">اکانت مورد نظر با موفقیت تغییر یافت</font>';
		else
			$status = '<img src="../theme/img/icons/exclamation-red.png"/> <font color="#FF0000" face="Tahoma" style="font-size: 15px">تغییر اکانت مورد نظر با مشکل روبرو شد!</font>';
	}
}elseif($_POST['credit'])
{
	if(!checkpermission('ACCOUNT_SEND'))
		redirect_to('index.php');
	if(add_credit($_POST['credit']*24*60*60))
		$cstatus = '<img src="../theme/img/icons/tick.png"/> <font color="green" face="Tahoma" style="font-size: 15px">به تمام کاربران مقدار '.$_POST['credit'].' روز اضافه شد</font>';
	else
		$cstatus = '<img src="../theme/img/icons/exclamation-red.png"/> <font color="#FF0000" face="Tahoma" style="font-size: 15px">تغییرات با خطا مواجه شد</font>';
		
}elseif(isset($_GET['unbann']))
{
	$sql = $database->prepare("DELETE FROM `dlinfo` WHERE userid = ?");
	
	if ($sql->execute(array (
			$_GET['unbann']
	)))
		$status = '<img src="../theme/img/icons/tick.png"/> <font color="green" face="Tahoma" style="font-size: 15px">ای پی اکانت مورد نظر با موفقیت حذف گردید</font>';
	else
		$status = '<img src="../theme/img/icons/exclamation-red.png"/> <font color="#FF0000" face="Tahoma" style="font-size: 15px">حذف اکانت مورد نظر با مشکل روبرو شد!</font>';
}
if( $setting['ajax']==1 && $_GET['ajax']==2 )
{
	echo $cstatus;
	die();
}
?>
<?php require_once $path.'theme/header.php';?>
<h1>مدیریت اکانت ها</h1>
<?php if(checkpermission('ACCOUNT_SEND')):?>
<span id="error">
<?php if($cstatus) echo $cstatus;?>
</span>
<script type="text/javascript">
function p_confirm()
{
	var r=confirm("ایا مطمئن هستید؟")
	if (r==false)
	{
		return false;
	}
}
</script>
<form method="post" id="ajaxform" action="account.php" onsubmit="return p_confirm();" class="title">
<label for="credit">اضافه کردن تعداد روز به تمام اعضا</label> <input type="text" name="credit" id="credit"/>
<input type="submit" name="addc" value="اضافه کردن" >
</form>

<div class="legend"></div>
<?php endif;?>
<?php if($status) echo $status;?>

<form method="get" id="ajaxform" action="account.php" class="title">
<label for="search">جستجو</label> <input type="text" name="search" id="search"/>
<input type="submit" value="جستجو" >
</form>

<form method="post" action="account.php?<?php echo http_build_query(array('page'=>$_GET['page'],'search'=>$_GET['search']))?>" class="form">
<table style="width: 100%">
	<tr>
		<th>شناسه اکانت
			<hr />
		</th>
		<th>نام کاربری
			<hr />
		</th>
		<th>تاریخ اتمام
			<hr />
		</th>
		<th>ایمیل
			<hr />
		</th>
		<th>وضعیت
			<hr />
		</th>
		<th>گزارش گیری
			<hr />
		</th>
		<th>مدیریت اکانت
			<hr />
		</th>
		<th>پاک کردن IP
			<hr />
		</th>
	</tr>
	<?php
	if ( isset($_GET['search']))
	{
		$_GET['page'] = $_GET['page'] ? (int)$_GET['page'] : 1;
		$count = "SELECT COUNT(*) as count FROM `users` WHERE `username` LIKE ?";
		$count = $database->prepare($count);
		$count->execute(array('%'.$_GET['search'].'%'));
		$count = $count->fetch();
		
		$page = new Pagination($_GET['page'], $setting['pagelimit'], $count['count']);
		
		
		$users = $database->prepare("SELECT * FROM `users` WHERE `username` LIKE ? OR `email` LIKE ? LIMIT $setting[pagelimit] OFFSET {$page->offset()}");
		$users->execute(array('%'.$_GET['search'].'%','%'.$_GET['search'].'%'));
		$search = "&search=".$_GET['search'];
	}
	else
	{
		$_GET['page'] = $_GET['page'] ? (int)$_GET['page'] : 1;
		$count = "SELECT COUNT(*) as count FROM `users`";
		
		$count = $database->query($count)->fetch();
		
		$page = new Pagination($_GET['page'], $setting['pagelimit'], $count['count']);
		
		$search = null;
		$users = $database->query("SELECT * FROM `users` LIMIT $setting[pagelimit] OFFSET {$page->offset()}");
	}
	$search .= '&page='.$_GET['page'];
	while ( $user = $users->fetch() )
	{
		if ($user['active'] == 1) {
			$active = 'غیر فعال کردن';
			$activehtml = "<span style='color:#2ecc71'>فعال</span>";
		} else {
			$active = 'فعال کردن';
			$activehtml = "<span style='color:#E74C3C'>غیر فعال</span>";
		}
		echo '<tr>';
		echo '<td>' . $user['id'] . '</td>';
		echo '<td>' . $user['username'] . '</td>';
		echo '<td>' . getTime($user['endtime']) . '</td>';
		echo '<td>' . $user['email'] . '</td>';
		echo '<td>' . $activehtml . '</td>';
		echo '<td id="ajax"><a href="dlinfo.php?user='.$user['id'].'"><i class="fa fa-list" title="لیست فایل های دانلود شده"></i>
				</td>';
		echo '<td id="ajax">';
		if(checkpermission('ACCOUNT_SEND')):
		echo '<a href="account_send.php?edit='.$user['id'].'"><i class="fa fa-user-plus"  title="ویرایش اکانت" ></i>
					<a onclick="if (confirm(\'پایا از حذف اکانت انتخاب شده مطمئن هستید؟\')) window.location = \'account.php?delete='.$user['id'].$search.'\';" href="#"><i class="fa fa-user-times" title="حذف اکانت"></i> </a>';
		endif;
		echo '<a onclick="if (confirm(\'آیا از '.$active.'  اکانت مطمئن هستید؟\')) window.location = \'account.php?active='.$user['id'].$search.'\';" href="#"><i class="fa fa-ban"  title="'.$active.'"></i> </a>
				</td>';
		echo '<td id="ajax"><a href="account.php?unbann='.$user['id'].$search.'"><i class="fa fa-exclamation-circle" title="پاک کردن ای پی"></i>
				</td>';
		echo '<td><input name="id[]" value="'.$user['id'].'" type="checkbox"></td>';
		echo '</tr>';
	}
	?>
</table>
<input type="submit" name="delete" value="حذف">
<input type="submit" name="active" value="فعال سازی">
<input type="submit" name="deactive" value="غیر فعال سازی">
</form>
<br/>
<div style="direction: ltr">
<?php echo $page->get_pagination($search ? "search=".$_GET['search'] : null);?>
</div>
<?php require_once $path.'theme/footer.php';?>