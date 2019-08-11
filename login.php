<?php
require 'lib/loader.php';

$loader->load_user();
$download = true;
$page_title = 'ورود';
if (isset ( $_POST ['login'] )) {
	$username = $_POST ['username'];
	$password = $_POST ['password'];
	
	$login_user = $database->prepare("SELECT * FROM `users` WHERE `username` = ?");
	$login_user->execute(array($username));
	
	$login_user = $login_user->fetch();
	
	$username_check = $login_user ['username'];
	$password_check = $login_user ['password'];
	
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
		
		if(isset($_POST['remember']))
		{
			$key = hash ( "sha256","password".$secret."username");
			setcookie($key,hash ( "sha512",$password.$secret),time()+60*60*24*7);
			setcookie("username",$username_check,time()+60*60*24*7);
		}
		$session->user_login($login_user['id']);
		if (isset($_GET['file']))
			redirect_to($_GET['file']);

		redirect_to('index.php');
	}
	catch (Exception $e)
	{
		$error = '<div style="font-size: 8pt;text-align:center;color:#FF0000">'.$e->getMessage().'</div>';
	}
}
	?>
<?php require_once 'theme/header.php';?>
<h1>ورود به کنترل پنل کاربری</h1>
<form action="login.php<?php if (isset($_GET['file'])) echo '?file='.urlencode($_GET['file'])?>" method="post" id="login">
<?php if($error) echo $error;?>
<table>
	<tr>
		<td><label for="username">نام کاربری</label></td>
		<td><input id="username" name="username" type="text"  autofocus placeholder="نام کاربری خود را وارد نمایید" required/></td>
	</tr>
	<tr>
		<td><label for="password">رمز عبور</label></td>
		<td><input name="password" id="password" type="password" placeholder="کلمه عبور خود را وارد نمایید" required/></td>
	</tr>
	<tr>
		<td><label for="remember">من را به خاطر بسپار</label></td>
		<td><input name="remember"  type="checkbox" id="remember"/></td>
	</tr>
	<tr>
		<td><label for="sec"></label></td>
		<td><img id="sec" border="0" src="theme/img/img.php" /> <a href="#"
							onClick="document.getElementById('sec').src = 'theme/img/img.php?id='+Math.floor(Math.random()*11); return false"><img
							src="theme/img/refresh.png" width="20" height="20" /></a></td>
	</tr>
	<tr>
		<td><label for="secnum">کد امنیتی</label></td>
		<td><input name="security" id="secnum" type="text" placeholder="عدد تصویر بالا را وارد نمایید" required/></td>
	</tr>
</table>
<p>
	<button type="submit" name="login" class="button gray" id="loginbtn">ورود</button>
</p>
<p><a href="forgot.php">در صورتی که رمز خود را فراموش کردید کلیک کنید</a></p>
</form>
<?php if($setting['dltext']):?>
<a href="payment.php">
<div class="fileinfo" style="text-align: center">
<?php echo $setting['dltext']?>
</div>
</a>
<?php endif;?>
<?php require_once 'theme/footer.php';?>