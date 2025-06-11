<?php
namespace App\Models;

use App\Core\Database;
use PDO;

class User
{
    // Inštancia databázy, ktorú budem používať vo všetkých metódach
    private $db;

    public function __construct()
    {
        // Získanie inštancie databázy pomocou singleton vzoru
        // Toto je fajn, lebo nemusím vytvárať nové pripojenie v každom modeli
        $this->db = Database::getInstance();
    }

    public function register($username, $password): bool
    {
        // Zahašovanie hesla - nikdy neukladáme heslo v čistom texte!
        // Password_hash automaticky pridá salt, takže je to bezpečné
        $hash = password_hash($password, PASSWORD_BCRYPT);
        $stmt = $this->db->prepare("INSERT INTO users (user, password) VALUES (?, ?)");
        return $stmt->execute([$username, $hash]);
    }

    public function userExists($username): bool
    {
        // Kontrola, či používateľ už existuje - použijeme LIMIT 1 pre optimalizáciu
        // Nemusíme načítať celého používateľa, stačí nám ID
        $stmt = $this->db->prepare("SELECT id_user FROM users WHERE user = :user LIMIT 1");
        $stmt->execute(['user' => $username]);
        return $stmt->fetch() !== false;
    }

    public function login($username, $password)
    {
        // Najprv nájdeme používateľa podľa mena
        // Používam prepare statements, aby som predišiel SQL injection
        $stmt = $this->db->prepare("SELECT * FROM users WHERE user = ?");
        $stmt->execute([$username]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
  
        // Overíme heslo pomocou password_verify - porovná hash s heslom
        // Toto je super bezpečné, lebo porovnáva hashe, nie čisté heslá
        if ($user && password_verify($password, $user['password'])) {
            // Vrátime len potrebné údaje, nie heslo
            // Tieto údaje sa potom uložia do session
            return [
                'user_id' => $user['id_user'],
                'user' => $user['user']
            ];
        }
        return null;
    }
}