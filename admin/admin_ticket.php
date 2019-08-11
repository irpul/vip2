<?php
require_once '../lib/loader.php';
$loader->load_admin();
$session->is_logged_in("login.php");

if(!checkpermission('TICKET'))
	redirect_to('index.php');

$page_title = 'مدیریت تیکت ها';

if ($_GET['delete'])
{
	$sql = $database->prepare("DELETE FROM `ticket` WHERE id = ?");
	$sql2= $database->prepare("DELETE FROM `ticketdata` WHERE `parentid` = ?");
	if ($sql->execute(array ($_GET['id'])) && $sql->execute(array($_GET['id'])) !== false)
		$status = '<img src="../theme/img/icons/tick.png"/> <font color="green" face="Tahoma" style="font-size: 8pt">دسته مورد نظر با موفقیت حذف گردید</font>';
	else
		$status = '<img src="../theme/img/icons/exclamation-red.png"/> <font color="#FF0000" face="Tahoma" style="font-size: 8pt">حذف دسته مورد نظر با مشکل روبرو شد!</font>';
}elseif ($_GET['close'])
{
	$sql = $database->prepare("UPDATE `ticket` SET `status`=0 WHERE `id` = ?");

	if ($sql->execute(array ($_GET['id'])))
		$status = '<img src="../theme/img/icons/tick.png"/> <font color="green" face="Tahoma" style="font-size: 8pt">تیکت بسته شد</font>';
	else
		$status = '<img src="../theme/img/icons/exclamation-red.png"/> <font color="#FF0000" face="Tahoma" style="font-size: 8pt">تغییرات مورد نظر با مشکل روبرو شد!</font>';
}
?>
<?php require_once $path.'theme/header.php';?>
<h1>مدیریت تیکت ها</h1>
<div class="fileinfo" style="text-align: center;">
<table style="width:100%">
<tr id="ajax">
	<td>لیست تیکت:</td>
	<td><a href="admin_ticket.php?action=all">تمام تیکت ها</a></td>
	<td> | </td>
	<td><a href="admin_ticket.php?action=1"><font color="#008000">در انتظار پاسخ</font></a></td>
	<td> | </td>
	<td><a href="admin_ticket.php?action=3"><font color="#800000">پاسخ مشتری</font></a></td>
	<td> | </td>
	<td><a href="admin_ticket.php?action=2"><font color="#000080">پاسخ کارمندان</font></a></td>
	<td> | </td>
	<td><a href="admin_ticket.php?action=0"><font color="#008080">تیکت های بسته</font></a></td>
</tr>
</table>
</div>
<?php if($status) echo $status;?>
<?php
if(isset($_GET['action']))
{
	?>
<table style="width: 100%">
	<tr>
		<th>شناسه تیکت
			<hr />
		</th>
		<th>عنوان تیکت
			<hr />
		</th>
		<th>نام کاربری
			<hr />
		</th>
		<th>وضعیت
			<hr />
		</th>
		<th>تاریخ ارسال
			<hr />
		</th>
		<th>مدیریت تیکت
			<hr />
		</th>
	</tr>
	<?php
		if($_GET['action'] == "all")
		{
			$_GET['page'] = $_GET['page'] ? (int)$_GET['page'] : 1;
			$count = "SELECT COUNT(*) as count FROM `ticket`";
			
			$count = $database->query($count)->fetch();
			
			$page = new Pagination($_GET['page'], $setting['pagelimit'], $count['count']);
			
			$tickets = $database->query("SELECT * FROM `ticket`  ORDER BY `time` DESC LIMIT $setting[pagelimit] OFFSET {$page->offset()}");
		}
		else
		{
			$_GET[action] = (int)$_GET[action];
			$_GET['page'] = $_GET['page'] ? (int)$_GET['page'] : 1;
			$count = "SELECT COUNT(*) as count FROM `ticket` WHERE `status` = $_GET[action]";
			
			$count = $database->query($count)->fetch();
			
			$page = new Pagination($_GET['page'], $setting['pagelimit'], $count['count']);
			
			$tickets = $database->query("SELECT * FROM `ticket` WHERE `status` = $_GET[action] ORDER BY `time` DESC LIMIT $setting[pagelimit] OFFSET {$page->offset()}");	
		}
				
	while ( $ticket = $tickets->fetch() )
	{
		switch ($ticket['status'])
		{
			case 0 :
				$status = '<span style="color:#008080">بسته شده</span>';
				break;
			case 1 :
				$status = '<span style="color:#008000">در انتظار پاسخ</span>';
				break;
			case 4 :
			case 2 :
				$status = '<span style="color:#000080">پاسخ کارمندان</span>';
				break;
			case 3 :
				$status = '<span style="color:#800000">پاسخ مشتری</span>';
				break;
			default:
				die("Error!");
		}
		$user = $database->query("SELECT `username` FROM `users` WHERE `id` = $ticket[userid]")->fetch();
		
		echo '<tr>';
		echo '<td><a href="admin_ticket_send.php?id='.$ticket['id'].'">' . $ticket['id'] . '</td>';
		echo '<td><a href="admin_ticket_send.php?id='.$ticket['id'].'">' . $ticket['title'] . '</td>';
		echo '<td><a href="admin_ticket_send.php?id='.$ticket['id'].'">' . $user['username'] . '</td>';
		echo '<td><a href="admin_ticket_send.php?id='.$ticket['id'].'">' . $status . '</td>';
		echo '<td><a href="admin_ticket_send.php?id='.$ticket['id'].'">' . getTime($ticket['time']) . '</td>';
		echo '<td><a onclick="if (confirm(\'ایا از حذف تیکت انتخاب شده مطمئن هستید؟\')) window.location = \'admin_ticket.php?id='.$ticket['id'].'&delete=1\';"
				href="#"><img src="../theme/img/page_delete.png" title="حذف تیکت" border="0"></a> <a href="admin_ticket.php?close=1&id='.$ticket['id'].'">بستن تیکت</a></td>';
		echo '</tr>';
	}
	?>
</table>
<br/>
<div style="direction: ltr">
<?php echo $page->get_pagination('action='.$_GET['action']);?>
</div>
<?php }?>
<?php require_once $path.'theme/footer.php';?>