<?php
/**
 * CheckUsernameController
 * 
 * Checks if a username already exists in the database
 */

use App\Core\Database;

class CheckUsernameController {
    /**
     * Database connection
     */
    private $db;
    
    /**
     * Constructor
     */
    public function __construct() {
        // Get database connection
        require_once __DIR__.'/../Core/Database.php';
        $this->db = Database::getInstance();
    }
    
    /**
     * Handle username checking request
     */
    public function handle() {
        // Prevent warnings from being output in the JSON response
        error_reporting(0);
        ini_set('display_errors', 0);
        
        header('Content-Type: application/json');
        
        // Get username from POST request
        $username = isset($_POST['username']) ? trim($_POST['username']) : '';
        
        // Check if username is empty
        if (empty($username)) {
            echo json_encode(['exists' => false, 'error' => 'Username is empty']);
            return;
        }
        
        // Check if username exists in the database
        $exists = $this->checkUsernameExists($username);
        
        // Return response
        echo json_encode(['exists' => $exists]);
    }
    
    /**
     * Check if username exists in the database
     * 
     * @param string $username The username to check
     * @return bool Whether the username exists
     */
    private function checkUsernameExists($username) {
        // Prepare query to check username existence
        $stmt = $this->db->prepare("SELECT COUNT(*) AS count FROM users WHERE user = :username");
        $stmt->execute([':username' => $username]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        
        // Check if username exists
        if ($result && isset($result['count'])) {
            return (int)$result['count'] > 0;
        }
        
        // Default to not exists if error
        return false;
    }
}
?>