<?php
namespace App\Controllers;

use App\Core\Session;
use App\Models\Task;

class TaskController
{
    private $task;

    public function __construct()
    {
        // Kontrola prihlásenia - toto by malo byť v každom controlleri
        // Alebo by to mohlo byť vyriešené cez middleware
        if (!Session::isLoggedIn()) {
            header("Location: /login");
            exit;
        }

        // Vytvorenie inštancie modelu úlohy
        // Používam ho vo viacerých metódach, preto ho mám ako property
        $this->task = new Task();
    }

    public function index()
    {
        // Kontrola, či je zadané ID projektu
        // Bez projektu neviem, ktoré úlohy mám zobraziť
        if (!isset($_GET['project'])) {
            echo "Project ID missing.";
            exit;
        }

        // Získanie ID projektu a načítanie úloh pre tento projekt
        // Používam model na získanie dát z databázy
        $projectId = $_GET['project'];
        $tasks = $this->task->getByProject($projectId);

        // Načítanie view pre zobrazenie úloh
        // Toto by sa dalo riešiť aj cez View triedu, ale pre jednoduchosť to robím takto
        require __DIR__ . '/../Views/task/index.php';
    }

    public function create()
    {
        // Spracovanie len pre POST requesty
        // Toto je základná ochrana pred neoprávneným vytváraním
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Logovanie prijatých POST dát pre debugovanie
            // Pri vývoji je dobré vidieť, čo vlastne prichádza
            error_log("Task create POST data: " . print_r($_POST, true));
            
            // Necháme validáciu na strane klienta, toto je len záloha
            // V reálnom projekte by som robil dôkladnejšiu validáciu
            if (empty($_POST['task_name']) || empty($_POST['task_colour']) || empty($_POST['deadline'])) {
                error_log("Task validation failed: missing task_name, task_colour, or deadline");
                // Namiesto zobrazenia chybovej správy presmerujeme späť
                header("Location: /projects?idProject=" . $_POST['id_project']);
                exit;
            }
            
            // Príprava dát pre model
            // Toto je súbor dát, ktoré potrebujeme na vytvorenie úlohy
            $data = [
                'id_user'         => Session::get('user_id'),
                'id_project'      => $_POST['id_project'],
                'task_status'     => $_POST['task_status'],
                'task_name'       => $_POST['task_name'],
                'task_description'=> $_POST['task_description'] ?? '',
                'task_colour'     => $_POST['task_colour'],
                'deadline'        => $_POST['deadline']
            ];
            
            // Logovanie dát posielaných do modelu
            // Vždy je dobré vedieť, čo posielame ďalej
            error_log("Task data for model: " . print_r($data, true));
            
            // Vytvorenie úlohy pomocou modelu
            // Mal by som skontrolovať návratovú hodnotu a zobraziť chybu, ak zlyhá
            $result = $this->task->create($data);
            
            // Logovanie výsledku
            // Toto mi pomôže pri debugovaní, ak by niečo nefungovalo
            error_log("Task creation result: " . ($result ? "Success" : "Failed"));
            
            // Presmerovanie späť na stránku projektu
            // Jednoduchšie ako vytvárať celú ďalšiu stránku s potvrdením
            header("Location: /projects?idProject=" . $_POST['id_project']);
            exit;
        }
    }

    public function edit()
    {
        // Spracovanie len pre POST requesty
        // Štandardná ochrana ako pri create metóde
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id_task'];
            $projectId = $_POST['id_project'];

            // Ak je požiadavka na vymazanie
            // Je to trochu divné, že to robím v edit metóde, ale budiž
            if (isset($_POST['delete'])) {
                // Logovanie pokusu o vymazanie
                error_log("Attempting to delete task ID: " . $id . " for project: " . $projectId);
                $result = $this->task->delete($id);
                error_log("Delete result: " . ($result ? "Success" : "Failed"));
            } else {
                // Inak aktualizácia údajov
                // Dáta, ktoré sa dajú upraviť
                $data = [
                    'task_name'        => $_POST['task_name'],
                    'task_description' => $_POST['task_description'],
                    'task_colour'      => $_POST['task_colour'],
                    'deadline'         => $_POST['deadline'],
                ];
                
                $this->task->update($id, $data);
            }

            // Presmerovanie späť na stránku projektu
            // Po úprave alebo vymazaní sa vrátime na zoznam úloh
            header("Location: /projects?idProject=" . $projectId);
            exit; // Zabezpečí, že skript sa ukončí po presmerovaní
        }
    }

    public function move()
    {
        // Spracovanie len pre POST requesty
        // Tento controller sa stará o zmenu stavu úlohy
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id_task'];
            $projectId = $_POST['id_project'];
            
            // Posun doprava (zvýšenie stavu)
            // Napríklad z "To Do" na "In Progress"
            if (isset($_POST['right'])) {
                $data = [
                    'task_status' => $_POST['task_status'] + 1,
                ];
            } else if (isset($_POST['left'])) {
                // Posun doľava (zníženie stavu)
                // Napríklad z "In Progress" na "To Do"
                $data = [
                    'task_status' => $_POST['task_status'] - 1,
                ];
            }
            
            // Aktualizácia stavu pomocou modelu
            // To je vlastne to, kvôli čomu je celá táto metóda
            $this->task->status_update($id, $data);
            header("Location: /projects?idProject=" . $projectId);
        }
    }
}