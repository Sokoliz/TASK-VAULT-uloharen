<?php include '../parts/header.php'; ?>
<?php 
if(isset($_SESSION['user'])) {
	header('Location: content.php');
	die();
} else {
	require 'views/main.view.php';
}

?>
<?php include '../parts/footer.php'; ?>