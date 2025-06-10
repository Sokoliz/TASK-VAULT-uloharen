<?php
namespace App\Views\Project;

/**
 * ProjectsView class
 * 
 * Object-oriented wrapper for the projects view
 */
class ProjectsView 
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
        require_once __DIR__ . '/../page/projects.view.php';
        
        // Create an instance of the projects view class and render it
        $projectsView = new \ProjectsView($this->viewData);
        return $projectsView->render();
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