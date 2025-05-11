<?php include '../parts/header.php'; ?>
<?php session_start();

session_destroy();
$_SESSION = array();

header('Location: login.php');
die();

?>
<?php include '../parts/footer.php'; ?>