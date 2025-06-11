<?php
namespace App\Models;

use App\Core\Database;
use PDO;
use PDOException;

class Task
{
    // Databázové pripojenie pre prácu s úlohami
    private $db;

    public function __construct()
    {
        // Získanie inštancie databázy
        // Aj tu používam singleton vzor, aby som neotvárala viac pripojení
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
            // Získanie úloh patriacich k danému projektu
            // Toto potrebujem napr. pri zobrazení detailu projektu
            $stmt = $this->db->prepare("SELECT * FROM tasks WHERE id_project = ?");
            $stmt->execute([$projectId]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            // Logovanie chyby a vrátenie prázdneho poľa
            error_log("Error retrieving tasks: " . $e->getMessage());
            return [];
        }
    }

    
    public function create($data)
    {
        try {
            // Vytvorenie novej úlohy
            // Najprv si pripravím SQL dopyt
            $sql = "INSERT INTO tasks (id_user, id_project, task_status, task_name, task_description, task_colour, deadline) 
                                    VALUES (?, ?, ?, ?, ?, ?, ?)";
            error_log("Executing SQL: " . $sql);
            
            // Potom pripravím prepared statement
            // Toto je bezpečné proti SQL injection
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
            
            // Vykonanie dotazu s parametrami
            // A kontrola výsledku
            $result = $stmt->execute($params);
            
            if (!$result) {
                // Získanie a logovanie chyby, ak nastala
                $errorInfo = $stmt->errorInfo();
                error_log("SQL Error: " . $errorInfo[2] . " (Code: " . $errorInfo[0] . ")");
            } else {
                // Logovanie úspechu s novým ID
                error_log("Task created successfully. New ID: " . $this->db->lastInsertId());
            }
            
            return $result;
        } catch (PDOException $e) {
            // Podrobné logovanie chyby pre ľahšie debugovanie
            // Toto je super, keď niečo nefunguje
            error_log("Error creating task: " . $e->getMessage());
            error_log("Error code: " . $e->getCode());
            error_log("SQL state: " . $e->errorInfo[0] ?? 'Unknown');
            return false;
        }
    }

    
    public function update($id, $data)
    {
        try {
            // Aktualizácia existujúcej úlohy
            // Nepotrebujem aktualizovať všetky stĺpce, len tie, ktoré sa mohli zmeniť
            $stmt = $this->db->prepare("UPDATE tasks SET task_name = ?, task_description = ?, task_colour = ?, deadline = ? WHERE id_task = ?");
            return $stmt->execute([
                $data['task_name'],
                $data['task_description'],
                $data['task_colour'],
                $data['deadline'],
                $id
            ]);
        } catch (PDOException $e) {
            // Logovanie chyby pri aktualizácii
            error_log("Error updating task: " . $e->getMessage());
            return false;
        }
    }

    
    public function status_update($id, $data)
    {
        try {
            // Aktualizácia stavu úlohy (napr. z "To Do" na "In Progress")
            // Používam min a max na obmedzenie hodnoty v platnom rozsahu
            $status = max(1, min(3, (int)$data['task_status']));
            
            // Jednoduchý update len stavu úlohy
            $stmt = $this->db->prepare("UPDATE tasks SET task_status = ? WHERE id_task = ?");
            return $stmt->execute([
                $status,
                $id
            ]);
        } catch (PDOException $e) {
            // Logovanie chyby pri aktualizácii stavu
            error_log("Error updating task status: " . $e->getMessage());
            return false;
        }
    }

    
    public function delete($id)
    {
        try {
            // Logovanie pre debugovanie
            // Toto mi pomáha sledovať, čo sa deje
            error_log("Deleting task with ID: " . $id);
            
            // Vymazanie úlohy podľa ID
            $stmt = $this->db->prepare("DELETE FROM tasks WHERE id_task = ?");
            $result = $stmt->execute([$id]);
            
            // Logovanie výsledku operácie
            error_log("Delete result: " . ($result ? "Success" : "Failed") . ", Rows affected: " . $stmt->rowCount());
            
            return $result;
        } catch (PDOException $e) {
            // Logovanie chyby pri mazaní
            error_log("Error deleting task: " . $e->getMessage());
            return false;
        }
    }

    
    public function getByDeadline($userId, $date)
    {
        try {
            // Získanie úloh s termínom na konkrétny dátum
            // Toto potrebujem napr. pre zobrazenie dnešných úloh
            $stmt = $this->db->prepare("SELECT * FROM tasks WHERE id_user = ? AND deadline = ?");
            $stmt->execute([$userId, $date]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            // Logovanie chyby pri získavaní úloh
            error_log("Error retrieving tasks by deadline: " . $e->getMessage());
            return [];
        }
    }

    
    public function getByStatus($userId, $status)
    {
        try {
            // Získanie úloh podľa stavu (To Do, In Progress, Complete)
            // Toto je užitočné pre filtrovanie úloh
            $stmt = $this->db->prepare("SELECT * FROM tasks WHERE id_user = ? AND task_status = ?");
            $stmt->execute([$userId, $status]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            // Logovanie chyby pri získavaní úloh podľa stavu
            error_log("Error retrieving tasks by status: " . $e->getMessage());
            return [];
        }
    }

    
    public function countByStatus($userId, $status)
    {
        try {
            // Počítanie úloh v danom stave
            // Používam COUNT(*) a fetchColumn() pre získanie len čísla
            $stmt = $this->db->prepare("SELECT COUNT(*) FROM tasks WHERE id_user = ? AND task_status = ?");
            $stmt->execute([$userId, $status]);
            return (int)$stmt->fetchColumn();
        } catch (PDOException $e) {
            // Logovanie chyby pri počítaní úloh
            error_log("Error counting tasks by status: " . $e->getMessage());
            return 0;
        }
    }

    
    public function getUpcomingDeadlines($userId, $days = 7)
    {
        try {
            // Získanie úloh s blížiacim sa termínom
            // Výpočet dátumov pre rozsah dní
            $today = date('Y-m-d');
            $future = date('Y-m-d', strtotime("+{$days} days"));
            
            // SQL dopyt na filtrovanie úloh
            // Nevyberám dokončené úlohy (task_status != 3)
            $stmt = $this->db->prepare("SELECT * FROM tasks 
                                       WHERE id_user = ? 
                                       AND deadline BETWEEN ? AND ? 
                                       AND task_status != 3 
                                       ORDER BY deadline ASC");
            $stmt->execute([$userId, $today, $future]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            // Logovanie chyby pri získavaní nadchádzajúcich úloh
            error_log("Error retrieving upcoming tasks: " . $e->getMessage());
            return [];
        }
    }

    
    public function getById($id)
    {
        try {
            // Získanie úlohy podľa ID
            // Očakávam len jeden záznam, takže použijem fetch
            $stmt = $this->db->prepare("SELECT * FROM tasks WHERE id_task = ?");
            $stmt->execute([$id]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            // Logovanie chyby pri získavaní úlohy
            error_log("Error retrieving task by ID: " . $e->getMessage());
            return false;
        }
    }
}