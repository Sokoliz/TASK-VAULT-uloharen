<?php
namespace App\Controllers;

use App\Core\Session;
use App\Models\Project;
use App\Models\Task;

// Kontrolér pre správu projektov a ich úloh
class ProjectController
{
    private $project;
    public $task=null;

    // Konštruktor inicializuje sedenie, kontroluje prihlásenie a vytvára inštancie modelov
    public function __construct()
    {
        Session::start();

        // Presmerovanie na login, ak používateľ nie je prihlásený
        if (!Session::isLoggedIn()) {
            header("Location: /login");
            exit;
        }

        // Vytvorenie inštancií modelov pre prácu s projektami a úlohami
        $this->project = new Project();
        $this->task = new Task();
    }

    // Základná metóda pre zobrazenie zoznamu projektov a ich úloh
    public function index()
    {
        // Získanie všetkých projektov prihlaseného používateľa
        $projects = $this->project->getAllByUser(Session::get('user_id'));

        // Kontrola, či bol vybraný konkrétny projekt pre zobrazenie úloh
        if(isset($_GET['idProject'])) {	
		// Ochrana proti XSS útokom - vždy čistíme vstupy
		$id_project_for_task = filter_var(htmlspecialchars($_GET['idProject']), FILTER_SANITIZE_STRING);	
		$id_user = filter_var(htmlspecialchars($_SESSION['user_id']), FILTER_SANITIZE_STRING);	
		$id_user = (int)$id_user; 	
        
        
		// Vyberieme úlohy pre daný projekt zoradené podľa termínu
		$show_tasks = $this->task->getByProject($id_project_for_task);			
		
	}	

        // Načítanie pohľadu pre zobrazenie projektov
        require __DIR__ . '/../Views/projects.php';
    }

    // Metóda pre vytvorenie nového projektu
    public function create()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Príprava dát pre nový projekt z formulára
            $data = [
                'id_user'            => Session::get('user_id'),
                'project_name'       => $_POST['project_name'],
                'project_description'=> $_POST['project_description'],
                'project_colour'     => $_POST['project_colour'],
                'start_date'         => $_POST['start_date'],
                'end_date'           => $_POST['end_date'],
            ];
            // Vytvorenie nového projektu v databáze
            $this->project->create($data);
        }

        // Presmerovanie späť na stránku s projektami
        header("Location: /projects");
    }

    // Metóda pre úpravu alebo vymazanie projektu
    public function edit()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Získanie ID projektu z formulára
            $id = $_POST['id_project'];

            // Ak bola požadovaná akcia vymazanie
            if (isset($_POST['delete'])) {
                // Vymazanie projektu z databázy
                $this->project->delete($id);
            } else {
                // Príprava dát pre aktualizáciu projektu
                $data = [
                    'project_name'        => $_POST['project_name'],
                    'project_description' => $_POST['project_description'],
                    'project_colour'      => $_POST['project_colour'],
                    'start_date'          => $_POST['start_date'],
                    'end_date'            => $_POST['end_date'],
                ];
                // Aktualizácia projektu v databáze
                $this->project->update($id, $data);
            }

            // Presmerovanie späť na stránku s projektami
            header("Location: /projects");
        }
    }
}