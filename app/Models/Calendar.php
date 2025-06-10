<?php
namespace App\Models;

use App\Core\Database;
use PDO;

class Calendar
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    public function getAllEventsByUser($userId)
    {
        $stmt = $this->db->prepare("SELECT * FROM calendar WHERE id_user = ?");
        $stmt->execute([$userId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

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

    public function getEventById($id)
    {
        $stmt = $this->db->prepare("SELECT * FROM calendar WHERE id_event = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

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

    public function deleteEvent($id)
    {
        try {
            $stmt = $this->db->prepare("DELETE FROM calendar WHERE id_event = ?");
            $result = $stmt->execute([$id]);
            return $result;
        } catch (\Exception $e) {
            return false;
        }
    }


    public function getEventsByStartDate($userId, $start, $end)
    {
        $stmt = $this->db->prepare("SELECT * FROM calendar WHERE id_user = ? AND (start_date BETWEEN ? AND ?)");
        $stmt->execute([$userId, $start, $end]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getEventsByEndDate($userId, $start, $end)
    {
        $stmt = $this->db->prepare("SELECT * FROM calendar WHERE id_user = ? AND (end_date BETWEEN ? AND ?)");
        $stmt->execute([$userId, $start, $end]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }




}