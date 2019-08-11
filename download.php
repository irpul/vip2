<?php
require 'lib/loader.php';
$loader->load_user ();
$download = true;
$server = (int)$_REQUEST ['server'];
$servers = $database->query("SELECT * FROM `servers` WHERE `id`='$server'")->fetch();
$server = $servers['url'];
if(!$server) redirect_to('index.php');

if (isset ( $_REQUEST ['file'] ) && !empty($_REQUEST['file']))
{
	$fileencode = urlencode($_REQUEST['file']);
	$file = fileinfo($_REQUEST['file'], $servers);
	
	$type = pathinfo($file['name'],PATHINFO_EXTENSION);

	if(!$file) redirect_to('index.php');
	
	if(isset($_POST['download']))
	{
		
		if($setting['guestcaptcha']==1 && $_SESSION ['img'] != $_POST['sec']) die();

		if( isset($_SESSION['time']) && $_SESSION['time'] < time()+$setting['guesttime'] )
		{
			$time = time();
			$sql = $database->prepare("INSERT INTO `dlinfo_guest` (`ip`,`time`,`file` ) VALUES (?,'$time',?)");
			$sql->execute(array($_SERVER[REMOTE_ADDR],$_REQUEST[file]));
			unset($_SESSION['time']);
			unset($_SESSION ['img']);
			echo "{$server}download.php?file=".$fileencode;
			exit;
		}

		
	}
	else
		$_SESSION['time'] = time();

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
}
else 
	redirect_to("index.php");

$page_title = 'دانلود فایل -' . $fname;
?>
<?php require_once 'theme/header.php';?>
<script type="text/javascript" src="theme/js/script.php"></script>

<div class="fileinfo"><div class="title">دانلود فایل</div> : <?php echo $fname;?><br/>
	<div class="info">
	نوع فایل : <?php echo $type; ?> - حجم فایل : <?php echo $size;?></div>
	<div class="clear"></div>
	</div>
<input id="file" value="<?php echo htmlspecialchars($_REQUEST['file']);?>" type="hidden">
<input id="server" value="<?php echo $_GET['server'];?>" type="hidden">
<div class="timer">
                	<div class="sec"><span id="Timer" style="display:block; width:70px; height:70px; line-height:67px; margin:0 35px; background: transparent url('theme/images/timer.gif') no-repeat; text-align:center; font-size:36px; color:#666">--</span></div>
                </div>
				<div id="Result">
                    <div style="display: none;" id="ab3" class="allert-box-3"><a href="">&nbsp;</a></div>
					<div class="allert-box-1">
						<p class="allert-1-txt">صبر کنید تا ثانیه شمار صفر - 0 - شود <br>سپس دانلود شما آماده می شود</p>
					</div>
				</div>

<?php if($setting['dltext']):?>
<a href="payment.php">
<div class="fileinfo" style="text-align: center">
<?php echo $setting['dltext']?>
</div>
</a>
<?php endif;?>
<?php require_once 'theme/footer.php';?>