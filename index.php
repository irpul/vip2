<?php
require_once('lib/loader.php');

$loader->load_user ();
$session->is_logged_in ( "login.php" );
$category = $database->query("SELECT `title` FROM `category` WHERE id = $user[categoryid]")->fetch();
$category = $category['title'];
?>
<?php require_once 'theme/header.php';?>
<h1>اطلاعات اکانت شما</h1>
<div class="legend"></div>
<table style="width: 100%">
	<tr>
		<td>نام کاربری :</td>
		<td><?php echo $user['username'];?></td>
	</tr>
	<tr>
		<td>تاریخ ایجاد حساب :</td>
		<td><?php echo getTime($user['starttime']);?></td>
	</tr>
	<tr>
		<td>تاریخ پایان اعتبار :</td>
		<td><?php echo getTime($user['endtime']);?></td>
	</tr>
	<tr>
		<td>وضعیت :</td>
		<td><?php echo $user['active']==1 ? 'فعال' : 'غیر فعال';?></td>
	</tr>
	<tr>
		<td>دسته :</td>
		<td><?php echo $category?></td>
	</tr>
	<tr>
		<td>لینک معرفی :</td>
		<td><?php echo "http://".$_SERVER ['HTTP_HOST'] .SITE_PATH.'payment.php?ref='.$user['id'];?></td>
	</tr>
</table>
<?php require_once 'theme/footer.php';?>