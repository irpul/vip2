<?php
require_once '../lib/loader.php';
$loader->load_admin();
$session->is_logged_in("login.php");

if(!checkpermission('TICKET'))
	redirect_to('index.php');

$page_title = 'مدیریت تیکت ها';

$id = (int)$_GET['id'];
if(isset($_POST['submit']))
{
	// qq-upload-file
	
	$to = $_POST['to'];
	$title = $_POST['title'];
	$content = $_POST['content'];
	$file = $_POST['qq-upload-file'];
	if($file && file_exists("uploads/".$file))
		$attach = 1;
	else
	{
		$file = "";
		$attach = 0;
	}
	try {
		
		if(!$content)
			throw new Exception("متن پیغام را مشخص نمایید");
		
		$time = time();
		if ($id == 0)
		{
			if(!$to)
				throw new Exception("مقصد پیغام را مشخص نمایید");
			$user = $database->prepare("SELECT * FROM `users` WHERE `username` = ?");
			$user->execute(array($to));
			$user = $user->fetch();
			if(!$user)
				throw new Exception("این نام کاربری وجود ندارد");
			
			if(!$title)
				throw new Exception("عنوان را مشخص نمایید");			
			
			$sql = $database->prepare("INSERT INTO `ticket` (`title`,`content`,`time`,`userid`,`usertype`,`status`,`attach` ,`file_name` )
					VALUES (?,?,?,?,2,4,?,? )");
			$sqli = $sql->execute(array($title,$content,$time,$user['id'],$attach,$file));
			
			$id = $database->lastInsertId();
		
			$time = getTime($time);
			$param = array(
					'id' => $id,
					'username' => $user['username'],
					'title' => $title,
					'content' => nl2br($content),
					'time' => $time,
			);
			email($user['email'],"ticket_new",$param);
		}
		else
		{
			
			$data = $database->query("SELECT * FROM `ticket` WHERE `id` = $id");
			if ( $data->rowCount() != 1)
			{
				throw new Exception("اشتباهی در ثبت اطلاعات رخ داده است");
			}
			$sql = $database->prepare("INSERT INTO `ticketdata` (`parentid`,`content`,`time`,`userid`,`usertype`,`attach` ,`file_name` )
					VALUES (?,?,'$time',?,'2',?,? )");
			if(!$sql->execute(array($id,$content,$admin['id'],$attach,$file)))
				throw new Exception("اشتباهی در ثبت اطلاعات رخ داده است");
			
			$database->exec("UPDATE `ticket` SET `status`=2 WHERE `id` = $id");
			
			$data = $data->fetch();
			$title = $data['title'];
			$data = $database->query("SELECT * FROM `users` WHERE `id` = $data[userid]")->fetch();
			$time = getTime($time);
			
			$param = array(
					'id' => $id,
					'username' => $data['username'],
					'title' => $title,
					'content' => nl2br($content),
					'time' => $time,
			);
			email($data['email'],"ticket_answer",$param);
			
			$sqli = 1;
		}
		
		if($sqli)
		{
			redirect_to("admin_ticket_send.php?id=$id");
		}
		else
			throw new Exception("اشتباهی در ثبت اطلاعات رخ داده است");
		
	}
	catch (Exception $e)
	{
		$error='<img src="../theme/img/icons/exclamation-red.png"/> <font color="#FF0000" face="Tahoma" style="font-size: 15px">'.$e->getMessage().'</font>';
	}
}
elseif(isset($_GET['close']))
{
	$database->exec("UPDATE `ticket` SET `status`=0 WHERE `id` = $id");
}
$page_title = $id ? "مشاهده تیکت" : "ارسال تیکت جدید" ;
?>
<?php require_once $path.'theme/header.php';?>
<script type="text/javascript">
        function createUploader(){
        var uploader = new qq.FileUploader({
                element: document.getElementById('upload'),
                listElement: document.getElementById('separate-list'),
                action: '../upload.php',
                onComplete: function(a,b,c){
                    $('input[type=hidden][name=qq-upload-file]').val(c.filename);
                }
            });
        }
        $(function() { createUploader(); } );
</script>
<?php 
if (isset($_GET['id']) )
{
	$ticket = $database->query("SELECT * FROM `ticket` WHERE `id` = $id");
	
	if ( $ticket->rowCount() != 1)
	{
		redirect_to('admin_ticket.php');
	}
	$ticket = $ticket->fetch();
	$userid= $ticket['userid'];
	$status = $ticket['status'];
	$time = getTime($ticket['time']);
	$content = $ticket['content'];
	$usertype = $ticket['usertype'];
	$attach = $ticket['attach'];
	$file = $ticket['file_name'];
	
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
	$user = $database->query("SELECT * FROM `users` WHERE `id` = $userid")->fetch();
	$category = $database->query("SELECT `title` FROM `category` WHERE id = $user[categoryid]")->fetch();
	$category = $category['title'];
?>
	<form method="get" action="admin_ticket_send.php">
	<h1>تیکت : <?php echo $ticket[title]?>
	<span style="float:left">
	<input type="hidden" name="close" value="1">
	<button class="button red" type="submit" name="id" value="<?php echo $id?>">بستن</button>
	</span></h1>
	</form>
<?php
	echo $error;
?>
<table style="width: 100%;" class="ticketdetailscontainer">
<tr>
	<td>
		ارسال شده
	</td>
	<td>
		نام کاربری
	</td>
	<td>
		وضعیت
	</td>
</tr>
<tr>
	<td>
		<div class="detail"><?php echo $time;?></div>
	</td>
	<td>
		<div class="detail"><?php echo $user['username'];?> | <?php  echo $category;?></div>
	</td>
	<td>
		<div class="detail"><?php echo $status;?></div>
	</td>
</tr>
</table>	
<div class="ticketmsgs">
<?php 
$tickets = $database->query("SELECT * FROM `ticketdata` WHERE `parentid` = $id");

$num = $tickets->rowCount();
for($i=1 ; $i<=$num+1 ; $i++ )
{
	$time = getTime($ticket['time']);
	$content = nl2br($content);
	if ( $usertype == 1 )
	{
		if ($attach)
			$content .= "<hr/> پیوست : <a href='/uploads/{$file}'>دانلود</a>";
		echo '<div class="clientheader">
				<div style="float: right;">'.$time.' &nbsp;</div>
					'.$user['username'].' || کاربر
				</div>
				<div class="clientmsg">
					'.$content.' 
			</div>';
	}
	else 
	{
		if ($attach)
			$content .= "<hr/> پیوست : <a href='/uploads/{$file}'>دانلود</a>";
		echo '<div class="adminheader">
							<div style="float: right;">'.$time.' &nbsp;</div>
							ادمین || کارمندان
						</div>
						<div class="adminmsg">
							'.$content.'
						</div>';
	}
	$ticket = $tickets->fetch();
	$time = $ticket['time'];
	$content = $ticket['content'];
	$usertype = $ticket['usertype'];
	$attach = $ticket['attach'];
	$file = $ticket['file_name'];
}?>
</div>
<form method="post" action="admin_ticket_send.php?id=<?php echo $id;?>">
<table style="width: 100%">
	<tr>
		<td><label for="content">متن :</label></td>
		<td>
			<textarea style="height: 200px; width: 98%" id="content" name="content"><?php echo htmlentities($content);?></textarea>
		</td>
	</tr>
	<tr>
		<td><label for="upload">آپلود فایل</label></td>
		<td>
			<div class="qq-upload-image"></div>
			<div id="upload"></div>
			<ul id="separate-list" class="qq-upload-list"><li></li></ul>
			<input type="hidden" name="qq-upload-file" id="qq-upload-file" value="<?php echo $file;?>" /></td>
	</tr>
	<tr>
		<td></td>
		<td>
			<button type="submit" name="submit" class="button gray">ارســال</button>
			<button type="reset" name="reset" class="button red">انصراف</button>
		</td>
	</tr>
</table>
</form>
<?php
}
else
{
?>
<h1>ارسال تیکت جدید</h1>
<?php echo $error;?>
<form method="post" action="admin_ticket_send.php">
<table style="width: 100%">
	<tr>
		<td><label for="to">به :</label></td>
		<td>
			<input type="text" value="<?php echo $to;?>" id="to" name="to" />
		</td>
	</tr>
	<tr>
		<td><label for="title">موضوع :</label></td>
		<td>
			<input type="text" value="<?php echo $title;?>" id="title" name="title" />
		</td>
	</tr>
	<tr>
		<td><label for="content">متن :</label></td>
		<td>
			<textarea style="height: 200px; width: 98%" id="content" name="content"><?php echo htmlentities($content);?></textarea>
		</td>
	</tr>
	<tr>
		<td><label for="upload">آپلود فایل</label></td>
		<td>
			<div class="qq-upload-image"></div>
			<div id="upload"></div>
			<ul id="separate-list" class="qq-upload-list"><li></li></ul>
			<input type="hidden" name="qq-upload-file" id="qq-upload-file" value="<?php echo $file;?>" /></td>
	</tr>
	<tr>
		<td></td>
		<td>
			<button type="submit" name="submit" class="button gray">ارســال</button>
			<button type="reset" name="reset" class="button red">انصراف</button>
		</td>
	</tr>
</table>
</form>
<?php }?>
<?php require_once $path.'theme/footer.php';?>