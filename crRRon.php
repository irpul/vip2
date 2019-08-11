<?php
require_once 'lib/loader.php';
// ersal email ; tedad roze baghie monde az etebar b adad
$day = $setting ['ekhtar1_time'];
$day2 = $setting ['ekhtar2_time'];

$time = time ();

$query = $database->query ( "UPDATE `users` SET `active` = 0 WHERE endtime<=$time " );

$body = "1 : " . "UPDATE `users` SET `active` = 0 WHERE endtime<=$time <br>";

$time = time () + 60 * 60 * 24 * $day;
$time2 = $time - 60 * 60 * 24;

$query = $database->query ( "select * from `users` where endtime<=$time AND endtime>$time2 AND active=1 AND email != '' " );
$count = $query->rowCount ();
$body .= "<br>2  $count : " . "select * from `users` where endtime<=$time AND endtime>$time2 AND active=1 AND email != '' ";

while($row = $query->fetch())
{
	$row ['endtime'] = getTime($row['endtime']);
	$param = array (
			'username' => $row ['username'],
			'endtime' => $row ['endtime']
	);
	email ( $row['email'], "ekhtar1", $param );
	
	$body .= " " . $row ['username'];
}

$time = time () + 60 * 60 * 24 * $day2;
$time2 = $time - 60 * 60 * 24;

$query = $database->query ( "select * from `users` where endtime<=$time AND endtime>$time2 AND active=1 AND email != '' " );
$count = $query->rowCount ();
$body .= "<br>3  $count : " . "select * from `users` where endtime<=$time AND endtime>$time2 AND active=1 AND email != '' ";

while($row = $query->fetch())
{
	$row ['endtime'] = getTime($row['endtime']);
	$param = array (
			'username' => $row ['username'],
			'endtime' => $row ['endtime']
	);
	email ( $row['email'], "ekhtar2", $param );

	$body .= " " . $row ['username'];
}

// send_mail("al.az20@yahoo.com","test","al.az20@yahoo.com","test","test2",$body,$signature=null,$attachment=null);

?>