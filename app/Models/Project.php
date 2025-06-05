<?php
namespace App\Models;

use App\Core\Database;
use PDO;

// Model pre prácu s projektami v databáze
class Project
{
    private $db;

    // Konštruktor inicializuje pripojenie k databáze
    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    // Získanie všetkých projektov pre konkrétneho používateľa
    public function getAllByUser($userId)
    {
        $stmt = $this->db->prepare("SELECT * FROM projects WHERE id_user = ?");
        $stmt->execute([$userId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Vytvorenie nového projektu v databáze
    public function create($data)
    {
        $stmt = $this->db->prepare("INSERT INTO projects (id_user, project_name, project_description, project_colour, start_date, end_date)
                                    VALUES (?, ?, ?, ?, ?, ?)");
        return $stmt->execute([
            $data['id_user'],
            $data['project_name'],
            $data['project_description'],
            $data['project_colour'],
            $data['start_date'],
            $data['end_date']
        ]);
    }

    // Aktualizácia existujúceho projektu v databáze
    public function update($id, $data)
    {
        $stmt = $this->db->prepare("UPDATE projects SET project_name = ?, project_description = ?, project_colour = ?, start_date = ?, end_date = ? WHERE id_project = ?");
        return $stmt->execute([
            $data['project_name'],
            $data['project_description'],
            $data['project_colour'],
            $data['start_date'],
            $data['end_date'],
            $id
        ]);
    }

    // Vymazanie projektu z databázy
    public function delete($id)
    {
        $stmt = $this->db->prepare("DELETE FROM projects WHERE id_project = ?");
        return $stmt->execute([$id]);
    }

    // Získanie projektov, ktoré začínajú v konkrétny dátum
    public function getByStartDate($userId, $date)
    {
        $stmt = $this->db->prepare("SELECT * FROM projects WHERE id_user = ? AND start_date = ?");
        $stmt->execute([$userId, $date]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Získanie projektov, ktoré končia v konkrétny dátum
    public function getByEndDate($userId, $date)
    {
        $stmt = $this->db->prepare("SELECT * FROM projects WHERE id_user = ? AND end_date = ?");
        $stmt->execute([$userId, $date]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}