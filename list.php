<?php
if ( !isset($download) ) die();


$file = $file['data'];
foreach( $file as $key=>$value )
{
if(!isset($_GET['rewrite']))
	$file[$key]['link'] = "file.php?server=" . $_GET['server'] . "&file=" .$fileencode. '/' . $value['name'] ;
else
	$file[$key]['link'] = SITE_PATH . $_GET['server'] . "/" .$_GET['file']. '/' . $value['name'] ;
	$file[$key]['type'] = pathinfo($value['name'],PATHINFO_EXTENSION);
	
	$size = $file[$key]['size'];
	$totalsize += $size;
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
	
	$file[$key]['size'] = $size;
}
	
	if($totalsize < 1024) {
		$totalsize = "{$totalsize} bytes";
	} elseif($totalsize < 1048576) {
		$totalsize = round($totalsize/1024 , 2);
		$totalsize = "{$totalsize} KB";
	} elseif ($totalsize < 1073741824) {
		$totalsize = round($totalsize/1048576, 2);
		$totalsize = "{$totalsize} MB";
	}
	else {
		$totalsize = round($totalsize/1073741824, 2);
		$totalsize = "{$totalsize} GB";
	}
$page_title = 'محتویات پوشه '. htmlentities($_GET['file']);
?>
<?php require_once 'theme/header.php';?>
<div class="fileinfo"><div class="title">محتویات پوشه </div> : <?php echo htmlentities($_GET['file']);?><br/>
	<div class="info">
	نوع فایل : <?php echo $file[0]['type']; ?> - حجم کل : <?php echo $totalsize;;?></div>
	<div class="clear"></div>
	</div>

<table style="width: 100%">
	<tr>
		<th style="width:10%">ردیف<hr/></th>
		<th style="width:50%">لینک فایل<hr/></th>
		<th style="width:20%">حجم<hr/></th>
		<th style="width:10%">نوع فایل<hr/></th>
	</tr>
	<?php
	$i = 1;
	foreach($file as $f )
	{
		$f[type] = $f[type] ? $f[type] : 'پوشه' ;
		echo "<tr>";
		echo "<td>$i</td>";
		echo "<td><a href=\"$f[link]\">$f[name]</a></td>";
		echo "<td>$f[size]</td>";
		echo "<td>$f[type]</td>";
		echo "</tr>";
		$i++;
	}
	?>
</table>
<?php if($setting['dltext']):?>
<a href="/payment.php">
<div class="fileinfo" style="text-align: center">
<?php echo $setting['dltext']?>
</div>
</a>
<?php endif;?>
<?php require_once 'theme/footer.php';?>
