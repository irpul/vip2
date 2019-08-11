<?php 
require_once 'config.php';
require_once 'database/database.php';
require_once 'class/session.php';
require_once 'functions.php';
require_once 'class/date.php';
require_once 'class/pagination.php';
require_once 'class/backup.php';

//permission
$permission = array(
	'ACCOUNT' 		=> 1,
	'ACCOUNT_SEND' 	=> 2,
	'TICKET' 		=> 4,
	'CATEGORY' 		=> 8,
	'EMAIL' 		=> 16,
	'NEWS' 			=> 32,
	'ONLINE' 		=> 64,
	'PAYINFO' 		=> 128,
	'PLUGIN' 		=> 256,
	'SERVER' 		=> 512,
	'SETTING' 		=> 1024,
	'ADMIN' 		=> 2048,
);

$permission_name = array(
	1 		=> 'مدیریت اکانت',
	2 		=> 'ساخت اکانت',
	4 		=> 'مدیریت تیکت ها',
	8 		=> 'مدیریت دسته ها',
	16 		=> 'مدیریت ایمیل ها',
	32 		=> 'مدیریت اخبار',
	64 		=> 'مدیریت کاربران آنلاین',
	128 	=> 'مدیریت گزارش پرداخت',
	256 	=> 'مدیریت پلاگین ها',
	512 	=> 'مدیریت سرور ها',
	1024 	=> 'مدیریت تنظیمات',
	2048 	=> 'مدیریت مدیران',
);

$database = new Database(true, $dbdatabase, $dbhost, $dbusername, $dbpassword);
$db = & $database;

$secret = urlencode($secret);

if (isset($_GET['logout'])){
	Session::logout();
	redirect_to("login.php");
}

$session = null;
$user = null;
$admin = null;
$now = time();
$setting=null;
$path = null;

class Loader{
	
	function __construct(){
		global $setting,$database;
		
		$setting = $database->query("SELECT * FROM setting LIMIT 1");
		$setting = $setting->fetch();
	}
	public function load_user(){
		global $session,$user,$database,$page_title;
		$session = new Session(Session::USER);
		
		if($session->is_logged_in()){
			$user = $database->prepare("SELECT * FROM `users` WHERE id = ?");
			$user->execute(array($session->user_id));
			$user = $user->fetch();
		}
		$page_title = "کنترل پنل کاربران";
	}
	public function load_admin(){
		global $session,$path,$database,$page_title,$admin;
		$session = new Session(Session::ADMIN);
		$path = '../';
		if($session->is_logged_in()){
			$admin = $database->prepare("SELECT * FROM `admin` WHERE id = ?");
			$admin->execute(array($session->user_id));

			$admin = $admin->fetch();
		}
		$page_title = "کنترل پنل مدیریت";
	}
}
$loader = new loader();
?>