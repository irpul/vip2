<?php 
$setting = $setting ? $setting : null;
$page_title = isset($page_title) ? $page_title : "کنترل پنل کاربران"; 
$page_title = $page_title . ' - ' . $setting['sitename'];
$path = isset($path) ? $path : null;

$download = isset($download) ? $download : null;

if(!$download && ( $setting['ajax']==1 && $_GET['ajax'] ))
{
	echo '<span style="display:none" id="pagetitle">'.$page_title.'</span><div class="countiner" align="center">';
	return;
}
?>

<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" dir="rtl">
<head>
	<link href='<?php echo $path?>theme/style/font-awesome.min.css' rel='stylesheet' type='text/css'>
	<link href="<?php echo $path?>theme/style/style.css" rel="stylesheet" type="text/css"/>
	
	<meta http-equiv="Content-Language" content="fa"/>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
	<title><?php echo $page_title;?></title>
	<script type="text/javascript" src="<?php echo $path?>theme/js/jquery.min.js"></script>	
	<script src="<?php echo $path?>theme/js/fileuploader.js" type="text/javascript"></script>
	
</head>
<body>
<div class="wrapper">
<div style="padding-bottom: 5px;">
	<div class="header">
		<div class="headermain">
			
			<div class="topmenu">
				<?php if($download && isset($_GET['file'])):?>
					<div class="dok"><a href="<?php echo $path ?>report.php?file=<?php 
							$_GET['file'] =  htmlspecialchars($_GET['file']);
							$_GET['server'] =  htmlspecialchars($_GET['server']);
							echo $_GET['file']."&server=".$_GET['server'];?>">گزارش مشکل</a></div>
				<?php elseif(!$download):?>
					<div class="dok"><a href="?logout">خروج</a></div>
				<?php endif;?>
				<?php if($setting['linktitle']):?>
					<div class="dok"><a href="<?php echo $setting['linkurl']?>"><?php echo $setting['linktitle']?></a></div>
				<?php endif;?>
				<div class="dok"><a href="<?php echo $path ?>index.php">پنل کاربری</a></div>
			</div>
			<div class="clear"></div>
			
			<a href="index.php"><img class="logo" src="<?php echo $path?>theme/img/logo.png"/></a>
			<div class="welmsg">به <?php echo $setting['sitename'];?> خوش آمدید.</div>
			<?php include 'c_header.php';?>
		</div>
		<div class="clear"></div>
		<div class="headernav"></div>
	</div>

<?php 
if ($download):
?>
<?php if($setting['news_top']):?>
<?php endif;?>
<div class="newstop"><div class="title"><i class="fa fa-warning"></i> اخبار سایت</div><div class="content"><?php echo $setting['news_top']?></div></div>

<div align="center" class="bodyborder" style="margin-left:auto;margin-right:auto;width:900px;height:auto;">
<?php
else: 
?>
<?php if($setting['news_top']):?>
<div class="newstop"><div class="title"><i class="fa fa-warning"></i> اخبار سایت</div><div class="content"><?php echo $setting['news_top']?></div></div>
<?php endif;?>


<?php if($path == '../'):?>
<div style="margin:0 auto;">
<?php require_once 'theme/sidebar.php';?>
<div class="bodyborder" style="width:auto;margin-left:20px;">
<div class="countiner" align="center">
<?php else:?>
<div style="margin:0 auto;width:900px;">
<?php require_once 'theme/sidebar.php';?>
<div class="bodyborder" style="width:auto;">
<div class="countiner" align="center">
<?php endif;?>


<?php endif;?>