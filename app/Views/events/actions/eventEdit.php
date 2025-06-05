<?php session_start();

// Kontrola ci je uzivatel prihlaseny - bezpecnostne opatrenie
if (isset($_SESSION['user'])) {
} else {
	http_response_code(401);
	header('Location: ../../login.php');
	die();
}
	
// Nacitanie funkcii z databazy
require_once('../../db/functions.php');
$database = new Database();
$db = $database->connection();

// Ak uzivatel chce vymazat event - kontrola ci existuje id_event a checkbox delete
if (isset($_POST['delete']) && isset($_POST['id_event'])){
		
		$id_event = $_POST['id_event'];

		// SQL prikaz na vymazanie eventu
		$sql = "DELETE FROM calendar WHERE id_event = '$id_event'";

		// Priprava SQL prikazu
		$query = $db->prepare( $sql );
		if ($query == false) {
			print_r($db->errorInfo());
			http_response_code(500);
			die ('There was a problem while loading');
		}

		// Vykonanie SQL prikazu
		$res = $query->execute();
		if ($res == false) {
			print_r($query->errorInfo());
			http_response_code(500);
			die ('There was a problem while running the query');
		}
		
// Ak uzivatel chce upravit event - kontrola ci su vsetky potrebne udaje
}else if (isset($_POST['title']) && isset($_POST['description']) && isset($_POST['start_date']) && isset($_POST['end_date']) && isset($_POST['colour']) && isset($_POST['id_event'])){
		
		// Nacitanie udajov z formulara
		$id_event = $_POST['id_event'];
		$title = $_POST['title'];
		$description = $_POST['description'];
		$start_date = $_POST['start_date'];
		$end_date = $_POST['end_date'];
		$colour = $_POST['colour'];

		// Formatovanie datumu - aby bol spravny format pre databazu
		$start_date= date('Y/m/d H:i:s', strtotime($start_date));
		$end_date= date('Y/m/d H:i:s', strtotime($end_date));
		
		// SQL prikaz na upravu eventu
		$sql = "UPDATE calendar SET  title = '$title', description = '$description', start_date = '$start_date', end_date = '$end_date', colour = '$colour' 
		WHERE id_event = '$id_event'";
		
		// Priprava SQL prikazu
		$query = $db->prepare( $sql );
		if ($query == false) {
			print_r($db->errorInfo());
			http_response_code(500);
			die ('There was a problem while loading');
		}

		// Vykonanie SQL prikazu
		$sth = $query->execute();
		if ($sth == false) {
			print_r($query->errorInfo());
			http_response_code(500);
			die ('There was a problem while running the query');
		}

}
	// Presmerovanie na stranku s kalendarom
	header('Location: ../../calendar.php');
?>