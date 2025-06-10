<?php
namespace App\Controllers;

use App\Core\Session;
use App\Views\Events\Actions\EventAdd;
require_once(__DIR__ . '/../Views/events/actions/Event.php');
require_once(__DIR__ . '/../Views/events/actions/EventAdd.php');

/**
 * Controller for adding events
 */
class EventAddController {
    /**
     * Process the event addition
     * 
     * @return void
     */
    public function process() {
        // Start session using Session class
        Session::start();

        // Check if user is logged in
        if (!Session::isLoggedIn()) {
            http_response_code(401);
            header('Location: /login');
            die();
        }

        // Instantiate and run
        $eventAdd = new EventAdd();
        $eventAdd->addEvent();
    }
}

// Create and run the controller
$controller = new EventAddController();
$controller->process(); 