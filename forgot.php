<?php
require 'lib/loader.php';
$loader->load_user ();
if($session->is_logged_in ())
	redirect_to('index.php');
$page_title = 'درخواست کلمه عبور جدید';
$download = true;
if (isset ( $_REQUEST['hash'] )) 
{
	$forgot = $database->prepare("SELECT * FROM `temp` WHERE `hash` = ?");
	$forgot->execute(array($_REQUEST['hash']));
	
	if ( $forgot->rowCount() == 1) {
		$forgot = $forgot->fetch();
		$id = $forgot['userid'];
		if (isset ( $_POST ['pass'] ) && isset ( $_POST ['passt'] ))
			$action = "change-pass-send";
		else
			$action = "change-pass";
		
		$form = '<input type="hidden" name="hash" value="'.$_REQUEST['hash'].'">
						<table >
						<tr>
							<td><label for="pass">پسورد جدید</label></td>
							<td><input id="pass" name="pass" type="password" /></td>
						</tr>
						<tr>
							<td><label for="passt">تکرار پسورد جدید</label></td>
							<td><input name="passt" id="passt" type="password" /></td>
						</tr>
						</table>';
		
		switch ($action) {
			case "change-pass" :
				$title = 'تغییر رمز عبور';
				break;
			case "change-pass-send" :
				$title = "تغییر رمز عبور";
				$pass = $_POST ['pass'];
				$passt = $_POST ['passt'];
				if ($pass and $passt) {
					if ($pass != $passt)
						$error = "پسورد جدید با تکرار آن همخوانی ندارد";
					else {
						$forgot = $database->prepare("UPDATE `users` SET `password` = ? WHERE `id`=$id");
						
						if ($forgot->execute(array($pass))) {
							$database->exec("DELETE FROM `temp` WHERE `userid` = $id");
							$form = '<div class="success msg">رمزعبور شما با موفقیت ویرایش شد<br>
									هم اکنون میتونید روی لینک زیر کلیک کنید تا به صفحه اصلی منتقل شوید<br>
									<a href="login.php">صفحه اصلی</a></div>';
							$succ = true;
						} else {
							$error = 'ویرایش اطلاعات با مشکل روبرو شد!';
						}
					}
				} else {
					$error = "لطفا تمامی فیلد ها را کامل کنید";
				}
				if ($error)
					$error = '<img src="theme/img/icons/exclamation-red.png"/><font color="#FF0000" face="Tahoma" style="font-size: 8pt">' . $error . '</font>';
				break;
		}
	}
	else
		redirect_to("login.php");
}
elseif (isset ( $_POST ['forgot'] )) {
	$title = 'درخواست کلمه عبور جدید';
	$username =$_POST ['username'];
	$email = $_POST ['email'];
	
	try {
		if ($_SESSION ['img'] != $_POST ['security'])
		throw new Exception('كد امنيتي اشتباه است');
		if ( ! $username || ! $email )
			throw new Exception('لطفا تمامي فيلد ها را كامل كنيد');
		
		$forgot = $database->prepare("SELECT * FROM `users` WHERE `username` = ? AND `email` = ?");

		$forgot->execute(array($username,$email));
		if ($forgot->rowCount() != 1)
		{
			throw new Exception('اطلاعات وارد شده صحیح نیست');
		}
		$forgot = $forgot->fetch();
		$code = hash ( "sha256", uniqid ( mt_rand(), TRUE ) );
		
		$database->exec("INSERT INTO `temp` (`userid`,`hash` ) VALUES ('$forgot[id]','$code')");
		
		$code = "http://".$_SERVER ['HTTP_HOST'] .SITE_PATH. $_SERVER ['PHP_SELF'] . "?hash=$code";
		
			$time = jDateTime::date("Y/n/j",time());
			$param = array(
					'username' => $forgot['username'],
					'time' => $time,
					'code' => $code,
		
			);
			email($email,"forgot",$param);
		
			unset($_SESSION ['img']);
			$error = '<img src="theme/img/icons/tick.png"/> <font color="green" face="Tahoma" style="font-size: 12pt">نحوه باز یابی پسورد به ایمیل شما ارسال شد</font>';
			
	}
	catch (Exception $e)
	{
		$error = '<img src="theme/img/icons/exclamation-red.png"/> <font color="#FF0000" face="Tahoma" style="font-size: 12pt">'.$e->getMessage().'</font>';
	}
}
else 
	$title = 'درخواست کلمه عبور جدید';
?>
<?php require_once 'theme/header.php';?>
<h1><?php echo $title;?></h1>
<form action="forgot.php" method="post">
<?php if($error) echo $error;?>
<?php if($form) echo $form;
else
{?>
<table >
	<tr>
		<td><label for="username">نام کاربری</label></td>
		<td><input id="username" name="username" type="text" required placeholder="نام کاربری خود را وارد نمایید"/></td>
	</tr>
	<tr>
		<td><label for="email">ایمیل</label></td>
		<td><input name="email" id="email" type="email" required placeholder="ایمیل اکانت خود را وارد نمایید"/></td>
	</tr>
	<tr>
		<td><label for="sec"></label></td>
		<td><img id="sec" border="0" src="theme/img/img.php" /> <a href="#"
							onClick="document.getElementById('sec').src = 'theme/img/img.php?id='+Math.floor(Math.random()*11); return false"><img
							src="theme/img/refresh.png" width="20" height="20" /></a></td>
	</tr>
	<tr>
		<td><label for="secnum">کد امنیتی</label></td>
		<td><input name="security" id="secnum" type="text" required placeholder="عدد تصویر بالا را وارد نمایید" /></td>
	</tr>
</table>
<?php }
if(!isset($succ)) {?>
<p>
	<button type="submit" name="forgot" class="button gray" id="loginbtn">ارسال</button>
	<button type="reset" name="reset" class="button red">انصراف</button>
</p>
<?php } require_once 'theme/footer.php';?>