<?php
require_once('View.php');

// Trieda ContentView pre zobrazenie hlavnej stránky s navigačnými kartami
// Toto je stránka, ktorú vidí používateľ po prihlásení, taký dashboard
class ContentView extends View {
    
    // Konštruktor - inicializuje základné vlastnosti
    public function __construct($data = []) {
        parent::__construct('Content', false, $data);
    }
    
    // Renderuje navigačné karty
    // Tieto karty slúžia na presmerovanie na hlavné sekcie aplikácie
    protected function renderNavigationCards() {
        $html = '<div class="container mt-0 mb-4">';
        $html .= '<div class="row d-flex m-2 mt-0">';
        
        // Karta pre dnešné úlohy
        // Používateľ si tu môže pozrieť, čo má dnes na pláne
        $html .= '<div class="col-sm-4 col-md-4">';
        $html .= '<a class="card my-card text-dark" href="/today">';
        $html .= '<div class="card-img-container">';
        $html .= '<img src="/public/img/today.jpg" alt="Today\'s Tasks" class="card-img-top" loading="lazy">';
        $html .= '</div>';
        $html .= '<div class="card-body d-flex justify-content-center">';
        $html .= '<h3 class="card-title">TODAY</h3>';
        $html .= '</div>';
        $html .= '</a>';
        $html .= '</div>';
        
        // Karta pre projekty
        // Tu môže používateľ spravovať svoje projekty a úlohy
        $html .= '<div class="col-sm-4 col-md-4">';
        $html .= '<a class="card my-card text-dark" href="/projects">';
        $html .= '<div class="card-img-container">';
        $html .= '<img src="/public/img/projects.jpg" alt="Projects Overview" class="card-img-top" loading="lazy">';
        $html .= '</div>';
        $html .= '<div class="card-body d-flex justify-content-center">';
        $html .= '<h3 class="card-title">PROJECTS</h3>';
        $html .= '</div>';
        $html .= '</a>';
        $html .= '</div>';
        
        // Karta pre kalendár
        // Pre zobrazenie a plánovanie udalostí v kalendári
        $html .= '<div class="col-sm-4 col-md-4">';
        $html .= '<a class="card my-card text-dark" href="/calendar">';
        $html .= '<div class="card-img-container">';
        $html .= '<img src="/public/img/calender.jpg" alt="Calendar View" class="card-img-top" loading="lazy">';
        $html .= '</div>';
        $html .= '<div class="card-body d-flex justify-content-center">';
        $html .= '<h3 class="card-title">CALENDAR</h3>';
        $html .= '</div>';
        $html .= '</a>';
        $html .= '</div>';
        
        $html .= '</div>';
        $html .= '</div>';
        
        return $html;
    }
    
    // Renderuje dodatočné skripty špecifické pre content stránku
    // Tu pridávame všetky potrebné JavaScripty pre interaktivitu
    protected function renderAdditionalScripts() {
        $html = parent::renderScripts();
        $html .= '<script src="/public/js/theme.js"></script>';
        $html .= '<script src="/public/js/dynamic-theme.js"></script>';
        $html .= '<link href="/public/css/dynamic-theme.css" rel="stylesheet" />';
        
        // Používame externý skript pre hodiny namiesto inline
        $html .= '<script src="/public/js/clock.js"></script>';
        
        // Pridávame inicializáciu tooltip do dynamic-theme.js
        
        return $html;
    }
    
    // Renderuje sekciu obsahu
    // Tu poskladáme celú stránku s navigačnými kartami
    protected function renderContent() {
        require_once __DIR__.'/../parts/navbar.php';
        $navbar = new Navbar(false, false, true, 'user_info');
        $html = $navbar->render();
        $html .= $this->renderNavigationCards();
        return $html;
    }
    
    // Renderuje kompletnú stránku
    // Prepisujeme rodičovskú metódu, aby sme pridali špeciálne skripty pre content
    public function render() {
        $html = '<!DOCTYPE html>';
        $html .= '<html lang="en">';
        
        // Sekcia head
        $html .= '<head>';
        $html .= $this->renderHead();
        $html .= '</head>';
        
        // Sekcia body s triedou pozadia
        $html .= '<body class="bg">';
        
        // Obsah
        $html .= $this->renderContent();
        
        // Skripty s dodatočnými skriptami špecifickými pre content
        $html .= $this->renderAdditionalScripts();
        
        $html .= '</body>';
        $html .= '</html>';
        
        return $html;
    }
}
?>