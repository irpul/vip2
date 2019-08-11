<?php

require_once '../lib/loader.php';
$loader->load_admin();
$session->is_logged_in("login.php");

if(!checkpermission('PLUGIN'))
	redirect_to('index.php');

$page_title = 'تنظیمات پلاگین';
if ($_GET['plugin'] AND is_installed($_GET['plugin']))
{
	$pluginData = null;
	foreach(glob("../plugins/*.php") as $plugin) {
		require_once($plugin);
	}
	unset($plugin);
	
	if(isset($_POST['submit']))
	{
		foreach ($pluginData[$_GET['plugin']][field][config] as $field)
		{
			$sql = 'SELECT * FROM `plugindata` WHERE `plugindata_uniq` = ? AND `plugindata_field_name` = ?';
			$data = $db->prepare($sql);
			$data->execute(array($pluginData[$_GET['plugin']][uniq],$field[name]));
			$data = $data->fetch();
			if (isset($data[plugindata_field_value]))
			{
				$sql = $db->prepare("UPDATE `plugindata` SET `plugindata_field_value` = ? WHERE `plugindata_uniq`= ? AND `plugindata_field_name` = ? LIMIT 1");
				$s = $sql->execute(array($_POST[$field[name]],$pluginData[$_GET['plugin']][uniq],$field[name]));
			}
			else
			{
				$insert[] 		= $pluginData[$_GET['plugin']][uniq];
				$insert[] 	= $field[name];
				$insert[] = $_POST[$field[name]];
				$sql = $db->prepare("INSERT INTO `plugindata` (`plugindata_uniq`,`plugindata_field_name`,`plugindata_field_value`)
						 VALUE (?,?,?)");
				$s = $sql->execute($insert);
			}
		}
		if (!$s)
			$status = '<img src="../theme/img/icons/exclamation-red.png"/> <font color="#FF0000" face="Tahoma" style="font-size: 15px">ذخیره اطلاعات با مشکل مواجه شد</font>';
		else
			$status = '<img src="../theme/img/icons/tick.png"/> <font color="green" face="Tahoma" style="font-size: 15px">اطلاعات با موفقیت ذخیره شد</font>';
		
	}
}
else
{
	redirect_to('plugin.php');
}
function is_installed($uniq)
{
	global $db;
	$result = $db->prepare("SELECT `plugin_id` FROM `plugin` WHERE `plugin_uniq` = ?");
	$result->execute(array($uniq));

	if($result->rowCount())
	{
		return true;
	}
	else
	{
		return false;
	}
}
if( $setting['ajax']==1 && $_GET['ajax']==2 )
{
	echo $status;
	die();
}

$plugin = $pluginData[$_GET['plugin']];
?>
<?php require_once $path.'theme/header.php';?>
<h1>تنظیمات پلاگین</h1>
<span id="error">
<?php echo $status;?>
</span>
<form id="ajaxform" action="pluginsetting.php?plugin=<?php echo $_GET['plugin']?>" method="post" style="text-align: right;font-family: BYekan">
<div class="legend">لطفا اطلاعات را وارد نمایید</div>
<?php if($plugin[note]) echo '<div class="legend">'.$plugin[note].'</div>';?>
<?php

	foreach ($plugin[field][config] as $field)
	{
		$sql = 'SELECT * FROM `plugindata` WHERE `plugindata_uniq` = ? AND `plugindata_field_name` = ?';
		$data = $db->prepare($sql);
		$data->execute(array($plugin[uniq],$field[name]));
		$data = $data->fetch();
		echo "<label for=\"$field[name]\">$field[title]</label>".'<br/>';
		echo '<input type="text" name="'.$field[name].'" id="'.$field[name].'" value="'.$data[plugindata_field_value].'" style="width:250px;"/>'.'<br/><br/>';
	}
?>

<br/><br/>
<button type="submit" name="submit" class="button gray">ذخیره</button>
<button type="reset" name="reset" class="button red">انصراف</button>

</form>
<?php require_once $path.'theme/footer.php';?>