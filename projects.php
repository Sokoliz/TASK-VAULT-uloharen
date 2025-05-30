<?php 
// Štartujeme session - bez toho by sme nemohli pracovať s prihláseným užívateľom
session_start();

// Kontrola prihlásenia - neprihlásených užívateľov nepustíme ďalej
if (isset($_SESSION['user'])) {
	include 'db/functions.php';
	$database = new Database();
	$connection = $database->connection();
} else {
	// Neprihlásených presmerujeme na hlavnú stránku
	header('Location: main.php');
	die();
}


// -------------------- PROJEKTY -------------------------
// Vyberieme všetky projekty pre aktuálneho užívateľa
$projects = $connection->prepare("SELECT SQL_CALC_FOUND_ROWS * FROM projects WHERE id_user = ? ORDER BY id_project DESC") ;			
$projects->execute(array($_SESSION['id_user']));
$projects = $projects->fetchAll();


// -------------------- ÚLOHY -------------------------
// Ak máme v URL parameter idProject, načítame úlohy pre daný projekt
if($_SERVER['REQUEST_METHOD'] == 'GET') {
	if(isset($_GET['idProject'])) {	
		// Ochrana proti XSS útokom - vždy čistíme vstupy
		$id_project_for_task = filter_var(htmlspecialchars($_GET['idProject']), FILTER_SANITIZE_STRING);	
		$id_user = filter_var(htmlspecialchars($_SESSION['id_user']), FILTER_SANITIZE_STRING);
		$id_user = (int)$id_user; 	

		// Vyberieme úlohy pre daný projekt zoradené podľa termínu
		$show_tasks = $connection->prepare("SELECT SQL_CALC_FOUND_ROWS * FROM tasks WHERE id_user = ? AND id_project = ? ORDER BY deadline DESC") ;			
		$show_tasks->execute(array($id_user, $id_project_for_task));
		$show_tasks = $show_tasks->fetchAll();
	}	
}
// Načítame šablónu pre zobrazenie projektov
require 'views/projects.view.php';


// Spracovanie formulárov - všetko čo príde cez POST
if($_SERVER['REQUEST_METHOD'] == 'POST') {
	
	// --------------------- NOVÝ PROJEKT -------------------------
	if ( isset($_POST['project_name']) AND isset($_POST['start_date']) AND isset($_POST['end_date']) ) {
		// Bezpečnostné čistenie vstupov
		$project_name = filter_var(htmlspecialchars($_POST['project_name']), FILTER_SANITIZE_STRING);
		$project_description = filter_var(htmlspecialchars($_POST['project_description']), FILTER_SANITIZE_STRING);
		$project_colour = filter_var(htmlspecialchars($_POST['project_colour']), FILTER_SANITIZE_STRING);
		$start_date= filter_var(htmlspecialchars($_POST['start_date']), FILTER_SANITIZE_STRING); 
		$start_date= date("Y-m-d", strtotime($start_date));
		$end_date= filter_var(htmlspecialchars($_POST['end_date']), FILTER_SANITIZE_STRING); 
		$end_date= date("Y-m-d", strtotime($end_date));
		$id_user = filter_var(htmlspecialchars($_POST['id_user']), FILTER_SANITIZE_STRING);
		$id_user = (int)$id_user; 

		// Uložíme nový projekt do databázy
		$statement = $connection->prepare('INSERT INTO projects (id_user, project_name, project_description, project_colour, start_date, end_date) VALUES
		(?, ?, ?, ?, ?, ?)');
		$statement->execute(array($id_user, $project_name, $project_description, $project_colour, $start_date, $end_date));	
		$add_project = $statement->fetch();
		
		// Ukážeme pekné hlásenie o úspechu
		if (isset($add_project)) {
			echo '<script language="javascript">';
			echo "Swal.fire({
				position: 'center',
				icon: 'success',
				title: 'Congrats!',
				text: 'Your project has been successfully created!',
				showConfirmButton: false,
				timer: 1200,
			}).then(function(){ 
				location.href = 'projects.php'				
				});";
			echo '</script>';
		}

	}

	// -------------------- ZMAZANIE PROJEKTU --------------------------
	if(isset($_POST['id_project']) && !isset($_POST['id_task'])) {	
		// Získame ID projektu na zmazanie
		$id_project = filter_var(htmlspecialchars($_POST['id_project']), FILTER_SANITIZE_STRING);
		
		// Najprv zmažeme všetky úlohy súvisiace s týmto projektom
		$del_tasks = $connection->prepare("DELETE FROM tasks WHERE id_project = ?");
		$del_tasks->execute(array($id_project));
		
		// Potom zmažeme samotný projekt z databázy
		$del_project = $connection->prepare("DELETE FROM projects WHERE id_project = ?");			
		$del_project->execute(array($id_project));
		
		// Potvrdíme užívateľovi zmazanie
		if ($del_project!==FALSE) {
			echo '<script language="javascript">';
			echo "Swal.fire({
				position: 'center',
				icon: 'success',
				title: 'Good bye!',
				text: 'Your project has been successfully deleted!',
				showConfirmButton: false,
				timer: 1200,
			}).then(function(){ 
				location.href = 'projects.php'
				});";
			echo '</script>';			
		} 

	}

	// --------------------------- ÚPRAVA PROJEKTU -------------------------------
	if(isset($_POST['edit_id_project'])) {	
		// Získame údaje z formulára
		$edit_id_project = filter_var(htmlspecialchars($_POST['edit_id_project']), FILTER_SANITIZE_STRING);
		$edit_project_name = filter_var(htmlspecialchars($_POST['edit_project_name']), FILTER_SANITIZE_STRING);
		$edit_project_description = filter_var(htmlspecialchars($_POST['edit_project_description']), FILTER_SANITIZE_STRING);
		$edit_project_colour = filter_var(htmlspecialchars($_POST['edit_project_colour']), FILTER_SANITIZE_STRING);
		$edit_start_date= filter_var(htmlspecialchars($_POST['edit_start_date']), FILTER_SANITIZE_STRING); 
		$edit_start_date= date("Y-m-d", strtotime($edit_start_date));
		$edit_end_date= filter_var(htmlspecialchars($_POST['edit_end_date']), FILTER_SANITIZE_STRING); 
		$edit_end_date= date("Y-m-d", strtotime($edit_end_date));

		// Aktualizujeme údaje v databáze
		$statement = $connection->prepare('UPDATE projects SET project_name=?, project_description=?, project_colour=?, start_date=?, end_date=? WHERE id_project=?');
		$statement->execute(array($edit_project_name, $edit_project_description, $edit_project_colour, $edit_start_date, $edit_end_date, $edit_id_project));	
		$edit_project = $statement->fetch();
		
		// Potvrdíme úspešnú aktualizáciu
		if (isset($edit_project)) {
			echo '<script language="javascript">';
			echo "Swal.fire({
				position: 'center',
				icon: 'success',
				title: 'Well Done!',
				text: 'Your project has been updated!',
				showConfirmButton: false,
				timer: 1200,
			}).then(function(){ 
				location.href = 'projects.php'
				});";
			echo '</script>';
		} 

	}
	

	// --------------------------- NOVÁ ÚLOHA -------------------------------
	if ( isset($_POST['task_name']) AND isset($_POST['id_task_project']) AND isset($_POST['id_user']) ) {
		// Získame údaje z formulára
		$id_project = filter_var(htmlspecialchars($_POST['id_task_project']), FILTER_SANITIZE_STRING);
		$id_project = (int)$id_project; 
		$id_user = filter_var(htmlspecialchars($_POST['id_user']), FILTER_SANITIZE_STRING);
		$id_user = (int)$id_user; 
		$task_status = filter_var(htmlspecialchars($_POST['task_status']), FILTER_SANITIZE_STRING);
		$task_status = (int)$task_status; 
		$task_name = filter_var(htmlspecialchars($_POST['task_name']), FILTER_SANITIZE_STRING);
		$task_description = filter_var(htmlspecialchars($_POST['task_description']), FILTER_SANITIZE_STRING);
		$task_colour = filter_var(htmlspecialchars($_POST['task_colour']), FILTER_SANITIZE_STRING);
		$deadline= filter_var(htmlspecialchars($_POST['deadline']), FILTER_SANITIZE_STRING); 
		$deadline= date("Y-m-d", strtotime($deadline));

		// Uložíme novú úlohu do databázy
		$statement = $connection->prepare('INSERT INTO tasks (id_user, id_project, task_status, task_name, task_description, task_colour, deadline) VALUES
		(?, ?, ?, ?, ?, ?, ?)');
		$statement->execute(array($id_user, $id_project, $task_status, $task_name, $task_description, $task_colour, $deadline));	
		$add_task = $statement->fetch();
		
		// Potvrdíme vytvorenie úlohy
		if (isset($add_task)) {
			echo '<script language="javascript">';
			echo "Swal.fire({
				position: 'center',
				icon: 'success',
				title: 'Congrats!',
				text: 'Your task has been successfully created!',
				showConfirmButton: false,
				timer: 1200,
			}).then(function(){ 
				location.href = 'projects.php?idProject=";				
			echo "$id_project'";			
			echo "});";
			echo '</script>';			
		}
		
	}

	// -------------------- ZMAZANIE ÚLOHY --------------------------
	if(isset($_POST['id_task']) && !isset($_POST['id_project_confirm'])) {	
		// Získame ID úlohy a projektu
		$id_task = filter_var(htmlspecialchars($_POST['id_task']), FILTER_SANITIZE_STRING);	
		$id_project = filter_var(htmlspecialchars($_POST['id_project']), FILTER_SANITIZE_STRING);	
		
		// Zmažeme úlohu z databázy
		$del_task = $connection->prepare("DELETE FROM tasks WHERE id_task = ?") ;			
		$del_task->execute(array($id_task));
		
		// Potvrdíme zmazanie úlohy
		if ($del_task!==FALSE) {
			echo '<script language="javascript">';
			echo "Swal.fire({
				position: 'center',
				icon: 'success',
				title: 'Good bye!',
				text: 'Your task has been deleted!',
				showConfirmButton: false,
				timer: 1200,
			}).then(function(){ 
				location.href = 'projects.php?idProject=";				
			echo "$id_project'";			
			echo "});";
			echo '</script>';			
		} 

	}

	// --------------------------- ÚPRAVA ÚLOHY -------------------------------
	if(isset($_POST['edit_id_task'])) {	
		// Získame údaje z formulára
		$id_project = filter_var(htmlspecialchars($_POST['id_task_project']), FILTER_SANITIZE_STRING);
		$edit_id_task = filter_var(htmlspecialchars($_POST['edit_id_task']), FILTER_SANITIZE_STRING);
		$edit_task_name = filter_var(htmlspecialchars($_POST['edit_task_name']), FILTER_SANITIZE_STRING);
		$edit_task_description = filter_var(htmlspecialchars($_POST['edit_task_description']), FILTER_SANITIZE_STRING);
		$edit_task_colour = filter_var(htmlspecialchars($_POST['edit_task_colour']), FILTER_SANITIZE_STRING);
		$deadline= filter_var(htmlspecialchars($_POST['deadline']), FILTER_SANITIZE_STRING); 
		$deadline= date("Y-m-d", strtotime($deadline));

		// Aktualizujeme údaje v databáze
		$statement = $connection->prepare('UPDATE tasks SET task_name=?, task_description=?, task_colour=?, deadline=? WHERE id_task=?');
		$statement->execute(array($edit_task_name, $edit_task_description, $edit_task_colour, $deadline, $edit_id_task));	
		$edit_task = $statement->fetch();
		if (isset($edit_task)) {
			echo '<script language="javascript">';
			echo "Swal.fire({
				position: 'center',
				icon: 'success',
				title: 'Well Done!',
				text: 'Your task has been updated!',
				showConfirmButton: false,
				timer: 1200,
			}).then(function(){ 
				location.href = 'projects.php?idProject=";				
			echo "$id_project'";			
			echo "});";
			echo '</script>';
		} 

	}

	// --------------------------- MOVING TASK TO THE RIGHT -------------------------------
	if(isset($_POST['id_task_right'])) {	
		$id_project_right = filter_var(htmlspecialchars($_POST['id_project_right']), FILTER_SANITIZE_STRING);
		$id_task_right = filter_var(htmlspecialchars($_POST['id_task_right']), FILTER_SANITIZE_STRING);
		$task_status = filter_var(htmlspecialchars($_POST['task_status']), FILTER_SANITIZE_STRING);
		$new_status = ((int)$task_status + 1);
				
		$statement = $connection->prepare('UPDATE tasks SET task_status=? WHERE id_task=?');
		$statement->execute(array($new_status, $id_task_right));	
		$move_task_right = $statement->fetch();
		if (isset($move_task_right)) {
			echo '<script language="javascript">';
			echo "Swal.fire({
				position: 'center',
				icon: 'success',				
				showConfirmButton: false,
				title: 'Well Done!',
				timer: 500,
			}).then(function(){ 
				location.href = 'projects.php?idProject=";				
			echo "$id_project_right'";			
			echo "});";
			echo '</script>';
		} 

	}

	// --------------------------- MOVING TASK TO THE LEFT -------------------------------
	if(isset($_POST['id_task_left'])) {	
		$id_project_left = filter_var(htmlspecialchars($_POST['id_project_left']), FILTER_SANITIZE_STRING);
		$id_task_left = filter_var(htmlspecialchars($_POST['id_task_left']), FILTER_SANITIZE_STRING);
		$task_status = filter_var(htmlspecialchars($_POST['task_status']), FILTER_SANITIZE_STRING);
		$new_status = ((int)$task_status - 1);
				
		$statement = $connection->prepare('UPDATE tasks SET task_status=? WHERE id_task=?');
		$statement->execute(array($new_status, $id_task_left));	
		$move_task_left = $statement->fetch();
		if (isset($move_task_left)) {
			echo '<script language="javascript">';
			echo "Swal.fire({
				position: 'center',
				icon: 'success',				
				showConfirmButton: false,
				title: 'Well Done!',
				timer: 500,
			}).then(function(){ 
				location.href = 'projects.php?idProject=";				
			echo "$id_project_left'";			
			echo "});";
			echo '</script>';
		} 

	}

}


?>