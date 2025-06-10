<?php
namespace App\Views\Events\Modals;

require_once(__DIR__ . '/Modal.php');

/**
 * TaskModal class for creating new tasks
 */
class TaskAddModal extends Modal {
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
        parent::__construct('new-task-modal', 'Create a New Task', '/task/create');
        
        $this->project_id = $project_id;
        
        // Initialize fields
        $this->initializeFields();
    }
    
    /**
     * Custom header rendering to match the original style
     */
    protected function renderHeader() {
        return '<div class="modal-header">
                    <h3 class="lead text-primary">Create a New Task</h3>
                    <a class="close text-dark btn" data-dismiss="modal">×</a>
                </div>';
    }
    
    /**
     * Custom method to add radio buttons for task status
     */
    private function addStatusRadioButtons() {
        $html = '<label class="text-dark">Status<span class="text-danger pl-1">*</span></label>';
        $html .= '<div class="form-group d-flex justify-content-around">';
        
        $html .= '<div class="form-check">';
        $html .= '<input class="form-check-input btn" type="radio" name="task_status" id="task_status_1" value="1" checked>';
        $html .= '<label class="form-check-label" for="task_status_1">To do</label>';
        $html .= '</div>';
        
        $html .= '<div class="form-check">';
        $html .= '<input class="form-check-input btn" type="radio" name="task_status" id="task_status_2" value="2">';
        $html .= '<label class="form-check-label" for="task_status_2">In progress</label>';
        $html .= '</div>';
        
        $html .= '<div class="form-check">';
        $html .= '<input class="form-check-input btn" type="radio" name="task_status" id="task_status_3" value="3">';
        $html .= '<label class="form-check-label" for="task_status_3">Complete</label>';
        $html .= '</div>';
        
        $html .= '</div>';
        
        return $html;
    }
    
    /**
     * Custom method to render deadline field
     */
    private function renderDeadlineField() {
        $html = '<div class="form-group d-flex justify-content-between mt-2">';
        $html .= '<div class="col-12 m-0 p-1">';
        $html .= '<label class="text-dark">Deadline<span class="text-danger pl-1">*</span></label>';
        $html .= '<input type="date" class="form-control" runat="server" name="deadline" min="' . date('Y-m-d') . '" data-date-format="yyyy-mm-dd" required/>';
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
        
        // Add status radio buttons
        $html .= $this->addStatusRadioButtons();
        
        // Render regular fields
        foreach ($this->fields as $field) {
            $html .= $this->renderField($field);
        }
        
        // Add deadline field
        $html .= $this->renderDeadlineField();
        
        // Add hidden fields
        $html .= '<div class="form-group">';
        $html .= '<input hidden id="id_task_project" name="id_project" value="' . $this->project_id . '">';
        $html .= '</div>';
        
        $html .= '<div class="form-group">';
        $html .= '<input hidden id="id_user" name="id_user" value="' . $_SESSION['user_id'] . '">';
        $html .= '</div>';
        
        $html .= '</div>';
        
        return $html;
    }
    
    /**
     * Override render method to ensure form wraps both body and footer
     */
    public function render($submitText = 'Create Task') {
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
        // Add task name field
        $this->addTextField('task_name', 'Task Name<span class="text-danger pl-1">*</span>', '', true);
        
        // Add description field
        $this->addTextareaField('task_description', 'Description', '', false);
        
        // Add priority select field
        $priorityOptions = [
            '#5cb85c' => ['text' => '<span style="color:#5cb85c; font-weight:bold;">&#9724;</span> Low', 'style' => 'color:#5cb85c; font-weight:bold;'],
            '#f0ad4e' => ['text' => '<span style="color:#f0ad4e; font-weight:bold;">&#9724;</span> Medium', 'style' => 'color:#f0ad4e; font-weight:bold;'],
            '#d9534f' => ['text' => '<span style="color:#d9534f; font-weight:bold;">&#9724;</span> High', 'style' => 'color:#d9534f; font-weight:bold;']
        ];
        
        $this->addSelectField('task_colour', 'Priority<span class="text-danger pl-1">*</span>', $priorityOptions, '', true);
    }
} 