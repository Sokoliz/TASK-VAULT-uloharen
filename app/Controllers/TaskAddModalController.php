<?php
namespace App\Controllers;

use App\Views\Events\Modals\TaskAddModal;
require_once(__DIR__ . '/../Views/events/modals/Modal.php');
require_once(__DIR__ . '/../Views/events/modals/TaskAddModal.php');

/**
 * Controller for displaying the task add modal
 */
class TaskAddModalController {
    /**
     * Project ID for the task
     */
    private $project_id;
    
    /**
     * Constructor
     * 
     * @param int $project_id Project ID for the task
     */
    public function __construct($project_id) {
        $this->project_id = $project_id;
    }
    
    /**
     * Render the modal
     * 
     * @return string HTML for the modal
     */
    public function render() {
        // Create and render the modal
        $modal = new TaskAddModal($this->project_id);
        return $modal->render('Create');
    }
    
    /**
     * Display the modal
     */
    public function display() {
        echo $this->render();
    }
}

// Get project ID from the URL parameter
$id_project_for_task = isset($_GET['idProject']) ? $_GET['idProject'] : 0;

// Create and run the controller
$controller = new TaskAddModalController($id_project_for_task);
$controller->display(); 