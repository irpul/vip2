<?php
require_once '../lib/loader.php';
$loader->load_admin();
$session->is_logged_in("login.php");

if(!checkpermission('CATEGORY'))
	redirect_to('index.php');

$page_title = 'IP جدید';
if (isset($_POST['submit']))
{
	$ip = $_POST['ip'];
	$type = $_POST['allow'];
	
	if (! filter_var($ip,FILTER_VALIDATE_IP) )
		$error = 'لطفا تمامی فیلد ها را کامل کنید';
	else
	{
		$sql = $database->prepare("INSERT INTO `blockip` (`ip`,`allow` ) VALUES (?,?)");
		if ($sql->execute(array($ip,$type)))
			$sus = 'اطلاعات با موفقیت ثبت شد';
		else
			$error = 'ثبت اطلاعات با مشکل روبرو شد!';
	}
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
<h1>IP جدید</h1>
<span id="error">
<?php if($status) echo $status;?>
</span>
<form id="ajaxform" method="post" action="blockip.php" >

<table>
	<tr>
		<td><label for="ip">IP</label></td>
		<td><input type="text" id="ip" name="ip"></td>
	</tr>
	<tr>
		<td><label for="allow">نوع</label></td>
		<td><select name="allow" id="allow"><option value="0">allow</option><option value="1">block</option></select></td>
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