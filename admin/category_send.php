<?php
require_once '../lib/loader.php';
$loader->load_admin();
$session->is_logged_in("login.php");

if(!checkpermission('CATEGORY'))
	redirect_to('index.php');

$page_title = 'دسته جدید';
if (isset($_POST['submit']))
{
	$title = $_POST['title'];
	$day = $_POST['day'];
	$price = $_POST['price'];
	
	$multi = $_POST['multi'];
	if (! $day || ! $title || !$price || !is_numeric($multi))
		$error = 'لطفا تمامی فیلد ها را کامل کنید';
	elseif(!is_numeric($day))
		$error = 'تعداد روز باید به عدد باشد';
	elseif(isset($_GET['edit']))
	{
		$sql = $database->prepare("UPDATE `category` SET multi=?,`title` = ?,`day` = ?,`price` = ? WHERE `id` = ? LIMIT 1");
		if ($sql->execute(array($multi,$title,$day,$price,$_GET['edit'])))
			$sus = 'اطلاعات با موفقیت ویرایش شد.';
		else
			$error = 'ویرایش اطلاعات با مشکل روبرو شد!';
	}
	else
	{
		$sql = $database->prepare("SELECT * FROM `category` WHERE `title` = ?");
		$sql->execute(array($title));
		if ($sql->rowCount() == 0)
		{
			$sql = $database->prepare("INSERT INTO `category` (multi,`title`,`day`,`price` ) VALUES (?,?,?,?)");
			if ($sql->execute(array($multi,$title,$day,$price)))
				$sus = 'اطلاعات با موفقیت ثبت شد';
			else
				$error = 'ثبت اطلاعات با مشکل روبرو شد!';
		}
		else
			$error = 'این عنوان موجود است!';
	}
}
if (isset($_GET['edit']))
{
	$sql = $database->prepare("SELECT * FROM `category` WHERE `id` = ?");
	$sql->execute(array($_GET['edit']));
	$category = $sql->fetch();
	if (! $category['id'])
	{
		redirect_to('category.php');
	}
	$multi = $category['multi'];
	$title = $category['title'];
	$day = $category['day'];
	$price = $category['price'];
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
<h1>دسته جدید</h1>
<span id="error">
<?php if($status) echo $status;?>
</span>
<form id="ajaxform" method="post" action="category_send.php<?php if($submit=="update") echo '?edit='.$_GET['edit'];?>" >

<table>
	<tr>
		<td><label for="title">عنوان دسته</label></td>
		<td><input type="text" value="<?php echo $title;?>" id="title" name="title"></td>
	</tr>
	<tr>
		<td><label for="day">تعداد روز</label></td>
		<td><input type="text" value="<?php echo $day;?>" id="day" name="day"></td>
	</tr>
	<tr>
		<td><label for="multi">تعداد کاربر</label></td>
		<td><input type="text" value="<?php echo $multi;?>" id="multi" name="multi"></td>
	</tr>
	<tr>
		<td><label for="price">مبلغ ( به ریال )</label></td>
		<td><input type="text" value="<?php echo $price;?>" id="price" name="price"></td>
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