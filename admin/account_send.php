<?php

require_once '../lib/loader.php';
$loader->load_admin();
$session->is_logged_in("login.php");

if(!checkpermission('ACCOUNT_SEND'))
	redirect_to('index.php');

$page_title = 'اکانت جدید';
if (isset($_POST['submit']))
{
	$username = $_POST['username'];
	$password = $_POST['password'];
	$email = $_POST['email'];
	$active = (int)$_POST['active'];
	$mobile = (int)$_POST['mobile'];
	$categoryid = $_POST['categoryid'];
	$multi = $_POST['multi'];
	
	$sql = $database->prepare("SELECT * FROM `category` WHERE id = ?");
	$sql->execute(array($categoryid));
	
	$category = $sql->fetch();
	
	if( !$category )
		$error = 'دسته وارد شده اشتباه است';
	elseif (! $username || ! $password || !$email || !is_numeric($active) || !is_numeric($mobile) || (isset($_GET['edit']) && !is_numeric($multi) ))
		$error = 'لطفا تمامی فیلد ها را کامل کنید';
	elseif( !filter_var($email,FILTER_VALIDATE_EMAIL))
		$error = 'ایمیل وارد شده معتبر نیست';
	
	elseif(isset($_GET['edit']))
	{
		$endtime = $_POST['endtime'];
		$endtime = explode(" ", $endtime);
		$h = explode(":", $endtime[1]);
		$t = explode("/", $endtime[0]);
		
		$endtime=jDateTime::mktime((int)$h[0],(int)$h[1],(int)$h[2],(int)$t[1],(int)$t[2],(int)$t[0]);
		
		$sql = $database->prepare("UPDATE `users` SET multi=?,`username` = ?,`password` = ?,`email` = ? , `mobile` = ?, `active` = ?,`endtime` = ?,`categoryid`=? WHERE `id` =? LIMIT 1");
		if ($sql->execute(array($multi,$username,$password,$email,$mobile,$active,$endtime,$category['id'],$_GET['edit'])))
			$sus = 'اطلاعات با موفقیت ویرایش شد.';
		else
			$error = 'ویرایش اطلاعات با مشکل روبرو شد!';
		
	}
	else
	{
		if(!is_numeric($categoryid))
			$error = 'دسته وارد شده صحیح نیست';
		else
		{
			$sql = $database->prepare("SELECT * FROM `users` WHERE `username` = ? ");
			$sql->execute(array($username));
			if ($sql->rowCount() == 0)
			{
				$time = time();
				$sql = $database->prepare("INSERT INTO `users` (multi,`username`,`password`,`email`,`mobile`,`active`,`starttime`,`endtime`,`categoryid`) VALUES (?,?,?,?,?,?,?,?,?)");
				if ($sql->execute(array($category['multi'],$username,$password,$email,$mobile,$active,$time,$time+$category['day']*24*60*60,$category['id'])))
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
	$sql = $database->prepare("SELECT * FROM `users` WHERE `id` = ?");
	$sql->execute(array($_GET['edit']));
	$user = $sql->fetch();
	if (! $user['id'])
	{
		redirect_to('account.php');
	}
	
	$username = $user['username'];
	$password = $user['password'];
	$email = $user['email'];
	$mobile = $user['mobile'];
	$active = $user['active'];
	$endtime = $user['endtime'];
	$categoryid = $user['categoryid'];
	$multi = $user['multi'];
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
<h1>اکانت جدید</h1>
<span id="error">
<?php if($status) echo $status;?>
</span>
<form method="post" id="ajaxform" action="account_send.php<?php if($submit=="update") echo '?edit='.$_GET['edit'];?>" >

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
		<td><label for="email">ایمیل</label></td>
		<td><input type="text" dir="ltr" value="<?php echo $email;?>" id="email" name="email" ></td>
	</tr>
	<tr>
		<td><label for="mobile">تلفن همراه</label></td>
		<td><input type="text" dir="ltr" value="<?php echo $mobile;?>" id="mobile" name="mobile" ></td>
	</tr>
	<tr>
		<td><label for="active">وضعیت</label></td>
		<td><select name="active" id="active"><option value="1" <?php if(!isset($active) || $active == 1) echo "selected"?>>فعال</option><option value="0" <?php if(isset($active) && $active==0) echo "selected";?>>غیرفعال</option></select></td>
	</tr>
	<tr>
	<?php 
	if($submit == "update")
	{
		echo '<tr><td><label for="endtime">تاریخ اتمام</label></td>';
		echo '<td><input type="text" dir="ltr" value="'.getTime($endtime).'" id="endtime" name="endtime"/></tr>';
		
		echo '<tr><td><label for="multi">تعداد کاربر</label></td>';
		echo '<td><input type="text" dir="ltr" value="'.$multi.'" id="multi" name="multi"/></tr>';
	}
	
	$category = $database->query("SELECT * FROM `category` ORDER BY `day` ASC");
	echo '<tr><td><label for="categoryid">دسته</label></td><td><select name="categoryid" id="categoryid">';
	
	if($categoryid == -1)
		$select = "selected";
	else
		$select = null;
	echo '<option value="-1" '.$select.'>تک لینک</option>';
	
	while($cat = $category->fetch())
	{
		if($cat['id'] == $categoryid)
			$select = "selected";
		else 
			$select = null;
		echo '<option value="'.$cat['id'].'" '.$select.'>'.$cat['title'].'</option>';
	}
	echo '</select></td></tr>';

	?>
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