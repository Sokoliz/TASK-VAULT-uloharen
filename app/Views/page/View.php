<?php
/**
 * Base View class for rendering pages
 */
class View {
    /**
     * Page title
     */
    protected $title;
    
    /**
     * Whether the page is public (doesn't require login)
     */
    protected $isPublic;
    
    /**
     * Data to be passed to the view
     */
    protected $data;
    
    /**
     * Constructor
     * 
     * @param string $title Page title
     * @param bool $isPublic Whether the page is public
     * @param array $data Data to be passed to the view
     */
    public function __construct($title = 'Productivity Hub', $isPublic = false, $data = []) {
        $this->title = $title;
        $this->isPublic = $isPublic;
        $this->data = $data;
    }
    
    /**
     * Render the head section
     * 
     * @return string HTML for the head section
     */
    protected function renderHead() {
        ob_start();
        $title = $this->title; // For use in the included file
        $public_page = $this->isPublic; // For use in the included file
        require_once __DIR__."/../parts/header.php";
        return ob_get_clean();
    }
    
    /**
     * Render the footer section
     * 
     * @return string HTML for the footer section
     */
    protected function renderFooter() {
        return '<footer>
            <div class="row m-0 p-0">
                <div class="col-12">
                    <div class="container d-flex justify-content-center">
                        <ul class="list-unstyled list-inline text-center d-flex justify-content-center align-items-center">
                            <small><span class="ml-2">Productivity Hub © 2025 All Rights Reserved.</span></small>
                        </ul>
                    </div>
                </div>
            </div>
        </footer>';
    }
    
    /**
     * Render the JavaScript imports
     * 
     * @return string HTML for the JavaScript imports
     */
    protected function renderScripts() {
        return '<script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>';
    }
    
    /**
     * Render the navigation bar
     * 
     * @param bool $showLoginButtons Whether to show login/register buttons
     * @return string HTML for the navigation bar
     */
    protected function renderNavbar($showLoginButtons = true) {
        $html = '<header>';
        $html .= '<nav class="navbar navbar-expand-lg py-3 text-dark">';
        $html .= '<div class="menu container">';
        $html .= '<a href="index.php" class="navbar-brand">';
        $html .= '<img src="/public/img/logo1.png" width="45" alt="Kalendar" class="d-inline-block align-middle mr-2">';
        $html .= '<span class="logo_text align-middle">Productivity Hub</span>';
        $html .= '</a>';
        
        if ($showLoginButtons) {
            $html .= '<button type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation" class="navbar-toggler"><span class="navbar-toggler-icon"></span></button>';
            $html .= '<div id="navbarSupportedContent" class="collapse navbar-collapse">';
            $html .= '<ul class="navbar-nav ml-auto">';
            $html .= '<li><a href="/login" class="btn text-primary mr-2">Log in</a></li>';
            $html .= '<li><a href="/register" class="btn btn-primary">Sign Up</a></li>';
            $html .= '</ul>';
            $html .= '</div>';
        }
        
        $html .= '</div>';
        $html .= '</nav>';
        $html .= '</header>';
        
        return $html;
    }
    
    /**
     * Render the complete page
     * 
     * @return string Complete HTML for the page
     */
    public function render() {
        $html = '<!DOCTYPE html>';
        $html .= '<html lang="en">';
        
        // Head section
        $html .= '<head>';
        $html .= $this->renderHead();
        $html .= '</head>';
        
        // Body section
        $html .= '<body>';
        
        // Content to be implemented by child classes
        $html .= $this->renderContent();
        
        // Footer section
        $html .= $this->renderFooter();
        
        // JavaScript imports
        $html .= $this->renderScripts();
        
        $html .= '</body>';
        $html .= '</html>';
        
        return $html;
    }
    
    /**
     * Render the content section (to be implemented by child classes)
     * 
     * @return string HTML for the content section
     */
    protected function renderContent() {
        return '';
    }
    
    /**
     * Display the page
     */
    public function display() {
        echo $this->render();
    }
    
    /**
     * Get a value from the data array
     * 
     * @param string $key Key to get
     * @param mixed $default Default value if key doesn't exist
     * @return mixed Value from the data array
     */
    protected function getData($key, $default = null) {
        return isset($this->data[$key]) ? $this->data[$key] : $default;
    }
}
?>