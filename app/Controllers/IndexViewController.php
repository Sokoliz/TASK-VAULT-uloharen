<?php
namespace App\Controllers;

use App\Core\Session;

/**
 * IndexViewController
 * 
 * Riadiaci kontrolér pre úvodnú stránku aplikácie
 */
class IndexViewController 
{
    /**
     * Overí, či je používateľ prihlásený a presmeruje ho na príslušnú stránku
     */
    public function processRequest() 
    {
        // Inicializácia session
        Session::start();
        
        // Kontrola, či je používateľ prihlásený
        if(Session::isLoggedIn()) {
            // Ak je používateľ prihlásený, presmerujeme ho na stránku s obsahom
            header('Location: /content');
            die();
        } else {
            // Ak používateľ nie je prihlásený, zobrazíme hlavnú stránku
            require_once __DIR__.'/../Views/page/main.view.php';
        }
    }
} 