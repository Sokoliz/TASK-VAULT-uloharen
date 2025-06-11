<?php
namespace App\Views\Events\Actions;

use App\Core\Database;
use App\Utility\Utility;

// Import triedy Utility
require_once __DIR__.'/../../../../config/Utility.php';

/**
 * Základná Event trieda pre prácu s udalosťami v kalendári
 */
class Event {
    // Databázové spojenie a vlastnosti udalosti
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
        // Spustenie session, ak ešte nie je spustená
        // PHP_SESSION_NONE je konštanta, ktorá hovorí, že session ešte nie je spustená
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        // Kontrola, či je používateľ prihlásený
        // Ak nie je prihlásený, presmerujem ho na login stránku
        if (!isset($_SESSION['user'])) {
            http_response_code(401);
            header('Location: ../../login.php');
            die();
        }

        // Inicializácia spojenia s databázou pomocou namespace verzie
        // Použitie singleton vzoru, aby sme mali len jedno spojenie
        $this->db = Database::getInstance();
    }

    
    protected function formatDate($date) {
        // Zabezpečíme, že máme platný formát dátumu
        // Ak je dátum prázdny, použijeme aktuálny čas
        if (empty($date)) {
            return date('Y-m-d H:i:s');
        }
        
        // Pokúsime sa analyzovať dátum pomocou triedy Utility
        // strtotime() prevádza textový dátum na timestamp
        $timestamp = strtotime($date);
        if ($timestamp === false) {
            // Ak analýza zlyhá, použijeme aktuálny dátum/čas
            // Toto je záložné riešenie, ak by dátum nebol v správnom formáte
            return date('Y-m-d H:i:s');
        }
        
        // Vrátime dátum vo formáte, ktorý očakáva databáza
        return date('Y-m-d H:i:s', $timestamp);
    }

    
    protected function validateFields($fields, $data) {
        // Overenie, či sú všetky požadované polia prítomné
        // Pre každé pole v zozname kontrolujeme, či existuje v dátach
        foreach ($fields as $field) {
            if (!isset($data[$field])) {
                return false;
            }
        }
        return true;
    }

    
    protected function executeQuery($query, $errorMessage = 'There was a problem') {
        // Kontrola, či sa podarilo pripraviť dotaz
        // Ak nie, vypisujeme chybu a končíme skript
        if ($query === false) {
            print_r($this->db->errorInfo());
            http_response_code(500);
            die($errorMessage . ' while loading');
        }

        // Vykonanie dotazu a kontrola výsledku
        // Ak sa dotaz nepodarí vykonať, vypisujeme chybu
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