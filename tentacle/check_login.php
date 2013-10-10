<?php
if (!isset($_SESSION)) {
	session_start();
}
function check_login(){
    if (!isset($_SESSION['edit_isLogin']) || $_SESSION['edit_isLogin'] == false) {
      	header('Location: login.php');
        exit;
    }
}
?>