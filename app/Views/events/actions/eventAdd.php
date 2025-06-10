<?php
namespace App\Views\Events\Actions;

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
use App\Core\Database;

// Inicializacia spojenia s databazou
$db = Database::getInstance();

require_once('Event.php');

/**
 * Class to handle adding new events
 */
class EventAdd extends Event {
	/**
	 * Add a new event to the database
	 * 
	 * @return void
	 */
	public function addEvent() {
		// Define required fields
		$requiredFields = ['title', 'description', 'start_date', 'end_date', 'colour'];
		
		// Validate required fields
		if (!$this->validateFields($requiredFields, $_POST)) {
			http_response_code(400);
			echo json_encode(['error' => 'Missing required fields.']);
			exit();
		}
		
		// Get data from POST
		$this->title = $_POST['title'];
		$this->description = $_POST['description'];
		$this->start_date = $this->formatDate($_POST['start_date']);
		$this->end_date = $this->formatDate($_POST['end_date']);
		$this->colour = $_POST['colour'];
		$this->id_user = $_SESSION['id_user'];
		
		// SQL insert query
		$sql = "INSERT INTO calendar(id_user, title, description, start_date, end_date, colour) 
				VALUES (:id_user, :title, :description, :start_date, :end_date, :colour)";
		
		// Prepare and execute query
		$query = $this->db->prepare($sql);
		$query->bindParam(':id_user', $this->id_user);
		$query->bindParam(':title', $this->title);
		$query->bindParam(':description', $this->description);
		$query->bindParam(':start_date', $this->start_date);
		$query->bindParam(':end_date', $this->end_date);
		$query->bindParam(':colour', $this->colour);
		
		$this->executeQuery($query, 'There was a problem');
		
		// Redirect back to referrer
		if(isset($_SERVER['HTTP_REFERER'])){
			header("Location:".$_SERVER['HTTP_REFERER']);
		}
	}
}

// Instantiate and run
$eventAdd = new EventAdd();
$eventAdd->addEvent();
?>