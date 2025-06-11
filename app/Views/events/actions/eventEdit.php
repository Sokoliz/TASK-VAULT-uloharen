<?php
namespace App\Views\Events\Actions;

use App\Core\Database;
use Exception;

session_start();

// Kontrola ci je uzivatel prihlaseny - bezpecnostne opatrenie
// Ak nie je, nepustíme ho ďalej a presmerujeme na login
if (isset($_SESSION['user'])) {
} else {
	http_response_code(401);
	header('Location: ../../login.php');
	die();
}
	
// Nacitanie Database triedy
// Používam absolútnu cestu od app adresára
require_once(__DIR__ . '/../../../Core/Database.php');

// Inicializacia spojenia s databazou
// Používame singleton vzor pre databázové spojenie
$db = Database::getInstance();

require_once('Event.php');

// Debug logovanie
// Toto je super na odhaľovanie chýb, vypíše všetky POST dáta
error_log('EventEdit.php called with POST data: ' . print_r($_POST, true));

/**
 * Trieda na úpravu existujúcich udalostí
 */
class EventEdit extends Event {
	public function updateEvent() {
		// Kontrola, či je nastavený príznak na vymazanie
		// Ak áno, vymazáme udalosť a skončíme
		if (isset($_POST['delete']) && !empty($_POST['id_event'])) {
			error_log('Delete flag is set, proceeding with event deletion');
			// Vymazanie udalosti bez ohľadu na hodnotu parametra delete
			$this->deleteEvent();
			return;
		}
		
		// Spracovanie dát z AJAX volania z calendar.js
		// Toto sa používa pri drag & drop aktualizácii v kalendári
		if (isset($_POST['Event']) && is_array($_POST['Event']) && count($_POST['Event']) >= 3) {
			error_log('Handling drag & drop update');
			$this->handleDragDropUpdate();
			return;
		}
		
		// Definovanie povinných polí
		// Všetky tieto údaje potrebujeme na úpravu udalosti
		$requiredFields = ['id_event', 'title', 'start_date', 'end_date'];
		
		// Validácia povinných polí
		// Ak niečo chýba, vrátime chybu
		if (!$this->validateFields($requiredFields, $_POST)) {
			error_log('Missing required fields for event update');
			http_response_code(400);
			echo json_encode(['error' => 'Missing required fields.']);
			exit();
		}
		
		error_log('Updating event with ID: ' . $_POST['id_event']);
		
		// Získanie dát z POST
		// Nastavenie hodnôt pre update
		$this->id_event = $_POST['id_event'];
		$this->title = $_POST['title'];
		$this->description = isset($_POST['description']) ? $_POST['description'] : '';
		$this->start_date = $this->formatDate($_POST['start_date']);
		$this->end_date = $this->formatDate($_POST['end_date']);
		$this->colour = isset($_POST['colour']) ? $_POST['colour'] : '';
		
		// SQL príkaz na aktualizáciu
		// Používam named parameters pre bezpečnosť
		$sql = "UPDATE calendar 
				SET title = :title, 
					description = :description, 
					start_date = :start_date, 
					end_date = :end_date, 
					colour = :colour 
				WHERE id_event = :id_event";
		
		// Príprava a vykonanie dotazu
		// Binding parametrov pre prepared statement
		$query = $this->db->prepare($sql);
		$query->bindParam(':id_event', $this->id_event);
		$query->bindParam(':title', $this->title);
		$query->bindParam(':description', $this->description);
		$query->bindParam(':start_date', $this->start_date);
		$query->bindParam(':end_date', $this->end_date);
		$query->bindParam(':colour', $this->colour);
		
		// Vykonanie dotazu a logovanie výsledku
		$result = $query->execute();
		error_log('Update query result: ' . ($result ? 'success' : 'failure'));
		
		// Presmerovanie na stránku kalendára
		// Po úprave sa vrátime na kalendár, aby sme videli zmeny
		header('Location: /calendar');
		exit();
	}
	
	protected function handleDragDropUpdate() {
		// Získanie údajov z POST pre drag & drop aktualizáciu
		// Event[0] = id, Event[1] = začiatok, Event[2] = koniec
		$id_event = $_POST['Event'][0];
		$start_date = $this->formatDate($_POST['Event'][1]);
		$end_date = $this->formatDate($_POST['Event'][2]);
		
		// Logovanie informácií o aktualizácii
		// Toto je užitočné pre debugging
		error_log("Drag & drop update for event ID: $id_event");
		error_log("New start date: $start_date");
		error_log("New end date: $end_date");
		
		// SQL príkaz na aktualizáciu dátumov
		// Tu aktualizujeme len začiatok a koniec udalosti
		$sql = "UPDATE calendar 
				SET start_date = :start_date, 
					end_date = :end_date
				WHERE id_event = :id_event";
		
		// Príprava a vykonanie dotazu
		// Opäť používam prepared statements pre bezpečnosť
		$query = $this->db->prepare($sql);
		$query->bindParam(':id_event', $id_event);
		$query->bindParam(':start_date', $start_date);
		$query->bindParam(':end_date', $end_date);
		
		// Vykonanie dotazu a logovanie výsledku
		$result = $query->execute();
		error_log('Drag & drop update result: ' . ($result ? 'success' : 'failure'));
		
		// Vrátenie odpovede pre AJAX
		// Toto používa JavaScript na zobrazenie notifikácie
		if ($result) {
			echo "OK";
		} else {
			echo "Error";
		}
		
		exit;
	}
	
	public function deleteEvent() {
		// Kontrola, či máme ID udalosti na vymazanie
		// Ak nie, vrátime sa na kalendár
		if (!isset($_POST['id_event']) || empty($_POST['id_event'])) {
			header('Location: /calendar');
			exit();
		}
		
		$this->id_event = $_POST['id_event'];
		
		// SQL príkaz na vymazanie
		// Jednoduché vymazanie podľa ID
		$sql = "DELETE FROM calendar WHERE id_event = :id_event";
		
		try {
			// Príprava a vykonanie dotazu
			// Používam try-catch, aby som zachytil prípadné chyby
			$query = $this->db->prepare($sql);
			$query->bindParam(':id_event', $this->id_event);
			
			$query->execute();
			
		} catch (Exception $e) {
			// Ignorujeme chyby - mohli by sme ich logovať
			// Ale nechceme ich zobrazovať používateľovi
		}
		
		// Presmerovanie na stránku kalendára
		// Po vymazaní sa vrátime na kalendár
		header('Location: /calendar');
		exit();
	}
}

// Vytvorenie inštancie a spustenie
// Toto je entry point pre úpravu udalosti
$eventEdit = new EventEdit();
$eventEdit->updateEvent();
?>