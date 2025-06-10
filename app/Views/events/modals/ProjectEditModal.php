<?php
namespace App\Views\Events\Modals;

require_once(__DIR__ . '/Modal.php');

/**
 * ProjectEditModal class for editing projects
 */
class ProjectEditModal extends Modal {
    /**
     * Project data for pre-filling fields
     */
    private $projectData;
    
    /**
     * Project index for generating unique modal ID
     */
    private $projectIndex;
    
    /**
     * Form action URL
     */
    private $formAction;
    
    /**
     * Constructor
     * 
     * @param array $projectData Project data to pre-fill the form
     * @param int $index Project index for generating unique modal ID
     */
    public function __construct($projectData, $index) {
        // Initialize with default values to prevent undefined array key warnings
        $this->projectData = array_merge([
            'project_name' => '',
            'project_description' => '',
            'project_colour' => '#0275d8',
            'start_date' => date('Y-m-d'),
            'end_date' => date('Y-m-d'),
            'completed' => 0,
            'id_project' => 0
        ], is_array($projectData) ? $projectData : []);
        
        $this->projectIndex = $index;
        $this->formAction = '/project/edit';
        
        parent::__construct('project-edit-' . $index, 'Edit your project', '/project/edit');
    }
    
    /**
     * Render the entire modal
     */
    public function render($submitButtonText = 'Submit') {
        $html = '<div id="project-edit-' . $this->projectIndex . '" class="modal fade" role="dialog">';
        $html .= '<div class="modal-dialog">';
        $html .= '<div class="modal-content">';
        
        // Header
        $html .= '<div class="modal-header">';
        $html .= '<h3 class="lead text-primary">Edit your project</h3>';
        $html .= '<a class="close text-dark btn" data-dismiss="modal">×</a>';
        $html .= '</div>';
        
        // Form
        $html .= '<form name="project" action="' . $this->formAction . '" method="POST" role="form">';
        
        // Body
        $html .= '<div class="modal-body">';
        
        // Project name field
        $html .= '<div class="form-group">';
        $html .= '<label class="text-dark" for="edit_name">Project Name<span class="text-danger pl-1">*</span></label>';
        $html .= '<input class="form-control" type="text" name="project_name" value="' . $this->projectData['project_name'] . '" required>';
        $html .= '</div>';
        
        // Description field
        $html .= '<div class="form-group">';
        $html .= '<label class="text-dark" for="edit_description">Description</label>';
        $html .= '<textarea class="form-control" type="text" name="project_description">' . $this->projectData['project_description'] . '</textarea>';
        $html .= '</div>';
        
        // Color field
        $html .= '<div class="form-group">';
        $html .= '<label for="edit_colour" class="text-dark">Colour</label>';
        
        // Set the color class and data attribute
        $colorClass = 'form-control color-select';
        $colorStyle = 'style="color:' . $this->projectData['project_colour'] . ' !important; background-color: #fff !important; font-weight:bold;"';
        $dataColor = 'data-color="' . $this->projectData['project_colour'] . '"';
        
        $html .= '<select name="project_colour" class="' . $colorClass . '" id="edit_colour" ' . $colorStyle . ' ' . $dataColor . '>';
        
        // Color options with proper styling for each option
        $colors = [
            '#0275d8' => 'Blue',
            '#5bc0de' => 'Tile',
            '#5cb85c' => 'Green',
            '#f0ad4e' => 'Orange',
            '#d9534f' => 'Red',
            '#292b2c' => 'Black'
        ];
        
        foreach ($colors as $colorValue => $colorName) {
            $selected = ($this->projectData['project_colour'] == $colorValue) ? ' selected' : '';
            $optionStyle = 'style="color:' . $colorValue . ' !important; font-weight:bold;"';
            
            $html .= '<option value="' . $colorValue . '"' . $selected . ' ' . $optionStyle . '>';
            $html .= '<span style="color:' . $colorValue . '; font-weight:bold;">&#9724;</span> ' . $colorName;
            $html .= '</option>';
        }
        
        $html .= '</select>';
        $html .= '</div>';
        
        // Date fields
        $html .= '<div class="form-group d-flex justify-content-between mt-2">';
        
        // Start date
        $html .= '<div class="col-6 mt-0 p-1">';
        $html .= '<label class="text-dark">Start Date<span class="text-danger pl-1">*</span></label>';
        $html .= '<input type="text" class="form-control" runat="server" id="startAdd1" name="start_date" value="' . $this->projectData['start_date'] . '" required data-date-format="yyyy-mm-dd"/>';
        $html .= '</div>';
        
        // End date
        $html .= '<div class="col-6 m-0 p-1">';
        $html .= '<label class="text-dark">End date<span class="text-danger pl-1">*</span></label>';
        $html .= '<input type="text" class="form-control" runat="server" id="endAdd1" name="end_date" value="' . $this->projectData['end_date'] . '"required data-date-format="yyyy-mm-dd"/>';
        $html .= '</div>';
        
        $html .= '</div>';
        
        // Hidden project ID field
        $html .= '<div class="form-group">';
        $html .= '<input hidden id="edit_id_project" name="id_project" value="' . $this->projectData['id_project'] . '" >';
        $html .= '</div>';
        
        $html .= '</div>'; // End modal body
        
        // Footer
        $html .= '<div class="modal-footer">';
        $html .= '<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>';
        $html .= '<button type="submit" class="btn btn-primary">' . $submitButtonText . '</button>';
        $html .= '</div>';
        
        $html .= '</form>';
        $html .= '</div>'; // End modal content
        $html .= '</div>'; // End modal dialog
        $html .= '</div>'; // End modal
        
        return $html;
    }
} 