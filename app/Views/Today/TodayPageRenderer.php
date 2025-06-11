<?php
namespace App\Views\Today;

// Trieda TodayPageRenderer - slúži ako obal pre pohľad today
// celé to robíme cez OOP aby to bolo pekne konzistentné s ostatnými časťami aplikácie
class TodayPageRenderer
{
    // Dáta, ktoré pošleme do pohľadu - jednoduché pole s vecami čo potrebujeme zobraziť
    private $viewData;
    
    // Konštruktor - uloží si dáta na neskoršie použitie
    // $data - pole s dátami pre pohľad, defaultne prázdne
    public function __construct($data = [])
    {
        $this->viewData = $data;
    }
    
    // Vykreslí pohľad a vráti HTML
    // toto je tá hlavná vec, ktorú tento renderer robí
    public function render()
    {
        // Načíta potrebné triedy pohľadov - musíme ich mať predtým ako ich použijeme
        require_once __DIR__ . '/../page/View.php';
        require_once __DIR__ . '/../page/today.view.php';
        
        // Vytvorí inštanciu triedy TodayView a vykreslí ju
        // delegujeme to na špecializovanú triedu pre today pohľad
        $todayView = new \TodayView($this->viewData);
        return $todayView->render();
    }
    
    // Statická metóda pre rýchle vytvorenie a vykreslenie pohľadu
    // $data - pole s dátami pre pohľad
    // výhoda je, že nemusíme volať zvlášť new a render, stačí jeden riadok kódu
    public static function display($data = [])
    {
        $renderer = new self($data);
        return $renderer->render();
    }
} 