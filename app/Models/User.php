<?php
namespace App\Models;

use App\Core\Database;
use PDO;

// Model pre prácu s používateľmi a autentifikáciu
class User
{
    private $db;

    // Konštruktor inicializuje pripojenie k databáze
    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    // Metóda pre registráciu nového používateľa
    public function register($username, $password): bool
    {
        // Vytvorenie bezpečného hash-u hesla pomocou bcrypt algoritmu
        $hash = password_hash($password, PASSWORD_BCRYPT);
        $stmt = $this->db->prepare("INSERT INTO users (user, password) VALUES (?, ?)");
        return $stmt->execute([$username, $hash]);
    }

    // Kontrola, či používateľské meno už existuje v databáze
    public function userExists($username): bool
    {
        $stmt = $this->db->prepare("SELECT id_user FROM users WHERE user = :user LIMIT 1");
        $stmt->execute(['user' => $username]);
        return $stmt->fetch() !== false;
    }

    // Overenie prihlasovacích údajov a získanie informácií o používateľovi
    public function login($username, $password)
    {
        // Vyhľadanie používateľa podľa mena
        $stmt = $this->db->prepare("SELECT * FROM users WHERE user = ?");
        $stmt->execute([$username]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
  
        // Overenie, či používateľ existuje a či je heslo správne
        if ($user && password_verify($password, $user['password'])) {
            // Vrátenie základných údajov o používateľovi pre sedenie
            return [
                'user_id' => $user['id_user'],
                'user' => $user['user']
            ];
        }
        return null;
    }
}