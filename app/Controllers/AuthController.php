<?php
namespace App\Controllers;

use App\Models\User;
use App\Core\Session;
use App\Views\Auth\LoginView;
use App\Views\Auth\RegisterView;
use App\Views\Auth\LoginErrorView;
use App\Views\Auth\RegisterErrorView;

class AuthController
{
    private $userModel;

    public function __construct()
    {
        Session::start();
        $this->userModel = new User();
    }

    public function register()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $user_form = filter_var(htmlspecialchars($_POST['username']), FILTER_SANITIZE_STRING);
            $password_form = filter_var(htmlspecialchars($_POST['password']), FILTER_SANITIZE_STRING);
            $password2_form = filter_var(htmlspecialchars($_POST['password2']), FILTER_SANITIZE_STRING);
            $errors = '';

            // Kontrola či nechýba meno alebo heslo
            if (empty($user_form) || empty($password_form)) {
                http_response_code(400);
                // Use OOP view instead of requiring a file
                $errorView = new RegisterErrorView(['errorMessage' => 'Missing username or password']);
                echo $errorView->render();
                exit();
            }
            if (empty($user_form) or empty($password_form) or empty($password2_form)) {
                $errors = '<li>Please fill in all the required fields.</li>';
            } else {


                // Ak fetch vráti niečo iné ako false, užívateľ už existuje
                if ($this->userModel->userExists($user_form)) {
                    $errors .= '<li>Sorry, the username already exists.</li>';
                }
                // Overíme, či sa heslá zhodujú
                if (hash('sha512', $password_form) != hash('sha512', $password2_form)) {
                    $errors .= '<li>The password confirmation does not match.</li>';
                }
            }

            // Ak nemáme žiadne chyby, vytvoríme nového užívateľa
            if ($errors == '') {
                $this->userModel->register($user_form, $password_form);
                http_response_code(401);
                header('Location: /login');
                exit;
            }
        }

        // Skontrolujeme, či je používateľ prihlásený
        error_log("Register page - checking if user is logged in");
        if(Session::isLoggedIn()){
            error_log("User is logged in, redirecting to /content from register page");
            header('Location: /content');
	        die();
        }
        
        // Načítaj a zobraz view - použiť OOP verziu
        error_log("User is not logged in, showing register form");
        $registerView = new RegisterView(['errors' => $errors ?? '']);
        echo $registerView->render();
    }

    public function login()
    {
        // Najprv skontrolujeme, či nie je používateľ už prihlásený
        error_log("Login page - checking if user is already logged in");
        if(Session::isLoggedIn()){
            error_log("User already logged in, redirecting to /content from login page");
            header('Location: /content');
            die();
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Add debugging log
            error_log("Login attempt - POST data received: " . print_r($_POST, true));
          
            $user_form = filter_var(htmlspecialchars($_POST['username']), FILTER_SANITIZE_STRING);
            $password_form = filter_var(htmlspecialchars($_POST['password']), FILTER_SANITIZE_STRING);
            $errors = '';

            if (empty($user_form) || empty($password_form)) {
                error_log("Login error: Empty username or password");
                http_response_code(400);
                // Use OOP view instead of requiring a file
                $errorView = new LoginErrorView(['errorMessage' => 'Missing username or password']);
                echo $errorView->render();
                exit();
            }

            $userId = $this->userModel->login($user_form, $password_form);
            error_log("Login result: " . ($userId ? "Success - User ID: " . $userId['user_id'] : "Failed - Invalid credentials"));

            if ($userId) {
                Session::start(); // Ensure session is started
                Session::set('user_id', $userId['user_id']);
                Session::set('user', $userId['user']);
                
                error_log("Session set - User ID: " . Session::get('user_id') . ", Username: " . Session::get('user'));
                error_log("Redirecting to /content");
                
                header("Location: /content");
                exit;
            } else {
                // Nastavenie chybovej správy pri neúspešnom prihlásení
                $errors = '<li>The username or password is incorrect</li>';
            }
        }
        
        // Načítaj a zobraz view - použiť OOP verziu
        error_log("Showing login form");
        $loginView = new LoginView(['errors' => $errors ?? '']);
        echo $loginView->render();
    }

    public function logout()
    {
        error_log("Logout called - destroying session");
        Session::start();
        Session::destroy();
        
        // Presmerujeme používateľa na hlavnú stránku
        error_log("Redirecting to home page after logout");
        header("Location: /");
        exit;
    }
}