<?php 
// Začiatok relácie - umožňuje používanie session premenných medzi stránkami
session_start();

// Kontrola, či je používateľ už prihlásený
if (isset($_SESSION['user'])) {
	// Ak je používateľ prihlásený, presmeruj ho na hlavnú stránku obsahu
	header('Location: content.php');
	die(); // Ukončí vykonávanie skriptu
}

// Kontrola, či bol odoslaný formulár (metóda POST)
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	// Filtrovanie a sanitizácia vstupných údajov pre bezpečnosť
	$user_form = filter_var(htmlspecialchars($_POST['user']), FILTER_SANITIZE_STRING);
	$password_form = filter_var(htmlspecialchars($_POST['password']), FILTER_SANITIZE_STRING);
	// Hashovanie hesla pre bezpečné porovnanie s databázou
	$password_form = hash('sha512', $password_form);
	$errors = '';
	
	// Kontrola, či sú vyplnené všetky povinné polia
	if (empty($user_form) || empty($password_form)) {
		http_response_code(400);
		echo 'Missing username or password';
		exit();
	}

	// ----------------------- PRIPOJENIE K DATABÁZE ------------------------------------
	include 'db/functions.php';

	$database = new Database();
    $connection = $database->connection();

	// Príprava a vykonanie SQL dotazu na overenie prihlasovacích údajov
	$statement = $connection->prepare('SELECT * FROM users WHERE user =? AND password =?');
	$statement->execute(array($user_form, $password_form));
	$result = $statement->rowCount();
	
	// Kontrola, či bol nájdený používateľ s danými prihlasovacími údajmi
	if ($result == 1) {
		// Získanie ID používateľa a uloženie do session
		while ($id = $statement->fetch(PDO::FETCH_ASSOC)) {
			$id_user = $id['id_user'];
			$_SESSION['id_user'] = $id_user;
			$_SESSION['user'] = $user_form;
		}

		// Presmerovanie na hlavnú stránku po úspešnom prihlásení
		header('Location: index.php');
	} else {
		// Nastavenie chybovej správy pri neúspešnom prihlásení
		$errors = '<li>The username or password is incorrect</li>';
	}
}

// Načítanie šablóny pre prihlasovaciu stránku
require 'views/login.view.php';

?>

