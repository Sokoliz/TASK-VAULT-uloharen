<?php
namespace App\Controllers;

use App\Views\Events\Modals\EventEditModal;
require_once(__DIR__ . '/../Views/events/modals/Modal.php');
require_once(__DIR__ . '/../Views/events/modals/EventEditModal.php');

/**
 * Controller for displaying the event edit modal
 */
class EventEditModalController {
    /**
     * Event data
     * 
     * @var array|null
     */
    private $eventData;
    
    /**
     * Constructor
     * 
     * @param array|null $eventData Event data
     */
    public function __construct($eventData = null) {
        $this->eventData = $eventData;
    }
    
    /**
     * Render the modal
     * 
     * @return string HTML for the modal
     */
    public function render() {
        // Create and render the modal
        $modal = new EventEditModal($this->eventData);
        return $modal->render('Save');
    }
    
    /**
     * Display the modal
     */
    public function display() {
        echo $this->render();
    }
}

// Create and run the controller
$controller = new EventEditModalController();
$controller->display(); 