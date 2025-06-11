<?php
namespace App\Controllers;

use App\Views\Events\Modals\EventAddModal;
require_once(__DIR__ . '/../Views/events/modals/Modal.php');
require_once(__DIR__ . '/../Views/events/modals/EventAddModal.php');

/**
 * Controller pre zobrazenie modálneho okna na pridanie udalosti
 */
class EventAddModalController {
    
    public function render() {
        // Vytvorenie a renderovanie modálneho okna
        // Toto je jednoduchý prípad, nepotrebujem žiadne dáta z modelu
        $modal = new EventAddModal();
        return $modal->render('Add');
    }
    
    /**
     * Zobrazenie modálneho okna
     */
    public function display() {
        echo $this->render();
    }
}

// Vytvorenie a spustenie controllera
// Opäť by sa toto dalo lepšie riešiť cez router
$controller = new EventAddModalController();
$controller->display(); 