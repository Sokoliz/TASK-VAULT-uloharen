<?php
namespace App\Controllers;

use App\Core\Session;
use App\Views\Events\Actions\EventEdit;
require_once(__DIR__ . '/../Views/events/actions/Event.php');
require_once(__DIR__ . '/../Views/events/actions/EventEdit.php');

/**
 * Controller pre úpravu udalostí v kalendári
 */
class EventEditController {
    
    public function process() {
        // Spustenie session pomocou Session triedy
        // Používame statickú triedu, aby sme nemuseli vytvárať inštanciu
        Session::start();

        // Kontrola, či je používateľ prihlásený
        // Bezpečnostné opatrenie, aby sa neprihlásení nedostali k úprave
        if (!Session::isLoggedIn()) {
            http_response_code(401);
            header('Location: /login');
            die();
        }

        // Debug logovanie - toto je super pri hľadaní chýb
        // Keď mám problém, vždy si takto vypíšem dáta
        error_log('EventEditController called with POST data: ' . print_r($_POST, true));

        // Vytvorenie inštancie a spustenie úpravy
        // Tu by som mohol pridať validáciu POST dát, ale asi to robí EventEdit trieda
        $eventEdit = new EventEdit();
        $eventEdit->updateEvent();
    }
}

// Vytvorenie a spustenie controllera
// Zase ten istý prístup, ktorý používam aj v ostatných controlleroch
$controller = new EventEditController();
$controller->process(); 