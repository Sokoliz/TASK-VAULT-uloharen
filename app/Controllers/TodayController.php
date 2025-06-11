<?php
namespace App\Controllers;

use App\Core\Session;
use App\Models\Project;
use App\Models\Calendar;
use App\Models\Task;
use App\Views\Today\TodayPageRenderer;

class TodayController
{
    private $project;
    private $calendar;
    private $task;

    public function __construct()
    {
        // Kontrola prihlásenia používateľa, ak nie je prihlásený, presmerujeme ho
        // Toto by som mohol riešiť cez middleware, ale zatiaľ to robím takto
        if (!Session::isLoggedIn()) {
            header("Location: /login");
            exit;
        }

        // Inicializácia modelov, ktoré budem potrebovať
        // Potrebujem prístup k projektom, kalendáru a úlohám
        $this->project = new Project();
        $this->calendar = new Calendar();
        $this->task = new Task();
    }

    public function index()
    {
        // Získanie ID prihláseného používateľa zo session
        // Používam to pre filtrovanie dát v dopytoch
        $userId = Session::get('user_id');

        // Nastavenie dátumov pre dnešný deň
        // Potrebujem začiatok a koniec dňa pre filtrovanie
        $today = date("Y-m-d");
        $today_start = $today . " 00:00:00";
        $today_end = $today . " 23:59:59";

        // Získanie projektov, ktoré začínajú alebo končia dnes
        // Toto sú dôležité míľniky pre používateľa
        $projects_start = $this->project->getByStartDate($userId, $today);
        $projects_end = $this->project->getByEndDate($userId, $today);

        // Získanie udalostí, ktoré začínajú alebo končia dnes
        // Filtrujem podľa presného času začiatku a konca dňa
        $events_start = $this->calendar->getEventsByStartDate($userId, $today_start, $today_end);
        $events_end = $this->calendar->getEventsByEndDate($userId, $today_start, $today_end);

        // Získanie úloh s termínom dokončenia dnes
        // Toto je asi najdôležitejšie pre používateľa
        $tasks_deadline = $this->task->getByDeadline($userId, $today);
        
        // Formátovanie úloh s dodatočnými informáciami
        // Potrebujem upraviť dáta pre zobrazenie vo view
        $formatted_tasks = $this->formatTasks($tasks_deadline);

        // Príprava dát pre view
        // Všetky dáta posielam v jednom poli
        $viewData = [
            'projects_start' => $projects_start,
            'projects_end' => $projects_end,
            'events_start' => $events_start,
            'events_end' => $events_end,
            'formatted_tasks' => $formatted_tasks,
            'today' => $today
        ];

        // Načítanie view triedy a vytvorenie jej inštancie
        // Podľa MVC vzoru oddeľujem logiku od zobrazovania
        require_once __DIR__ . '/../Views/page/today.view.php';
        $todayView = new \TodayView($viewData);
        echo $todayView->render();
    }
    
    /**
     * Formátovanie dát úloh pre zobrazenie
     * 
     * @param array $tasks Úlohy na formátovanie
     * @return array Formátované úlohy
     */
    private function formatTasks($tasks)
    {
        $formatted = [];
        
        foreach ($tasks as $task) {
            // Určenie textového stavu úlohy
            // Prevod číselného stavu na text pre ľahšie pochopenie
            $status_text = 'To Do';
            if ($task['task_status'] == 2) {
                $status_text = 'In Progress';
            } elseif ($task['task_status'] == 3) {
                $status_text = 'Complete';
            }
            
            // Získanie informácií o projekte
            // Potrebujem vedieť, ku ktorému projektu úloha patrí
            $project = $this->project->getById($task['id_project']);
            $project_name = $project ? $project['project_name'] : 'Unknown project';
            
            // Formátovanie úlohy s dodatočnými informáciami
            // Vytváram novú štruktúru s prehľadnejšími názvami properties
            $formatted[] = [
                'id_task' => $task['id_task'],
                'title' => $task['task_name'],
                'description' => $task['task_description'],
                'colour' => $task['task_colour'],
                'status' => $status_text,
                'deadline' => $task['deadline'],
                'project_id' => $task['id_project'],
                'project_name' => $project_name
            ];
        }
        
        return $formatted;
    }
}