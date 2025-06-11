<?php
namespace App\Views\Auth;

/**
 * RegisterView trieda
 * 
 * Objektovo-orientovaný wrapper pre registračný pohľad
 */
class RegisterView 
{
    // Dáta, ktoré budú poslané do view
    private $viewData;
    
    
    public function __construct($data = [])
    {
        // Uloženie dát do atribútu triedy
        // Tieto dáta môžu obsahovať chybové správy alebo údaje z formulára
        $this->viewData = $data;
    }
    
    
    public function render()
    {
        // Načítanie súboru s konkrétnou implementáciou view
        // Toto je trochu zvláštne riešenie, ale funguje
        require_once __DIR__ . '/../page/register.view.php';
        
        // Vytvorenie inštancie a renderovanie
        // V podstate tu idem z jednej triedy do druhej
        $registerView = new \RegisterView($this->viewData);
        return $registerView->render();
    }
    
    
    public static function display($data = [])
    {
        // Statická metóda pre jednoduchšie použitie
        // Takto nemusím vytvárať inštanciu explicitne
        $renderer = new self($data);
        return $renderer->render();
    }
} 