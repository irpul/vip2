<?php

require_once '../lib/loader.php';
$loader->load_admin();
$session->is_logged_in("login.php");

if(!checkpermission('PLUGIN'))
	redirect_to('index.php');

$page_title = 'مدیریت پلاگین ها';

$pluginData = null;
foreach(glob("../plugins/*.php") as $plugin) {  
	require_once($plugin);  
}
unset($plugin);

$now = time();

if ($_GET['action']=="change" AND $_GET['uniq'])
{
	$request[uniq] = $_GET['uniq'];
	$status = $database->prepare("SELECT `plugin_status` FROM `plugin` WHERE `plugin_uniq` = ?");
	$status->execute(array($request[uniq]));
	
	$status = $status->fetch();
	$status = $status['plugin_status'];
	if (!$status)
	{
		$plugin[plugin_uniq]	= $request[uniq];
		$plugin[plugin_name]	= $pluginData[$request[uniq]][name];
		$plugin[plugin_type]	= $pluginData[$request[uniq]][type];
		$plugin[plugin_status]	= 1;
		$plugin[plugin_time]	= $now;
		$sql = $database->prepare("INSERT INTO `plugin` (plugin_uniq,plugin_name,plugin_type,plugin_status,plugin_time)
				VALUE (:plugin_uniq,:plugin_name,:plugin_type,:plugin_status,:plugin_time)");
		$sql->execute($plugin);
	}
	elseif ($status == 2)
	{
		$update[plugin_uniq]	= $request[uniq];
		$update[plugin_status]	= 1;
		$update[plugin_name]	= $pluginData[$request[uniq]][name];
		$update[plugin_type]	= $pluginData[$request[uniq]][type];
		$sql = $database->prepare("UPDATE `plugin` SET plugin_status= :plugin_status ,plugin_name = :plugin_name, plugin_type = :plugin_type
				 WHERE `plugin_uniq` = :plugin_uniq LIMIT 1");
		$sql->execute($update);
		
	}
	elseif ($status == 1)
	{
		$update[plugin_uniq]	= $request[uniq];
		$update[plugin_status]	= 2;
		$update[plugin_name]	= $pluginData[$request[uniq]][name];
		$update[plugin_type]	= $pluginData[$request[uniq]][type];
		$sql = $database->prepare("UPDATE `plugin` SET plugin_status= :plugin_status ,plugin_name = :plugin_name, plugin_type = :plugin_type
				 WHERE `plugin_uniq` = :plugin_uniq LIMIT 1");
		$sql->execute($update);
	}
	redirect_to("?save=1");
}
if($_GET['save'])
	$status = '<img src="../theme/img/icons/tick.png"/> <font color="green" face="Tahoma" style="font-size: 15px">تغییرات ذخیره شد</font>';
?>
<?php require_once $path.'theme/header.php';?>
<h1>مدیریت پلاگین ها</h1>
<?php if($status) echo $status;?>
<table style="width: 100%">
	<tr>
		<th>نام پلاگین
			<hr />
		</th>
		<th>تنظیمات پلاگین
			<hr />
		</th>
		<th>مدیریت پلاگین
			<hr />
		</th>
	</tr>
<?php
foreach($pluginData as $plugin)
{
	$status = $database->prepare("SELECT `plugin_status` FROM `plugin` WHERE `plugin_uniq` = ?");
	$status->execute(array($plugin[uniq]));	
	$status = $status->fetch();
	$status = $status['plugin_status'];
	
	if ($status == 1)
	{
		$status = '<font color="green">فعال</font> (<a href="plugin.php?action=change&uniq='.$plugin[uniq].'">غیرفعال</a>)';
		$config = '<a href="pluginsetting.php?plugin='.$plugin[uniq].'">تنظیمات</a>';
	}
	elseif ($status == 2)
	{
		$status = '<font color="red">غیرفعال</font> (<a href="plugin.php?action=change&uniq='.$plugin[uniq].'">فعال</a>)';
		$config = '<a href="pluginsetting.php?plugin='.$plugin[uniq].'">تنظیمات</a>';
	}
	else
	{
		$status = '<font color="red">نصب نشده</font> (<a href="plugin.php?action=change&uniq='.$plugin[uniq].'">نصب</a>)';
		$config = '-';
	}
	echo '<tr>';
	echo '<td>'.$plugin[name].'<br />'.$plugin[description].'</td>';
	echo '<td id="ajax">'.$config.'</td>';
	echo '<td>'.$status.'</td>';
	echo '</tr>';
}
?>
</table>
<?php require_once $path.'theme/footer.php';?>