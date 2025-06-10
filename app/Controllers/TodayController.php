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
        if (!Session::isLoggedIn()) {
            header("Location: /login");
            exit;
        }

        $this->project = new Project();
        $this->calendar = new Calendar();
        $this->task = new Task();
    }

    public function index()
    {
        $userId = Session::get('user_id');

        $today = date("Y-m-d");
        $today_start = $today . " 00:00:00";
        $today_end = $today . " 23:59:59";

        // Get projects starting or ending today
        $projects_start = $this->project->getByStartDate($userId, $today);
        $projects_end = $this->project->getByEndDate($userId, $today);

        // Get events starting or ending today
        $events_start = $this->calendar->getEventsByStartDate($userId, $today_start, $today_end);
        $events_end = $this->calendar->getEventsByEndDate($userId, $today_start, $today_end);

        // Get tasks with deadlines today
        $tasks_deadline = $this->task->getByDeadline($userId, $today);
        
        // Format the tasks with additional information
        $formatted_tasks = $this->formatTasks($tasks_deadline);

        // Prepare view data
        $viewData = [
            'projects_start' => $projects_start,
            'projects_end' => $projects_end,
            'events_start' => $events_start,
            'events_end' => $events_end,
            'formatted_tasks' => $formatted_tasks,
            'today' => $today
        ];

        // Načítanie view triedy a vytvorenie jej inštancie
        require_once __DIR__ . '/../Views/page/today.view.php';
        $todayView = new \TodayView($viewData);
        echo $todayView->render();
    }
    
    /**
     * Format task data for display
     * 
     * @param array $tasks Tasks to format
     * @return array Formatted tasks
     */
    private function formatTasks($tasks)
    {
        $formatted = [];
        
        foreach ($tasks as $task) {
            // Determine task status text
            $status_text = 'To Do';
            if ($task['task_status'] == 2) {
                $status_text = 'In Progress';
            } elseif ($task['task_status'] == 3) {
                $status_text = 'Complete';
            }
            
            // Get project information
            $project = $this->project->getById($task['id_project']);
            $project_name = $project ? $project['project_name'] : 'Unknown project';
            
            // Format the task with additional information
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