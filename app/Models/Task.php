<?php
namespace App\Models;

use App\Core\Database;
use PDO;
use PDOException;

class Task
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    /**
     * Get tasks by project ID
     * 
     * @param int $projectId Project ID
     * @return array List of tasks
     */
    public function getByProject($projectId)
    {
        try {
            $stmt = $this->db->prepare("SELECT * FROM tasks WHERE id_project = ?");
            $stmt->execute([$projectId]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error retrieving tasks: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Create a new task
     * 
     * @param array $data Task data
     * @return bool Success status
     */
    public function create($data)
    {
        try {
            $sql = "INSERT INTO tasks (id_user, id_project, task_status, task_name, task_description, task_colour, deadline) 
                                    VALUES (?, ?, ?, ?, ?, ?, ?)";
            error_log("Executing SQL: " . $sql);
            
            $stmt = $this->db->prepare($sql);
            $params = [
                $data['id_user'],
                $data['id_project'],
                $data['task_status'],
                $data['task_name'],
                $data['task_description'],
                $data['task_colour'],
                $data['deadline']
            ];
            
            $result = $stmt->execute($params);
            
            if (!$result) {
                $errorInfo = $stmt->errorInfo();
                error_log("SQL Error: " . $errorInfo[2] . " (Code: " . $errorInfo[0] . ")");
            } else {
                error_log("Task created successfully. New ID: " . $this->db->lastInsertId());
            }
            
            return $result;
        } catch (PDOException $e) {
            error_log("Error creating task: " . $e->getMessage());
            error_log("Error code: " . $e->getCode());
            error_log("SQL state: " . $e->errorInfo[0] ?? 'Unknown');
            return false;
        }
    }

    /**
     * Update a task
     * 
     * @param int $id Task ID
     * @param array $data Task data
     * @return bool Success status
     */
    public function update($id, $data)
    {
        try {
            $stmt = $this->db->prepare("UPDATE tasks SET task_name = ?, task_description = ?, task_colour = ?, deadline = ? WHERE id_task = ?");
            return $stmt->execute([
                $data['task_name'],
                $data['task_description'],
                $data['task_colour'],
                $data['deadline'],
                $id
            ]);
        } catch (PDOException $e) {
            error_log("Error updating task: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Update task status
     *
     * @param int $id Task ID
     * @param array $data Task data
     * @return bool Success status
     */
    public function status_update($id, $data)
    {
        try {
            // Ensure task status is within valid range (1-3)
            $status = max(1, min(3, (int)$data['task_status']));
            
            $stmt = $this->db->prepare("UPDATE tasks SET task_status = ? WHERE id_task = ?");
            return $stmt->execute([
                $status,
                $id
            ]);
        } catch (PDOException $e) {
            error_log("Error updating task status: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Delete a task
     * 
     * @param int $id Task ID
     * @return bool Success status
     */
    public function delete($id)
    {
        try {
            // Log for debugging
            error_log("Deleting task with ID: " . $id);
            
            $stmt = $this->db->prepare("DELETE FROM tasks WHERE id_task = ?");
            $result = $stmt->execute([$id]);
            
            // Log the result
            error_log("Delete result: " . ($result ? "Success" : "Failed") . ", Rows affected: " . $stmt->rowCount());
            
            return $result;
        } catch (PDOException $e) {
            error_log("Error deleting task: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Get tasks by deadline
     * 
     * @param int $userId User ID
     * @param string $date Date in Y-m-d format
     * @return array List of tasks
     */
    public function getByDeadline($userId, $date)
    {
        try {
            $stmt = $this->db->prepare("SELECT * FROM tasks WHERE id_user = ? AND deadline = ?");
            $stmt->execute([$userId, $date]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error retrieving tasks by deadline: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Get tasks by status
     * 
     * @param int $userId User ID
     * @param int $status Status (1=To Do, 2=In Progress, 3=Complete)
     * @return array List of tasks
     */
    public function getByStatus($userId, $status)
    {
        try {
            $stmt = $this->db->prepare("SELECT * FROM tasks WHERE id_user = ? AND task_status = ?");
            $stmt->execute([$userId, $status]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error retrieving tasks by status: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Count tasks by status
     * 
     * @param int $userId User ID
     * @param int $status Status (1=To Do, 2=In Progress, 3=Complete)
     * @return int Number of tasks
     */
    public function countByStatus($userId, $status)
    {
        try {
            $stmt = $this->db->prepare("SELECT COUNT(*) FROM tasks WHERE id_user = ? AND task_status = ?");
            $stmt->execute([$userId, $status]);
            return (int)$stmt->fetchColumn();
        } catch (PDOException $e) {
            error_log("Error counting tasks by status: " . $e->getMessage());
            return 0;
        }
    }

    /**
     * Get tasks with approaching deadlines
     * 
     * @param int $userId User ID
     * @param int $days Number of days ahead
     * @return array List of tasks
     */
    public function getUpcomingDeadlines($userId, $days = 7)
    {
        try {
            $today = date('Y-m-d');
            $future = date('Y-m-d', strtotime("+{$days} days"));
            
            $stmt = $this->db->prepare("SELECT * FROM tasks 
                                       WHERE id_user = ? 
                                       AND deadline BETWEEN ? AND ? 
                                       AND task_status != 3 
                                       ORDER BY deadline ASC");
            $stmt->execute([$userId, $today, $future]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error retrieving upcoming tasks: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Get task by ID
     * 
     * @param int $id Task ID
     * @return array|false Task data or false if not found
     */
    public function getById($id)
    {
        try {
            $stmt = $this->db->prepare("SELECT * FROM tasks WHERE id_task = ?");
            $stmt->execute([$id]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error retrieving task by ID: " . $e->getMessage());
            return false;
        }
    }
}
