<?php
require_once '../lib/loader.php';
$loader->load_admin();
$session->is_logged_in("login.php");

if(!checkpermission('EMAIL'))
	redirect_to('index.php');

$page_title = 'خبرنامه جدید';
if (isset($_POST['submit']))
{
	$subject = $_POST['subject'];
	$text = $_POST['text'];
	$category = $_POST['category'];
	$status = $_POST['status'];
	$type = $_POST['type'];
	
	if (! $subject || ! $text || !is_numeric($type))
		$error = 'لطفا تمامی فیلد ها را کامل کنید';
	else
	{
		$where = '1=1';
			
		if($category !== 'all')
			$where .= ' AND categoryid = '.(int)$category;
		if($status !== 'all')
			$where .= ' AND active = '.(int)$status;
		$time = time();
		if($type == 1 || $type == 3)
		{
			$sql = $database->prepare("INSERT INTO `newslist`(`text`, `subject`, `number`, `sent`) VALUES(?,?,?,?)");
			$sql->execute(array($text,$subject,0,0));
			$id = $database->lastInsertId();
			
			
				
			$sql = $database->prepare("INSERT INTO `job` (email,`time`,`newslistid` ) SELECT email,$time,$id FROM users WHERE $where");
			if ($sql->execute(array()))
			{
				$count = $sql->rowCount();
				$sql = "UPDATE newslist SET number = $count WHERE id = $id";
				$database->exec($sql);
				$sus = 'اطلاعات با موفقیت ثبت شد';
			}
			else
				$error = 'ثبت اطلاعات با مشکل روبرو شد!';
		}
		if($type == 2 || $type == 3)
		{
			$sql = $database->prepare("INSERT INTO `ticket` (`title`,`content`,`time`,`userid`,`usertype`,`status`,`attach` ,`file_name` )
					SELECT ?,?,?,users.id,2,4,?,? FROM users WHERE $where");
		
			if ($sql = $sql->execute(array($subject,$text,$time,0,'')))
			{
				$sus = 'اطلاعات با موفقیت ثبت شد';
			}
			else
				$error = 'ثبت اطلاعات با مشکل روبرو شد!';
		}
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
<h1>دسته جدید</h1>
<span id="error">
<?php if($status) echo $status;?>
</span>
<form id="ajaxform" method="post" action="newslist_send.php" >

<table>
	<tr>
		<td><label for="subject">موضوع خبرنامه</label></td>
		<td><input type="text" value="<?php echo $subject;?>" id="subject" name="subject"></td>
	</tr>
	<tr>
		<td><label for="text">متن خبرنامه</label></td>
		<td><textarea  id="text" name="text"><?php echo $text;?></textarea></td>
	</tr>
	<tr>
		<td><label for="category">دسته</label></td>
		<td><select id="category" name="category">
			<option value="all">همه</option>
			<?php 
			$category = $database->query("SELECT * from category");
			while($cat = $category->fetch())
			{
				echo '<option value="'.$cat['id'].'" >'.$cat['title'].'</option>';
			}
			?>
		</select>
		</td>
	</tr>
	<tr>
		<td><label for="status">وضعیت</label></td>
		<td>
			<select id="status" name="status">
				<option value="all">همه</option>
				<option value="1">فعال</option>
				<option value="0">غیرفعال</option>
			</select>
		</td>
	</tr>
	<tr>
		<td><label for="type">نوع</label></td>
		<td>
			<select id="type" name="type">
				<option value="3">تیکت و ایمیل</option>
				<option value="1">ایمیل</option>
				<option value="2">تیکت</option>
			</select>
		</td>
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