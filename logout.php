<?php
session_start();

// Destroy session
$_SESSION = array();
session_destroy();

// Redirect to home
header('Location: index.php');
exit;
?>
