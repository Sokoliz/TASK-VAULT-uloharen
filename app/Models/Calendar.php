<?php
namespace App\Models;

use App\Core\Database;
use PDO;

class Calendar
{
    // Inštancia databázy pre prácu s kalendárom
    private $db;

    public function __construct()
    {
        // Získanie inštancie databázy - opäť cez singleton
        // Je dobré, že to je konzistentné so všetkými modelmi
        $this->db = Database::getInstance();
    }

    public function getAllEventsByUser($userId)
    {
        // Získanie všetkých udalostí pre daného používateľa
        // Používam rovnaký vzor pre všetky get metódy
        $stmt = $this->db->prepare("SELECT * FROM calendar WHERE id_user = ?");
        $stmt->execute([$userId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function createEvent($data)
    {
        // Vytvorenie novej udalosti v kalendári
        // Pripravím si SQL a potom vykonám s parametrami
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

    public function getEventById($id)
    {
        // Získanie konkrétnej udalosti podľa ID
        // Používam fetch namiesto fetchAll, lebo očakávam len jeden záznam
        $stmt = $this->db->prepare("SELECT * FROM calendar WHERE id_event = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function updateEvent($id, $data)
    {
        // Aktualizácia existujúcej udalosti
        // Podobné ako createEvent, ale s WHERE podmienkou na id_event
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

    public function deleteEvent($id)
    {
        try {
            // Vymazanie udalosti z kalendára
            // Obaľujem to do try-catch, aby sa aplikácia nezrútila pri chybe
            $stmt = $this->db->prepare("DELETE FROM calendar WHERE id_event = ?");
            $result = $stmt->execute([$id]);
            return $result;
        } catch (\Exception $e) {
            return false;
        }
    }


    public function getEventsByStartDate($userId, $start, $end)
    {
        // Získanie udalostí podľa dátumu začiatku
        // Filtrujem podľa časového rozsahu, napr. pre zobrazenie dnešných udalostí
        $stmt = $this->db->prepare("SELECT * FROM calendar WHERE id_user = ? AND (start_date BETWEEN ? AND ?)");
        $stmt->execute([$userId, $start, $end]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getEventsByEndDate($userId, $start, $end)
    {
        // Získanie udalostí podľa dátumu konca
        // Podobné ako predchádzajúca metóda, len hľadám podľa end_date
        $stmt = $this->db->prepare("SELECT * FROM calendar WHERE id_user = ? AND (end_date BETWEEN ? AND ?)");
        $stmt->execute([$userId, $start, $end]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}