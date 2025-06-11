<?php
namespace App\Controllers;

use App\Views\Events\Modals\EventEditModal;
require_once(__DIR__ . '/../Views/events/modals/Modal.php');
require_once(__DIR__ . '/../Views/events/modals/EventEditModal.php');

/**
 * Controller pre zobrazenie modálneho okna na úpravu udalosti
 */
class EventEditModalController {
    
    private $eventData;
    
    
    public function __construct($eventData = null) {
        $this->eventData = $eventData;
    }
    
    /**
     * Renderovanie modálneho okna
     * 
     * @return string HTML pre modálne okno
     */
    public function render() {
        // Vytvorenie a renderovanie modálneho okna
        // V tomto prípade poskytujeme dáta udalosti, ktoré chceme upraviť
        $modal = new EventEditModal($this->eventData);
        return $modal->render('Save');
    }
    
    /**
     * Zobrazenie modálneho okna
     */
    public function display() {
        echo $this->render();
    }
}

// Vytvorenie a spustenie controllera
// Toto je zjednodušená verzia, v reálnej aplikácii by som získaval dáta z DB
$controller = new EventEditModalController();
$controller->display(); 