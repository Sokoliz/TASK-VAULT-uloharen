
<?php session_start();

if (isset($_SESSION['user'])) {
	include 'db/functions.php';
	$database = new Database();
	$connection = $database->connection();

} else {
	http_response_code(401);
	header('Location: login.php');
	die();
}
$del_project_confirm = $connection->prepare('DELETE FROM projects WHERE id_project=?');
$del_project_confirm->execute(array($id_project)); 

?>