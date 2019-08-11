	 <div class="menu" id="ajax" >
<?php if($setting['news_right']):?>
	<div class="menutitle"><img src="../theme/img/icons/information-octagon.png"/>پیام مدیر سایت</div>
	<div class="mnu">
	<div><font color="red"><?php echo $setting['news_right']?></font></div>
	</div>
<?php endif;?>
	
	<div class="menutitle">تنظیمات</div>
	<div class="mnu">
	<a href="index.php"><div>صفحه اصلی</div></a>
	<?php if(checkpermission('SETTING')):?>
	<a href="setting.php"><div>تنظبمات کلی</div></a>
	<a href="blockip.php"><div>IP جدید</div></a>
	<a href="block.php"><div>تنظیمات IP</div></a>
	<?php endif;?>
	<?php if(checkpermission('NEWS')):?>
	<a href="news.php"><div>تنظیمات اخبار</div></a>
	<?php endif;?>
	<?php if(checkpermission('EMAIL')):?>
	<a href="email.php"><div>تنظیمات ایمیل</div></a>
	<?php endif;?>
	<?php if(checkpermission('PLUGIN')):?>
	<a href="plugin.php"><div>تنظیمات پلاگین</div></a>
	<?php endif;?>
	<?php if(checkpermission('ADMIN')):?>
	<a href="backup.php" id="noajax"	><div>پشتیبان گیری</div></a>
	<a href="restore.php" id="noajax"	><div>بازگرداندن</div></a>
	<?php endif;?>
		<a href="cache.php"><div>پاک کردن کش</div></a>
		<a href="change.php"><div>تغییر رمز عبور</div></a>
	</div>
	
	<?php if(checkpermission('ACCOUNT_SEND') || checkpermission('ACCOUNT') || checkpermission('ONLINE') || checkpermission('PAYINFO')):?>
	<div class="menutitle">کاربران</div>
	<div class="mnu">
	<?php if(checkpermission('ACCOUNT_SEND')):?>
	<a href="account_send.php"><div>اکانت جدید</div></a>
	<?php endif;?>
	<?php if(checkpermission('ACCOUNT')):?>
	<a href="account.php"><div>مدیریت اکانت ها</div></a>
	
	<?php endif;?>
	</div>
	<?php endif;?>
	
	<div class="menutitle">گزارش گیری</div>
	<div class="mnu">
	<?php if(checkpermission('ONLINE')):?>
	<a href="online.php"><div>کاربران آنلاین</div></a>
	<a href="onlineg.php"><div>مهمان های آنلاین</div></a>
	<a href="dlinfo.php"><div>فایل ها دانلود شده</div></a>
	<a href="dlinfomax.php"><div>بیشترین فایل ها دانلود شده</div></a>
	<?php endif;?>
	<?php if(checkpermission('PAYINFO')):?>
	<a href="payinfo.php"><div>گزارش پرداخت</div></a>
	<?php endif;?>
	</div>
	
	<?php if(checkpermission('EMAIL')):?>
	<div class="menutitle">خبرنامه ها</div>
	<div class="mnu">
	<a href="newslist_send.php"><div>خبرنامه جدید</div></a>
	<a href="newslist.php"><div>مدیریت خبرنامه ها</div></a>
	</div>
	<?php endif;?>
	
	<?php if(checkpermission('SERVER')):?>
	<div class="menutitle">سرور ها</div>
	<div class="mnu">
	<a href="server_send.php"><div>سرور جدید</div></a>
	<a href="server.php"><div>مدیریت سرور ها</div></a>
	</div>
	<?php endif;?>
	<?php if(checkpermission('TICKET')):?>
	<div class="menutitle">تیکت ها</div>
	<div class="mnu">
	<a href="admin_ticket_send.php"><div>تیکت جدید</div></a>
	<a href="admin_ticket.php?action=all"><div>مدیریت تیکت ها</div></a>
	</div>
	<?php endif;?>
	<?php if(checkpermission('CATEGORY')):?>
	<div class="menutitle">دسته ها</div>
	<div class="mnu">
	<a href="category_send.php"><div>دسته جدید</div></a>
	<a href="category.php"><div>مدیریت دسته ها</div></a>
	</div>
	<?php endif;?>
	<?php if(checkpermission('ADMIN')):?>
	<div class="menutitle">مدیران</div>
	<div class="mnu">
	<a href="admin_send.php"><div>مدیر جدید</div></a>
	<a href="admin.php"><div>مدیران سایت</div></a>
	</div>
	<?php endif;?>
</div>