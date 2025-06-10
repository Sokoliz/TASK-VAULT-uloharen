<?php
namespace App\Views\Auth;

/**
 * RegisterView class
 * 
 * Object-oriented wrapper for the register view
 */
class RegisterView 
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
        require_once __DIR__ . '/../page/register.view.php';
        
        // Create an instance of the RegisterView class and render it
        $registerView = new \RegisterView($this->viewData);
        return $registerView->render();
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