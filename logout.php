<?php
require_once "navbar.php";
$_SESSION = array();
session_destroy();
header("location: avaleht.php");
exit;
?>
