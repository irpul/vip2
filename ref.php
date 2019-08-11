<?php
require 'lib/loader.php';
$loader->load_user ();
$session->is_logged_in ( "login.php" );
$page_title = 'کاربران معرفی شده';

?>
<?php require_once 'theme/header.php';?>
<h1>کاربران معرفی شده</h1> 

<table style="width:100%;text-align: center;">
<thead>
<tr>
	<th>ردیف<hr/></th>
	<th>تعداد روز هدیه<hr/></th>
	<th>کاربر<hr/></th>
	<th>زمان<hr/></th>
</tr>
</thead>
<tbody>
<?php 
	$_GET['page'] = $_GET['page'] ? (int)$_GET['page'] : 1;

	
	$count = "SELECT COUNT(*) as count FROM `users_ref` WHERE `userid`=$user[id]";

	$count = $database->query($count)->fetch();

	$page = new Pagination($_GET['page'], $setting['pagelimit'], $count['count']);
	
	$query = "SELECT * FROM `users_ref` WHERE `userid`=$user[id] ORDER BY `time` DESC LIMIT $setting[pagelimit] OFFSET {$page->offset()}";
	$refs = $database->query($query);
	$i=0;
	
	while($ref = $refs->fetch()):
		$from = $database->query("SELECT `username` FROM users WHERE id = $ref[from]")->fetch();
		$from = $from['username'];
	?>
	<tr>
		<td><?php echo ++$i?></td>
		<td><?php echo $ref['day']?></td>
		<td><?php echo $from?></td>
		<td><?php echo getTime($ref['time'])?></td>
	</tr>
	<?php endwhile;?>
						</tbody>
					</table>
					<br/>
					<div style="direction: ltr">
						<?php echo $page->get_pagination('action='.$_GET['action']);?>
						
					</div>
		
<?php require_once 'theme/footer.php';?>