<?php 
// Spustenie session pre správu prihláseného používateľa
session_start();

// Kontrola, či je používateľ prihlásený
if(isset($_SESSION['user'])) {
	// Ak je používateľ prihlásený, presmerujeme ho na stránku s obsahom
	header('Location: content.php');
	die();
} else {
	// Ak používateľ nie je prihlásený, presmerujeme ho na hlavnú stránku
	header('Location: main.php');
}

?>