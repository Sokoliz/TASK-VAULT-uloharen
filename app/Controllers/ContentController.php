<?php
namespace App\Controllers;

use App\Core\Session;
use App\Views\Content\ContentView;

class ContentController
{
    public function index()
    {
        Session::start();
        
        // Logovanie kontroly prihlásenia používateľa - toto mi pomáha pri debugovaní
        error_log("ContentController - checking if user is logged in");
        if (!Session::isLoggedIn()) {
            // Používateľ nie je prihlásený, presmerujem ho na login stránku
            error_log("User not logged in, redirecting to /login");
            header("Location: /login");
            exit;
        }
        
        // Používateľ je prihlásený, môžem mu zobraziť obsah
        error_log("User is logged in, showing content page");
        
        // Vytvorenie a zobrazenie view pomocou OOP
        // Toto je lepšie ako vkladanie HTML priamo tu v controlleri
        $contentView = new ContentView();
        echo $contentView->render();
    }
}