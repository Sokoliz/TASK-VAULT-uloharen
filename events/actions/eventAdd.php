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

	// Kontrola ci su vsetky potrebne udaje - aby sa nevytvoril prazdny event
	if (!isset($_POST['title']) || !isset($_POST['description']) || !isset($_POST['start_date']) || !isset($_POST['end_date']) || !isset($_POST['colour'])) {
    http_response_code(400);
    echo json_encode(['error' => 'Missing required fields.']);
    exit();
} else if (isset($_POST['title']) && isset($_POST['description']) && isset($_POST['start_date']) && isset($_POST['end_date']) && isset($_POST['colour'])){
		
		// Nacitanie udajov z formulara
		$title = $_POST['title'];
		$description = $_POST['description'];
		$start_date = $_POST['start_date'];
		$end_date = $_POST['end_date'];
		$colour = $_POST['colour'];
		$id_user = $_SESSION['id_user'];

		// Formatovanie datumu - aby bol spravny format pre databazu
		$start_date= date('Y/m/d H:i:s', strtotime($start_date));
		$end_date= date('Y/m/d H:i:s', strtotime($end_date));

		// SQL prikaz na vlozenie eventu do databazy
		$sql = "INSERT INTO calendar(id_user, title, description, start_date, end_date, colour) 
		values ('$id_user', '$title', '$description', '$start_date', '$end_date', '$colour')";
		
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
		}

	}
	// Presmerovanie spat na stranku odkial prisla poziadavka
	if(isset($_SERVER['HTTP_REFERER'])){
		header("Location:".$_SERVER['HTTP_REFERER']."");
	}
?>