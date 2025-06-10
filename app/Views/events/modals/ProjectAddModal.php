<?php
namespace App\Views\Events\Modals;

require_once(__DIR__ . '/Modal.php');

/**
 * ProjectAddModal class for adding new projects
 */
class ProjectAddModal extends Modal {
    /**
     * Constructor
     */
    public function __construct() {
        parent::__construct('new-project-modal', 'Create a New Project', '/project/create');
        
        // Initialize fields
        $this->initializeFields();
    }
    
    /**
     * Custom header rendering to match the original style
     */
    protected function renderHeader() {
        return '<div class="modal-header">
                    <h3 class="lead text-primary">Create a New Project</h3>
                    <a class="close text-dark btn" data-dismiss="modal">×</a>
                </div>';
    }
    
    /**
     * Custom method to render date fields
     */
    private function renderDateFields() {
        $html = '<div class="form-group d-flex justify-content-between mt-2">';
        
        // Start date field
        $html .= '<div class="col-6 mt-0 p-1">';
        $html .= '<label class="text-dark">Start Date<span class="text-danger pl-1">*</span></label>';
        
        // Use a properly formatted date input with today's date as default
        $today = date('Y-m-d');
        $html .= '<input type="date" class="form-control" id="start_date" name="start_date" value="' . $today . '" required/>';
        $html .= '</div>';
        
        // End date field
        $html .= '<div class="col-6 m-0 p-1">';
        $html .= '<label class="text-dark">End date<span class="text-danger pl-1">*</span></label>';
        
        // Use a properly formatted date input with default value one week from today
        $nextWeek = date('Y-m-d', strtotime('+1 week'));
        $html .= '<input type="date" class="form-control" id="end_date" name="end_date" value="' . $nextWeek . '" required/>';
        $html .= '</div>';
        
        $html .= '</div>';
        
        return $html;
    }
    
    /**
     * Override render body to include custom fields
     */
    protected function renderBody() {
        $html = '<div class="modal-body">';
        
        // Add validation error container
        $html .= '<div class="alert alert-danger validation-errors" style="display:none;"></div>';
        
        // Add detailed debug info in hidden field
        $html .= '<input type="hidden" name="debug_info" value="Modal rendered at: ' . date('Y-m-d H:i:s') . '">';
        
        // Render regular fields
        foreach ($this->fields as $field) {
            $html .= $this->renderField($field);
        }
        
        // Add date fields
        $html .= $this->renderDateFields();
        
        // Add hidden field for user ID
        $html .= '<div class="form-group">';
        $html .= '<input hidden id="id_user" name="id_user" value="' . $_SESSION['user_id'] . '">';
        $html .= '</div>';
        
        $html .= '</div>';
        
        return $html;
    }

    /**
     * Override the footer to ensure submit button is properly included
     */
    protected function renderFooter($submitText = 'Save') {
        return '<div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">' . $submitText . '</button>
                </div>';
    }
    
    /**
     * Override render method to ensure form wraps both body and footer
     */
    public function render($submitText = 'Create Project') {
        $html = '<div class="modal fade" id="' . $this->modalId . '" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">';
        $html .= '<div class="modal-dialog">';
        $html .= '<div class="modal-content">';
        
        $html .= $this->renderHeader();
        
        // Open the form here to include both body and footer
        $html .= '<form class="form-horizontal" method="post" action="' . $this->action . '">';
        
        $html .= $this->renderBody();
        $html .= $this->renderFooter($submitText);
        
        // Close the form here after the footer
        $html .= '</form>';
        
        $html .= '</div>';
        $html .= '</div>';
        $html .= '</div>';
        
        return $html;
    }
    
    /**
     * Initialize modal fields
     */
    private function initializeFields() {
        // Add project name field
        $this->addTextField('project_name', 'Project Name<span class="text-danger pl-1">*</span>', '', true);
        
        // Add description field
        $this->addTextareaField('project_description', 'Description', '', false);
        
        // Color options with proper styling
        $colorOptions = [
            '#0275d8' => ['text' => '<span style="color:#0275d8; font-weight:bold;">&#9724;</span> Blue', 'style' => 'color:#0275d8; background-color: #fff; font-weight:bold;'],
            '#5bc0de' => ['text' => '<span style="color:#5bc0de; font-weight:bold;">&#9724;</span> Tile', 'style' => 'color:#5bc0de; background-color: #fff; font-weight:bold;'],
            '#5cb85c' => ['text' => '<span style="color:#5cb85c; font-weight:bold;">&#9724;</span> Green', 'style' => 'color:#5cb85c; background-color: #fff; font-weight:bold;'],
            '#f0ad4e' => ['text' => '<span style="color:#f0ad4e; font-weight:bold;">&#9724;</span> Orange', 'style' => 'color:#f0ad4e; background-color: #fff; font-weight:bold;'],
            '#d9534f' => ['text' => '<span style="color:#d9534f; font-weight:bold;">&#9724;</span> Red', 'style' => 'color:#d9534f; background-color: #fff; font-weight:bold;'],
            '#292b2c' => ['text' => '<span style="color:#292b2c; font-weight:bold;">&#9724;</span> Black', 'style' => 'color:#292b2c; background-color: #fff; font-weight:bold;']
        ];
        
        // Add color selection with green as default
        $this->addSelectField('project_colour', 'Colour', $colorOptions, '#5cb85c');
    }
} 