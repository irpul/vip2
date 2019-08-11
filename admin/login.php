<?php
require_once '../lib/loader.php';

$loader->load_admin();
$download = true;
$page_title = 'ورود';

if (isset ( $_POST ['login'] )) {
	$username = $_POST ['username'];
	$password = $_POST ['password'];
	
	$login_user = $database->prepare("SELECT * FROM `admin` WHERE `username` = ?");
	$login_user->execute(array($username));
	
	$login_user = $login_user->fetch();
	$username_check = $login_user['username'];
	$password_check = $login_user['password'];
	
	try {
		if ($_SESSION ['img'] != $_POST ['security'])
			throw new Exception('تصویر امنیتی اشتباه است<br/>');
		
		if (! $username)
			$error = 'لطفا نام کاربری را وارد نمایید<br/>';
		if (! $password)
			$error .= 'لطفا کلمه عبور را وارد نمایید<br/>';
		if($error)
			throw new Exception($error);

		if($username != $username_check || $password != $password_check)
			throw new Exception('نام کاربری یا کلمه عبور اشتباه است<br/>');
		
		$session->admin_login($login_user['id']);

		redirect_to('index.php');
	}
	catch (Exception $e)
	{
		$error = '<img src="../theme/img/icons/exclamation-red.png"/> <font color="#FF0000" face="Tahoma" style="font-size: 8pt">'.$e->getMessage().'</font>';
	}
}
	?>
<?php require_once $path.'theme/header.php';?>
<h1>ورود به کنترل پنل مدیریت</h1>
<form action="login.php" method="post">
<?php if($error) echo $error;?>
<table>
	<tr>
		<td><label for="username">نام کاربری</label></td>
		<td><input id="username" name="username" type="text" /></td>
	</tr>
	<tr>
		<td><label for="password">رمز عبور</label></td>
		<td><input name="password" id="password" type="password" /></td>
	</tr>
	<tr>
		<td><label for="sec"></label></td>
		<td><img id="sec" border="0" src="../theme/img/img.php" /> <a href="#"
							onClick="document.getElementById('sec').src = '../theme/img/img.php?id='+Math.floor(Math.random()*11); return false"><img
							src="../theme/img/refresh.png" width="20" height="20" /></a></td>
	</tr>
	<tr>
		<td><label for="secnum">کد امنیتی</label></td>
		<td><input name="security" id="secnum" type="text" /></td>
	</tr>
</table>
				<p>
					<button type="submit" name="login" class="button gray"
						id="loginbtn">ورود</button>
					<button type="reset" name="reset" class="button red">انصراف</button>

				</p>
</form>
<?php require_once $path.'theme/footer.php';?>