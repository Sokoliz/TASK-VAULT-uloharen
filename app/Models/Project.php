<?php
namespace App\Models;

use App\Core\Database;
use PDO;
use PDOException;

class Project
{
    // Databázové pripojenie pre prácu s projektmi
    private $db;

    public function __construct()
    {
        // Získanie inštancie databázy
        // Toto je štandardný postup pre všetky modely
        $this->db = Database::getInstance();
    }

    public function getAllByUser($userId)
    {
        try {
            // Získanie všetkých projektov používateľa
            // Používam ORDER BY pre zoradenie od najnovších
            $stmt = $this->db->prepare("SELECT * FROM projects WHERE id_user = ? ORDER BY id_project DESC");
            $stmt->execute([$userId]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            // Logujeme chybu a vrátime prázdne pole
            // Toto je dobrá praktika pre zachytenie chýb
            error_log("Error retrieving projects: " . $e->getMessage());
            return [];
        }
    }

    public function create($data)
    {
        try {
            // Vytvorenie nového projektu
            // Potrebujem všetky potrebné údaje uložiť do databázy
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
        } catch (PDOException $e) {
            // Logovanie chyby pre prípad zlyhania
            // Toto je super pri debugovaní
            error_log("Error creating project: " . $e->getMessage());
            return false;
        }
    }

    public function update($id, $data)
    {
        try {
            // Aktualizácia existujúceho projektu
            // Používam WHERE podmienku, aby som aktualizoval správny záznam
            $stmt = $this->db->prepare("UPDATE projects SET project_name = ?, project_description = ?, project_colour = ?, start_date = ?, end_date = ? WHERE id_project = ?");
            return $stmt->execute([
                $data['project_name'],
                $data['project_description'],
                $data['project_colour'],
                $data['start_date'],
                $data['end_date'],
                $id
            ]);
        } catch (PDOException $e) {
            // Opäť logujem chybu pre jednoduchšie debugovanie
            error_log("Error updating project: " . $e->getMessage());
            return false;
        }
    }

    public function delete($id)
    {
        try {
            // Vymazanie projektu podľa ID
            // Treba byť opatrný, toto vymaže projekt natrvalo
            $stmt = $this->db->prepare("DELETE FROM projects WHERE id_project = ?");
            return $stmt->execute([$id]);
        } catch (PDOException $e) {
            // Logovanie chyby pri mazaní
            error_log("Error deleting project: " . $e->getMessage());
            return false;
        }
    }

    public function getByStartDate($userId, $date)
    {
        try {
            // Získanie projektov podľa dátumu začiatku
            // Toto používam napr. pre zobrazenie na dashboarde
            $stmt = $this->db->prepare("SELECT * FROM projects WHERE id_user = ? AND start_date = ?");
            $stmt->execute([$userId, $date]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            // Logovanie chyby a vrátenie prázdneho poľa
            error_log("Error retrieving projects by start date: " . $e->getMessage());
            return [];
        }
    }

    public function getByEndDate($userId, $date)
    {
        try {
            // Získanie projektov podľa dátumu ukončenia
            // Podobné ako getByStartDate, len pre koncový dátum
            $stmt = $this->db->prepare("SELECT * FROM projects WHERE id_user = ? AND end_date = ?");
            $stmt->execute([$userId, $date]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            // Logovanie chyby pri získavaní projektov
            error_log("Error retrieving projects by end date: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Get a project by ID
     * 
     * @param int $id Project ID
     * @return array|false Project data or false if not found
     */
    public function getById($id)
    {
        try {
            // Získanie projektu podľa ID
            // Používam fetch, nie fetchAll, lebo očakávam len jeden záznam
            $stmt = $this->db->prepare("SELECT * FROM projects WHERE id_project = ?");
            $stmt->execute([$id]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            // Logovanie chyby pri načítaní projektu
            error_log("Error retrieving project by ID: " . $e->getMessage());
            return false;
        }
    }
}