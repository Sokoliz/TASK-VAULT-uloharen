<?php
namespace App\Models;

use App\Core\Database;
use PDO;

// Model pre prácu s kalendárovými udalosťami v databáze
class Calendar
{
    private $db;

    // Konštruktor inicializuje pripojenie k databáze
    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    // Získanie všetkých udalostí pre konkrétneho používateľa
    public function getAllEventsByUser($userId)
    {
        $stmt = $this->db->prepare("SELECT * FROM calendar WHERE id_user = ?");
        $stmt->execute([$userId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Vytvorenie novej udalosti v kalendári
    public function createEvent($data)
    {
        $stmt = $this->db->prepare("INSERT INTO calendar (id_user, title, description, colour, start_date, end_date) VALUES (?, ?, ?, ?, ?, ?)");
        return $stmt->execute([
            $data['id_user'],
            $data['title'],
            $data['description'],
            $data['colour'],
            $data['start_date'],
            $data['end_date']
        ]);
    }

    // Získanie konkrétnej udalosti podľa jej ID
    public function getEventById($id)
    {
        $stmt = $this->db->prepare("SELECT * FROM calendar WHERE id_event = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Aktualizácia existujúcej udalosti v kalendári
    public function updateEvent($id, $data)
    {
        $stmt = $this->db->prepare("UPDATE calendar SET title = ?, description = ?, colour = ?, start_date = ?, end_date = ? WHERE id_event = ?");
        return $stmt->execute([
            $data['title'],
            $data['description'],
            $data['colour'],
            $data['start_date'],
            $data['end_date'],
            $id
        ]);
       
    }

    // Vymazanie udalosti z kalendára
    public function deleteEvent($id)
    {
        $stmt = $this->db->prepare("DELETE FROM calendar WHERE id_event = ?");
        return $stmt->execute([$id]);
    }

    // Získanie udalostí, ktoré začínajú v zadanom časovom rozmedzí
    public function getEventsByStartDate($userId, $start, $end)
    {
        $stmt = $this->db->prepare("SELECT * FROM calendar WHERE id_user = ? AND (start_date BETWEEN ? AND ?)");
        $stmt->execute([$userId, $start, $end]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Získanie udalostí, ktoré končia v zadanom časovom rozmedzí
    public function getEventsByEndDate($userId, $start, $end)
    {
        $stmt = $this->db->prepare("SELECT * FROM calendar WHERE id_user = ? AND (end_date BETWEEN ? AND ?)");
        $stmt->execute([$userId, $start, $end]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}