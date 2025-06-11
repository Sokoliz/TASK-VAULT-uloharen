<?php
/**
 * CheckUsernameController
 * 
 * Kontroluje, či používateľské meno už existuje v databáze
 * Tento controller sa používa pri registrácii na asynchrónnu validáciu
 */

use App\Core\Database;

class CheckUsernameController {
    
    private $db;
    
    
    public function __construct() {
        // Získanie databázového pripojenia
        // Toto musím urobiť v každom controlleri, nie je to moc elegantné ale funguje
        require_once __DIR__.'/../Core/Database.php';
        $this->db = Database::getInstance();
    }
    
    
    public function handle() {
        // Zabránenie výpisu varovaní v JSON odpovedi
        // Toto som našiel na Stack Overflow, pomáha to pri JSON responses
        error_reporting(0);
        ini_set('display_errors', 0);
        
        header('Content-Type: application/json');
        
        // Získanie používateľského mena z POST requestu
        // Použil som tu trim() na odstránenie medzier z okrajov
        $username = isset($_POST['username']) ? trim($_POST['username']) : '';
        
        // Kontrola, či je používateľské meno prázdne
        // Jednoduchá validácia na začiatok
        if (empty($username)) {
            echo json_encode(['exists' => false, 'error' => 'Username is empty']);
            return;
        }
        
        // Kontrola, či používateľské meno existuje v databáze
        // Volám privátnu metódu na kontrolu duplicity
        $exists = $this->checkUsernameExists($username);
        
        // Vrátenie odpovede klientovi
        // Jednoduchý JSON s bool hodnotou
        echo json_encode(['exists' => $exists]);
    }
    
    /**
     * Kontrola, či používateľské meno existuje v databáze
     * 
     * @param string $username Používateľské meno na kontrolu
     * @return bool Či používateľské meno existuje
     */
    private function checkUsernameExists($username) {
        // Príprava dotazu na kontrolu existencie používateľského mena
        // Používam prepared statement kvôli bezpečnosti - ochrana proti SQL injection
        $stmt = $this->db->prepare("SELECT COUNT(*) AS count FROM users WHERE user = :username");
        $stmt->execute([':username' => $username]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        
        // Kontrola, či používateľské meno existuje
        // Ak je count > 0, znamená to, že meno už existuje
        if ($result && isset($result['count'])) {
            return (int)$result['count'] > 0;
        }
        
        // Predvolená hodnota ak nastala chyba
        // Pre istotu vraciam false, aby sme zbytočne neblokovali registráciu
        return false;
    }
}
?>