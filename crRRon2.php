<?php
require_once 'lib/loader.php';

$query = "SELECT `email` , `time` , `text` , `subject` , `newslistid` FROM `job` JOIN `newslist` on newslist.id = job.newslistid
 WHERE `time` < $now LIMIT $setting[emaillimit]";
$jobs = $database->query($query)->fetchAll();

foreach ($jobs as $job)
{
	$query = "UPDATE `newslist` SET `sent`=`sent`+1 WHERE `id` = $job[newslistid]";
	$database->exec($query);
	send_mail( $job['email'],$job['email'], $job['subject'], $job['text']);
}
$query = "DELETE FROM `job` WHERE `time` < $now LIMIT $setting[emaillimit]";
$database->exec($query);