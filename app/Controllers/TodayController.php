<?php
namespace App\Controllers;

use App\Core\Session;
use App\Models\Project;
use App\Models\Calendar;
use App\Models\Task;

// Kontrolér pre zobrazenie všetkých dôležitých údajov pre aktuálny deň
class TodayController
{
    private $project;
    private $calendar;
    private $task;

    // Konštruktor inicializuje sedenie, kontroluje prihlásenie a vytvára inštancie potrebných modelov
    public function __construct()
    {
        Session::start();
        // Ak používateľ nie je prihlásený, presmeruje ho na prihlásenie
        if (!Session::isLoggedIn()) {
            header("Location: /login");
            exit;
        }

        // Vytvorenie inštancií všetkých potrebných modelov
        $this->project = new Project();
        $this->calendar = new Calendar();
        $this->task = new Task();
    }

    // Metóda pre zobrazenie všetkých údajov relevantných pre dnešný deň
    public function index()
    {
        // Získanie ID prihláseného používateľa
        $userId = Session::get('user_id');

        // Príprava dátumových premenných pre dnešný deň
        $today = date("Y-m-d");
        $today_start = $today . " 00:00:00";
        $today_end = $today . " 23:59:59";

        // Získanie projektov, ktoré začínajú alebo končia dnes
        $projects_start = $this->project->getByStartDate($userId, $today);
        $projects_end   = $this->project->getByEndDate($userId, $today);

        // Získanie udalostí kalendára, ktoré začínajú alebo končia dnes
        $events_start = $this->calendar->getEventsByStartDate($userId, $today_start, $today_end);
        $events_end   = $this->calendar->getEventsByEndDate($userId, $today_start, $today_end);

        // Získanie úloh, ktoré majú termín dokončenia dnes
        $tasks = $this->task->getByDeadline($userId, $today);

        // Načítanie pohľadu pre zobrazenie dnešných udalostí a úloh
        require __DIR__ . '/../Views/today.php';
    }
}