<?php
require_once '../lib/loader.php';
$loader->load_admin();
$session->is_logged_in("login.php");

if(!checkpermission('EMAIL'))
	redirect_to('index.php');

$page_title = 'مدیریت ایمیل ها';

if (isset($_POST ['submit'])) {
	$data['name'] = $_POST['name'];
	$data['emaillimit'] = $_POST['emaillimit'];
	$data['signature'] = $_POST['signature'];
	$data['email'] = $_POST['email'];
	$data['replyto'] = $_POST['replyto'];
	$data['reportsubject'] = $_POST['reportsubject'];
	$data['ticket_new_subject'] = $_POST['ticket_new_subject'];
	$data['ticket_new_content'] = $_POST['ticket_new_content'];
	$data['ticket_answer_subject'] = $_POST['ticket_answer_subject'];
	$data['ticket_answer_content'] = $_POST['ticket_answer_content'];
	$data['register_subject'] = $_POST['register_subject'];
	$data['register_content'] = $_POST['register_content'];
	$data['forgot_subject'] = $_POST['forgot_subject'];
	$data['forgot_content'] = $_POST['forgot_content'];
	$data['ekhtar1_subject'] = $_POST['ekhtar1_subject'];
	$data['ekhtar1_content'] = $_POST['ekhtar1_content'];
	$data['ekhtar1_time'] = $_POST['ekhtar1_time'];
	$data['ekhtar2_subject'] = $_POST['ekhtar2_subject'];
	$data['ekhtar2_content'] = $_POST['ekhtar2_content'];
	$data['ekhtar2_time'] = $_POST['ekhtar2_time'];
	$data['issmtp'] = $_POST['issmtp'];
	$data['smtpauth'] = $_POST['smtpauth'];
	$data['smtpusername'] = $_POST['smtpusername'];
	$data['smtppassword'] = $_POST['smtppassword'];
	$data['smtphost'] = $_POST['smtphost'];
	$data['smtpport'] = $_POST['smtpport'];
	$data['smtpsecure'] = $_POST['smtpsecure'];
	
	$sql = $database->prepare("UPDATE `setting` SET 
			`name` = :name,
			`emaillimit` = :emaillimit,
			`signature` = :signature ,
			`email` = :email,
			`replyto` = :replyto,
			`reportsubject` = :reportsubject,
			`ticket_new_subject`= :ticket_new_subject , 
			`ticket_new_content`= :ticket_new_content , 
			`ticket_answer_subject`= :ticket_answer_subject , 
			`ticket_answer_content`= :ticket_answer_content , 
			`register_subject`= :register_subject , 
			`register_content`= :register_content , 
			`forgot_subject`= :forgot_subject , 
			`forgot_content`= :forgot_content , 
			`ekhtar1_subject`= :ekhtar1_subject, 
			`ekhtar1_content`= :ekhtar1_content , 
			`ekhtar1_time` = :ekhtar1_time,
			`ekhtar2_subject`= :ekhtar2_subject,
			`ekhtar2_content`= :ekhtar2_content,
			`ekhtar2_time` = :ekhtar2_time ,
			`issmtp` = :issmtp,
			`smtpauth` = :smtpauth,
			`smtpusername` = :smtpusername,
			`smtppassword` = :smtppassword,
			`smtphost` = :smtphost,
			`smtpport` = :smtpport,
			`smtpsecure` = :smtpsecure
			");
	
	if ( $sql->execute($data) )
		$sus = "اطلاعات با موفقیت ذخیره شد";
	else
		$error = "خطا در ذخیره سازی اطلاعات";
} 
$data = $database->query("SELECT * FROM `setting`")->fetch();

foreach ($data as $key=>$value)
{
	$data[$key] = stripcslashes($value);
}

if ($error)
	$status = '<img src="../theme/img/icons/exclamation-red.png"/> <font color="#FF0000" face="Tahoma" style="font-size: 15px">' . $error . '</font>';
else if ($sus)
	$status = '<img src="../theme/img/icons/tick.png"/> <font color="green" face="Tahoma" style="font-size: 15px">' . $sus . '</font>';

if( $setting['ajax']==1 && $_GET['ajax']==2 )
{
	echo $status;
	die();
}
?>
<?php require_once $path.'theme/header.php';?>

<h1>مدیریت ایمیل</h1>
<span id="error">
<?php echo $status;?>
</span>
<form method="post" id="ajaxform" style="text-align: right;font-family: BYekan;" action="email.php">

<div class="left">
<div class="legend">تنظیمات smtp</div>
<label for="issmtp">ارسال با smtp</label>
<br/>
<select name="issmtp" id="issmtp">
<option value="0">غیرفعال</option>
<option value="1" <?php if($data['issmtp']==1) echo "selected"?>>فعال</option>
</select>
<br/>

<label for="smtpauth">تأیید اعتبار</label>
<br/>
<select name="smtpauth" id="smtpauth">
<option value="0">غیرفعال</option>
<option value="1" <?php if($data['smtpauth']==1) echo "selected"?>>فعال</option>
</select>
<br/>

<label for="smtpusername">نام کاربری</label>
<br/>
<input type="text" class="ltr" value="<?php echo $data['smtpusername'];?>" id="smtpusername" name="smtpusername"/>
<br/>

<label for="smtppassword">کلمه عبور</label>
<br/>
<input type="password" class="ltr" value="<?php echo $data['smtppassword'];?>" id="smtppassword" name="smtppassword"/>
<br/>

<label for="smtphost">سرور (برای گوگل smtp.gmail.com )</label>
<br/>
<input type="text" class="ltr" value="<?php echo $data['smtphost'];?>" id="smtphost" name="smtphost"/>
<br/>

<label for="smtpport">پورت پورت ( برای گوگل 465 )</label>
<br/>
<input type="text" class="ltr" value="<?php echo $data['smtpport'];?>" id="smtpport" name="smtpport"/>
<br/>

<label for="smtpsecure">امنیت ( برای گوگل ssl )</label>
<br/>
<select name="smtpsecure" id="smtpauth" class="ltr">
<option value="">هیچ</option>
<option value="ssl" <?php if($data['smtpsecure']=="ssl") echo "selected"?>>ssl</option>
<option value="tls" <?php if($data['smtpsecure']=="tls") echo "selected"?>>tls</option>
</select><br/><br/>
</div>


<div>
<div class="legend">تنظیمات ایمیل</div>
<label for="name">نام</label>
<br/>
<input type="text" value="<?php echo $data['name'];?>" id="name" name="name"/>
<br/><br/>

<label for="email">ایمیل</label>
<br/>
<input type="text" value="<?php echo $data['email'];?>" id="email" name="email"/>	
<br/><br/>

<label for="replyto">ارسال پاسخ ایمیل ها</label>
<br/>
<input type="text" value="<?php echo $data['replyto'];?>" id="replyto" name="replyto"/>	
<br/><br/>

<label for="emaillimit">محدودیت ارسال در ساعت</label>
<br/>
<input type="text" value="<?php echo $data['emaillimit'];?>" id="emaillimit" name="emaillimit"/>	
<br/><br/>

<label for="reportsubject">موضوع ایمیل ارسال گزارش خرابی</label>
<br/>
<input type="text" value="<?php echo $data['reportsubject'];?>" id="reportsubject" name="reportsubject"/>	
<br/><br/>

<label for="signature">امضا</label>
<br/>
<input type="text" value="<?php echo $data['signature'];?>" id="signature" name="signature"/>
<br/><br/>

<label for="ticket_new_subject">موضوع ارسال تیکت جدید</label>
<br/>
<input type="text" value="<?php echo $data['ticket_new_subject'];?>" id="ticket_new_subject" name="ticket_new_subject"/>
<br/><br/>

<label for="ticket_new_content">متن ارسال تیکت جدید ( {id} , {username} , {title} , {content} , {time} )</label>
<br/>
<textarea id="ticket_new_content" name="ticket_new_content"><?php echo $data['ticket_new_content']?></textarea>
<br/><br/>

<label for="ticket_answer_subject">موضوع پاسخ تیکت</label>
<br/>
<input type="text" value="<?php echo $data['ticket_answer_subject'];?>" id="ticket_answer_subject" name="ticket_answer_subject"/>
<br/><br/>

<label for="ticket_answer_content">متن پاسخ تیکت ( {id} , {username} , {title} , {content} , {time} )</label>
<br/>
<textarea id="ticket_answer_content" name="ticket_answer_content"><?php echo $data['ticket_answer_content']?></textarea>
<br/><br/>

<label for="register_subject">موضوع ایمیل پرداخت</label>
<br/>
<input type="text" value="<?php echo $data['register_subject'];?>" id="register_subject" name="register_subject"/>
<br/><br/>
			
<label for="register_content">متن ایمیل پرداخت ( {username} , {password} , {email} , {time} , {resnum} , {refnum} , {category} )</label>
<br/>
<textarea id="register_content" name="register_content"><?php echo $data['register_content']?></textarea>
<br/><br/>

<label for="forgot_subject">موضوع ایمیل فراموشی</label>
<br/>
<input type="text" value="<?php echo $data['forgot_subject'];?>" id="forgot_subject" name="forgot_subject"/>
<br/><br/>

<label for="forgot_content">متن ایمیل فراموشی ({username} , {time} , {code})</label>
<br/>
<textarea id="forgot_content" name="forgot_content"><?php echo $data['forgot_content']?></textarea>
<br/><br/>

<label for="ekhtar1_time">زمان اخطاریه اول ( به روز )</label>
<br/>
<input type="text" value="<?php echo $data['ekhtar1_time'];?>" id="ekhtar1_time" name="ekhtar1_time"/>
<br/><br/>

<label for="ekhtar1_subject">موضوع اخطاریه اول</label>
<br/>
<input type="text" value="<?php echo $data['ekhtar1_subject'];?>" id="ekhtar1_subject" name="ekhtar1_subject"/>
<br/><br/>

<label for="ekhtar1_content">متن اخطاریه اول ( {username} , {endtime} )</label>
<br/>
<textarea id="ekhtar1_content" name="ekhtar1_content"><?php echo $data['ekhtar1_content']?></textarea>
<br/><br/>

<label for="ekhtar2_time">زمان اخطاریه دوم ( به روز )</label>
<br/>
<input type="text" value="<?php echo $data['ekhtar2_time'];?>" id="ekhtar2_time" name="ekhtar2_time"/>
<br/><br/>

<label for="ekhtar2_subject">موضوع اخطاریه دوم</label>
<br/>
<input type="text" value="<?php echo $data['ekhtar2_subject'];?>" id="ekhtar2_subject" name="ekhtar2_subject"/>
<br/><br/>

<label for="ekhtar2_content">متن اخطاریه دوم ( {username} , {endtime} )</label>
<br/>
<textarea id="ekhtar2_content" name="ekhtar2_content"><?php echo $data['ekhtar2_content']?></textarea>
<br/><br/>
<button type="submit" name="submit" class="button gray">ذخیره</button>
<button type="reset" name="reset" class="button red">انصراف</button>
</div>



<div class="clear"></div>
</form>
<?php require_once $path.'theme/footer.php';?>