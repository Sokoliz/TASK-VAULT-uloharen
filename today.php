<?php 
// Štartujeme session
session_start();

// Kontrola prihlásenia
if (isset($_SESSION['user'])) {
	include 'db/functions.php';
	$database = new Database();
	$connection = $database->connection();
} else {
	// Neprihlásených rovno presmerujeme
	header('Location: main.php');
	die();
}

// Nastavíme dnešný dátum a časové rozpätie pre celý deň
$today = date("Y-m-d");
$today_start =date("Y-m-d")." 00:00:00";
$today_end =date("Y-m-d")." 23:59:59";

// Projekty, ktoré dnes začínajú
$projects_start = $connection->prepare("SELECT SQL_CALC_FOUND_ROWS * FROM projects WHERE id_user = ? AND start_date= ? ORDER BY id_project DESC") ;			
$projects_start->execute(array($_SESSION['id_user'], $today));
$projects_start = $projects_start->fetchAll();

// Projekty, ktoré dnes končia
$projects_end = $connection->prepare("SELECT SQL_CALC_FOUND_ROWS * FROM projects WHERE id_user = ? AND end_date= ? ORDER BY id_project DESC") ;			
$projects_end->execute(array($_SESSION['id_user'], $today));
$projects_end = $projects_end->fetchAll();

// Udalosti, ktoré dnes začínajú
$events_start = $connection->prepare("SELECT SQL_CALC_FOUND_ROWS * FROM calendar WHERE id_user = ? AND (start_date BETWEEN ? AND ?) ORDER BY id_event DESC") ;			
$events_start->execute(array($_SESSION['id_user'], $today_start, $today_end));
$events_start = $events_start->fetchAll();

// Udalosti, ktoré dnes končia
$events_end = $connection->prepare("SELECT SQL_CALC_FOUND_ROWS * FROM calendar WHERE id_user = ? AND (end_date BETWEEN ? AND ?) ORDER BY id_event DESC") ;			
$events_end->execute(array($_SESSION['id_user'], $today_start, $today_end));
$events_end = $events_end->fetchAll();

// Úlohy, ktorých termín je dnes
$tasks = $connection->prepare("SELECT SQL_CALC_FOUND_ROWS * FROM tasks WHERE id_user = ? AND deadline= ? ORDER BY id_task DESC") ;			
$tasks->execute(array($_SESSION['id_user'], $today));
$tasks = $tasks->fetchAll();

// Zobrazíme šablónu s dnešnými aktivitami
require 'views/today.view.php';


?>