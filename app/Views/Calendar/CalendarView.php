<?php
namespace App\Views\Calendar;

use App\Core\Session;

/**
 * CalendarView trieda
 * 
 * Objektovo-orientovaný wrapper pre pohľad kalendára
 */
class CalendarView 
{
    // Dáta, ktoré budú poslané do view
    private $viewData;
    
    
    public function __construct($data = [])
    {
        // Uloženie dát do atribútu, aby boli dostupné pri renderovaní
        $this->viewData = $data;
    }
    
    
    public function render()
    {
        // Ochrana - ak nemáme používateľa, presmerujeme ho
        // Toto je základné bezpečnostné opatrenie proti neoprávnenému prístupu
        if (!Session::isLoggedIn()) {
            header('Location: /login');
            exit;
        }
        
        // Extrahujeme udalosti pre kalendár ak existujú
        // Používam ?? operátor na ošetrenie prípadu, keď kľúč neexistuje
        $events = $this->viewData['events'] ?? [];
        
        // Načítanie požadovanej view triedy
        // Tento súbor obsahuje konkrétnu implementáciu kalendára
        require_once __DIR__ . '/../page/calendar.view.php';
        
        // Vytvorenie inštancie CalendarView a jej renderovanie
        // Presúvam dáta do view, aby mohlo pracovať s premennými
        $calendarView = new \CalendarView($this->viewData);
        return $calendarView->render();
    }
    
    
    public static function display($data = [])
    {
        // Statická metóda pre rýchle vytvorenie a zobrazenie kalendára
        // Toto je pohodlnejšie ako vytvárať inštanciu a volať render zvlášť
        $renderer = new self($data);
        return $renderer->render();
    }
} 