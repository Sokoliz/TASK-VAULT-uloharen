<?php
namespace App\Models;

use App\Core\Database;
use PDO;

// Model pre prácu s úlohami v rámci projektov
class Task
{
    private $db;

    // Konštruktor inicializuje pripojenie k databáze
    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    // Získanie všetkých úloh pre konkrétny projekt
    public function getByProject($projectId)
    {
        $stmt = $this->db->prepare("SELECT * FROM tasks WHERE id_project = ?");
        $stmt->execute([$projectId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Vytvorenie novej úlohy v databáze
    public function create($data)
    {
        $stmt = $this->db->prepare("INSERT INTO tasks (id_user, id_project, task_status, task_name, task_description, task_colour, deadline) 
                                    VALUES (?, ?, ?, ?, ?, ?, ?)");
        return $stmt->execute([
            $data['id_user'],
            $data['id_project'],
            $data['task_status'],
            $data['task_name'],
            $data['task_description'],
            $data['task_colour'],
            $data['deadline']
        ]);
    }

    // Aktualizácia údajov existujúcej úlohy v databáze
    public function update($id, $data)
    {
        $stmt = $this->db->prepare("UPDATE tasks SET task_name = ?, task_description = ?, task_colour = ?, deadline = ? WHERE id_task = ?");
        return $stmt->execute([
            $data['task_name'],
            $data['task_description'],
            $data['task_colour'],
            $data['deadline'],
            $id
        ]);
    }

    // Aktualizácia stavu úlohy (pre posun v Kanban tabuľke)
    public function status_update($id, $data)
    {
        $stmt = $this->db->prepare("UPDATE tasks SET task_status = ? WHERE id_task = ?");
        return $stmt->execute([
            $data['task_status'],
            $id
        ]);
    }

    // Vymazanie úlohy z databázy
    public function delete($id)
    {
        $stmt = $this->db->prepare("DELETE FROM tasks WHERE id_task = ?");
        return $stmt->execute([$id]);
    }

    // Získanie úloh, ktoré majú termín dokončenia v konkrétny dátum
    public function getByDeadline($userId, $date)
    {
        $stmt = $this->db->prepare("SELECT * FROM tasks WHERE id_user = ? AND deadline = ?");
        $stmt->execute([$userId, $date]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}