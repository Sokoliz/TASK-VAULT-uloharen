<?php
namespace App\Controllers;

use App\Core\Session;

// Kontrolér pre správu obsahu, ktorý je prístupný len prihláseným používateľom
class ContentController
{
    // Základná metóda pre zobrazenie obsahu stránky
    public function index()
    {
        // Spustenie sedenia pre prístup k údajom o prihlásení
        Session::start();

        // Kontrola, či je používateľ prihlásený, ak nie, presmeruje ho na login
        if (!Session::isLoggedIn()) {
            header("Location: /login");
            exit;
        }

        // Načítanie pohľadu s obsahom pre prihláseného používateľa
        require __DIR__ . '/../Views/content.php';
    }
}