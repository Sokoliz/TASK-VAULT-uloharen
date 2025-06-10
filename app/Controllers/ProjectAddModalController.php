<?php
namespace App\Controllers;

use App\Views\Events\Modals\ProjectAddModal;
require_once(__DIR__ . '/../Views/events/modals/Modal.php');
require_once(__DIR__ . '/../Views/events/modals/ProjectAddModal.php');

/**
 * Controller for displaying the project add modal
 */
class ProjectAddModalController {
    /**
     * Render the modal
     * 
     * @return string HTML for the modal
     */
    public function render() {
        // Create and render the modal
        $modal = new ProjectAddModal();
        $modalHtml = $modal->render('Create');

        // Add reference to external JavaScript file for form validation
        $modalHtml .= '<script src="/public/js/project-form-validation.js"></script>';

        return $modalHtml;
    }
    
    /**
     * Display the modal
     */
    public function display() {
        echo $this->render();
    }
}

// Create and run the controller
$controller = new ProjectAddModalController();
$controller->display(); 