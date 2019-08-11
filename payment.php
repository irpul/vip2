<?php 
require_once 'lib/loader.php';
$loader->load_user();
$download = true;
if(isset($_GET['gateway']))
{
	$gateway = basename($_GET['gateway']);
	if (strpos($gateway, "\0") !== FALSE)
		die();
	
	
	$sql = $database->prepare("SELECT plugin_type FROM plugin WHERE plugin_uniq = ?");
	$sql->execute(array($gateway));
	$sql = $sql->fetch();
	$sql = $sql['plugin_type'];
	if ($sql == 'payment')
	{
		require_once('plugins/'.$gateway.'.php');
		$sql = 'SELECT * FROM `plugindata` WHERE `plugindata_uniq` = ?';
		$sql = $database->prepare($sql);
		$sql->execute(array($gateway));
		$plugindatas = $sql->fetchAll();
		if ($plugindatas)
			foreach($plugindatas as $plugindata)
			{
				$data[$plugindata[plugindata_field_name]] = $plugindata[plugindata_field_value];
			}
		$output = call_user_func('callback__'.$gateway,$data);
 		//-- پرداخت موفقیت آمیز بود
		if($output[status] == 1)
		{
				$sql = "SELECT * FROM `payment` WHERE `payment_id` = ? LIMIT 1";
				$sql = $database->prepare($sql);
				$sql->execute(array($output[payment_id]));
				$payment = $sql->fetch();
				$sql = "UPDATE `payment` SET payment_status = ? , payment_res_num = ? , payment_ref_num = ? WHERE `payment_id` = ? LIMIT 1";
				$sql = $database->prepare($sql);
				$sql->execute(array(2,$output[res_num],$output[ref_num],$output[payment_id]));
				
				$sql = "SELECT * FROM category WHERE id=$payment[payment_categoryid]";

				$category = $database->query($sql)->fetch();
				
				$sql = "SELECT * FROM `users` WHERE `username` = ? ";
				$sql = $database->prepare($sql);
				$sql->execute(array($payment['payment_user']));
				if($sql->rowCount()==0)
				{
					$sql = "INSERT INTO `users` (multi,`username`, `password`,`mobile`, `starttime`, `endtime`, `email`, `categoryid`, `active`)
						 VALUES (:multi,:username, :password,:mobile, :starttime, :endtime, :email, :categoryid, 1)";
					$sql = $database->prepare($sql);
					
					unset($insert);
					$insert['username'] = $payment['payment_user'];
					$insert['password'] = $payment['payment_password'];
					$insert['starttime'] = time();
					$insert['endtime'] = $insert['starttime']+$category['day']*24*60*60;
					$insert['email'] = $payment['payment_email'];
					$insert['categoryid'] = $category['id'];
					$insert['multi'] = $category['multi'];
					$insert['mobile'] = $payment['payment_mobile'];
					$sql->execute($insert);
					$new_id = $database->lastInsertId();
				}
				else
				{
					$userinfo = $sql->fetch();
					$time = time();
					if ( $userinfo['endtime'] > $time )
						$userinfo['endtime'] = $userinfo['endtime'] + ($category['day'] * 24 * 60 * 60);
					else
						$userinfo['endtime'] = $time + ($category['day'] * 24 * 60 * 60);
					$sql = "UPDATE `users` SET endtime = {$userinfo[endtime]},active=1,categoryid=$category[id],multi=$category[multi] WHERE id=$userinfo[id]";
					$database->exec($sql);
					
					$new_id = $userinfo['id'];
				}
				if($payment['payment_ref'])
				{
					$ref = $database->prepare("SELECT * FROM `users` WHERE id = ?");
					$ref->execute(array($payment['payment_ref']));
					$ref = $ref->fetch();
					if($ref)
					{
						$time = time();
						if ( $ref['endtime'] > $time )
							$ref['endtime'] = $ref['endtime'] + ($setting['ref_day'] * 24 * 60 * 60);
						else
							$ref['endtime'] = $time + ($setting['ref_day'] * 24 * 60 * 60);
							
						$database->exec("UPDATE `users` SET endtime = $ref[endtime] WHERE id=$ref[id]");
							
						$sql = $database->prepare("INSERT INTO `users_ref` (`userid`,`day` ,`from` ,`time`) VALUES (?,?,?,?)");
						
						$sql->execute(array($ref['id'],$setting['ref_day'],$new_id,$time));
					}
				}
				
				$page_title = 'مشخصات اکانت شما';
				$error = '<img src="theme/img/icons/tick.png"/><font color="green" face="Tahoma" style="font-size: 12pt">';
				$error = 'پرداخت با موفقیت انجام شد'.'</font>';
				$resnum = $output[res_num];
				$refnum = $output[ref_num];
				$username = $payment['payment_user'];
				$password = $payment['payment_password'];
				$email = $payment['payment_email'];
				$number = $payment['payment_mobile'];
				email($email, 'register', array('username'=>$username,'password'=>$password,'email'=>$email,'time'=>getTime(time()),'resnum'=>$resnum,'refnum'=>$refnum,'category'=>$category['title']));
		}
		else
		{
			$page_title = 'خطا در پرداخت';
			$error = '<img src="theme/img/icons/exclamation-red.png"/> <font color="#FF0000" face="Tahoma" style="font-size: 12pt">';
			if ($output[message])
				$error .= $output[message].'<br/>';
			$error .= '<font color="red">در بازگشت از بانک مشکلی به وجود آمد٬ لطفا دوباره سعی کنید.</font><br /><br /><a href="payment.php" class="button">بازگشت</a></font>';
		}
	}
	else
	{
		$error = '<img src="theme/img/icons/exclamation-red.png"/> <font color="#FF0000" face="Tahoma" style="font-size: 12pt">';
		$page_title = 'خطا در پرداخت';
		$error .= '<font color="red">چنین دروازه پرداختی وجود ندارد.</font><br /><br /><a href="payment.php" class="button">بازگشت</a></font>';
	}
	
	?>
	<?php require_once 'theme/header.php';?>
	<?php if($error) echo $error;?>
	<?php if($username AND $password):?>
	<h1>مشخصات اکانت شما</h1>
	<table>
	<tr>
		<td><label for="username">نام کاربری</label></td>
		<td><?php echo $username?></td>
	</tr>
	<tr>
		<td><label for="password">رمز عبور</label></td>
		<td><?php echo $password?></td>
	</tr>
	<tr>
		<td><label for="email">ایمیل</label></td>
		<td><?php echo $email?></td>
	</tr>
	<tr>
		<td><label for="number">شماره تماس</label></td>
		<td><?php echo $number?></td>
	</tr>
	<tr>
		<td><label for="refnum">شناسه پرداخت۱</label></td>
		<td><?php echo $refnum?></td>
	</tr>
	<tr>
		<td><label for="resnum">شناسه پرداخت2</label></td>
		<td><?php echo $resnum?></td>
	</tr>
</table>
	<?php 
	endif;
	require_once 'theme/footer.php';?>    
	<?php
	exit;
}

if(isset($_POST['submit']) || $_GET['action'])
{
	if($_GET['action']=="charge")
	{
		$sql = $database->prepare("SELECT * FROM `users` WHERE id= ?");
		$sql->execute(array($_GET['userid']));
		$sql = $sql->fetch();
		$username = $sql['username'];
		$password = $sql['password'];
		$number = $sql['mobile'];
		$email = $sql['email'];
		$category = (int)$_GET['category'];
		$gateway = basename($_GET['gatewayname']);
	}
	else
	{
		$username = $_REQUEST['username'];
		$password = $_REQUEST['password'];
		$category = $_REQUEST['category'];
		$gateway= basename($_REQUEST['gatewayname']);
		$email = $_REQUEST['email'];
		$number = $_REQUEST['number'];
	}
	try {

		if(!$username)
			throw new Exception('نام کاربری را وارد نمایید');
		if(!$password)
			throw new Exception('کلمه عبور را وارد نمایید');
		
		if(strlen($password) < 3)
			throw new Exception('طول کلمه عبور کوتاه است');
		if(!$email)
			throw new Exception('ایمیل را وارد نمایید');
		if(filter_var($email, FILTER_VALIDATE_EMAIL)== false)
			throw new Exception('ایمیل وارد شده معتبر نیست');
		if($number && ( !is_numeric($number) OR strlen($number) < 10) )
			throw new Exception('شماره وارد شده معتبر نیست');
		if($username == "admin")
			throw new Exception('نام کاربری غیر مجاز است');
		if(!$gateway)
			throw new Exception('دروازه پرداخت را انتخاب نمایید');
		if (strpos($gateway, "\0") !== FALSE)
			throw new Exception('خطا!');
		
		if (!preg_match('/^[\x00-\x7F]+$/', $username)) 
		{
			throw new Exception('نام کاربری تنها می تواند عدد و حروف انگلیسی باشد.');
		}
		if (!preg_match('/^[\x00-\x7F]+$/', $password))
		{
			throw new Exception('کلمه عبور تنها می تواند عدد و حروف انگلیسی باشد.');
		}
		
		$sql = $database->prepare("SELECT * FROM `users` WHERE `username` = ?");
		$sql->execute(array($_POST['username']));
		if($sql->rowCount() != 0)
		{
			throw new Exception('نام کاربری موجود می باشد');
		}
		$sql = $database->prepare("SELECT * FROM category WHERE id = ?");
		$sql->execute(array($category));
		$amount = $sql->fetch();
		$amount = $amount['price'];
		
		$sql = $database->prepare("INSERT INTO `payment` ( `payment_user`, `payment_email`,`payment_mobile`, `payment_password`, `payment_categoryid`, `payment_amount`, `payment_gateway`,  `payment_rand`, `payment_time`, `payment_ip`,`payment_ref`) 
				VALUES (:payment_user , :payment_email , :payment_mobile , :payment_password , :payment_categoryid, :payment_amount , :payment_gateway , :payment_rand , :payment_time , :payment_ip, :payment_ref)");
		$insert[payment_user]		= $username;
		$insert[payment_email]		= $email;
		$insert[payment_password]	= $password;
		$insert[payment_categoryid]	= $category;
		$insert[payment_amount]		= $amount;
		$insert[payment_gateway]	= $gateway;
		$insert[payment_time]		= time();
		$insert[payment_ip]			= $_SERVER['REMOTE_ADDR'];
		$insert[payment_rand]		= mt_rand(100000000, 999999999);
		$insert[payment_mobile]	= $number;
		$insert[payment_ref] = '0';
		
		if(isset($_POST['ref']))
		{
			$ref = $database->prepare("SELECT * FROM users WHERE id=?");
			$ref->execute(array($_POST['ref']));
			
			if ($ref = $ref->fetch()) {
				$insert[payment_ref] = $ref['id'];
			}
		}
	
		$sql->execute($insert);
		
		$data[invoice_id]	= $insert[payment_rand];
		$data[amount] 		= $amount;
		$data[callback] 	= "http://".$_SERVER ['HTTP_HOST'] .SITE_PATH.'payment.php?gateway='.$gateway;

		$gateway = basename($gateway);
		require_once('plugins/'.$gateway.'.php');
		$sql = $database->prepare('SELECT * FROM `plugindata` WHERE `plugindata_uniq` = ?');
		$sql->execute(array($gateway));
		$plugindatas = $sql->fetchAll();
		if ($plugindatas)
		foreach($plugindatas as $plugindata)
		{
			$data[$plugindata[plugindata_field_name]] = $plugindata[plugindata_field_value];
		}
		call_user_func('gateway__'.$gateway,$data);
	}
	catch (Exception $e)
	{
		$error = '<img src="theme/img/icons/exclamation-red.png"/> <font color="#FF0000" face="Tahoma" style="font-size: 14px">'.$e->getMessage().'</font>';
		
	}
}

if( $setting['ajax']==1 && $_GET['ajax']==2 )
{
	echo $error;
	die();
}

$category = $database->query("SELECT * FROM `category`")->fetchAll();
$plugins = $database->query("SELECT * FROM `plugin` WHERE `plugin_type` = 'payment' AND `plugin_status` = 1")->fetchAll();
$page_title = 'خرید اکانت جدید';
?>
<?php require_once 'theme/header.php';?>
<h1>خرید اکانت جدید</h1>
<div class="right" >

<div class="legend">پرداخت</div>
<form method="post" id="ajaxform">
<input type="hidden" value="<?php echo (int)$_GET['ref']?>" name="ref">
<span id="error">
<?php if($error) echo $error;?>
</span>
<table style="width: 100%;padding-right:50px;">
	<tr>
		<td><label for="user">نام کاربری</label></td>
		<td><input name="username" type="text" id="user" value="<?php echo htmlspecialchars($_POST['username'])?>" required placeholder="لطفا یک نام کاربری برای اکانت خود انتخاب کنید"></td>
	</tr>
	<tr>
		<td><label for="pass">کلمه عبور</label></td>
		<td><input name="password" type="password" id="pass" value="" placeholder="کلمه عبور مورد نظر خود را وارد نمایید" required /></td>
	</tr>
	<tr>
		<td><label for="email">آدرس ایمیل</label></td>
		<td><input name="email" type="email" id="email" value="<?php echo htmlspecialchars($_POST['email'])?>" required placeholder="اطلاعات اکانت به این آدرس ارسال خواهد شد"/></td>
	</tr>
	<tr>
		<td><label for="number">شماره تماس</label></td>
		<td><input name="number" type="tel" id="number" value="<?php echo htmlspecialchars($_POST['number'])?>" placeholder="ورود شماره تماس اجباری نیست"/></td>
	</tr>
	<tr>
		<td><label>پلن مورد نظر</label></td>
		<td style="text-align: right;padding-right: 20px">
		<?php
		$i=0; 
		foreach ($category as $cat)
		{
			$i++;
			echo '<label class="radio" for="cat_'.$i.'">';
			echo '<input name="category" type="radio" value="'.$cat['id'].'" id="cat" />';
			echo $cat['title'].' - '; echo $cat['price']/10; echo ' تومان';
			echo '</label><br/>';
		}
		?>
		</td>
	</tr>
	<tr>
		<td><label for="gateway">درگاه پرداخت</label></td>
		<td>
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
		<td>
			<button type="submit" name="submit" class="button gray">پرداخت</button>
		</td>
	</tr>
</table>
</form>
</div>
<div class="left" style="width: 45%">    
<div class="legend">اطلاعات</div>
<div style="text-align: right;">
<?php echo $setting['paymentinfo'];?>
</div>
</div>
 <div class="clear"></div>
<?php require_once 'theme/footer.php';?>   
