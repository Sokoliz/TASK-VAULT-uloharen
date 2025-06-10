<?php
namespace App\Views\Events\Modals;

require_once(__DIR__ . '/Modal.php');

/**
 * EventAddModal class for adding new events
 */
class EventAddModal extends Modal {
    /**
     * Constructor
     */
    public function __construct() {
        parent::__construct('ModalAdd', 'New event', '/calendar/create');
        
        // Initialize fields
        $this->initializeFields();
    }
    
    /**
     * Initialize modal fields
     */
    private function initializeFields() {
        // Add title field
        $this->addTextField('title', 'Title', '', true, 'title');
        
        // Add description field
        $this->addTextareaField('description', 'Description', '', false, 'Description');
        
        // Add colour select field
        $colorOptions = [
            '#0275d8' => ['text' => '&#9724; Blue', 'style' => 'color:#0275d8'],
            '#5bc0de' => ['text' => '&#9724; Tile', 'style' => 'color:#5bc0de'],
            '#5cb85c' => ['text' => '&#9724; Green', 'style' => 'color:#5cb85c'],
            '#f0ad4e' => ['text' => '&#9724; Orange', 'style' => 'color:#f0ad4e'],
            '#d9534f' => ['text' => '&#9724; Red', 'style' => 'color:#d9534f'],
            '#292b2c' => ['text' => '&#9724; Black', 'style' => 'color:#292b2c']
        ];
        
        $this->addSelectField('colour', 'Colour', $colorOptions, '', true);
        
        // Add date fields
        $this->addDateField('start_date', 'Start date', '', true);
        $this->addDateField('end_date', 'End date', '', true);
    }
    
    /**
     * Override render method to ensure form wraps both body and footer
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