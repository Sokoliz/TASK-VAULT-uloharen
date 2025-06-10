<?php
namespace App\Views\Content;

/**
 * ContentView class
 * 
 * Object-oriented wrapper for the content view
 */
class ContentView 
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
        // Load the required view class
        require_once __DIR__ . '/../page/content.view.php';
        
        // Create an instance of the ContentView class and render it
        $contentView = new \ContentView($this->viewData);
        return $contentView->render();
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