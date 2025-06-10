<?php
namespace App\Controllers;

use App\Core\Session;
use App\Models\Calendar;
use App\Views\Calendar\CalendarView;
use App\Views\Calendar\Calendar2View;

class CalendarController
{
    private $calendar;

    public function __construct()
    {
        if (!Session::isLoggedIn()) {
            header("Location: /login");
            exit;
        }

        $this->calendar = new Calendar();
    }

    public function index()
    {
        // Získanie udalostí pre aktuálneho používateľa
        $events = $this->calendar->getAllEventsByUser(Session::get('user_id'));
        
        // Predanie dát do premennej $viewData pre view
        $viewData = [
            'events' => $events
        ];
        
        // Používame OOP triedu na vykreslenie view
        $calendarView = new CalendarView($viewData);
        
        // Inicializácia JavaScript pre kalendár pomocou Calendar2View
        $calendar2View = new Calendar2View($events);
        
        // Vykreslenie základného view a potom kalendár skripty
        echo $calendarView->render();
        echo $calendar2View->render();
    }

    public function create()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $startDate = $_POST['start_date'] ? date('Y-m-d H:i:s', strtotime($_POST['start_date'])) : "";
            $endDate = $_POST['end_date'] ? date('Y-m-d H:i:s', strtotime($_POST['end_date'])) : "";
            
            // Validation is now handled client-side
            
            $data = [
                'id_user'     => Session::get('user_id'),
                'title'       => $_POST['title'] ?? '',
                'description' => $_POST['description'] ?? '',
                'colour'      => $_POST['colour'],
                'start_date'  => $startDate, 
                'end_date'    => $endDate,
            ];

            
            $this->calendar->createEvent($data);
            header("Location: /calendar");
        }
    }

    public function edit()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Debug log POST premenných
            error_log('CalendarController.edit called with POST data: ' . print_r($_POST, true));
            
            if (!isset($_POST['id_event']) || empty($_POST['id_event'])) {
                error_log('Error: Missing or empty id_event in POST data');
                
                // Skontrolujeme, či ide o pokus o vymazanie udalosti
                if (isset($_POST['delete'])) {
                    error_log('Delete action was requested, but id_event is missing');
                    // Presmerujeme späť do kalendára s flash message
                    $_SESSION['flash_message'] = 'Chyba: Nemožno vymazať udalosť, pretože chýba ID.';
                }
                
                header("Location: /calendar");
                exit;
            }
            
            $id = $_POST['id_event'];
            error_log('Processing event with ID: ' . $id);
            
            // Skontrolujeme, či ID nie je dočasné (generované)
            if (strpos($id, 'temp_') === 0) {
                error_log('Temporary ID detected, redirecting to calendar');
                $_SESSION['flash_message'] = 'Túto udalosť nemožno upraviť ani vymazať. Prosím, vytvorte novú udalosť.';
                header("Location: /calendar");
                exit;
            }
            
            if (isset($_POST['delete'])) {
                error_log('Delete action requested for event ID: ' . $id);
                $result = $this->calendar->deleteEvent($id);
                error_log('Delete result: ' . ($result ? 'success' : 'failure'));
                
                if (!$result) {
                    $_SESSION['flash_message'] = 'Chyba pri mazaní udalosti.';
                }
            } else {
                $startDate = $_POST['start_date'] ? date('Y-m-d H:i:s', strtotime($_POST['start_date'])) : "";
                $endDate = $_POST['end_date'] ? date('Y-m-d H:i:s', strtotime($_POST['end_date'])) : "";
                
                // Validation is now handled client-side
                
                $data = [
                    'title'       => $_POST['title'],
                    'description' => $_POST['description'],
                    'colour'      => $_POST['colour'],
                    'start_date'  => $startDate,
                    'end_date'    => $endDate,
                ];

                $result = $this->calendar->updateEvent($id, $data);
                error_log('Update result: ' . ($result ? 'success' : 'failure'));
            }
            header("Location: /calendar");
        }
    }
}