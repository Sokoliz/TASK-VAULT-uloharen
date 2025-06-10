<?php
namespace App\Controllers;

use App\Views\Events\Modals\EventAddModal;
require_once(__DIR__ . '/../Views/events/modals/Modal.php');
require_once(__DIR__ . '/../Views/events/modals/EventAddModal.php');

/**
 * Controller for displaying the event add modal
 */
class EventAddModalController {
    /**
     * Render the modal
     * 
     * @return string HTML for the modal
     */
    public function render() {
        // Create and render the modal
        $modal = new EventAddModal();
        return $modal->render('Add');
    }
    
    /**
     * Display the modal
     */
    public function display() {
        echo $this->render();
    }
}

// Create and run the controller
$controller = new EventAddModalController();
$controller->display(); 