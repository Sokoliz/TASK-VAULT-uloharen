<?php
namespace App\Controllers;

use App\Core\Session;
use App\Models\Task;

class TaskController
{
    private $task;

    public function __construct()
    {
        if (!Session::isLoggedIn()) {
            header("Location: /login");
            exit;
        }

        $this->task = new Task();
    }

    public function index()
    {
        if (!isset($_GET['project'])) {
            echo "Project ID missing.";
            exit;
        }

        $projectId = $_GET['project'];
        $tasks = $this->task->getByProject($projectId);

        require __DIR__ . '/../Views/task/index.php';
    }

    public function create()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Log received POST data for debugging
            error_log("Task create POST data: " . print_r($_POST, true));
            
            // We'll let client-side validation handle most validation errors
            // This is just a fallback for security
            if (empty($_POST['task_name']) || empty($_POST['task_colour']) || empty($_POST['deadline'])) {
                error_log("Task validation failed: missing task_name, task_colour, or deadline");
                // Instead of showing an error message, just redirect back
                header("Location: /projects?idProject=" . $_POST['id_project']);
                exit;
            }
            
            $data = [
                'id_user'         => Session::get('user_id'),
                'id_project'      => $_POST['id_project'],
                'task_status'     => $_POST['task_status'],
                'task_name'       => $_POST['task_name'],
                'task_description'=> $_POST['task_description'] ?? '',
                'task_colour'     => $_POST['task_colour'],
                'deadline'        => $_POST['deadline']
            ];
            
            // Log data being sent to the model
            error_log("Task data for model: " . print_r($data, true));
            
            $result = $this->task->create($data);
            
            // Log result
            error_log("Task creation result: " . ($result ? "Success" : "Failed"));
            
            // Success message has been removed as requested
            
            header("Location: /projects?idProject=" . $_POST['id_project']);
            exit;
        }
    }

    public function edit()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id_task'];
            $projectId = $_POST['id_project'];

            if (isset($_POST['delete'])) {
                // Log deletion attempt for debugging
                error_log("Attempting to delete task ID: " . $id . " for project: " . $projectId);
                $result = $this->task->delete($id);
                error_log("Delete result: " . ($result ? "Success" : "Failed"));
            } else {
                $data = [
                    'task_name'        => $_POST['task_name'],
                    'task_description' => $_POST['task_description'],
                    'task_colour'      => $_POST['task_colour'],
                    'deadline'         => $_POST['deadline'],
                ];
                
                $this->task->update($id, $data);
            }

            header("Location: /projects?idProject=" . $projectId);
            exit; // Ensure script execution stops after redirect
        }
    }

    public function move()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id_task'];
            $projectId = $_POST['id_project'];
            
            if (isset($_POST['right'])) {
                $data = [
                    'task_status' => $_POST['task_status'] + 1,
                ];
            } else if (isset($_POST['left'])) {
                $data = [
                    'task_status' => $_POST['task_status'] - 1,
                ];
            }
            
            $this->task->status_update($id, $data);
            header("Location: /projects?idProject=" . $projectId);
        }
    }









}