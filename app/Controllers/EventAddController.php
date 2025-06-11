<?php
namespace App\Controllers;

use App\Core\Session;
use App\Views\Events\Actions\EventAdd;
require_once(__DIR__ . '/../Views/events/actions/Event.php');
require_once(__DIR__ . '/../Views/events/actions/EventAdd.php');

/**
 * Controller pre pridávanie udalostí do kalendára
 */
class EventAddController {
    
    public function process() {
        // Spustenie session pomocou Session triedy
        // Toto je lepšie ako používať natívne PHP session funkcie
        Session::start();

        // Kontrola, či je používateľ prihlásený
        // Nechcem, aby niekto pridával udalosti bez prihlásenia
        if (!Session::isLoggedIn()) {
            http_response_code(401);
            header('Location: /login');
            die();
        }

        // Vytvorenie inštancie a spustenie
        // Toto by sa dalo vylepšiť použitím dependency injection
        $eventAdd = new EventAdd();
        $eventAdd->addEvent();
    }
}

// Vytvorenie a spustenie controllera
// Toto je jednoduchšie ako používať router, ale nie je to najlepší prístup
$controller = new EventAddController();
$controller->process(); 