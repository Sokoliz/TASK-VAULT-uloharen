<?php
namespace App\Views\Events\Actions;

session_start();

// Kontrola ci je uzivatel prihlaseny - bezpecnostne opatrenie
// Ak nie je prihlásený, presmerujeme ho na login
if (isset($_SESSION['user'])) {
} else {
	http_response_code(401);
	header('Location: ../../login.php');
	die();
}

// Nacitanie Database triedy
// Pouzivam relativnu cestu od aktualneho suboru
require_once(__DIR__ . '/../../../Core/Database.php');
use App\Core\Database;

// Inicializacia spojenia s databazou
// Singleton pattern, aby sme mali len jedno spojenie
$db = Database::getInstance();

require_once('Event.php');

/**
 * Trieda na pridávanie nových udalostí
 */
class EventAdd extends Event {
	/**
	 * Add a new event to the database
	 * 
	 * @return void
	 */
	public function addEvent() {
		// Definovanie povinných polí
		// Všetky tieto polia musia byť vyplnené vo formulári
		$requiredFields = ['title', 'description', 'start_date', 'end_date', 'colour'];
		
		// Validácia povinných polí
		// Ak chýba niektoré pole, vrátime chybu
		if (!$this->validateFields($requiredFields, $_POST)) {
			http_response_code(400);
			echo json_encode(['error' => 'Missing required fields.']);
			exit();
		}
		
		// Získanie dát z POST
		// Uloženie údajov z formulára do premenných triedy
		$this->title = $_POST['title'];
		$this->description = $_POST['description'];
		$this->start_date = $this->formatDate($_POST['start_date']);
		$this->end_date = $this->formatDate($_POST['end_date']);
		$this->colour = $_POST['colour'];
		$this->id_user = $_SESSION['id_user'];
		
		// SQL príkaz na vloženie dát
		// Používam named parameters pre bezpečnosť
		$sql = "INSERT INTO calendar(id_user, title, description, start_date, end_date, colour) 
				VALUES (:id_user, :title, :description, :start_date, :end_date, :colour)";
		
		// Príprava a vykonanie dotazu
		// Používam prepared statements proti SQL injection
		$query = $this->db->prepare($sql);
		$query->bindParam(':id_user', $this->id_user);
		$query->bindParam(':title', $this->title);
		$query->bindParam(':description', $this->description);
		$query->bindParam(':start_date', $this->start_date);
		$query->bindParam(':end_date', $this->end_date);
		$query->bindParam(':colour', $this->colour);
		
		// Vykonanie dotazu pomocou metódy z rodičovskej triedy
		// Ak nastane chyba, metóda sa postará o výpis
		$this->executeQuery($query, 'There was a problem');
		
		// Presmerovanie späť na stránku, odkiaľ používateľ prišiel
		// HTTP_REFERER obsahuje URL stránky, z ktorej používateľ prišiel
		if(isset($_SERVER['HTTP_REFERER'])){
			header("Location:".$_SERVER['HTTP_REFERER']);
		}
	}
}

// Vytvorenie inštancie a spustenie
// Toto je entry point, ktorý vyvolá celú akciu
$eventAdd = new EventAdd();
$eventAdd->addEvent();
?>