<?php
require 'lib/loader.php';
$loader->load_user ();
$session->is_logged_in ( "login.php" );
$page_title = 'مدیریت تیکت ها';

$id = (int)$_GET['id'];
if(isset($_GET['close']))
{
	if($database->exec("UPDATE `ticket` SET `status`=0 WHERE `id`= $id AND `userid` = $user[id]"))
	{
		$error = '<img src="theme/img/icons/tick.png"/><font color="green" face="Tahoma" style="font-size: 8pt">تیکت بسته شد</font>';
	}
	else
		$error = '<img src="theme/img/icons/exclamation-red.png"/><font color="#FF0000" face="Tahoma" style="font-size: 8pt">خطا در ذخیره سازی اطلاعات</font>';
	
}
?>
<?php require_once 'theme/header.php';?>
<h1>مدیریت تیکت ها</h1>
<?php if($error) echo $error;?>
<div class="fileinfo" style="text-align: center;">
<table style="width:100%">
<tr id="ajax">
	<td>لیست تیکت:</td>
	<td><a href="ticket.php?action=all">تمام تیکت ها</a></td>
	<td> | </td>
	<td><a href="ticket.php?action=1"><font color="#008000">تیکت های باز</font></a></td>
	<td> | </td>
	<td><a href="ticket.php?action=0"><font color="#008080">تیکت های بسته</font></a></td>
</tr>
</table>
</div>
<?php 
if(isset($_GET['action']) )
{
?>
<table style="width:100%;text-align: center;">
<thead>
<tr>
	<th>ردیف<hr/></th>
	<th>عنوان تیکت<hr/></th>
	<th>وضعیت<hr/></th>
	<th>تاریخ ارسال<hr/></th>
	<th>عملیات مدیریت<hr/></th>
</tr>
</thead>
<tbody>
<?php 
	$_GET['page'] = $_GET['page'] ? (int)$_GET['page'] : 1;

	if ($_GET['action'] == '0')
	{
		$status = ' AND `status` = 0 ';
	}
	elseif($_GET['action'] == '1')
	{
		$status = ' AND `status` != 0 ';
	}
	else
	{
		$status = null;
	}
	$count = "SELECT COUNT(*) as count FROM `ticket` WHERE `userid`=$user[id] $status";

	$count = $database->query($count)->fetch();

	$page = new Pagination($_GET['page'], $setting['pagelimit'], $count['count']);
	
	$query = "SELECT * FROM `ticket` WHERE `userid`=$user[id] $status ORDER BY `time` DESC LIMIT $setting[pagelimit] OFFSET {$page->offset()}";
	$tickets = $database->query($query);
	$i=0;
	
	while($ticket = $tickets->fetch())
	{
		$i++;
		$id = $ticket['id'];
		$title = $ticket['title'];
		$time = getTime($ticket['time']);
		$status = $ticket['status'];
		switch ($status)
		{
			case 0 :
				$status = '<span style="color:#008080">بسته شده</span>';
				break;
			case 1 :
				$status = '<span style="color:#008000">باز</span>';
				break;
			case 4 :
			case 2 :
				$status = '<span style="color:#000080">پاسخ کارمندان</span>';
				break;
			case 3 :
				$status = '<span style="color:#800000">پاسخ مشتری</span>';
				break;
			default:
				redirect_to('ticket.php');
		}
?>
		<tr>
			<td><a href="ticket_send.php?id=<?php echo $id;?>"><?php echo $i;?></a></td>
			<td><a href="ticket_send.php?id=<?php echo $id;?>"><?php echo $title;?></a></td>
			<td><a href="ticket_send.php?id=<?php echo $id;?>"><?php echo $status;?></a></td>
			<td><a href="ticket_send.php?id=<?php echo $id;?>"><?php echo $time;?></a></td>
			<td><a href="?action=<?php echo $_GET['action']?>&close=1&id=<?php echo $id;?>">بستن تیکت</a></td>
		</tr>
<?php } ?>
						</tbody>
					</table>
					<br/>
					<div style="direction: ltr">
						<?php echo $page->get_pagination('action='.$_GET['action']);?>
						
					</div>
<?php }?>
		
<?php require_once 'theme/footer.php';?>