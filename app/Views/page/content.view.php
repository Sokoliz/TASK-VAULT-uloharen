<?php
require_once('View.php');

/**
 * ContentView class for rendering the main content page with navigation cards
 */
class ContentView extends View {
    /**
     * Constructor
     * 
     * @param array $data Data to be passed to the view
     */
    public function __construct($data = []) {
        parent::__construct('Content', false, $data);
    }
    
    /**
     * Render the navigation cards
     * 
     * @return string HTML for the navigation cards
     */
    protected function renderNavigationCards() {
        $html = '<div class="container mt-0 mb-4">';
        $html .= '<div class="row d-flex m-2 mt-0">';
        
        // Today card
        $html .= '<div class="col-sm-4 col-md-4">';
        $html .= '<a class="card my-card text-dark" href="/today">';
        $html .= '<div class="card-img-container">';
        $html .= '<img src="/public/img/today.jpg" alt="Today\'s Tasks" class="card-img-top" loading="lazy">';
        $html .= '</div>';
        $html .= '<div class="card-body d-flex justify-content-center">';
        $html .= '<h3 class="card-title">TODAY</h3>';
        $html .= '</div>';
        $html .= '</a>';
        $html .= '</div>';
        
        // Projects card
        $html .= '<div class="col-sm-4 col-md-4">';
        $html .= '<a class="card my-card text-dark" href="/projects">';
        $html .= '<div class="card-img-container">';
        $html .= '<img src="/public/img/projects.jpg" alt="Projects Overview" class="card-img-top" loading="lazy">';
        $html .= '</div>';
        $html .= '<div class="card-body d-flex justify-content-center">';
        $html .= '<h3 class="card-title">PROJECTS</h3>';
        $html .= '</div>';
        $html .= '</a>';
        $html .= '</div>';
        
        // Calendar card
        $html .= '<div class="col-sm-4 col-md-4">';
        $html .= '<a class="card my-card text-dark" href="/calendar">';
        $html .= '<div class="card-img-container">';
        $html .= '<img src="/public/img/calender.jpg" alt="Calendar View" class="card-img-top" loading="lazy">';
        $html .= '</div>';
        $html .= '<div class="card-body d-flex justify-content-center">';
        $html .= '<h3 class="card-title">CALENDAR</h3>';
        $html .= '</div>';
        $html .= '</a>';
        $html .= '</div>';
        
        $html .= '</div>';
        $html .= '</div>';
        
        return $html;
    }
    
    /**
     * Render additional scripts specific to content page
     * 
     * @return string HTML for the additional scripts
     */
    protected function renderAdditionalScripts() {
        $html = parent::renderScripts();
        $html .= '<script src="/public/js/theme.js"></script>';
        $html .= '<script src="/public/js/dynamic-theme.js"></script>';
        $html .= '<link href="/public/css/dynamic-theme.css" rel="stylesheet" />';
        
        // Use external clock script instead of inline
        $html .= '<script src="/public/js/clock.js"></script>';
        
        // Add tooltip initialization to dynamic-theme.js
        
        return $html;
    }
    
    /**
     * Render the content section
     * 
     * @return string HTML for the content section
     */
    protected function renderContent() {
        // Nastavenie premenných pre navbar.php
        $isPublic = false;
        $showHomeButton = false; // Netreba Home tlačidlo, keďže sme na home stránke
        $showThemeSwitch = true;
        $navbarType = 'user_info';
        
        // Include navbar.php
        ob_start();
        include_once __DIR__.'/../parts/navbar.php';
        $html = ob_get_clean();
        
        $html .= $this->renderNavigationCards();
        
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
        
        // Body section with background class
        $html .= '<body class="bg">';
        
        // Content
        $html .= $this->renderContent();
        
        // Scripts with additional content-specific scripts
        $html .= $this->renderAdditionalScripts();
        
        $html .= '</body>';
        $html .= '</html>';
        
        return $html;
    }
}
?>