<?php
if (!isset($_SESSION)) {
	session_start();
}
// db properties
//$dbhost = 'mysql2.smarterlinux.com';
/*$dbhost = '76.12.16.213';
$dbuser = 'nervegass'; 
$dbpass = 'gsn3625';*/
$dbhost = '127.0.0.1';//:3307
$dbuser = 'root'; 
$dbpass = '';
      
//$dbname = 'nervegass';
define('dbname','tentacle_dev');

// make a connection to mysql here
$conn = mysql_connect ($dbhost, $dbuser, $dbpass) or die ("I cannot connect to the database because: " . mysql_error());
mysql_select_db (dbname) or die ("I cannot select the database '$dbname' because: " . mysql_error());
?> 
