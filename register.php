<?php 
// Štartujeme session pre prácu s prihlásením
session_start();

// Ak je užívateľ už prihlásený, presmerujeme ho rovno na obsah
if (isset($_SESSION['user'])) {
	header('Location: content.php');
	die();
}

// Spracovanie odoslaného registračného formulára
if($_SERVER['REQUEST_METHOD'] == 'POST') {
	
	// Čistíme vstupy od nebezpečného kódu
	$user_form = filter_var(htmlspecialchars($_POST['user']), FILTER_SANITIZE_STRING);
	$password_form = filter_var(htmlspecialchars($_POST['password']), FILTER_SANITIZE_STRING);
	$password2_form = filter_var(htmlspecialchars($_POST['password2']), FILTER_SANITIZE_STRING);
	$errors = '';
	
	// Kontrola či nechýba meno alebo heslo
	if (empty($user_form) || empty($password_form)) {
		http_response_code(400);
		echo 'Missing username or password';
		exit();
	}

	// Kontrola prázdnych polí - všetky polia musia byť vyplnené
	if (empty($user_form) or empty($password_form) or empty($password2_form)) {
		$errors = '<li>Please fill in all the required fields.</li>';
	} else {
		// Pripojíme sa k databáze
		include 'db/functions.php';
		$database = new Database();
		$connection = $database->connection();

		// Overíme, či užívateľské meno už náhodou neexistuje
		$statement = $connection->prepare('SELECT * FROM users WHERE user = :user LIMIT 1');
		$statement->execute(array(
			':user' => $user_form));	
		$result = $statement->fetch();
	
		// Ak fetch vráti niečo iné ako false, užívateľ už existuje
		if ($result != false) {
			$errors .= '<li>Sorry, the username already exists.</li>';
		}

		// Zahashujeme heslo - nikdy neukladáme heslo v čistom texte!
		$password_form = hash('sha512', $password_form);
		$password2_form = hash('sha512', $password2_form);

		// Overíme, či sa heslá zhodujú
		if ($password_form != $password2_form) {
			$errors .= '<li>The password confirmation does not match.</li>';
		}
	}

	// Ak nemáme žiadne chyby, vytvoríme nového užívateľa
	if ($errors == '') {
		$statement = $connection->prepare('INSERT INTO users (id_user, user, password) VALUES (null, :user, :password)');
		$statement->execute(array(
					':user' => $user_form,
				':password' => $password_form
			));
		http_response_code(401);
		header('Location: login.php');
	}

}

// Zobrazíme registračný formulár
require 'views/register.view.php';
?>