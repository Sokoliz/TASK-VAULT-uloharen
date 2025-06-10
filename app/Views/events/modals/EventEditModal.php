<?php
namespace App\Views\Events\Modals;

require_once(__DIR__ . '/Modal.php');

/**
 * EventEditModal class for editing events
 */
class EventEditModal extends Modal {
	/**
	 * Event data for pre-filling fields
	 */
	private $eventData;
	
	/**
	 * Constructor
	 * 
	 * @param array $eventData Optional event data to pre-fill the form
	 */
	public function __construct($eventData = null) {
		// Update the form action to point to the calendar controller edit method
		parent::__construct('ModalEdit', 'Event', '/calendar/edit');
		
		$this->eventData = $eventData;
		
		// Initialize fields
		$this->initializeFields();
	}
	
	/**
	 * Initialize modal fields
	 */
	private function initializeFields() {
		// Get values from event data if available
		$title = isset($this->eventData['title']) ? $this->eventData['title'] : '';
		$description = isset($this->eventData['description']) ? $this->eventData['description'] : '';
		$colour = isset($this->eventData['colour']) ? $this->eventData['colour'] : '';
		$start_date = isset($this->eventData['start_date']) ? $this->eventData['start_date'] : '';
		$end_date = isset($this->eventData['end_date']) ? $this->eventData['end_date'] : '';
		$id_event = isset($this->eventData['id_event']) ? $this->eventData['id_event'] : '';
		
		// Add title field
		$this->addTextField('title', 'Title', $title, true, 'Title');
		
		// Add description field
		$this->addTextareaField('description', 'Description', $description, false, 'Description');
		
		// Add colour select field
		$colorOptions = [
			'#0275d8' => ['text' => '<span style="color:#0275d8; font-weight:bold;">&#9724;</span> Blue', 'style' => 'color:#0275d8; font-weight:bold;'],
			'#5bc0de' => ['text' => '<span style="color:#5bc0de; font-weight:bold;">&#9724;</span> Tile', 'style' => 'color:#5bc0de; font-weight:bold;'],
			'#5cb85c' => ['text' => '<span style="color:#5cb85c; font-weight:bold;">&#9724;</span> Green', 'style' => 'color:#5cb85c; font-weight:bold;'],
			'#f0ad4e' => ['text' => '<span style="color:#f0ad4e; font-weight:bold;">&#9724;</span> Orange', 'style' => 'color:#f0ad4e; font-weight:bold;'],
			'#d9534f' => ['text' => '<span style="color:#d9534f; font-weight:bold;">&#9724;</span> Red', 'style' => 'color:#d9534f; font-weight:bold;'],
			'#292b2c' => ['text' => '<span style="color:#292b2c; font-weight:bold;">&#9724;</span> Black', 'style' => 'color:#292b2c; font-weight:bold;']
		];
		
		$this->addSelectField('colour', 'Colour', $colorOptions, $colour, true);
		
		// Add date fields
		$this->addDateField('start_date', 'Start date', $start_date, true);
		$this->addDateField('end_date', 'End date', $end_date, true);
		
		// Add hidden field for event ID
		$this->addHiddenField('id_event', $id_event);
	}
	
	/**
	 * Override render method to add script reference
	 */
	public function render($submitText = 'Save') {
		// Use the parent render method
		$html = parent::render($submitText);
		
		// Add script reference to the external JavaScript file
		$html .= '<script src="/public/js/event-modal.js"></script>';
		
		return $html;
	}
	
	/**
	 * Custom renderField method to handle our custom field type
	 */
	protected function renderField($field) {
		if (isset($field['type']) && $field['type'] === 'custom') {
			return $field['html'];
		}
		
		return parent::renderField($field);
	}
	
	/**
	 * Override renderFooter to add a delete button
	 */
	protected function renderFooter($submitText = 'Save') {
		return '<div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
					<button type="submit" name="delete" value="1" class="btn btn-danger mr-2">Delete</button>
					<button type="submit" class="btn btn-primary">' . $submitText . '</button>
				</div>';
	}
} 