<?php
require_once '../lib/loader.php';
$loader->load_admin();
$session->is_logged_in("login.php");

if(!checkpermission('ADMIN'))
	redirect_to('index.php');

$page_title = 'مدیر جدید';
if (isset($_POST['submit']))
{
	$username = $_POST['username'];
	$password = $_POST['password'];
	
	$type = null;
	foreach ($permission as $name=>$p)
	{
		if(isset($_POST[$name]))
		{
			$type = $type | $p; 
		}
	}
	
	if (! $username || ! $password )
		$error = 'لطفا تمامی فیلد ها را کامل کنید';
	if(!$error)
	{
		if( isset($_GET['edit']))
		{
			if($_GET['edit'] == $admin['id'] && $type != $admin['type'])
			{
				$error = 'شما نمیتوانید دسترسی خودتان را تغییر دهید!';
			}
			else
			{
				$sql = $database->prepare("UPDATE `admin` SET `username` = ?,`password` = ?, `type` = ? WHERE `id` =? LIMIT 1");
				if ($sql->execute(array($username,$password,$type,$_GET['edit'])))
					$sus = 'اطلاعات با موفقیت ویرایش شد.';
				else
					$error = 'ویرایش اطلاعات با مشکل روبرو شد!';
			}
		
		
		}
		else
		{
		
			$sql = $database->prepare("SELECT * FROM `admin` WHERE `username` = ? ");
			$sql->execute(array($username));
			if ($sql->rowCount() == 0)
			{
				$time = time();
				$sql = $database->prepare("INSERT INTO `admin` (`username`,`password`,`type`) VALUES (?,?,?)");
				if ($sql->execute(array($username,$password,$type)))
					$sus = 'اطلاعات با موفقیت ثبت شد';
				else
					$error = 'ثبت اطلاعات با مشکل روبرو شد!';
			}
			else
				$error = 'این نام کاربری موجود است!';
		}
	}
	
}
if (isset($_GET['edit']))
{
	$sql = $database->prepare("SELECT * FROM `admin` WHERE `id` = ?");
	$sql->execute(array($_GET['edit']));
	$user = $sql->fetch();
	if (! $user['id'])
	{
		redirect_to('admin.php');
	}
	
	$username = $user['username'];
	$password = $user['password'];
	$type = $user['type'];
	$submit = "update";
}

if ($error)
	$status = '<img src="../theme/img/icons/exclamation-red.png"/> <font color="#FF0000" face="Tahoma" style="font-size: 15px">' . $error . '</font>';
else if ($sus)
	$status = '<img src="../theme/img/icons/tick.png"/> <font color="green" face="Tahoma" style="font-size: 15px">' . $sus . '</font>';

if( $setting['ajax']==1 && $_GET['ajax']==2 )
{
	echo $status;
	die();
}
?>
<?php require_once $path.'theme/header.php';?>
<h1>مدیر جدید</h1>
<span id="error">
<?php if($status) echo $status;?>
</span>
<form id="ajaxform" method="post" action="admin_send.php<?php if($submit=="update") echo '?edit='.$_GET['edit'];?>" >

<table>
	<tr>
		<td><label for="username">نام کاربری</label></td>
		<td><input type="text" dir="ltr" value="<?php echo $username;?>" id="username" name="username"></td>
	</tr>
	<tr>
		<td><label for="password">کلمه عبور</label></td>
		<td><input type="text" dir="ltr" value="<?php echo $password;?>" id="password" name="password" ></td>
	</tr>
	<tr>
		<td>نوع</td>
		<td style="text-align: right">
		<?php 
		foreach ($permission as $name=>$p)
		{
			if($type & $p)
				$select = "checked";
			else
				$select = null;
			echo '<input type="checkbox" id="'.$name.'" name="'.$name.'" value="1" '.$select.'/> <label for="'.$name.'">'.$permission_name[$p].'</label><br/>';
		}
		?>
		</td>
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
					
<?php require_once $path.'theme/footer.php';?>