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
        <a href="register.php" class="btn btn-primary btn-back">Go Back</a>
    </div>
</body>
</html>';
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