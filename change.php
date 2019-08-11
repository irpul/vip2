<?php
require 'lib/loader.php';
$loader->load_user ();
$session->is_logged_in ( "login.php" );
$page_title = 'تغییر اطلاعات کاربری';

if(isset($_POST['submit']))
{
	try {
		if(! $_POST['email'])
		{
			throw new Exception('لطفا ایمیل را وارد نمایید');
		}
		if(filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)== false)
			throw new Exception('ایمیل وارد شده معتبر نیست');
		
		if($_POST['number'] && ( !is_numeric($_POST['number']) OR strlen($_POST['number']) < 10) )
			throw new Exception('شماره وارد شده معتبر نیست');
		
		$pw = $database->prepare("UPDATE `users` SET `email` = ? ,`mobile` = ? WHERE id= ?");
		if (!$pw->execute(array($_POST['email'],$_POST['number'],$user['id'])))
			throw new Exception('خطا در ذخیره سازی اطلاعات');
		
		$error = '<img src="theme/img/icons/tick.png"/> <font color="green" face="Tahoma" style="font-size: 12pt">اطلاعات ذخیره شد</font>';
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
<h1>تغییر اطلاعات کاربری</h1>
<span id="error">
<?php if($error) echo $error;?>
</span>
<form method="post" action="change.php" id="ajaxform">

<table>
	<tr>
		<td><label for="email">ایمیل</label></td>
		<td><input type="email" name="email" id="email" required placeholder="ایمیل اکانت خود را وارد نمایید" value="<?php echo $user['email']?>"/></td>
	</tr>
	<tr>
		<td><label for="number">شماره تماس</label></td>
		<td><input type="tel" name="number" id="number" placeholder="شماره تماس خود را وارد نمایید" value="<?php echo $user['mobile']?>"/></td>
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