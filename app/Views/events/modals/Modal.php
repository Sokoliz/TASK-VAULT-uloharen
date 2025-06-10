<?php
namespace App\Views\Events\Modals;

/**
 * Base Modal class for handling modal dialogs
 */
class Modal {
    /**
     * ID of the modal
     */
    protected $modalId;
    
    /**
     * Title of the modal
     */
    protected $title;
    
    /**
     * Action URL for the form
     */
    protected $action;
    
    /**
     * Modal form fields
     */
    protected $fields = [];
    
    /**
     * Constructor
     * 
     * @param string $modalId ID of the modal
     * @param string $title Title of the modal
     * @param string $action Action URL for the form
     */
    public function __construct($modalId, $title, $action) {
        $this->modalId = $modalId;
        $this->title = $title;
        $this->action = $action;
    }
    
    /**
     * Render the modal header
     * 
     * @return string HTML for the modal header
     */
    protected function renderHeader() {
        return '<div class="modal-header d-flex justify-content-between">
                    <h4 class="modal-title" id="myModalLabel">' . $this->title . '</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                </div>';
    }
    
    /**
     * Render the modal footer with submit button
     * 
     * @param string $submitText Text for the submit button
     * @return string HTML for the modal footer
     */
    protected function renderFooter($submitText = 'Save') {
        return '<div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">' . $submitText . '</button>
                </div>';
    }
    
    /**
     * Add a text field to the modal
     * 
     * @param string $name Name of the field
     * @param string $label Label for the field
     * @param string $value Default value
     * @param bool $required Whether the field is required
     * @param string $placeholder Placeholder text
     */
    public function addTextField($name, $label, $value = '', $required = false, $placeholder = '') {
        $this->fields[] = [
            'type' => 'text',
            'name' => $name,
            'label' => $label,
            'value' => $value,
            'required' => $required,
            'placeholder' => $placeholder
        ];
    }
    
    /**
     * Add a textarea field to the modal
     * 
     * @param string $name Name of the field
     * @param string $label Label for the field
     * @param string $value Default value
     * @param bool $required Whether the field is required
     * @param string $placeholder Placeholder text
     */
    public function addTextareaField($name, $label, $value = '', $required = false, $placeholder = '') {
        $this->fields[] = [
            'type' => 'textarea',
            'name' => $name,
            'label' => $label,
            'value' => $value,
            'required' => $required,
            'placeholder' => $placeholder
        ];
    }
    
    /**
     * Add a date field to the modal
     * 
     * @param string $name Name of the field
     * @param string $label Label for the field
     * @param string $value Default value
     * @param bool $required Whether the field is required
     */
    public function addDateField($name, $label, $value = '', $required = false) {
        $this->fields[] = [
            'type' => 'date',
            'name' => $name,
            'label' => $label,
            'value' => $value,
            'required' => $required
        ];
    }
    
    /**
     * Add a hidden field to the modal
     * 
     * @param string $name Name of the field
     * @param string $value Value of the field
     */
    public function addHiddenField($name, $value) {
        // Špeciálna kontrola pre id_event, aby sme zabezpečili, že nie je prázdne
        if ($name === 'id_event' && (empty($value) || $value === 'null' || $value === 'undefined')) {
            // Logujeme chybu
            error_log('Warning: Attempting to add empty or invalid ID: ' . var_export($value, true));
            // Pre ID polia použijeme aspoň placeholder, aby sme zabránili úplne prázdnym hodnotám
            $value = '';
        }
        
        $this->fields[] = [
            'type' => 'hidden',
            'name' => $name,
            'value' => $value
        ];
    }
    
    /**
     * Add a select field to the modal
     * 
     * @param string $name Name of the field
     * @param string $label Label for the field
     * @param array $options Options for the select
     * @param string $selectedValue Selected option value
     * @param bool $required Whether the field is required
     */
    public function addSelectField($name, $label, $options, $selectedValue = '', $required = false) {
        $this->fields[] = [
            'type' => 'select',
            'name' => $name,
            'label' => $label,
            'options' => $options,
            'selected' => $selectedValue,
            'required' => $required
        ];
    }
    
    /**
     * Add a checkbox field to the modal
     * 
     * @param string $name Name of the field
     * @param string $label Label for the field
     * @param bool $checked Whether the checkbox is checked
     */
    public function addCheckboxField($name, $label, $checked = false) {
        $this->fields[] = [
            'type' => 'checkbox',
            'name' => $name,
            'label' => $label,
            'checked' => $checked
        ];
    }
    
    /**
     * Render a field based on its type
     * 
     * @param array $field Field data
     * @return string HTML for the field
     */
    protected function renderField($field) {
        $html = '';
        
        switch ($field['type']) {
            case 'text':
                $html .= '<div class="form-group">';
                $html .= '<label for="' . $field['name'] . '" class="col-sm-4 control-label">' . $field['label'] . '</label>';
                $html .= '<div class="col-sm-12">';
                $html .= '<input type="text" name="' . $field['name'] . '" class="form-control" id="' . $field['name'] . '" ';
                
                if (!empty($field['placeholder'])) {
                    $html .= 'placeholder="' . $field['placeholder'] . '" ';
                }
                
                if (!empty($field['value'])) {
                    $html .= 'value="' . $field['value'] . '" ';
                }
                
                if ($field['required']) {
                    $html .= 'required';
                }
                
                $html .= '>';
                // Add a div for validation messages
                if ($field['name'] === 'title') {
                    $html .= '<div class="validation-message"></div>';
                }
                $html .= '</div>';
                $html .= '</div>';
                break;
                
            case 'textarea':
                $html .= '<div class="form-group">';
                $html .= '<label for="' . $field['name'] . '" class="col-sm-4 control-label">' . $field['label'] . '</label>';
                $html .= '<div class="col-sm-12">';
                $html .= '<textarea name="' . $field['name'] . '" class="form-control" id="' . $field['name'] . '" ';
                
                if (!empty($field['placeholder'])) {
                    $html .= 'placeholder="' . $field['placeholder'] . '" ';
                }
                
                if ($field['required']) {
                    $html .= 'required';
                }
                
                $html .= '>';
                
                if (!empty($field['value'])) {
                    $html .= $field['value'];
                }
                
                $html .= '</textarea>';
                $html .= '</div>';
                $html .= '</div>';
                break;
                
            case 'select':
                $html .= '<div class="form-group">';
                $html .= '<label for="' . $field['name'] . '" class="col-sm-4 control-label">' . $field['label'] . '</label>';
                $html .= '<div class="col-sm-12">';
                
                // Pre výberové polia farieb, nastav farbu výberového poľa podľa vybranej hodnoty
                $selectStyle = '';
                $selectClass = 'form-control';
                $dataColor = '';
                
                if ($field['name'] === 'colour') {
                    $selectClass .= ' colour-select';
                    
                    if (!empty($field['selected'])) {
                        $selectStyle = 'style="background-color:' . $field['selected'] . '; color: white;"';
                        $dataColor = 'data-color="' . $field['selected'] . '"';
                    }
                }
                
                $html .= '<select name="' . $field['name'] . '" class="' . $selectClass . '" id="' . $field['name'] . '" ' . $selectStyle . ' ' . $dataColor;
                
                if ($field['required']) {
                    $html .= ' required';
                }
                
                $html .= '>';
                
                foreach ($field['options'] as $value => $option) {
                    $selected = ($value === $field['selected']) ? 'selected' : '';
                    
                    // Handle both string and array option formats
                    if (is_array($option)) {
                        $optionStyle = isset($option['style']) ? $option['style'] : '';
                        $optionText = isset($option['text']) ? $option['text'] : $value;
                    } else {
                        $optionStyle = '';
                        $optionText = $option;
                    }
                    
                    $html .= '<option value="' . $value . '" ' . $selected . ' style="' . $optionStyle . '">' . $optionText . '</option>';
                }
                
                $html .= '</select>';
                $html .= '</div>';
                $html .= '</div>';
                break;
                
            case 'date':
                $html .= '<div class="form-group">';
                $html .= '<label for="' . $field['name'] . '" class="col-sm-4 control-label">' . $field['label'] . '</label>';
                $html .= '<div class="col-sm-12">';
                $html .= '<input type="text" name="' . $field['name'] . '" class="form-control date-picker" id="' . $field['name'] . '" ';
                
                if (!empty($field['value'])) {
                    $html .= 'value="' . $field['value'] . '" ';
                }
                
                if ($field['required']) {
                    $html .= 'required';
                }
                
                $html .= '>';
                $html .= '</div>';
                $html .= '</div>';
                break;
                
            case 'hidden':
                $html .= '<input type="hidden" name="' . $field['name'] . '" id="' . $field['name'] . '" value="' . $field['value'] . '">';
                break;
                
            case 'checkbox':
                $checked = $field['checked'] ? 'checked' : '';
                $html .= '<div class="form-group">';
                $html .= '<div class="col-sm-offset-4 col-sm-12">';
                $html .= '<div class="checkbox">';
                $html .= '<label>';
                $html .= '<input type="checkbox" name="' . $field['name'] . '" id="' . $field['name'] . '" ' . $checked . '> ' . $field['label'];
                $html .= '</label>';
                $html .= '</div>';
                $html .= '</div>';
                $html .= '</div>';
                break;
        }
        
        return $html;
    }
    
    /**
     * Render the modal body with form fields
     * 
     * @return string HTML for the modal body
     */
    protected function renderBody() {
        $html = '<div class="modal-body">';
        
        foreach ($this->fields as $field) {
            $html .= $this->renderField($field);
        }
        
        $html .= '</div>';
        
        return $html;
    }
    
    /**
     * Render the complete modal
     * 
     * @param string $submitText Text for the submit button
     * @return string HTML for the complete modal
     */
    public function render($submitText = 'Save') {
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
}
?>