<?php
namespace App\Views\Content;

/**
 * ContentView trieda
 * 
 * Objektovo-orientovaný wrapper pre pohľad obsahu
 */
class ContentView 
{
    // Dáta, ktoré budú poslané do view
    private $viewData;
    
    
    public function __construct($data = [])
    {
        // Uloženie dát do premennej triedy
        // Tieto dáta sa neskôr použijú pri renderovaní
        $this->viewData = $data;
    }
    
    
    public function render()
    {
        // Načítanie požadovanej view triedy
        // Musím použiť absolútnu cestu, aby to fungovalo správne
        require_once __DIR__ . '/../page/content.view.php';
        
        // Vytvorenie inštancie ContentView a jej renderovanie
        // Tu mám takú malú rekurziu - volám triedu s rovnakým názvom, ale z iného namespace
        $contentView = new \ContentView($this->viewData);
        return $contentView->render();
    }
    
    
    public static function display($data = [])
    {
        // Statická factory metóda pre jednoduchšie použitie
        // Takto môžem priamo volať ContentView::display() bez vytvárania inštancie
        $renderer = new self($data);
        return $renderer->render();
    }
} 