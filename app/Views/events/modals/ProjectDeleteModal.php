<?php
namespace App\Views\Events\Modals;

require_once(__DIR__ . '/Modal.php');

/**
 * ProjectDeleteModal class for confirming project deletion
 */
class ProjectDeleteModal extends Modal {
    /**
     * Project data
     */
    private $projectData;
    
    /**
     * Project index for generating unique modal ID
     */
    private $projectIndex;
    
    /**
     * Constructor
     * 
     * @param array $projectData Project data
     * @param int $index Project index for generating unique modal ID
     */
    public function __construct($projectData, $index) {
        // Initialize with default values to prevent undefined array key warnings
        $this->projectData = array_merge([
            'project_name' => 'Unnamed Project',
            'id_project' => 0
        ], is_array($projectData) ? $projectData : []);
        
        $this->projectIndex = $index;
        
        parent::__construct('project-delete-' . $index, 'Delete Project', '/project/edit');
        
        // Initialize fields
        $this->initializeFields();
    }
    
    /**
     * Custom header rendering to match the original style
     */
    protected function renderHeader() {
        return '<div class="modal-header">
                    <h3 class="lead text-danger">Delete Project</h3>
                <a class="close text-dark btn" data-dismiss="modal">×</a>
                </div>';
    }
    
    /**
     * Override render body to include confirmation message
     */
    protected function renderBody() {
        $html = '<div class="modal-body">';
        $html .= '<p>Are you sure you want to delete this project?</p>';
        $html .= '<p><strong>' . $this->projectData['project_name'] . '</strong></p>';
        $html .= '<p class="text-danger">All tasks in this project will also be deleted!</p>';
        
        // Add hidden fields
        $html .= '<input type="hidden" name="id_project" value="' . $this->projectData['id_project'] . '">';
        $html .= '<input type="hidden" name="delete" value="1">'; // Add this field for deletion logic
        $html .= '</div>';
        
        return $html;
    }
    
    /**
     * Override renderFooter to customize buttons
     */
    protected function renderFooter($submitText = 'Delete') {
        return '<div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger">' . $submitText . '</button>
                </div>';
    }
    
    /**
     * Override render method to add a custom ID to the form
     */
    public function render($submitText = 'Save') {
        $html = '<div id="project-delete-' . $this->projectIndex . '" class="modal fade" role="dialog">';
        $html .= '<div class="modal-dialog">';
        $html .= '<div class="modal-content">';
        $html .= $this->renderHeader();
        $html .= '<form id="delete-project-form-' . $this->projectIndex . '" name="project" action="' . $this->action . '" method="POST" role="form">';
        $html .= $this->renderBody();
        $html .= $this->renderFooter($submitText);
        $html .= '</form>';
        $html .= '</div>';
        $html .= '</div>';
        $html .= '</div>';
        
        return $html;
    }
    
    /**
     * Initialize modal fields (no visible fields in this modal)
     */
    private function initializeFields() {
        // No visible fields needed
    }
} 