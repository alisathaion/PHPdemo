<?php
session_start();
$_SESSION = array();
setcookie('user', null, time() -3600);
session_destroy();

header('Location:index.php');
exit();
?>