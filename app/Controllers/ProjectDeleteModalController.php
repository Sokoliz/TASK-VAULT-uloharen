<?php
namespace App\Controllers;

use App\Views\Events\Modals\ProjectDeleteModal;
require_once(__DIR__ . '/../Views/events/modals/Modal.php');
require_once(__DIR__ . '/../Views/events/modals/ProjectDeleteModal.php');

/**
 * Controller pre zobrazenie modálneho okna na vymazanie projektu
 */
class ProjectDeleteModalController {
    
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
        // Posielam aj dáta projektu, aby sa zobrazili detaily mazaného projektu
        $modal = new ProjectDeleteModal($this->projectData, $this->projectIndex);
        return $modal->render('Delete');
    }
    
    /**
     * Zobrazenie modálneho okna
     */
    public function display() {
        echo $this->render();
    }
}

// Získanie dát a indexu projektu z premenných v scope
// V reálnej aplikácii by tieto údaje prišli z databázy alebo z GET parametrov
// Nie som si istý, či je toto najlepší spôsob, ale funguje to
$projectData = isset($p) ? $p : [];
$projectIndex = isset($i) ? $i : 0;

// Vytvorenie a spustenie controllera
// Modálne okno na mazanie potrebuje vedieť, ktorý projekt sa má vymazať
$controller = new ProjectDeleteModalController($projectData, $projectIndex);
$controller->display(); 