<?php
namespace App\Views\Calendar;

use App\Core\Session;

/**
 * CalendarView class
 * 
 * Object-oriented wrapper for the calendar view
 */
class CalendarView 
{
    /**
     * Data to be passed to the view
     * @var array
     */
    private $viewData;
    
    /**
     * Constructor
     * 
     * @param array $data Data to be passed to the view
     */
    public function __construct($data = [])
    {
        $this->viewData = $data;
    }
    
    /**
     * Render the view
     * 
     * @return string The rendered HTML
     */
    public function render()
    {
        // Ochrana - ak nemáme používateľa, presmerujeme ho
        if (!Session::isLoggedIn()) {
            header('Location: /login');
            exit;
        }
        
        // Extrahujeme udalosti pre kalendár ak existujú
        $events = $this->viewData['events'] ?? [];
        
        // Load the required view class
        require_once __DIR__ . '/../page/calendar.view.php';
        
        // Create an instance of the CalendarView class and render it
        $calendarView = new \CalendarView($this->viewData);
        return $calendarView->render();
    }
    
    /**
     * Static factory method to create and render the view
     * 
     * @param array $data Data to be passed to the view
     * @return string The rendered HTML
     */
    public static function display($data = [])
    {
        $renderer = new self($data);
        return $renderer->render();
    }
} 