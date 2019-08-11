<?php

require 'lib/loader.php';
$loader->load_user ();
$login = $session->is_logged_in();
$download = true;
$server = (int)$_GET ['server'];
$server = $database->query("SELECT * FROM `servers` WHERE `id`='$server'")->fetch();
if(!$server) redirect_to('/index.php');
$path = SITE_PATH;
$_GET['file'] = $_GET['file'] ? $_GET['file'] : '.';
if (isset ( $_GET ['file'] ) && !empty($_GET['file']))
{
	$fileencode = urlencode($_GET['file']);
	$file = fileinfo($_GET['file'],$server);
	
	
	if( $file['type'] == "dir" )
	{
		require "list.php";
		die();
	}

	if(!$file) $error="فایل پیدا نشد";
	
	
	if ( !$error )
	{
		$type = pathinfo($file['name'],PATHINFO_EXTENSION);
		try {
			
			if ($login)
			{
				redirect_to(generateLink($user, $server, $fileencode));
			}
			elseif ( $_SERVER['PHP_AUTH_USER'] && $_SERVER['PHP_AUTH_PW'] )
			{
				$login_user = $database->prepare("SELECT * FROM `users` WHERE `username` = ?");
				$login_user->execute(array($_SERVER['PHP_AUTH_USER']));
				$login_user = $login_user->fetch();
				
				if ( $login_user['password'] == $_SERVER['PHP_AUTH_PW'])
				{
					redirect_to(generateLink($login_user, $server, $fileencode));
				}
			}
			else 
			{
				$size = $file['size'];
				if($size < 1024) {
						$size = "{$size} bytes";
				} elseif($size < 1048576) {
					$size = round($size/1024 , 2);
					$size = "{$size} KB";
				} elseif ($size < 1073741824) {
					$size = round($size/1048576, 2);
					$size = "{$size} MB";
				}
				else {
					$size = round($size/1073741824, 2);
					$size = "{$size} GB";
				}
				$fname = $file['name'];
				$vlink = SITE_PATH."login.php?file=$server[id]/{$fileencode}";
				$slink = SITE_PATH."single.php?server={$_GET['server']}&file=".$fileencode;
				$glink = SITE_PATH."download.php?server={$_GET['server']}&file=".$fileencode;
			}
		
		}
		catch (Exception $e)
		{
			$error = translate($e->getMessage());
		}
	}
	else 
		$error = "فایل پیدا نشد";
	
}
else 
	$error = "فایل پیدا نشد";

if ( isset($error))
{
	$size = null;
	$fname = $error;
	$glink = $vlink = $slink = "#";
}

$page_title = 'دانلود فایل -' . $fname;
?>
<?php require_once 'theme/header.php';?>
<div class="fileinfo"><div class="title">دانلود فایل</div> : <?php echo $fname;?><br/>
	<div class="info">
	نوع فایل : <?php echo $type; ?> - حجم فایل : <?php echo $size;?></div>
	<div class="clear"></div>
	</div>
	
	<br/>
	<?php if($setting['singletime']):
	
	$price = $file['size']/1048576;
	$price = $server['price'] + $price * $setting['singleprice'];
	$price = (int)($price/10);
	$price = $price*10;
	
	?>
	<a href="<?php echo $slink; ?>">
	<div class="fileinfo " style="margin-top:-20px;width: 300px;background: #E74C3C;color: #fff;text-align: center;">
		<i class="fa fa-download"></i>
		خرید فایل به صورت جداگانه<br> با قیمت : <?php echo $price?> ریال 
	</div>
	</a>
	<?php endif;?>
	<table><tr>
	<td>
	
	<div class="eshterak">
	اشتراک ویژه<br/>
	<img src="<?php echo SITE_PATH?>theme/img/premiumdownload.gif" width="200px"/><br/><br/>
	<h5><img src="<?php echo SITE_PATH?>theme/img/connection.png"/>&nbsp;تعداد کانکشن دانلود: 16کانکشن</h5>
	<h5><img src="<?php echo SITE_PATH?>theme/img/dl.png"/>&nbsp;قابلیت دانلود همزمان فایل: دارد</h5>
	<h5><img src="<?php echo SITE_PATH?>theme/img/speed.png"/>&nbsp;حداکثر سرعت دانلود: نامحدود</h5>
	<h5><img src="<?php echo SITE_PATH?>theme/img/manager.png"/>&nbsp;پشتیبانی از Download Manager: دارد</h5>
	<h5><img src="<?php echo SITE_PATH?>theme/img/resume.png"/>&nbsp;ادامه دانلود: دارد</h5>
	<h5><img src="<?php echo SITE_PATH?>theme/img/wait.png"/>&nbsp;انتظار دانلود: 0 ثانیه</h5>
	<a class="vip" href="<?php echo $vlink;?>">دانلود</a>
	</div>
	</td>
	
	</tr></table>
	
<?php if($setting['dltext']):?>
<a href="<?php echo SITE_PATH?>payment.php">
<div class="fileinfo" style="text-align: center">
<?php echo $setting['dltext']?>

</div>
</a>
<?php endif;?>
<?php require_once 'theme/footer.php';?>