<?php
require_once '../lib/loader.php';
$loader->load_admin();
$session->is_logged_in("login.php");

if(!checkpermission('SETTING'))
	redirect_to('index.php');

$page_title = 'تنظیمات';

if (isset($_POST ['submit'])) {
	
	$data['sitename'] = $_POST['sitename'];
	$data['ajax'] = $_POST['ajax'];
	$data['singleprice'] = $_POST['singleprice'];
	$data['singletime'] = $_POST['singletime'];
	$data['paymentinfo'] = $_POST['paymentinfo'];
	$data['pagelimit'] = $_POST['pagelimit'];
	$data['guestspeed'] = $_POST['guestspeed'];
	$data['guesttime'] = $_POST['guesttime'];
	$data['guestcaptcha'] = $_POST['guestcaptcha'];
	$data['guestreserve'] = $_POST['guestreserve'];
	$data['vipbantime'] = $_POST['vipbantime'];
	$data['timeformat'] = $_POST['timeformat'];
	$data['linktitle'] = $_POST['linktitle'];
	$data['linkurl'] = $_POST['linkurl'];
	$data['dltext'] = $_POST['dltext'];
	$data['ref_day'] = $_POST['ref_day'];
	$data['blockip'] = $_POST['blockip'];
	$sql = $database->prepare("UPDATE `setting` SET 
			`ajax` = :ajax,
			`sitename` = :sitename,
			`singleprice` = :singleprice,
			`singletime` = :singletime,
			`paymentinfo` = :paymentinfo,
			`pagelimit` = :pagelimit,
			`guestspeed` = :guestspeed,
			`guesttime` = :guesttime,
			`guestcaptcha` = :guestcaptcha,
			`guestreserve` = :guestreserve,
			`vipbantime` = :vipbantime,
			`timeformat` = :timeformat,
			`linktitle` = :linktitle,
			`linkurl` = :linkurl,
			`dltext` = :dltext,
			`ref_day` = :ref_day,
			blockip=:blockip
			");
	
	if ( $sql->execute($data) )
	{
		$sus = "اطلاعات با موفقیت ذخیره شد";
	}
	else
		$error = "خطا در ذخیره سازی اطلاعات";
} 
$data = $database->query("SELECT * FROM `setting`")->fetch();

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
<h1>تنظیمات</h1>
<span id="error">
<?php echo $status;?>
</span>
<form method="post" id="ajaxform" style="text-align: right;font-family: BYekan;" action="setting.php">

<div class="left">
<div class="legend">تنظیمات دانلود</div>
<label for="blockip">محدودیت کشور ( با , جدا کنید )</label>
<br/>
<input type="text" value="<?php echo $data['blockip'];?>" id="blockip" name="blockip"/>
<br/>

<label for="guestspeed">سرعت دانلود رایگان ( به کیلو بایت )</label>
<br/>
<input type="text" value="<?php echo $data['guestspeed'];?>" id="guestspeed" name="guestspeed"/>
<br/>

<label for="guesttime">انتظار دانلود</label>
<br/>
<input type="text" value="<?php echo $data['guesttime'];?>" id="guesttime" name="guesttime"/>
<br/>

<label for="guestcaptcha">نمایش کد امنیتی</label>
<br/>
<select name="guestcaptcha" id="guestcaptcha">
<option value="0">غیرفعال</option>
<option value="1" <?php if($data['guestcaptcha']==1) echo "selected"?>>فعال</option>
</select>
<br/>

<label for="guestreserve">مدت اعتبار لینک مهمان ( به ساعت )</label>
<br/>
<input type="text" value="<?php echo $data['guestreserve'];?>" id="guestreserve" name="guestreserve"/>
<br/>

<label for="vipbantime">زمان قفل کردن ای پی ( به دقیقه )</label>
<br/>
<input type="text" value="<?php echo $data['vipbantime'];?>" id="vipbantime" name="vipbantime"/>
<br/>

<label for="singletime">زمان اعتبار تک لینک ( به ساعت - 0 برای غیرفعال )</label>
<br/>
<input type="text" value="<?php echo $data['singletime'];?>" id="singletime" name="singletime"/>
<br/>

<label for="singleprice">هزینه مازاد هر مگابایت تک لینک ( به ریال )</label>
<br/>
<input type="text" value="<?php echo $data['singleprice'];?>" id="singleprice" name="singleprice"/>
<br/>


</div>
			
<div>
<div class="legend">تنظیمات کلی</div>
<label for="sitename">نام سایت</label>
<br/>
<input type="text" value="<?php echo $data['sitename'];?>" id="sitename" name="sitename"/>
<br/><br/>

<label for="ref_day">هدیه کاربر (به روز)</label>
<br/>
<input type="text" value="<?php echo $data['ref_day'];?>" id="ref_day" name="ref_day"/>
<br/><br/>

<label for="ajax">Ajax</label>
<br/>
<select name="ajax" id="ajax">
<option value="0">غیرفعال</option>
<option value="1" <?php if($data['ajax']==1) echo "selected"?>>فعال</option>
</select>
<br/><br/>

<label for="pagelimit">محدود نمایش در هر صفحه</label>
<br/>
<input type="text" value="<?php echo $data['pagelimit'];?>" id="pagelimit" name="pagelimit"/>	
<br/><br/>

<label for="timeformat">فرمت نمایش زمان</label>
<br/>
<input type="text" value="<?php echo $data['timeformat'];?>" id="timeformat" name="timeformat"/>	
<br/><br/>

<label for="linktitle">عنوان لینک منوی بالا</label>
<br/>
<input type="text" value="<?php echo $data['linktitle'];?>" id="linktitle" name="linktitle"/>	
<br/><br/>

<label for="linkurl">لینک منوی بالا</label>
<br/>
<input type="text" value="<?php echo $data['linkurl'];?>" id="linkurl" name="linkurl" class="ltr"/>	
<br/><br/>

<label for="dltext">توضیحات صفحه دانلود</label>
<br/>
<textarea id="dltext" name="dltext"><?php echo $data['dltext']?></textarea>
<br/><br/>

<label for="paymentinfo">توضیحات صفحه پرداخت</label>
<br/>
<textarea id="paymentinfo" name="paymentinfo"><?php echo $data['paymentinfo']?></textarea>
<br/><br/>

<button type="submit" name="submit" class="button gray">ذخیره</button>
<button type="reset" name="reset" class="button red">انصراف</button>
</div>



<div class="clear"></div>
</form>
<?php require_once $path.'theme/footer.php';?>