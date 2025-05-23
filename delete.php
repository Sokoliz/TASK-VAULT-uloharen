<?php 
// Štartujeme session
session_start();

// Kontrola prihlásenia - neprihlásených presmerujeme
if (isset($_SESSION['user'])) {
	// Pripojíme sa k databáze
	include 'db/functions.php';
	$database = new Database();
	$connection = $database->connection();

} else {
	// Nastavíme kód 401 - neautorizovaný prístup
	http_response_code(401);
	header('Location: login.php');
	die();
}

// Vymažeme projekt z databázy
$del_project_confirm = $connection->prepare('DELETE FROM projects WHERE id_project=?');
$del_project_confirm->execute(array($id_project)); 

?>