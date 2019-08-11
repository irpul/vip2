<?php 
require '../../lib/loader.php';
$loader->load_user ();
?>
$(document).ready(function(){
    $("#ab3").hide();
});
var i = <?php echo $setting['guesttime']?>;

var Start = 0;
Timer = setInterval(function() {
	if(i >= 0)
	{
		$("#Timer").text(i--);
		if(i==0)
			Start = 1;
	}
	else if(Start == 1)
	{
		$(".sec").remove();
		$(".timer").addClass("timer-2");
		$(".timer").append('<div class="dl-ready"></div>');
		Start++;
	}
	else if(Start == 2) 
	{
		$("#Result").fadeOut();
		makeCaptcha();
		clearInterval(Timer);
	}
}, 1000);
function makeCaptcha() {
    $.post("captcha.php", { Level:"Serial" },
	   function(data) {
			$(".allert-box-1").remove();
			$("#Result").html(data);
			$("#Result").fadeIn();
	   }
    );
}

function startDownloadAndCheckCaptcha(captchacode, file2,server2) {
    $.post("download.php", { sec:captchacode, file:file2,server:server2 , download:"true" },
	   function(data) {
			if(data) {
                fileUrl = data;
                $(".allert-box-1").remove();
    			$("#Result").html('<div id="ab3" class="allert-box-3"><a href="'+fileUrl+'">&nbsp;</a></div>');
    			$("#Result").fadeIn();
                window.location = fileUrl;
            }
            else {
                makeCaptcha();
                alert('کد امنیتی اشتباه است .');
            }
	   }
    );
}

function sleep(milliseconds) {
  var start = new Date().getTime();
  for (var i = 0; i < 1e7; i++) {
    if ((new Date().getTime() - start) > milliseconds){
      break;
    }
  }
}

function disableEnterKey(e)
{
     var key;
     if(window.event)
          key = window.event.keyCode;     //IE
     else
          key = e.which;     //firefox
     if(key == 13)
          return false;
     else
          return true;
}

document.onkeypress = disableEnterKey; 