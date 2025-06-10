<?php
namespace App\Views\Auth;

/**
 * RegisterErrorView class
 * 
 * Object-oriented wrapper for the register error view
 */
class RegisterErrorView 
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
        // Set default error message if not provided
        $errorMessage = isset($this->viewData['errorMessage']) 
            ? $this->viewData['errorMessage'] 
            : 'Missing username or password';
            
        $html = '<!DOCTYPE html>';
        $html .= '<html lang="en">';
        
        // Head section
        $html .= '<head>';
        $html .= '<meta charset="UTF-8">';
        $html .= '<meta name="viewport" content="width=device-width, initial-scale=1.0">';
        $html .= '<title>Error</title>';
        $html .= '<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css">';
        $html .= '<link rel="stylesheet" href="/public/css/error.css">';
        $html .= '</head>';
        
        // Body section
        $html .= '<body>';
        $html .= '<div class="error-box">';
        $html .= '<div class="error-title">Error</div>';
        $html .= '<div class="error-message">' . $errorMessage . '</div>';
        $html .= '<a href="/register" class="btn btn-primary btn-back">Go Back</a>';
        $html .= '</div>';
        $html .= '</body>';
        $html .= '</html>';
        
        return $html;
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