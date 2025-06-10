<?php
namespace App\Views\Today;

/**
 * TodayPageRenderer class
 * 
 * This class serves as an object-oriented wrapper for the today view
 * to ensure proper OOP implementation throughout the application.
 */
class TodayPageRenderer
{
    /**
     * Data to be passed to the view
     * 
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
        // Load the required view classes
        require_once __DIR__ . '/../page/View.php';
        require_once __DIR__ . '/../page/today.view.php';
        
        // Create an instance of the TodayView class and render it
        $todayView = new \TodayView($this->viewData);
        return $todayView->render();
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