<?php
namespace App\Views\Auth;

/**
 * LoginView trieda
 * 
 * Objektovo-orientovaný wrapper pre login pohľad
 */
class LoginView 
{
    // Dáta, ktoré budú poslané do view
    private $viewData;
    
    
    public function __construct($data = [])
    {
        // Uloženie dát pre použitie pri renderovaní
        // Môžu tu byť napr. chybové správy alebo predvyplnené údaje
        $this->viewData = $data;
    }
    
    /**
     * Render the view
     * 
     * @return string The rendered HTML
     */
    public function render()
    {
        // Načítanie požadovanej view triedy
        // Používam relatívnu cestu, aby to fungovalo všade
        require_once __DIR__ . '/../page/login.view.php';
        
        // Vytvorenie inštancie LoginView triedy a jej renderovanie
        // Posielam dáta do view, aby mohlo pracovať s premennými
        $loginView = new \LoginView($this->viewData);
        return $loginView->render();
    }
    
    
    public static function display($data = [])
    {
        // Statická factory metóda pre vytvorenie a renderovanie view
        // Toto zjednodušuje použitie, keď chcem rýchlo zobraziť login
        $renderer = new self($data);
        return $renderer->render();
    }
} 