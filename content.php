<?php 
// Začiatok relácie - umožňuje používanie session premenných medzi stránkami
session_start();

// Kontrola, či je používateľ prihlásený
if (isset($_SESSION['user'])) {
	// Ak je používateľ prihlásený, načítaj šablónu s obsahom
	require 'views/content.view.php';
} else {
	// Ak používateľ nie je prihlásený, presmeruj ho na úvodnú stránku
	header('Location: main.php');
	die(); // Ukončí vykonávanie skriptu
}
?>