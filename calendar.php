<?php 
// Štartujeme session pre prácu s prihlásením
session_start();

// Kontrola prihlásenia - neprihlásených hneď presmerujeme
if (isset($_SESSION['user'])) {
} else {
	header('Location: main.php');
	die();
}

// Získame ID prihláseného užívateľa
$id_user = $_SESSION['id_user'];

// Pripojíme sa k databáze
require_once('db/functions.php');

	$database = new Database();
	$connection = $database->connection();

	// Vyberieme všetky udalosti pre tohto užívateľa z kalendára
	$statement = $connection->prepare("SELECT id_event, title, description, start_date, end_date, colour FROM calendar Where id_user = ? ");
	$statement->execute(array($id_user));
	$events = $statement->fetchAll();

// Zobrazíme šablónu kalendára
require_once 'views/calendar.view.php';

?>
