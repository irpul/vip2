<?php 
require 'lib/loader.php';
$loader->load_user ();
$session->is_logged_in ( "login.php" );
$page_title = 'تمدید اکانت';

if(isset($_POST['submit'])){
	$send['action'] = "charge";
	$send['userid'] = $user['id'];
	$send['category'] = $_POST['category'];
	$send['gatewayname'] = $_POST['gatewayname'];
	$send = http_build_query($send);
	
	redirect_to("payment.php?$send");
}


$category = $database->query("SELECT * FROM `category`")->fetchAll();
$plugins = $database->query("SELECT * FROM `plugin` WHERE `plugin_type` = 'payment' AND `plugin_status` = 1")->fetchAll();

?>
<?php require_once 'theme/header.php';?>
<h1>تمدید اکانت</h1>
<form method="post" action="charge.php">
<table>
	<tr>
		<td colspan=2><font face="Tahoma" size="2">در صورتیکه قصد خرید عضویت ویژه در سایت را دارید از قسمت پایین اشتراک مورد نظرتان را انتخاب و تمدید کنید</font></td>
	</tr>
	<tr>
		<td style="text-align:right"><label>پلن مورد نظر</label></td>
		<td style="text-align:right"><?php
		$i=0; 
		foreach ($category as $cat)
		{
			$i++;
			echo '<label class="radio" for="cat_'.$i.'">';
			echo '<input name="category" type="radio" value="'.$cat['id'].'" id="cat" required/>';
			echo $cat['title'].' - '.$cat['price'];
			echo '</label><br/>';
		}
		?></td>
	</tr>
	<tr>
		<td style="text-align:right"><label for="gateway">درگاه پرداخت</label></td>
		<td style="text-align:right">
		<select name="gatewayname" id="gateway">
		<?php 
		foreach ($plugins as $p)
		{
			echo '<option value="'.$p['plugin_uniq'].'">'.$p['plugin_name'].'</option>';
		}
		?>
		</select></td>
	</tr>
	<tr>
		<td></td>
		<td style="text-align:right">
			<button type="submit" name="submit" class="button gray">پرداخت</button>
			<button type="reset" name="reset" class="button red">انصراف</button>
		</td>
	</tr>
</table>
</form>
<?php require_once 'theme/footer.php';?>