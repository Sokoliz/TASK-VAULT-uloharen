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
		echo '<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Error</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css">
    <style>
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background-color: #f8f9fa;
        }
        .error-box {
            background-color: #ffebee;
            border-left: 4px solid #f44336;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
            max-width: 400px;
            width: 100%;
        }
        .error-title {
            color: #b71c1c;
            font-weight: bold;
            margin-bottom: 10px;
        }
        .error-message {
            color: #b71c1c;
        }
        .btn-back {
            margin-top: 15px;
        }
    </style>
</head>
<body>
    <div class="error-box">
        <div class="error-title">Error</div>
        <div class="error-message">Missing username or password</div>
        <a href="login.php" class="btn btn-primary btn-back">Go Back</a>
    </div>
</body>
</html>';
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

