<?php 
// Kontrola prihlásenia - prihlásených presmerujeme na obsah
if(isset($_SESSION['user'])) {
	header('Location: content.php');
	die();
} else {
	// Neprihlásených necháme na úvodnej stránke
	require 'views/main.view.php';
}

?>