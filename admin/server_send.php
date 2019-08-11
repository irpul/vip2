<?php
require_once '../lib/loader.php';
$loader->load_admin();
$session->is_logged_in("login.php");

if(!checkpermission('SERVER'))
	redirect_to('index.php');

$page_title = 'سرور جدید';
if (isset($_POST['submit']))
{
	$url = $_POST['url'];
	$id = $_POST['id'];
	$price = $_POST['price'];
	if (! $url || ! $id)
		$error = 'لطفا تمامی فیلد ها را کامل کنید';
	elseif(!is_numeric($id) || !filter_var($url,FILTER_VALIDATE_URL))
		$error = 'اطلاعات وارد شده معتبر نیست';
	
	elseif(isset($_GET['edit']))
	{
		$sql = $database->prepare("UPDATE `servers` SET `id` = ?,`url` = ?,price=? WHERE `id` =? LIMIT 1");
		if ($sql->execute(array($id,$url,$price,$_GET['edit'])))
			$sus = 'اطلاعات با موفقیت ویرایش شد.';
		else
			$error = 'ویرایش اطلاعات با مشکل روبرو شد!';
	}
	else
	{
		$sql = $database->prepare("SELECT * FROM `servers` WHERE `url` = ? OR `id` = ?");
		$sql->execute(array($url,$id));
			
		if ($sql->rowCount() == 0)
		{
			$sql = $database->prepare("INSERT INTO `servers` (`id`,`url`,price ) VALUES (?,?,?)");
			if ($sql->execute(array($id,$url,$price)))
				$sus = 'اطلاعات با موفقیت ثبت شد';
			else
				$error = 'ثبت اطلاعات با مشکل روبرو شد!';
		}
		else
			$error = 'خطا!ممکن است نام موجود باشد';
	}
}
if (isset($_GET['edit']))
{
	$sql = $database->prepare("SELECT * FROM `servers` WHERE `id` = ?");
	$sql->execute(array($_GET['edit']));
	$server = $sql->fetch();
	if (! $server['url'])
	{
		redirect_to('server.php');
	}
	
	$url = $server['url'];
	$id = $server['id'];
	$price = $server['price'];
	$rewrtie = $server['rewrite'];
	$submit = 'update';
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
<h1>سرور جدید</h1>
<span id="error">
<?php if($status) echo $status;?>
</span>
<form id="ajaxform" method="post" action="server_send.php<?php if($submit=="update") echo '?edit='.$_GET['edit'];?>" >

<table>
	<tr>
		<td><label for="id">ای دی سرور</label></td>
		<td><input type="text" dir="ltr" value="<?php echo $id;?>" id="id" name="id" dir="ltr"></td>
	</tr>
	<tr>
		<td><label for="url">لینک سرور ( همراه با / اخر )</label></td>
		<td><input type="text" dir="ltr" value="<?php echo $url;?>" id="url" name="url"></td>
	</tr>
	<tr>
		<td><label for="price">هزینه هر فایل ( به ریال )</label></td>
		<td><input type="text" dir="ltr" value="<?php echo $price;?>" id="price" name="price"></td>
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