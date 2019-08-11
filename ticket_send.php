<?php
require 'lib/loader.php';
$loader->load_user ();
$session->is_logged_in ( "login.php" );
$page_title = 'مدیریت تیکت ها';
$id = (int)$_GET['id'];
if(isset($_POST['submit']))
{
	// qq-upload-file
	
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
			if(!$title)
				throw new Exception("عنوان را مشخص نمایید");			
			
			$sql = $database->prepare("INSERT INTO `ticket` (`title`,`content`,`time`,`userid`,`usertype`,`status`,`attach` ,`file_name` )
					VALUES (?,?,?,?,'1','1',?,? )");
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
			email($setting['replyto'],"ticket_new",$param);
		}
		else
		{
			
			$sql = $database->query("SELECT `id` FROM `ticket` WHERE `id` = $id AND `userid`=$user[id]");
			if ( $sql->rowCount() != 1)
			{
				throw new Exception("اشتباهی در ثبت اطلاعات رخ داده است");
			}
			$sql = $database->prepare("INSERT INTO `ticketdata` (`parentid`,`content`,`time`,`userid`,`usertype`,`attach` ,`file_name` )
					VALUES (?,?,'$time',?,'1',?,? )");
			if(!$sql->execute(array($id,$content,$user['id'],$attach,$file)))
				throw new Exception("اشتباهی در ثبت اطلاعات رخ داده است");
			$time = getTime($time);
			$param = array(
					'id' => $id,
					'username' => $user['username'],
					'title' => $title,
					'content' => nl2br($content),
					'time' => $time,
			);
			email($setting['replyto'],"ticket_answer",$param);
			
			$sqli = $database->exec("UPDATE `ticket` SET `status`=3 WHERE `id` = $id");
		}
		
		if($sqli!==false)
		{
			/*
			if( $setting['ajax']==1 && $_GET['ajax']==2 )
			{
				redirect_to("?id=$id&ajax=1");
				die();
			}
			*/
			redirect_to("ticket_send.php?id=$id");
		}
		else
			throw new Exception("اشتباهی در ثبت اطلاعات رخ داده است");
		
	}
	catch (Exception $e)
	{
		$error='<img src="theme/img/icons/exclamation-red.png"/><font color="#FF0000" face="Tahoma" style="font-size: 8pt">'.$e->getMessage().'</font>';
		if( $setting['ajax']==1 && $_GET['ajax']==2 )
		{
			echo $error;
			die();
		}
	}
}
elseif(isset($_GET['close']))
{
	$database->exec("UPDATE `ticket` SET `status`=0 WHERE `id` = $id AND userid=$user[id]");
}
$page_title = $id ? "مشاهده تیکت" : "ارسال تیکت جدید" ;
?>
<?php require_once 'theme/header.php';?>
<script type="text/javascript">
        function createUploader(){
        var uploader = new qq.FileUploader({
                element: document.getElementById('upload'),
                listElement: document.getElementById('separate-list'),
                action: 'upload.php',
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
	$ticket = $database->query("SELECT * FROM `ticket` WHERE `id` = $id AND `userid` = $user[id]");
	
	if ( $ticket->rowCount() != 1)
	{
		redirect_to('ticket.php');
	}
	$ticket = $ticket->fetch();
	
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
?>
	<form method="get" action="ticket_send.php">
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
		<div class="detail"><?php echo $user['username'];?></div>
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
	$content = nl2br($content);
	$time = getTime($ticket['time']);
	if ( $usertype == 1 )
	{
		if ($attach)
			$content .= "<hr/> پیوست : <a href='".SITE_PATH."uploads/{$file}'>دانلود</a>";
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
			$content .= "<hr/> پیوست : <a href='".SITE_PATH."uploads/{$file}'>دانلود</a>";
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
<form method="post" action="ticket_send.php?id=<?php echo $id;?>">
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
<span id="error">
<?php echo $error;?>
</span>
<form method="post" action="ticket_send.php" id="ajaxform">
<table style="width: 100%">
	<tr>
		<td><label for="title">موضوع :</label></td>
		<td>
			<input type="text" value="<?php echo $title;?>" id="title" name="title" required/>
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
<?php require_once 'theme/footer.php';?>