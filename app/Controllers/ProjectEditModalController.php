<?php
namespace App\Controllers;

use App\Views\Events\Modals\ProjectEditModal;
require_once(__DIR__ . '/../Views/events/modals/Modal.php');
require_once(__DIR__ . '/../Views/events/modals/ProjectEditModal.php');

/**
 * Controller pre zobrazenie modálneho okna na úpravu projektu
 */
class ProjectEditModalController {
    
    private $projectData;
    
    private $projectIndex;
    
    /**
     * Konštruktor
     * 
     * @param array $projectData Dáta projektu
     * @param int $projectIndex Index projektu
     */
    public function __construct($projectData = [], $projectIndex = 0) {
        $this->projectData = $projectData;
        $this->projectIndex = $projectIndex;
    }
    
    /**
     * Renderovanie modálneho okna
     * 
     * @return string HTML pre modálne okno
     */
    public function render() {
        // Vytvorenie a renderovanie modálneho okna
        // Názov tlačidla "Update" sa používa na odlíšenie od formulára na pridanie
        $modal = new ProjectEditModal($this->projectData, $this->projectIndex);
        return $modal->render('Update');
    }
    
    /**
     * Zobrazenie modálneho okna
     */
    public function display() {
        echo $this->render();
    }
}

// Získanie dát a indexu projektu z premenných v scope
// V reálnej aplikácii by tieto údaje prišli z databázy alebo parametrov requestu
// Asi by to bolo lepšie riešiť cez GET parameter a potom načítať z DB
$projectData = isset($p) ? $p : [];
$projectIndex = isset($i) ? $i : 0;

// Vytvorenie a spustenie controllera
// Opäť ten istý vzorec ako pri ostatných modal controlleroch
$controller = new ProjectEditModalController($projectData, $projectIndex);
$controller->display(); 