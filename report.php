<?php
require 'lib/loader.php';
$loader->load_user ();
$login = $session->is_logged_in ();
$download = true;

if(!isset($_GET['file']) || !isset($_GET['server']) )
redirect_to('index.php');
if(isset($_POST['submit']) )
{
	if(isset($_POST['link']) && $_SESSION ['img'] == $_POST['sec'] )
	{	
		$m = send_mail($setting['replyto'],$setting['name'],$setting['reportsubject'],"لینک : ".$_POST['link']. "<br>".$_POST['text']."<br> ایمیل :".$_POST['email']);
		if($m===true)
			$error = '<img src="theme/img/icons/tick.png"/><font color="green" face="Tahoma" style="font-size: 12pt">درخواست شما ارسال شد</font>';
	}
	else
		$error = '<img src="theme/img/icons/exclamation-red.png"/> <font color="#FF0000" face="Tahoma" style="font-size: 12pt">خطا در اطلاعات ورودی</font>';
}
$_GET['file'] =  htmlspecialchars($_GET['file']);
$_GET['server'] =  htmlspecialchars($_GET['server']);
	

$page_title = 'گزارش خرابی لینک';
?>
<?php require_once 'theme/header.php';?>
<h1>گزارش خرابی لینک <?php echo $_GET['file'];?></h1>
<?php if($error) echo $error;?>
<form method="post">
<table style="width: 100%">
	<tr>
		<td><label for="email">ایمیل شما</label></td>
		<td><input id="email" name="email" type="email" required placeholder="لطفا ایمیل خود را وارد نمایید" value="<?php if($user) echo $user['email'].'" readonly="';?>" /></td>
	</tr>
	<tr>
		<td><label for="link">لینک فیلم</label></td>
		<td><input name="link" id="link" type="url" class="ltr" required value="<?php echo "http://".$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF'].'?file='.$_GET['file'].'&server='.$_GET['server']?>"/></td>
	</tr>
	<tr>
		<td><label for="text">توضیحات</label></td>
		<td><textarea name="text" placeholder="توضحیات خود را وارد نمایید"></textarea></td>
	</tr>
	<tr>
		<td><label for="sec"></label></td>
		<td><img id="sec" border="0" src="theme/img/img.php" /> <a href="#"
							onClick="document.getElementById('sec').src = 'theme/img/img.php?id='+Math.floor(Math.random()*11); return false"><img
							src="theme/img/refresh.png" width="20" height="20" /></a></td>
	</tr>
	<tr>
		<td><label for="secnum">کد امنیتی</label></td>
		<td><input name="sec" id="secnum" type="text" required placeholder="عدد تصویر بالا را وارد نمایید" /></td>
	</tr>
</table>
				<p>
					<button type="submit" name="submit" class="button gray"
						id="loginbtn">ورود</button>
					<button type="reset" name="reset" class="button red">انصراف</button>

				</p>
</form>
<?php require_once 'theme/footer.php';?>