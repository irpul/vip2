<?php
require 'lib/loader.php';
$loader->load_user ();
$session->is_logged_in ( "login.php" );
$page_title = 'تغییر کلمه عبور';

if(isset($_POST['submit']))
{
	try {
		if(! $_POST['oldpw'])
		{
			throw new Exception('کلمه عبور قدیمی را وارد نمایید');
		}
		if(! $_POST['newpw'])
			throw new Exception('کلمه عبور جدید را وارد نمایید');
		if($_POST['newpw'] != $_POST['newpwr'])
			throw new Exception('کلمه عبور جدید با تکرار ان برابر نیست');
		
		if($_POST['oldpw'] != $user['password'])
			throw new Exception('کلمه عبور قدیمی اشتباه است');
		
		$pw = $database->prepare("UPDATE `users` SET `password` = ? WHERE id= ?");
		if (!$pw->execute(array($_POST['newpw'],$user['id'])))
			throw new Exception('خطا در ذخیره سازی اطلاعات');
		
		$error = '<img src="theme/img/icons/tick.png"/> <font color="green" face="Tahoma" style="font-size: 12pt">کلمه عبور تغییر یافت</font>';
	}
	catch (Exception $e)
	{
		
		$error = '<img src="theme/img/icons/exclamation-red.png"/> <font color="#FF0000" face="Tahoma" style="font-size: 12pt">'.$e->getMessage().'</font>';
	}
}

if( $setting['ajax']==1 && $_GET['ajax']==2 )
{
	echo $error;
	die();
}

?>
<?php require_once 'theme/header.php';?>
<h1>تغییر کلمه عبور</h1>
<span id="error">
<?php if($error) echo $error;?>
</span>
<form method="post" action="changepw.php" id="ajaxform">

<table>
	<tr>
		<td><label for="oldpw">کلمه عبور قدیمی</label></td>
		<td><input type="password" name="oldpw" id="oldpw" required placeholder="کلمه عبور قدیمی را وارد نمایید"/></td>
	</tr>
	<tr>
		<td><label for="newpw">کلمه عبور جدید:</label></td>
		<td><input type="password" name="newpw" id="newpw" required placeholder="کلمه عبور جدید را وارد نمایید"/></td>
	</tr>
	<tr>
		<td><label for="newpwr">تکرار کلمه عبور جدید:</label></td>
		<td><input type="password" name="newpwr" id="newpwr" required placeholder="کلمه عبور جدید را دوباره وارد نمایید"/></td>
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
<?php require_once 'theme/footer.php';?>