<?php
namespace App\Controllers;

use App\Views\Events\Modals\ProjectAddModal;
require_once(__DIR__ . '/../Views/events/modals/Modal.php');
require_once(__DIR__ . '/../Views/events/modals/ProjectAddModal.php');

/**
 * Controller pre zobrazenie modálneho okna na pridanie projektu
 */
class ProjectAddModalController {
    /**
     * Renderovanie modálneho okna
     * 
     * @return string HTML pre modálne okno
     */
    public function render() {
        // Vytvorenie a renderovanie modálneho okna
        // Na názve tlačidla záleží, preto ho posielam ako parameter 'Create'
        $modal = new ProjectAddModal();
        $modalHtml = $modal->render('Create');

        // Pridanie odkazu na externý JavaScript súbor pre validáciu formulára
        // Validácia na strane klienta šetrí dáta a čas servera
        $modalHtml .= '<script src="/public/js/project-form-validation.js"></script>';

        return $modalHtml;
    }
    
    /**
     * Zobrazenie modálneho okna
     */
    public function display() {
        echo $this->render();
    }
}

// Vytvorenie a spustenie controllera
// Tieto modálne okná sú jednoduché, preto nie je potrebný žiadny parameter
$controller = new ProjectAddModalController();
$controller->display(); 