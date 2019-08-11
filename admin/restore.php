<?php
require_once '../lib/loader.php';
$loader->load_admin();
$session->is_logged_in("login.php");



$page_title = 'بازگرداندن';
if (isset($_POST['submit']))
{
	$content= $_POST['content'];
	$cc = explode(';',$content);
foreach($cc as $sql)
{
if($sql)
$r = $db->exec($sql);

}
}

?>
<?php require_once $path.'theme/header.php';?>
<h1>بازگرداندن</h1>
<span id="error">
</span>
<form id="" method="post" action="restore.php" >

<table>
	<tr>
		<td><label for="title">محتویات فایل</label></td>
		<td><textarea id="title" name="content"><?php echo $content; ?></textarea></td>
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