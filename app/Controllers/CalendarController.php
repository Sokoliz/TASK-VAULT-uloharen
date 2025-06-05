<?php
namespace App\Controllers;

use App\Core\Session;
use App\Models\Calendar;

// Kontrolér pre správu kalendára a udalostí používateľa
class CalendarController
{
    private $calendar;

    // Konštruktor inicializuje sedenie a kontroluje, či je používateľ prihlásený
    public function __construct()
    {
        Session::start();

        // Ak používateľ nie je prihlásený, presmeruje ho na prihlasovaciu stránku
        if (!Session::isLoggedIn()) {
            header("Location: /login");
            exit;
        }

        // Vytvorenie inštancie modelu kalendára
        $this->calendar = new Calendar();
    }

    // Základná metóda pre zobrazenie kalendára a udalostí používateľa
    public function index()
    {
        // Získanie všetkých udalostí pre aktuálne prihláseného používateľa
        $events = $this->calendar->getAllEventsByUser(Session::get('user_id'));
        // Načítanie pohľadu kalendára
        require __DIR__ . '/../Views/calendar.php';
    }

    // Metóda pre vytvorenie novej udalosti v kalendári
    public function create()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Konverzia dátumov z formulára do správneho formátu
            $startDate = $_POST['start_date'] ? date('Y-m-d H:i:s', strtotime($_POST['start_date'])) : "";
            $endDate = $_POST['end_date'] ? date('Y-m-d H:i:s', strtotime($_POST['end_date'])) : "";
            
            // Príprava dát pre novú udalosť
            $data = [
                'id_user'     => Session::get('user_id'),
                'title'       => $_POST['title'] ?? '',
                'description' => $_POST['description'] ?? '',
                'colour'      => $_POST['colour'] ?? '#000000',
                'start_date'  => $startDate, 
                'end_date'    => $endDate,
            ];

            // Uloženie novej udalosti do databázy
            $this->calendar->createEvent($data);
            // Presmerovanie späť na kalendár
            header("Location: /calendar");
        }
    }

    // Metóda pre úpravu alebo vymazanie existujúcej udalosti
    public function edit()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Získanie ID udalosti z formulára
            $id = $_POST['id_event'];
            
            // Ak bola požadovaná akcia vymazanie
            if (isset($_POST['delete'])) {
                // Vymazanie udalosti z databázy
                $this->calendar->deleteEvent($id);
            } else {
                // Konverzia dátumov z formulára do správneho formátu
                $startDate = $_POST['start_date'] ? date('Y-m-d H:i:s', strtotime($_POST['start_date'])) : "";
                $endDate = $_POST['end_date'] ? date('Y-m-d H:i:s', strtotime($_POST['end_date'])) : "";
                
                // Príprava dát pre aktualizáciu udalosti
                $data = [
                    'title'       => $_POST['title'],
                    'description' => $_POST['description'],
                    'colour'      => $_POST['colour'],
                    'start_date'  => $startDate,
                    'end_date'    => $endDate,
                ];

                // Aktualizácia udalosti v databáze
                $this->calendar->updateEvent($id, $data);
            }
            // Presmerovanie späť na kalendár
            header("Location: /calendar");
        }
    }
}