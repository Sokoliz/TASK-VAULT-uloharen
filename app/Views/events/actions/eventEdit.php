<?php
namespace App\Views\Events\Actions;

use App\Core\Database;
use Exception;

session_start();

// Kontrola ci je uzivatel prihlaseny - bezpecnostne opatrenie
if (isset($_SESSION['user'])) {
} else {
	http_response_code(401);
	header('Location: ../../login.php');
	die();
}
	
// Nacitanie Database triedy
require_once(__DIR__ . '/../../../Core/Database.php');

// Inicializacia spojenia s databazou
$db = Database::getInstance();

require_once('Event.php');

// Debug logging
error_log('EventEdit.php called with POST data: ' . print_r($_POST, true));

/**
 * Class to handle editing events
 */
class EventEdit extends Event {
	/**
	 * Update an existing event in the database
	 * 
	 * @return void
	 */
	public function updateEvent() {
		// Check if the delete flag is set
		if (isset($_POST['delete']) && !empty($_POST['id_event'])) {
			error_log('Delete flag is set, proceeding with event deletion');
			// Delete the event regardless of the value of the delete parameter
			$this->deleteEvent();
			return;
		}
		
		// Spracovanie dát z AJAX volania z calendar.js
		if (isset($_POST['Event']) && is_array($_POST['Event']) && count($_POST['Event']) >= 3) {
			error_log('Handling drag & drop update');
			$this->handleDragDropUpdate();
			return;
		}
		
		// Define required fields
		$requiredFields = ['id_event', 'title', 'start_date', 'end_date'];
		
		// Validate required fields
		if (!$this->validateFields($requiredFields, $_POST)) {
			error_log('Missing required fields for event update');
			http_response_code(400);
			echo json_encode(['error' => 'Missing required fields.']);
			exit();
		}
		
		error_log('Updating event with ID: ' . $_POST['id_event']);
		
		// Get data from POST
		$this->id_event = $_POST['id_event'];
		$this->title = $_POST['title'];
		$this->description = isset($_POST['description']) ? $_POST['description'] : '';
		$this->start_date = $this->formatDate($_POST['start_date']);
		$this->end_date = $this->formatDate($_POST['end_date']);
		$this->colour = isset($_POST['colour']) ? $_POST['colour'] : '';
		
		// SQL update query
		$sql = "UPDATE calendar 
				SET title = :title, 
					description = :description, 
					start_date = :start_date, 
					end_date = :end_date, 
					colour = :colour 
				WHERE id_event = :id_event";
		
		// Prepare and execute query
		$query = $this->db->prepare($sql);
		$query->bindParam(':id_event', $this->id_event);
		$query->bindParam(':title', $this->title);
		$query->bindParam(':description', $this->description);
		$query->bindParam(':start_date', $this->start_date);
		$query->bindParam(':end_date', $this->end_date);
		$query->bindParam(':colour', $this->colour);
		
		$result = $query->execute();
		error_log('Update query result: ' . ($result ? 'success' : 'failure'));
		
		// Redirect to calendar page
		header('Location: /calendar');
		exit();
	}
	
	/**
	 * Handle update from drag & drop in calendar
	 * 
	 * @return void
	 */
	protected function handleDragDropUpdate() {
		$id_event = $_POST['Event'][0];
		$start_date = $this->formatDate($_POST['Event'][1]);
		$end_date = $this->formatDate($_POST['Event'][2]);
		
		error_log("Drag & drop update for event ID: $id_event");
		error_log("New start date: $start_date");
		error_log("New end date: $end_date");
		
		// SQL update query pre aktualizáciu dátumov
		$sql = "UPDATE calendar 
				SET start_date = :start_date, 
					end_date = :end_date
				WHERE id_event = :id_event";
		
		// Prepare and execute query
		$query = $this->db->prepare($sql);
		$query->bindParam(':id_event', $id_event);
		$query->bindParam(':start_date', $start_date);
		$query->bindParam(':end_date', $end_date);
		
		$result = $query->execute();
		error_log('Drag & drop update result: ' . ($result ? 'success' : 'failure'));
		
		if ($result) {
			echo "OK";
		} else {
			echo "Error";
		}
		
		exit;
	}
	
	/**
	 * Delete an event from the database
	 * 
	 * @return void
	 */
	public function deleteEvent() {
		if (!isset($_POST['id_event']) || empty($_POST['id_event'])) {
			header('Location: /calendar');
			exit();
		}
		
		$this->id_event = $_POST['id_event'];
		
		// SQL delete query
		$sql = "DELETE FROM calendar WHERE id_event = :id_event";
		
		try {
			// Prepare and execute query
			$query = $this->db->prepare($sql);
			$query->bindParam(':id_event', $this->id_event);
			
			$query->execute();
			
		} catch (Exception $e) {
			// Silence exceptions
		}
		
		// Redirect to calendar page
		header('Location: /calendar');
		exit();
	}
}

// Instantiate and run
$eventEdit = new EventEdit();
$eventEdit->updateEvent();
?>