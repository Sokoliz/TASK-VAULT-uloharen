<?php 
// Štartujeme session
session_start();

// Zrušíme session a vyčistíme všetky údaje
session_destroy();
$_SESSION = array();

// Presmerujeme na prihlasovaciu stránku
header('Location: login.php');
die();

?>