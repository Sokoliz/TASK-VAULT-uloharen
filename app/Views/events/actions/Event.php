<?php
namespace App\Views\Events\Actions;

use App\Core\Database;
use App\Utility\Utility;

// Import triedy Utility
require_once __DIR__.'/../../../../config/Utility.php';

/**
 * Base Event class for handling calendar events
 */
class Event {
    protected $db;
    protected $id_event;
    protected $title;
    protected $description;
    protected $start_date;
    protected $end_date;
    protected $colour;
    protected $id_user;

    /**
     * Constructor - initialize database connection
     */
    public function __construct() {
        // Start session if not already started
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        // Check if user is logged in
        if (!isset($_SESSION['user'])) {
            http_response_code(401);
            header('Location: ../../login.php');
            die();
        }

        // Inicializácia spojenia s databázou pomocou namespace verzie
        $this->db = Database::getInstance();
    }

    /**
     * Format date to database format
     * 
     * @param string $date Date to format
     * @return string Formatted date
     */
    protected function formatDate($date) {
        // Make sure we have a valid date format
        if (empty($date)) {
            return date('Y-m-d H:i:s');
        }
        
        // Try to parse the date using Utility class
        $timestamp = strtotime($date);
        if ($timestamp === false) {
            // If parsing fails, use current date/time
            return date('Y-m-d H:i:s');
        }
        
        return date('Y-m-d H:i:s', $timestamp);
    }

    /**
     * Validate that all required fields are present
     * 
     * @param array $fields Array of field names to check
     * @param array $data Data to check fields in
     * @return bool True if all fields exist
     */
    protected function validateFields($fields, $data) {
        foreach ($fields as $field) {
            if (!isset($data[$field])) {
                return false;
            }
        }
        return true;
    }

    /**
     * Execute a query and handle errors
     * 
     * @param object $query PDO prepared statement
     * @param string $errorMessage Error message to display
     * @return bool True if query executed successfully
     */
    protected function executeQuery($query, $errorMessage = 'There was a problem') {
        if ($query === false) {
            print_r($this->db->errorInfo());
            http_response_code(500);
            die($errorMessage . ' while loading');
        }

        $result = $query->execute();
        if ($result === false) {
            print_r($query->errorInfo());
            http_response_code(500);
            die($errorMessage . ' while running the query');
        }

        return true;
    }
}
?>