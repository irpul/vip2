<?php 
require 'lib/loader.php';
$loader->load_user ();
if($setting['guestcaptcha']==1):

?>
<form method="post" style="font-family: BYekan" action="" onsubmit="startDownloadAndCheckCaptcha(document.getElementById('captcha').value, document.getElementById('file').value, document.getElementById('server').value); return false;">
<div align="center">
<img src="theme/img/img.php?i=<?php echo mt_rand(10000, 90000);?>" width="128" height="55" onclick="makeCaptcha();return false;">
<p>کد امنیتی را وارد نمایید</p>

<input type="text" id="captcha" name="s" >
<br/>
<div class="regesnemaa3"><button type="submit" name="submit">دانلود</button></div>
</div>
</form>
<?php 
else:
?>
<script type="text/javascript">
startDownloadAndCheckCaptcha(null, document.getElementById('file').value, document.getElementById('server').value);
</script>
<?php 
endif;?>
