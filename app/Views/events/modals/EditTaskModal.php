<?php
namespace App\Views\Events\Modals;

require_once(__DIR__ . '/Modal.php');

/**
 * EditTaskModal class for editing tasks
 */
class EditTaskModal extends Modal {
    /**
     * Task data for pre-filling fields
     */
    private $taskData;
    
    /**
     * Task index for generating unique modal ID
     */
    private $taskIndex;
    
    /**
     * Form action URL
     */
    private $formAction;
    
    /**
     * Constructor
     * 
     * @param array $taskData Task data to pre-fill the form
     * @param int $index Task index for generating unique modal ID
     */
    public function __construct($taskData, $index) {
        // Initialize with default values to prevent undefined array key warnings
        $this->taskData = array_merge([
            'task_name' => '',
            'task_description' => '',
            'task_colour' => '#5cb85c',
            'id_project' => 0,
            'id_task' => 0,
            'deadline' => ''
        ], is_array($taskData) ? $taskData : []);
        
        $this->taskIndex = $index;
        $this->formAction = '/task/edit';
        
        parent::__construct('task-edit-' . $index, 'Edit your task', '/task/edit');
    }
    
    /**
     * Render the entire modal
     */
    public function render($submitButtonText = 'Submit') {
        $html = '<div id="task-edit-' . $this->taskIndex . '" class="modal fade" role="dialog">';
        $html .= '<div class="modal-dialog">';
        $html .= '<div class="modal-content">';
        
        // Header
        $html .= '<div class="modal-header">';
        $html .= '<h3 class="lead text-primary" >Edit your task</h3>';
        $html .= '<a class="close text-dark btn" data-dismiss="modal">×</a>';
        $html .= '</div>';
        
        // Form
        $html .= '<form name="task" action="' . $this->formAction . '" method="POST" role="form">';
        
        // Body
        $html .= '<div class="modal-body">';
        
        // Add validation error container
        $html .= '<div class="alert alert-danger validation-errors" style="display:none;"></div>';
        
        // Task name field
        $html .= '<div class="form-group">';
        $html .= '<label class="text-dark" for="edit_name">Task Name<span class="text-danger pl-1">*</span></label>';
        $html .= '<input class="form-control" type="text" name="task_name" value="' . $this->taskData['task_name'] . '" required>';
        $html .= '</div>';
        
        // Description field
        $html .= '<div class="form-group">';
        $html .= '<label class="text-dark" for="edit_description">Description</label>';
        $html .= '<textarea class="form-control" type="text" name="task_description">' . $this->taskData['task_description'] . '</textarea>';
        $html .= '</div>';
        
        // Color/Priority field
        $html .= '<div class="form-group">';
        $html .= '<label for="edit_colour" class="text-dark">Colour</label>';
        
        // Determine color values
        $colorStyle = 'style="color:' . $this->taskData['task_colour'] . ' !important; font-weight:bold;"';
        
        $html .= '<select name="task_colour" class="form-control" ' . $colorStyle . '>';
        
        // Color options in fixed order: Low, Medium, High
        $html .= '<option value="#5cb85c" ' . ($this->taskData['task_colour'] == '#5cb85c' ? 'selected ' : '') . 'style="color:#5cb85c !important; font-weight:bold;"><span style="color:#5cb85c">&#9724;</span> Low</option>';
        $html .= '<option value="#f0ad4e" ' . ($this->taskData['task_colour'] == '#f0ad4e' ? 'selected ' : '') . 'style="color:#f0ad4e !important; font-weight:bold;"><span style="color:#f0ad4e">&#9724;</span> Medium</option>';
        $html .= '<option value="#d9534f" ' . ($this->taskData['task_colour'] == '#d9534f' ? 'selected ' : '') . 'style="color:#d9534f !important; font-weight:bold;"><span style="color:#d9534f">&#9724;</span> High</option>';
        
        $html .= '</select>';
        $html .= '</div>';
        
        // Deadline field
        $html .= '<div class="form-group d-flex justify-content-between mt-2">';
        $html .= '<div class="col-12 m-0 p-1">';
        $html .= '<label class="text-dark">Deadline<span class="text-danger pl-1">*</span></label>';
        
        // Format deadline value - don't show if it's the default date
        $deadlineValue = '';
        if (isset($this->taskData['deadline']) && $this->taskData['deadline'] !== '1970-01-01') {
            $deadlineValue = $this->taskData['deadline'];
        }
        
        $html .= '<input type="date" class="form-control" runat="server" name="deadline" value="' . $deadlineValue . '" data-date-format="yyyy-mm-dd" required/>';
        $html .= '</div>';
        $html .= '</div>';
        
        // Hidden fields - Fixed field names to match the controller expectations
        $html .= '<div class="form-group">';
        $html .= '<input hidden id="id_task" name="id_task" value="' . $this->taskData['id_task'] . '">';
        $html .= '</div>';
        
        $html .= '<div class="form-group">';
        $html .= '<input hidden id="id_project" name="id_project" value="' . $this->taskData['id_project'] . '">';
        $html .= '</div>';
        
        $html .= '</div>'; // End modal body
        
        // Footer
        $html .= '<div class="modal-footer">';
        $html .= '<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>';
        $html .= '<button type="submit" class="btn btn-primary">Update</button>';
        $html .= '</div>';
        
        $html .= '</form>';
        $html .= '</div>'; // End modal content
        $html .= '</div>'; // End modal dialog
        $html .= '</div>'; // End modal
        
        return $html;
    }
} 