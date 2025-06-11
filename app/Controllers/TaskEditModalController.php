<?php
namespace App\Controllers;

use App\Views\Events\Modals\TaskEditModal;
require_once(__DIR__ . '/../Views/events/modals/Modal.php');
require_once(__DIR__ . '/../Views/events/modals/TaskEditModal.php');

/**
 * Controller pre zobrazenie modálneho okna na úpravu úlohy
 */
class TaskEditModalController {
    
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
        // Podobne ako u ostatných modálnych okien, predávam dáta do view
        $modal = new TaskEditModal($this->taskData, $this->taskIndex);
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
// V skutočnej aplikácii by som tieto dáta získaval z databázy
// V budúcnosti by som toto mohol prerobiť na použitie GET parametrov
$taskData = isset($s) ? $s : [];
$taskIndex = isset($i) ? $i : 0;

// Vytvorenie a spustenie controllera
// Jednoduchý vzorec, ktorý sa opakuje vo viacerých controlleroch
$controller = new TaskEditModalController($taskData, $taskIndex);
$controller->display(); 