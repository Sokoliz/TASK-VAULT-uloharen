<?php include '../parts/header.php'; ?>
<?php session_start();

if(isset($_SESSION['user'])) {
	header('Location: content.php');
	die();
} else {
	header('Location: main.php');
}

?>
<?php include '../parts/footer.php'; ?>