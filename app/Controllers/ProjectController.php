<?php
namespace App\Controllers;

use App\Core\Session;
use App\Models\Project;
use App\Models\Task;
use App\Views\Project\ProjectsView;

class ProjectController
{
    private $project;
    public $task=null;

    public function __construct()
    {
        if (!Session::isLoggedIn()) {
            header("Location: /login");
            exit;
        }

        $this->project = new Project();
        $this->task = new Task();
    }

    public function index()
    {
        $projects = $this->project->getAllByUser(Session::get('user_id'));
        $show_tasks = [];
        
        if(isset($_GET['idProject'])) {	
            // Ochrana proti XSS útokom - vždy čistíme vstupy
            $id_project_for_task = filter_var(htmlspecialchars($_GET['idProject']), FILTER_SANITIZE_STRING);	
            
            // Check if the project exists and belongs to the user
            $project_exists = false;
            foreach ($projects as $project) {
                if ($project['id_project'] == $id_project_for_task) {
                    $project_exists = true;
                    break;
                }
            }
            
            if ($project_exists) {
                // Vyberieme úlohy pre daný projekt zoradené podľa termínu
                $show_tasks = $this->task->getByProject($id_project_for_task);
            }
        }	

        // Make data available to the view
        $viewData = [
            'projects' => $projects,
            'show_tasks' => $show_tasks
        ];
        
        // Používame OOP triedu na vykreslenie view
        $projectsView = new ProjectsView($viewData);
        echo $projectsView->render();
    }

    public function create()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // We'll let client-side validation handle most validation errors
            // This is just a fallback for security
            if (empty($_POST['project_name']) || empty($_POST['start_date']) || empty($_POST['end_date'])) {
                // Instead of showing an error message, just redirect back
                header("Location: /projects");
                exit;
            }
            
            // Format dates properly
            $startDate = date('Y-m-d', strtotime($_POST['start_date']));
            $endDate = date('Y-m-d', strtotime($_POST['end_date']));
            
            $data = [
                'id_user'            => Session::get('user_id'),
                'project_name'       => $_POST['project_name'],
                'project_description'=> $_POST['project_description'] ?? '',
                'project_colour'     => $_POST['project_colour'] ?? '#5cb85c',
                'start_date'         => $startDate,
                'end_date'           => $endDate,
            ];
            
            $result = $this->project->create($data);
            
            // Success message has been removed as requested
        }

        // Force cache revalidation to ensure fresh data
        header("Cache-Control: no-cache, must-revalidate");
        header("Location: /projects");
        exit;
    }

    public function edit()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id_project'];

            if (isset($_POST['delete'])) {
                $this->project->delete($id);
            } else {
                // Format dates properly
                $startDate = date('Y-m-d', strtotime($_POST['start_date']));
                $endDate = date('Y-m-d', strtotime($_POST['end_date']));
                
                $data = [
                    'project_name'        => $_POST['project_name'],
                    'project_description' => $_POST['project_description'] ?? '',
                    'project_colour'      => $_POST['project_colour'],
                    'start_date'          => $startDate,
                    'end_date'            => $endDate,
                ];
                $this->project->update($id, $data);
            }

            header("Location: /projects");
        }
    }

    /**
     * Get statistics for projects and tasks
     *
     * @return array Project and task statistics
     */
    public function getStatistics()
    {
        $userId = Session::get('user_id');
        
        // Get total counts
        $projects = $this->project->getAllByUser($userId);
        $projectCount = count($projects);
        
        // Get task counts by status
        $todoCount = $this->task->countByStatus($userId, 1);
        $inProgressCount = $this->task->countByStatus($userId, 2);
        $completedCount = $this->task->countByStatus($userId, 3);
        $totalTasks = $todoCount + $inProgressCount + $completedCount;
        
        // Calculate completion percentage
        $completionPercentage = ($totalTasks > 0) 
            ? round(($completedCount / $totalTasks) * 100) 
            : 0;
        
        // Get upcoming deadlines
        $upcomingDeadlines = $this->task->getUpcomingDeadlines($userId, 7);
        
        return [
            'project_count' => $projectCount,
            'todo_count' => $todoCount,
            'in_progress_count' => $inProgressCount,
            'completed_count' => $completedCount,
            'total_tasks' => $totalTasks,
            'completion_percentage' => $completionPercentage,
            'upcoming_deadlines' => $upcomingDeadlines
        ];
    }

    /**
     * Get task metrics for a specific project
     *
     * @param int $projectId Project ID
     * @return array Task metrics for the project
     */
    public function getProjectMetrics($projectId)
    {
        $userId = Session::get('user_id');
        
        // Get all tasks for this project
        $tasks = $this->task->getByProject($projectId);
        
        // Count tasks by status
        $todoCount = 0;
        $inProgressCount = 0;
        $completedCount = 0;
        
        foreach ($tasks as $task) {
            if ($task['task_status'] == 1) {
                $todoCount++;
            } elseif ($task['task_status'] == 2) {
                $inProgressCount++;
            } elseif ($task['task_status'] == 3) {
                $completedCount++;
            }
        }
        
        $totalTasks = count($tasks);
        
        // Calculate completion percentage
        $completionPercentage = ($totalTasks > 0) 
            ? round(($completedCount / $totalTasks) * 100) 
            : 0;
        
        return [
            'todo_count' => $todoCount,
            'in_progress_count' => $inProgressCount,
            'completed_count' => $completedCount,
            'total_tasks' => $totalTasks,
            'completion_percentage' => $completionPercentage
        ];
    }
}