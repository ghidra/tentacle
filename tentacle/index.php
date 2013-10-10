<?php
require_once 'check_login.php';
check_login();
require_once 'test_speed.php';
require_once 'allowed_nodes.php';
require_once 'html.php';
require_once 'mysql.php';
//require_once '../config.php';
require_once 'path.php';//I only need this cause I am calling mysql which needs it for getting config
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title>Tentacle Node Editor</title>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<link rel="stylesheet" href="css/tentacle.css" type="text/css" media="screen" >
<script type="text/javascript" language="Javascript" src="js/tentacle.js"></script>
</head>
<body>
<!--**********************************-->
<?php 
$mysql=new mysql();//get a mysql object
$node_menu = new allowed_nodes();

 $menu=open_menu_bar();//from html.php

//----mysql menu
$menu.=open_db_menu_html(true);
$mysql->grab_navigation();
for($i=0;$i<=sizeof($mysql->navigation)-1;$i++){
	$menu.=database_menu_buttons($mysql->navigation[$i]);//['title'],['id'],['link'],['type'],['visibility']
}
$menu.=close_db_menu_html();
//$script_speed=new test_speed();//	echo 'script time:'.$script_speed->get_time();//get the elapse time of script

$menu.=$node_menu->assemble_menu();

$menu.=close_menu_bar();//from html.php
print $menu;
?>
<!--**********************************-->
<div id="composition">
</div>
<!--**********************************-->
<!--<iframe style="display:none;" src="http://tentaclecloud.com/users.php?users=<?php echo $_SESSION['edit_user'] ?>" />-->
</body>
</html>
