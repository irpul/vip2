<?php
require_once '../lib/loader.php';
$loader->load_admin();
$session->is_logged_in("login.php");

if(!checkpermission('NEWS'))
	redirect_to('index.php');

$page_title = 'مدیریت اخبار';

if (isset($_POST['submit']))
{
	$news1 = $_POST['news1'];
	$news2 = $_POST['news2'];
	$news3 = $_POST['news3'];
	
	$sql = $database->prepare("UPDATE `setting` SET `news_top`= ? , `news_right`= ?, `news_bottom` = ?");
	
	if ($sql->execute(array($news1,$news2,$news3)))
		$sus = "اطلاعات با موفقیت ذخیره شد";
	else
		$error = "خطا در ذخیره سازی اطلاعات";
}
$news = $database->query("SELECT `news_top` , `news_right` , `news_bottom` FROM `setting`")->fetch();

if ($sus)
	$status = '<img src="../theme/img/icons/tick.png"/> <font color="green" face="Tahoma" style="font-size: 15px">' . $sus . '</font>';
else if ($error)
	$status = '<img src="../theme/img/icons/exclamation-red.png"/> <font color="#FF0000" face="Tahoma" style="font-size: 15px">' . $error . '</font>';

if( $setting['ajax']==1 && $_GET['ajax']==2 )
{
	echo $status;
	die();
}
?>
<?php require_once $path.'theme/header.php';?>

<h1>مدیریت اخبار</h1>
<span id="error">
<?php echo $status;?>
</span>
<form method="post" action="news.php" id="ajaxform">
<table>
	<tr>
		<td><label for="newstitle1">بالای صفحه</label></td>
		<td><textarea id="newstitle1" name="news1"><?php echo $news['news_top']?></textarea></td>
	</tr>
	<tr>
		<td><label for="newstitle2">سمت راست</label></td>
		<td><textarea id="newstitle2" name="news2"><?php echo $news['news_right']?></textarea></td>
	</tr>
	<tr>
		<td><label for="newstitle1">پایین صفحه</label></td>
		<td><textarea id="newstitle3" name="news3"><?php echo $news['news_bottom']?></textarea></td>
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