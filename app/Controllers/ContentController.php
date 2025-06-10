<?php
namespace App\Controllers;

use App\Core\Session;
use App\Views\Content\ContentView;

class ContentController
{
    public function index()
    {
        Session::start();
        
        error_log("ContentController - checking if user is logged in");
        if (!Session::isLoggedIn()) {
            error_log("User not logged in, redirecting to /login");
            header("Location: /login");
            exit;
        }
        
        error_log("User is logged in, showing content page");
        
        // Vytvorenie a zobrazenie view pomocou OOP
        $contentView = new ContentView();
        echo $contentView->render();
    }
}