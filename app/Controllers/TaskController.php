<?php
namespace App\Controllers;

use App\Core\Session;
use App\Models\Task;

// Kontrolér pre správu úloh v rámci projektov
class TaskController
{
    private $task;

    // Konštruktor inicializuje sedenie a overuje prihlásenie používateľa
    public function __construct()
    {
        Session::start();

        // Ak používateľ nie je prihlásený, presmeruje ho na prihlásenie
        if (!Session::isLoggedIn()) {
            header("Location: /login");
            exit;
        }

        // Vytvorenie inštancie modelu pre prácu s úlohami
        $this->task = new Task();
    }

    // Základná metóda pre zobrazenie úloh konkrétneho projektu
    public function index()
    {
        // Kontrola, či bol zadaný parameter projektu v URL
        if (!isset($_GET['project'])) {
            echo "Project ID missing.";
            exit;
        }

        // Získanie ID projektu z URL a načítanie jeho úloh
        $projectId = $_GET['project'];
        $tasks = $this->task->getByProject($projectId);

        // Načítanie pohľadu pre zobrazenie úloh
        require __DIR__ . '/../Views/task/index.php';
    }

    // Metóda pre vytvorenie novej úlohy v projekte
    public function create()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Príprava dát pre novú úlohu z formulára
            $data = [
                'id_user'         => Session::get('user_id'),
                'id_project'      => $_POST['id_project'],
                'task_status'     => $_POST['task_status'],
                'task_name'       => $_POST['task_name'],
                'task_description'=> $_POST['task_description'],
                'task_colour'     => $_POST['task_colour'],
                'deadline'        => $_POST['deadline']
            ];
            // Vytvorenie novej úlohy v databáze
            $this->task->create($data);
            // Presmerovanie späť na stránku projektu s jeho úlohami
            header("Location: /projects?idProject=" . $_POST['id_project']);
        }
    }

    // Metóda pre úpravu alebo vymazanie existujúcej úlohy
    public function edit()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Získanie ID úlohy z formulára
            $id = $_POST['id_task'];

            // Ak bola požadovaná akcia vymazanie
            if (isset($_POST['delete'])) {
                // Vymazanie úlohy z databázy
                $this->task->delete($id);
            } else {
                // Príprava dát pre aktualizáciu úlohy
                $data = [
                    'task_name'        => $_POST['task_name'],
                    'task_description' => $_POST['task_description'],
                    'task_colour'      => $_POST['task_colour'],
                    'deadline'         => $_POST['deadline'],
                ];
                
                // Aktualizácia úlohy v databáze
                $this->task->update($id, $data);
                
            }

            // Presmerovanie späť na stránku projektu s jeho úlohami
            header("Location: /projects?idProject=" . $_POST['id_project']);
        }
    }

    // Metóda pre zmenu stavu úlohy (posun v rámci workflow)
    public function move()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Získanie ID úlohy z formulára
            $id = $_POST['id_task'];

            // Posun stavu úlohy doprava (zvýšenie hodnoty stavu)
            if (isset($_POST['right'])) {
                $data = [
                    'task_status'        => $_POST['task_status'] +1,
                ];
            } else {
                // Posun stavu úlohy doľava (zníženie hodnoty stavu)
                $data = [
                    'task_status'        => $_POST['task_status'] -1 ,
                    
                ];
            }

            // Aktualizácia stavu úlohy v databáze
            $this->task->status_update($id, $data);

            // Presmerovanie späť na stránku projektu s jeho úlohami
            header("Location: /projects?idProject=" . $_POST['id_project']);
        }
    }
}