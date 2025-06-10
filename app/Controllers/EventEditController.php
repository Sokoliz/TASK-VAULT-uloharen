<?php
namespace App\Controllers;

use App\Core\Session;
use App\Views\Events\Actions\EventEdit;
require_once(__DIR__ . '/../Views/events/actions/Event.php');
require_once(__DIR__ . '/../Views/events/actions/EventEdit.php');

/**
 * Controller for editing events
 */
class EventEditController {
    /**
     * Process the event edit
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

        // Debug logging
        error_log('EventEditController called with POST data: ' . print_r($_POST, true));

        // Instantiate and run
        $eventEdit = new EventEdit();
        $eventEdit->updateEvent();
    }
}

// Create and run the controller
$controller = new EventEditController();
$controller->process(); 