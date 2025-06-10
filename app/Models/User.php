<?php
namespace App\Models;

use App\Core\Database;
use PDO;

class User
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    public function register($username, $password): bool
    {
        $hash = password_hash($password, PASSWORD_BCRYPT);
        $stmt = $this->db->prepare("INSERT INTO users (user, password) VALUES (?, ?)");
        return $stmt->execute([$username, $hash]);
    }

    public function userExists($username): bool
    {
        $stmt = $this->db->prepare("SELECT id_user FROM users WHERE user = :user LIMIT 1");
        $stmt->execute(['user' => $username]);
        return $stmt->fetch() !== false;
    }

    public function login($username, $password)
    {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE user = ?");
        $stmt->execute([$username]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
  
       
        if ($user && password_verify($password, $user['password'])) {
           
            
            return [
                'user_id' => $user['id_user'],
                'user' => $user['user']
            ];
        }
        return null;
    }
}