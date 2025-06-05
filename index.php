<?php
// Načítanie autoloadera Composeru pre automatické načítanie tried
require_once __DIR__ . '/vendor/autoload.php';


// Import kontrolérov, ktoré budú použité na spracovanie požiadaviek
use App\Controllers\AuthController;
use App\Controllers\ContentController;
use App\Controllers\CalendarController;
use App\Controllers\ProjectController;
use App\Controllers\TaskController;
use App\Controllers\TodayController;


// Získanie čistej URL cesty z požiadavky (bez parametrov v query string)
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

// Vytvorenie inštancie kontroléra pre autentifikáciu
$auth = new AuthController();


// Smerovanie požiadaviek podľa URL cesty
switch ($uri) {
    // Spracovanie registrácie nového používateľa
    case '/register':
        $auth->register();
        break;
    // Spracovanie prihlásenia používateľa
    case '/login':
      
        $auth->login();
        break;
    // Spracovanie odhlásenia používateľa
    case '/logout':
        $auth->logout();
        break;
     // Zobrazenie hlavného obsahu po prihlásení
     case '/content':
        (new ContentController())->index();
        break;
    // Zobrazenie kalendára udalostí
    case '/calendar':
        (new CalendarController())->index();
        break;
    // Vytvorenie novej udalosti v kalendári
    case '/calendar/create':
        (new CalendarController())->create();
        break;
    // Úprava existujúcej udalosti v kalendári
    case '/calendar/edit':
        (new CalendarController())->edit();
        break;
    // Zobrazenie zoznamu projektov
    case '/projects':
        // echo "project a astace....";  // Zakomentovaný debug výpis
        (new ProjectController())->index();
        break;
    // Vytvorenie nového projektu
    case '/project/create':
        (new ProjectController())->create();
        break;
    // Úprava existujúceho projektu
    case '/project/edit':
        (new ProjectController())->edit();
        break;
    // Vytvorenie novej úlohy v projekte
    case '/task/create':
        (new TaskController())->create();
        break;
    // Úprava existujúcej úlohy
    case '/task/edit':
        (new TaskController())->edit();
        break;
    // Posun úlohy doprava v Kanban tabuľke (zmena stavu)
    case '/task/right':
        (new TaskController())->move();
        break;
    // Posun úlohy doľava v Kanban tabuľke (zmena stavu)
    case '/task/left':
        (new TaskController())->move();
        break;
    // Zobrazenie dnešných udalostí a úloh
    case '/today':
        (new TodayController())->index();
        break;

    // Predvolená cesta - zobrazenie domovskej stránky
    default:
        
        require_once "./app/Views/index.php";
        break;
}