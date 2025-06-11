<?php
namespace App\Controllers;

use App\Views\Events\Modals\EditTaskModal;
require_once(__DIR__ . '/../Views/events/modals/Modal.php');
require_once(__DIR__ . '/../Views/events/modals/EditTaskModal.php');

/**
 * Controller pre zobrazenie modálneho okna na úpravu úlohy
 */
class EditTaskModalController {
    
    private $taskData;
    
    
    private $taskIndex;
    
    /**
     * Konštruktor
     * 
     * @param array $taskData Dáta úlohy
     * @param int $taskIndex Index úlohy
     */
    public function __construct($taskData = [], $taskIndex = 0) {
        $this->taskData = $taskData;
        $this->taskIndex = $taskIndex;
    }
    
    /**
     * Renderovanie modálneho okna
     * 
     * @return string HTML pre modálne okno
     */
    public function render() {
        // Vytvorenie a renderovanie modálneho okna
        // Tu využívam dependency injection a posúvam dáta do view
        $modal = new EditTaskModal($this->taskData, $this->taskIndex);
        return $modal->render();
    }
    
    /**
     * Zobrazenie modálneho okna
     */
    public function display() {
        echo $this->render();
    }
}

// Získanie dát a indexu úlohy z premenných v scope
// V reálnej aplikácii by tieto údaje prišli z databázy alebo parametrov requestu
// Toto vyzerá trochu divne, určite by som to v reálnom projekte robil inak
$taskData = isset($s) ? $s : [];
$taskIndex = isset($i) ? $i : 0;

// Vytvorenie a spustenie controllera
// Dalo by sa to riešiť cez Router, ale toto je jednoduchšie riešenie
$controller = new EditTaskModalController($taskData, $taskIndex);
$controller->display(); 