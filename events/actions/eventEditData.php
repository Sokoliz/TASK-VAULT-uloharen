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

	// Kontrola ci su vsetky potrebne udaje - toto je pre drag and drop v kalendari
	if (!isset($_POST['title']) || !isset($_POST['description']) || !isset($_POST['start_date']) || !isset($_POST['end_date']) || !isset($_POST['colour'])) {
    http_response_code(400);
    echo json_encode(['error' => 'Missing required fields.']);
    exit();
} else if (isset($_POST['Event'][0]) && isset($_POST['Event'][1]) && isset($_POST['Event'][2])){
		
		// Nacitanie udajov z AJAX requestu - toto bolo trochu tazke
		$id_event = $_POST['Event'][0];
		$start_date = $_POST['Event'][1];
		$end_date = $_POST['Event'][2];

		// Formatovanie datumu - aby bol spravny format pre databazu
		$start_date= date('Y/m/d H:i:s', strtotime($start_date));
		$end_date= date('Y/m/d H:i:s', strtotime($end_date));

		// SQL prikaz na upravu eventu - iba datumy lebo to je drag and drop
		$sql = "UPDATE calendar SET  start_date = '$start_date', end_date = '$end_date' WHERE id_event = '$id_event' ";
		
		// Priprava SQL prikazu
		$query = $db->prepare( $sql );
		if ($query == false) {
			print_r($db->errorInfo());
			die ('There was a problem while loading');
		}

		// Vykonanie SQL prikazu
		$sth = $query->execute();
		if ($sth == false) {
			print_r($query->errorInfo());
			die ('There was a problem while running the query');
		}else{
			die ('OK');
		}

	}
	//header('Location: '.$_SERVER['HTTP_REFERER']);
?>
