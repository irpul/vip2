<div class="menu" id="ajax">
<?php if($setting['news_right']):?>
	<div class="menutitle"><i class="fa fa-globe"></i> پیام مدیر سایت</div>
	<div class="mnu">
	<a href="#"><div><?php echo $setting['news_right']?></div></a>
	</div>
<?php endif;?>
	<div class="menutitle"><i class="fa fa-cog"></i> تنظیمات اکانت</div>
	<div class="mnu">
	<a href="index.php"><div><i class="fa fa-home"></i> صفحه کاربری</div></a>
	<a href="change.php"><div><i class="fa fa-cogs"></i> تغییر اطلاعات کاربری</div></a>
	<a href="changepw.php"><div><i class="fa fa-lock"></i> تغییر کلمه عبور</div></a>
	<a href="charge.php"><div><i class="fa fa-bank"></i> تمدید اکانت</div></a>
	<a href="ref.php"><div><i class="fa fa-users"></i> کاربران معرفی شده</div></a>
	</div>
	
	<div class="menutitle"><i class="fa fa-support"></i> پشتیبانی</div>
	<div class="mnu">
	<a href="ticket_send.php"><div><i class="fa fa-comment"></i> ارسال تیکت جدید</div></a>
	<a href="ticket.php?action=all"><div><i class="fa fa-comments"></i> تیکت ها</div></a>
	
</div>
</div>