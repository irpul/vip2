<?php
function translate($word)
{
	global $translate;
	if(isset($translate[$word]))
		return $translate[$word];
	else
		return $word;
}
function fileinfo($file,$server)
{
	global $secret;
	$out=false;

	if (file_exists(__DIR__.'/../temp/'.md5($file).$server['id']))
	{
		$out = file_get_contents(__DIR__.'/../temp/'.md5($file).$server['id']);
		$out = json_decode($out,true);

		if($out['time'] < time())
			$out=false;
	
	}
	if (!$out)
	{
		$out = file_get_contents($server['url']."fileinfo.php?file=".urlencode($file)."&secret=$secret");
		$out = json_decode($out,true);

		$out['time'] = time() + 24*60*60;
		file_put_contents(__DIR__.'/../temp/'.md5($file).$server['id'], json_encode($out));

	}
	
	return $out;
}
function add_credit($time)
{
	global $database;
	if ($time != 0)
	{
		$time = (int)$time;
		return $database->exec("UPDATE `users` SET endtime = {$time}+endtime ");
	}
	return false;
}
function checkIP($checkip)
{
	global $database,$setting;
	
	if(!filter_var($checkip, FILTER_VALIDATE_IP)) exit;
	
	$sql = $database->prepare("SELECT * FROM `blockip` WHERE ip = ?");
	$sql->execute(array($checkip));
	$ip = $sql->fetch();
	if (isset($ip['allow']) && $ip['allow'] == 0 )
	{
		echo translate('Block IP');
		exit;
	}
	if(!isset($ip['allow']) && !empty($setting['blockip']))
	{
	
		$sql = $database->prepare("SELECT * FROM `geoip` WHERE ? BETWEEN `from` AND `to`");
		$sql->execute(array(ip2long($checkip)));
		$user = $sql->fetch();
	
		if(!in_array($user['country'], explode(',', $setting['blockip'])))
		{
			echo translate('Invalid IP');
			exit;
		}
	}
}
function generateHash($user,$file,$ip)
{
	global $secret;
	
	$time=24*60*60+time();
	if ( $user['active'] != 1 || $user['endtime'] < time() || !logUser($user,$file,$ip))
	{
		throw new Exception('Account Expire');
		return ;
	}
	if($time > $user['endtime'])
		$time = $user['endtime'];
	$key = sha1((md5($time).$secret));
	$data = sha1($key.md5($user['username'].$user['password']).$key);
	
	$data = array('data'=>$data,'ip'=>$ip,'key'=>$key,'time'=>$time,'hash'=>md5(md5($data.$secret.$ip)));
	
	$data = serialize($data);
	$data = base64_encode(encrypt($data,$secret));
	return urlencode($data);
}
function generateLink($user,$server,$file)
{
		checkIP($_SERVER['REMOTE_ADDR']);
		$data = generateHash($user, $file,$_SERVER['REMOTE_ADDR']);

	
	return rtrim($server['url'],'/').'/?file='.$file.'&hash='.$user['id'].'_'.($data);
}

function generateSingleLink($time,$user,$file,$ip)
{
	try {
		global $secret;
		checkIP($ip);
		
		logUser($user,$file,$ip);
		
		$key = sha1((md5($time).$secret));
		$data = sha1($key.md5($user['username'].$user['password']).$key);
		
		$data = array('data'=>$data,'single'=>true,'ip'=>$ip,'key'=>$key,'time'=>$time,'hash'=>md5(md5($data.$secret.$ip)));
		
		$data = serialize($data);
		$data = base64_encode(encrypt($data,$secret));
		$data = urlencode($data);
		return $data;
		
	}
	catch(Exception $e)
	{
		echo '<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />'.translate($e->getMessage());
		exit;
	}
}

function logUser($user,$file,$ip)
{
	global $setting,$database;
	$time = time()-60*$setting['vipbantime'];
	$userid = $user['id'];

	$sql = $database->query("SELECT id,ip FROM `dlinfo` where `time` >= $time AND `userid`=$userid GROUP BY ip");

	$time = time();
	if ( $sql->rowCount() != 0 )
	{
		$c = $sql->rowCount();
		$sql = $sql->fetchAll();

		$found = false;

		foreach ($sql as $t)
		{
			if ( $sql['ip'] == $_GET['ip'])
			{
				$found = $sql['id'];
				break;
			}
		}

		if ( $found === false )
		{
			if($c < $user['multi'] || $user['multi'] == 0)
			{
				$sql = $database->prepare( "INSERT INTO `dlinfo` (`userid`,`ip`,`time`,`file`,hash ) VALUES ($userid, ? ,'$time', ?,?)");
				$sql->execute(array($ip,urldecode($file),md5($file)));
			}
			else
				throw new Exception("Multi IP");
		}
		else
		{
			$sql = $database->prepare( "INSERT INTO `dlinfo` (`userid`,`ip`,`time`,`file`,hash ) VALUES ($userid, ? ,'$time', ?,?)");
			$sql->execute(array($ip,urldecode($file),md5($file)));
		}
	}
	else
	{
		$sql = $database->prepare( "INSERT INTO `dlinfo` (`userid`,`ip`,`time`,`file`,hash ) VALUES ($userid, ? ,'$time', ?,?)");
		$sql->execute(array($ip,urldecode($file),md5($file)));
	}
	return true;
}
function decrypt($data,$key=null)
{

	$decrypted = rtrim(mcrypt_decrypt(MCRYPT_RIJNDAEL_256, md5($key), $data, MCRYPT_MODE_CBC, md5(md5($key))), "\0");
	return $decrypted;
}

function encrypt($data,$key=null)
{
	$encrypted = mcrypt_encrypt(MCRYPT_RIJNDAEL_256, md5($key), $data, MCRYPT_MODE_CBC, md5(md5($key)));
	return $encrypted;
}

function email($to , $method , $param)
{
	global $database,$setting;
	
	$possible = array('ticket_new','ticket_answer','register','forgot','ekhtar1','ekhtar2');
	
	if(! in_array($method, $possible))
		return false;
	
	$detail = $setting;
	
	foreach ($param as $key=>$value)
	{
		$detail["{$method}_content"] = str_replace("{{$key}}",$value,$detail["{$method}_content"]);
	}
	if(!isset($param['username']))
		$param['username'] = $to;
	$return = send_mail($to,$param['username'],$detail["{$method}_subject"],stripcslashes($detail["{$method}_content"]) );

	return $return;
}

function send_mail($to_email,$to_name,$subject,$mail_body,$attachment=null) {
	global $setting;
	require_once('class/class.phpmailer.php');
	if($setting['signature'])
		$mail_body = $mail_body.'<br/><hr>'.$setting['signature'];
	$mail = new PHPMailer(true);
	try {
		if($setting['issmtp']==1 )
			$mail->IsSMTP();
		$mail->SMTPAuth = $setting['smtpauth']==1 ? true : false;
		$mail->Username = $setting['smtpusername'];
		$mail->Password = $setting['smtppassword'];
		$mail->Host = $setting['smtphost'];
		$mail->Port = $setting['smtpport'];
		$mail->SMTPSecure = $setting['smtpsecure'];
		$mail->AddReplyTo($setting['replyto'], $setting['name']);
		$mail->SetFrom($setting['email'], $setting['name']);
		$mail->AddAddress($to_email, $to_name);
		$mail->CharSet = 'UTF-8';
		$mail->Subject = $subject;
		$mail->AltBody = 'To view the message, please use an HTML compatible email viewer!'; // optional - MsgHTML will create an alternate automatically
		$mail->MsgHTML($mail_body);
		if ($attachment)
			$mail->AddAttachment($attachment);
		$mail->Send();
		return true;
	} catch (phpmailerException $e) {
		return $e->errorMessage(); //Pretty error messages from PHPMailer
	} catch (Exception $e) {
		return $e->getMessage(); //Boring error messages from anything else!
	}
}

function redirect_to ($location = NULL)
{
	global $setting;
	if( $setting['ajax']==1 && $_GET['ajax'])
	{
		if($location == 'login.php')
			echo '2__'.$location;
		else
			echo '1__'.$location;
		exit;
	}
	if ($location != NULL)
	{
		header("Location: {$location}");
		exit();
	}
}

function getTime($time,$format=null)
{
	global $setting;
	if ($format == null)
	{
		global $setting;
		$format = $setting['timeformat'];
	}
	elseif ($format == "U")
		return $time;
	
	return jDateTime::date($format, $time);
}
/*
 * function __autoload($class_name) { $class_name = strtolower($class_name);
 * $path = "lib/{$class_name}.php"; if(file_exists($path)) {
 * require_once($path); } else { die("The file {$class_name}.php could not be
 * found."); } }
 */

function checkpermission($check)
{
	global $permission,$admin;

	if(!$admin)
		return false;
	if(isset($permission[$check]))
	{
		return $admin['type'] & $permission[$check];
	}
	return false;
}
