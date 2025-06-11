<?php
namespace App\Controllers;

use App\Views\Events\Modals\TaskAddModal;
require_once(__DIR__ . '/../Views/events/modals/Modal.php');
require_once(__DIR__ . '/../Views/events/modals/TaskAddModal.php');

/**
 * Controller pre zobrazenie modálneho okna na pridanie úlohy
 */
class TaskAddModalController {
    /**
     * ID projektu pre úlohu
     */
    private $project_id;
    
    /**
     * Konštruktor
     * 
     * @param int $project_id ID projektu pre úlohu
     */
    public function __construct($project_id) {
        $this->project_id = $project_id;
    }
    
    /**
     * Renderovanie modálneho okna
     * 
     * @return string HTML pre modálne okno
     */
    public function render() {
        // Vytvorenie a renderovanie modálneho okna
        // Tu posielam project_id, aby úloha mohla byť priradená konkrétnemu projektu
        $modal = new TaskAddModal($this->project_id);
        return $modal->render('Create');
    }
    
    /**
     * Zobrazenie modálneho okna
     */
    public function display() {
        echo $this->render();
    }
}

// Získanie ID projektu z parametra URL
// Toto je lepšie riešenie ako pri predchádzajúcich controlleroch - používa GET parameter
$id_project_for_task = isset($_GET['idProject']) ? $_GET['idProject'] : 0;

// Vytvorenie a spustenie controllera
// Teraz potrebujeme aj ID projektu pre správne vytvorenie úlohy
$controller = new TaskAddModalController($id_project_for_task);
$controller->display(); 