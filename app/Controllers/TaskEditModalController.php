<?php
namespace App\Controllers;

use App\Views\Events\Modals\TaskEditModal;
require_once(__DIR__ . '/../Views/events/modals/Modal.php');
require_once(__DIR__ . '/../Views/events/modals/TaskEditModal.php');

/**
 * Controller for displaying the task edit modal
 */
class TaskEditModalController {
    /**
     * Task data
     * 
     * @var array
     */
    private $taskData;
    
    /**
     * Task index
     * 
     * @var int
     */
    private $taskIndex;
    
    /**
     * Constructor
     * 
     * @param array $taskData Task data
     * @param int $taskIndex Task index
     */
    public function __construct($taskData = [], $taskIndex = 0) {
        $this->taskData = $taskData;
        $this->taskIndex = $taskIndex;
    }
    
    /**
     * Render the modal
     * 
     * @return string HTML for the modal
     */
    public function render() {
        // Create and render the modal
        $modal = new TaskEditModal($this->taskData, $this->taskIndex);
        return $modal->render();
    }
    
    /**
     * Display the modal
     */
    public function display() {
        echo $this->render();
    }
}

// Get task data and index from variables in scope
// In a real application, these would come from a database or request parameters
$taskData = isset($s) ? $s : [];
$taskIndex = isset($i) ? $i : 0;

// Create and run the controller
$controller = new TaskEditModalController($taskData, $taskIndex);
$controller->display(); 