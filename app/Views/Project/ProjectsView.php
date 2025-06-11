<?php
namespace App\Views\Project;

// Trieda ProjectsView - obaľuje pohľad pre projekty 
// používa OOP prístup aby sa to krajšie integrovalo do zvyšku kódu
class ProjectsView 
{
    // Dáta, ktoré posunieme do pohľadu - taká malá krabička s vecami
    private $viewData;
    
    // Konštruktor - len si uloží dáta, nič extra
    // $data - pole s dátami pre pohľad, defaultne prázdne
    public function __construct($data = [])
    {
        $this->viewData = $data;
    }
    
    // Vykreslí pohľad a vráti HTML
    // toto je hlavná metóda, ktorú budeme volať z kontroléra
    public function render()
    {
        // Načíta triedu pohľadu - tá je v inom súbore
        require_once __DIR__ . '/../page/projects.view.php';
        
        // Vytvorí inštanciu triedy projekty a vykreslí ju
        // toto je fajn, lebo delegujeme vykresľovanie na špecializovanú triedu
        $projectsView = new \ProjectsView($this->viewData);
        return $projectsView->render();
    }
    
    // Statická metóda pre jednoduchšie použitie - vytvorí a vykreslí pohľad v jednom kroku
    // $data - pole s dátami pre pohľad
    // praktické keď nechceme vytvárať inštanciu manuálne
    public static function display($data = [])
    {
        $renderer = new self($data);
        return $renderer->render();
    }
} 