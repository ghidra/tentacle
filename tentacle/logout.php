<?php
session_start();
// To logout we only need to set the value of isLogin to false
$_SESSION['edit_isLogin'] = false;
header('Location: login.php');
exit;
?> 