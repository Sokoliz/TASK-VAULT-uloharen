<?php
namespace App\Views\Calendar;

use App\Core\Session;

/**
 * Calendar2View class
 * 
 * Riadi zobrazenie kalendára s dynamickým načítaním udalostí
 */
class Calendar2View 
{
    /**
     * Dáta udalostí vo formáte JSON
     */
    private $eventsJson;
    
    /**
     * Konštruktor
     * 
     * @param array $events Udalosti na zobrazenie
     */
    public function __construct($events = []) 
    {
        // Ochrana stránky - neprihlásených presmerujeme preč
        if (!Session::isLoggedIn()) {
            header('Location: /');
            die();
        }

        $this->eventsJson = $this->prepareEventsJson($events);
    }
    
    /**
     * Pripraví dáta udalostí vo formáte JSON
     * 
     * @param array $events Udalosti na konverziu
     * @return string JSON reprezentácia udalostí
     */
    protected function prepareEventsJson($events) 
    {
        // Pripravíme dáta udalostí vo formáte JSON
        $eventsArray = [];
        
        foreach ($events as $event) {
            // Debug log každej udalosti
            error_log('Processing event: ' . json_encode($event));
            
            $start = explode(" ", $event['start_date']);
            $end = explode(" ", $event['end_date']);
            
            if ($start[1] == '00:00:00') {
                $start = $start[0];
            } else {
                $start = $event['start_date'];
            }
            
            if ($end[1] == '00:00:00') {
                $end = $end[0];
            } else {
                $end = $event['end_date'];
            }

            // Zabezpečíme, že každá udalosť má platné ID - ak nie je k dispozícii, vytvoríme alternatívne ID
            $eventId = isset($event['id_event']) && !empty($event['id_event']) ? $event['id_event'] : null;
            
            // Ak stále nemáme ID, vygenerujeme random ID
            if ($eventId === null) {
                // Pre udalosti bez ID vygenerujeme dočasné ID
                $eventId = 'temp_' . mt_rand(1000, 9999);
                error_log('Generated temporary ID for event: ' . $eventId);
            }

            $eventsArray[] = [
                'id' => $eventId,
                'title' => isset($event['title']) ? $event['title'] : '',
                'description' => isset($event['description']) ? $event['description'] : '',
                'start' => $start,
                'end' => $end,
                'ends' => $end,
                'color' => isset($event['colour']) ? $event['colour'] : '#000000'
            ];
        }

        // Konvertujeme PHP pole na JSON
        return json_encode($eventsArray);
    }
    
    /**
     * Vygeneruje potrebné JavaScript premenné pre kalendár
     * 
     * @return string HTML a JavaScript pre inicializáciu kalendára
     */
    protected function generateCalendarInitialization() 
    {
        $html = '<script>' . PHP_EOL;
        $html .= '    // Globálne premenné pre kalendár' . PHP_EOL;
        $html .= '    const currentDate = "' . date('Y-m-d') . '";' . PHP_EOL;
        $html .= '    const calendarEvents = ' . $this->eventsJson . ';' . PHP_EOL;
        $html .= '</script>' . PHP_EOL;
        
        // Vloženie externých JavaScript súborov
        $html .= '<script src="/public/js/calendar.js"></script>' . PHP_EOL;
        $html .= '<script src="/public/js/calendar-init.js"></script>' . PHP_EOL;
        
        return $html;
    }
    
    /**
     * Vykresľuje stránku s kalendárom
     * 
     * @return string HTML kód pre kalendár
     */
    public function render() 
    {
        return $this->generateCalendarInitialization();
    }
} 