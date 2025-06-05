<?php
namespace App\Controllers;

use App\Models\User;
use App\Core\Session;

// Kontrolér pre autentifikáciu používateľov - spravuje registráciu, prihlásenie a odhlásenie
class AuthController
{
    private $userModel;

    // Konštruktor inicializuje sedenie a vytvorí inštanciu modelu používateľa
    public function __construct()
    {
        Session::start();
        $this->userModel = new User();
    }

    // Metóda pre registráciu nového používateľa
    public function register()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            // Sanitizácia vstupných údajov z formulára pre bezpečnosť
            $user_form = filter_var(htmlspecialchars($_POST['username']), FILTER_SANITIZE_STRING);
            $password_form = filter_var(htmlspecialchars($_POST['password']), FILTER_SANITIZE_STRING);
            $password2_form = filter_var(htmlspecialchars($_POST['password2']), FILTER_SANITIZE_STRING);
            $errors = '';

            // Kontrola či nechýba meno alebo heslo
            if (empty($user_form) || empty($password_form)) {
                // Nastavenie HTTP kódu pre chybu a zobrazenie chybovej stránky
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
            // Druhá kontrola, či sú vyplnené všetky polia formulára
            if (empty($user_form) or empty($password_form) or empty($password2_form)) {
                $errors = '<li>Please fill in all the required fields.</li>';
            } else {

                // Kontrola, či používateľské meno už existuje v databáze
                if ($this->userModel->userExists($user_form)) {
                    $errors .= '<li>Sorry, the username already exists.</li>';
                }
                // Overenie, či sa heslo a potvrdenie hesla zhodujú pomocou hashu
                if (hash('sha512', $password_form) != hash('sha512', $password2_form)) {
                    $errors .= '<li>The password confirmation does not match.</li>';
                }
            }

            // Ak nie sú žiadne chyby, registrujeme nového používateľa
            if ($errors == '') {
                // Zavolanie metódy na vytvorenie nového používateľa v databáze
                $this->userModel->register($user_form, $password_form);
                http_response_code(401);
                // Presmerovanie na prihlasovací formulár
                header('Location: /login');
            }
        }

        // Ak je používateľ už prihlásený, presmerujeme ho na obsah
        if(Session::isLoggedIn()){
            header('Location: /content');
	        die();
        }
        // Zobrazenie registračného formulára
        require __DIR__ . '/../Views/register.php';
    }

    // Metóda pre prihlásenie používateľa
    public function login()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            // Sanitizácia vstupov z prihlasovacieho formulára
            $user_form = filter_var(htmlspecialchars($_POST['username']), FILTER_SANITIZE_STRING);
            $password_form = filter_var(htmlspecialchars($_POST['password']), FILTER_SANITIZE_STRING);
            $errors = '';

            // Kontrola, či boli zadané používateľské meno a heslo
            if (empty($user_form) || empty($password_form)) {
                // Nastavenie HTTP kódu pre chybu a zobrazenie chybovej stránky
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

            // Pokus o prihlásenie používateľa, vracia ID používateľa ak je prihlásenie úspešné
            $userId = $this->userModel->login($user_form, $password_form);

            // Ak prihlásenie prebehlo úspešne
            if ($userId ) {
                // Nastavenie premenných sedenia pre prihlásenie používateľa
                Session::set('user_id', $userId['user_id']);
                Session::set('user', $userId['user']);

                // Presmerovanie na obsah po úspešnom prihlásení
                header("Location: /content");
                exit;
                } else {
                    // Nastavenie chybovej správy pri neúspešnom prihlásení
                    $errors = '<li>The username or password is incorrect</li>';
                }
           
        }

        // Ak je používateľ už prihlásený, presmerujeme ho na obsah
        if(Session::isLoggedIn()){
            header('Location: /content');
	        die();
        }
        // Zobrazenie prihlasovacieho formulára
        require __DIR__ . '/../Views/login.php';
    }

    // Metóda pre odhlásenie používateľa
    public function logout()
    {
        // Začatie sedenia a následné zničenie sedenia pre odhlásenie
        Session::start();
        Session::destroy();
        // Presmerovanie na domovskú stránku po odhlásení
        header("Location: /");
        exit;
    }
}