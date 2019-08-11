<?php 
require_once '../lib/loader.php';
$loader->load_admin();
$session->is_logged_in("login.php");

header('Content-disposition: attachment; filename=backup.sql');
header('Content-type: text/plain');

$connection = new Backup($dbhost,$dbdatabase,$dbusername,$dbpassword);
echo $connection->backup_tables(); /*Save all tables and it values in selected database*/
$connection->closeConnection();

exit;