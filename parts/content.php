<?php include '../parts/header.php'; ?>
<?php session_start();

if (isset($_SESSION['user'])) {
	require 'views/content.view.php';
} else {
	header('Location: main.php');
	die();
}
?>
<?php include '../parts/footer.php'; ?>