<?php

require_once '../lib/loader.php';
$loader->load_user();

$secret = urldecode($secret);
if($_GET['secret'] != $secret )
	die();

checkIP($_GET['ip']);



if(isset($_GET['action']) && $_GET['action'] == "free")
{
	if (!isset($_GET['file']))
		die();
	if (!isset($_GET['ip']))
		die();
	if($_GET['ip'] && $_GET['file'])
	{
		$time = time()-60*60*$setting['guestreserve'];
		$database->exec("DELETE FROM `dlinfo_guest` where `time` < $time");
		
		$sql = $database->prepare("SELECT * FROM `dlinfo_guest` WHERE `ip` = ? AND `file` = ?");
		$sql->execute(array($_GET['ip'],$_GET['file']));
		
		if($sql->rowCount() != 0)
		{
			
			$info['status'] = "true";
			$info['speed'] = $setting['guestspeed'];
			echo json_encode($info);
		}
		else 
			echo '<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />'.translate('Plz Request agian');
	}
	exit;
}

try
{
	if(isset($_GET['hash']))
	{
		if (!isset($_GET['file']))
			die();
		if (!isset($_GET['ip']))
			die();
		$hash = explode('_', $_GET['hash']);
		$userid = (int)$hash[0];
		if($userid == 0)
			throw new Exception("Invalid User/Pass");
		$time = time();
		$login_user = $database->query("SELECT * FROM `users` where `id`=$userid AND `active`=1 AND `endtime`>$time ")->fetch();

		$data = base64_decode($hash[1]);
		
		$data = decrypt($data,$secret);
		
		$data = unserialize($data);
		
		if ($data['data'] != sha1($data['key'].md5($login_user['username'].$login_user['password']).$data['key']) || $data['key'] != sha1(md5($data['time']).$secret) || md5(md5($data['data'].$secret.$data['ip'])) != $data['hash'])
			throw new Exception("Invalid User/Pass");
		
		if(isset($_GET['single']))
		{
			$sql = $database->prepare('SELECT * FROM single WHERE userid = ?');
			$sql->execute($login_user['id']);
			$sql = $sql->execute();
			
			if($sql['time'] > time() && $_GET['file'] == $sql['file'])
			{
				echo json_encode(array('data'=>generateSingleLink($sql['time'], $login_user, $_GET['file'],$_GET['ip']),'status'=>true));
			}
		}
		else
		{
			echo json_encode(array('data'=>generateHash($login_user, $_GET['file'],$_GET['ip']),'status'=>true));
		}
		exit();
	}
	
}
catch(Exception $e)
{
	echo json_encode(array('data'=>translate($e->getMessage()),'status'=>true));
	exit;
}

