<?php
$dbusername	= "root"; 
$dbpassword	= ""; 
$dbhost		= "localhost";
$dbdatabase = "vip2";

define(SITE_PATH, '/');

error_reporting(E_ALL);

$translate = array(
	'Invalid User/Pass' => "نام کاربری/کلمه عبور اشتباه است",
	'Account Expire'	=>'اعتبار اکانت شما به پایان رسیده است',
	'You must login' 	=> "شما باید وارد اکانت خود شوید",
	'Multi IP'			=> "مولتی ای پی",
	'Plz Request agian' => "لطفا برای دانلود دوباره درخواست دهید",
	'Invalid IP'		=> 'تنها ای پی ایران میتواند دانلود کند',
	'Block IP'			=> 'ای پی شما بلاک شده است'
);
$secret = '938520';

$allowip = array(
	'1.1.1.1',
);
?>