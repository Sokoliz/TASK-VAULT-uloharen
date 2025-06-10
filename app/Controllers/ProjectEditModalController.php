<?php
namespace App\Controllers;

use App\Views\Events\Modals\ProjectEditModal;
require_once(__DIR__ . '/../Views/events/modals/Modal.php');
require_once(__DIR__ . '/../Views/events/modals/ProjectEditModal.php');

/**
 * Controller for displaying the project edit modal
 */
class ProjectEditModalController {
    /**
     * Project data
     * 
     * @var array
     */
    private $projectData;
    
    /**
     * Project index
     * 
     * @var int
     */
    private $projectIndex;
    
    /**
     * Constructor
     * 
     * @param array $projectData Project data
     * @param int $projectIndex Project index
     */
    public function __construct($projectData = [], $projectIndex = 0) {
        $this->projectData = $projectData;
        $this->projectIndex = $projectIndex;
    }
    
    /**
     * Render the modal
     * 
     * @return string HTML for the modal
     */
    public function render() {
        // Create and render the modal
        $modal = new ProjectEditModal($this->projectData, $this->projectIndex);
        return $modal->render('Update');
    }
    
    /**
     * Display the modal
     */
    public function display() {
        echo $this->render();
    }
}

// Get project data and index from variables in scope
// In a real application, these would come from a database or request parameters
$projectData = isset($p) ? $p : [];
$projectIndex = isset($i) ? $i : 0;

// Create and run the controller
$controller = new ProjectEditModalController($projectData, $projectIndex);
$controller->display(); 