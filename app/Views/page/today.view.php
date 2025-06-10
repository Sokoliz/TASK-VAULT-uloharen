<?php
require_once('View.php');

/**
 * TodayView class for rendering the today's events and tasks page
 */
class TodayView extends View {
    /**
     * Constructor
     * 
     * @param array $data Data to be passed to the view
     */
    public function __construct($data = []) {
        parent::__construct('Today\'s Activities', false, $data);
    }
    
    /**
     * Render the head section with additional styles and scripts
     * 
     * @return string HTML for the head section
     */
    protected function renderHead() {
        $html = parent::renderHead();
        $html .= '<link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.standalone.min.css" rel="stylesheet" type="text/css" />';
        $html .= '<link href="/public/css/theme-icons.css" rel="stylesheet" />';
        $html .= '<link href="/public/css/dynamic-theme.css" rel="stylesheet" />';
        $html .= '<script src="/public/js/theme.js"></script>';
        $html .= '<script src="/public/js/dynamic-theme.js"></script>';
        return $html;
    }
    
    /**
     * Render the navigation bar with theme switch
     * 
     * @return string HTML for the navbar
     */
    protected function renderNavbarWithThemeSwitch() {
        return '<header class="m-0 p-0">
	<nav class="navbar navbar-expand-lg pt-3 text-dark">
		<div class="menu container">
			<a href="index.php" class="navbar-brand">
			<img src="/public/img/logo1.png" width="45" alt="Kalendar" class="d-inline-block align-middle mr-2">
			<span class="logo_text align-middle">Productivity Hub</span>
			</a>
            
			<button type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation" class="navbar-toggler"><span class="navbar-toggler-icon"></span></button>
			<div id="navbarSupportedContent" class="collapse navbar-collapse">
				<ul class="navbar-nav ml-auto">
                    <li><a href="/content" class="btn text-primary mr-2"><i class="fas fa-home pr-2"></i>Home</a></li>	
					<li><a href="/logout" class="btn text-primary mr-2">Log out</a></li>
                    <!-- Prepínač tmavého režimu -->
					<li class="nav-item theme-switch-wrapper">
                        <span class="mode-text btn text-primary">Mode</span>
                        <i class="fas fa-moon mode-icon fa-lg"></i>
                        <div id="toggle-button-ui"></div>
                    </li>				
				</ul>
			</div>
		</div>
	</nav>
        </header>';
    }
    
    /**
     * Render a card with a list of items
     * 
     * @param string $title Card title
     * @param array $items Items to display
     * @param string $type Type of items (events or projects)
     * @return string HTML for the card
     */
    protected function renderCard($title, $items, $type = 'events') {
        $html = '<div class="card-hover-shadow-2x mb-3 card text-dark">';
        $html .= '<div class="card-header-tab card-header">';
        $html .= '<h5 class="card-header-title font-weight-normal"><i class="fa fa-suitcase mr-3"></i>' . $title . '</h5>';
        $html .= '</div>';
        $html .= '<div class="scroll-area">';
        $html .= '<perfect-scrollbar class="ps-show-limits">';
        $html .= '<div style="position: static;" class="ps ps--active-y">';
        $html .= '<div class="ps-content">';
        $html .= '<ul class="list-group list-group-flush">';
        
        if (!empty($items)) {
            foreach ($items as $item) {
                $color = $type === 'events' ? $item['colour'] : $item['project_colour'];
                $html .= '<li class="list-group-item pe-auto">';
                $html .= '<div class="todo-indicator ml-2" style="background-color:' . $color . ';"></div>';
                $html .= '<div class="widget-content p-0 ml-4">';
                $html .= '<div class="widget-content-wrapper">';
                $html .= '<div class="widget-content-left">';
                $html .= '<div class="text-left widget-heading text-primary">' . ($type === 'events' ? $item['title'] : $item['project_name']) . '</div>';
                
                if ($type === 'events') {
                    $html .= '<div class="widget-subheading text-muted"><i>Start: ' . $item['start_date'] . '</i></div>';
                    $html .= '<div class="widget-subheading text-muted"><i>End: ' . $item['end_date'] . '</i></div>';
                } else {
                    $html .= '<div class="widget-subheading text-muted"><i>Start: ' . $item['start_date'] . ' | End: ' . $item['end_date'] . '</i></div>';
                }
                
                $html .= '</div>';
                $html .= '</div>';
                $html .= '</div>';
                $html .= '</li>';
            }
        }
        
        $html .= '</ul>';
        $html .= '</div>';
        $html .= '</div>';
        $html .= '</perfect-scrollbar>';
        $html .= '</div>';
        $html .= '</div>';
        
        return $html;
    }
    
    /**
     * Render the task column
     * 
     * @return string HTML for the task column
     */
    protected function renderTaskColumn() {
        $tasks = $this->getData('formatted_tasks', []);
        
        $html = '<div class="col-3">';
        $html .= '<div class="card-hover-shadow-2x mb-3 card text-dark">';
        $html .= '<div class="card-header-tab card-header">';
        $html .= '<h5 class="card-header-title font-weight-normal"><i class="fa fa-tasks mr-3"></i>TASKS</h5>';
        $html .= '</div>';
        $html .= '<div class="scroll-area">';
        $html .= '<perfect-scrollbar class="ps-show-limits">';
        $html .= '<div style="position: static;" class="ps ps--active-y">';
        $html .= '<div class="ps-content">';
        $html .= '<ul class="list-group list-group-flush">';
        
        if (!empty($tasks)) {
            foreach ($tasks as $task) {
                $html .= '<li class="list-group-item pe-auto">';
                $html .= '<div class="todo-indicator ml-2" style="background-color:' . $task['colour'] . ';"></div>';
                $html .= '<div class="widget-content p-0 ml-4">';
                $html .= '<div class="widget-content-wrapper">';
                $html .= '<div class="widget-content-left">';
                $html .= '<div class="text-left widget-heading text-primary">' . $task['title'] . '</div>';
                $html .= '<div class="widget-subheading text-muted"><i>Project: ' . $task['project_name'] . '</i></div>';
                $html .= '<div class="widget-subheading text-muted"><i>Status: ' . $task['status'] . '</i></div>';
                $html .= '</div>';
                $html .= '</div>';
                $html .= '</div>';
                $html .= '</li>';
            }
        }
        
        $html .= '</ul>';
        $html .= '</div>';
        $html .= '</div>';
        $html .= '</perfect-scrollbar>';
        $html .= '</div>';
        $html .= '</div>';
        $html .= '</div>';
        
        return $html;
    }
    
    /**
     * Render the events column
     * 
     * @return string HTML for the events column
     */
    protected function renderEventsColumn() {
        $events_start = $this->getData('events_start', []);
        $events_end = $this->getData('events_end', []);
        
        $html = '<div class="col-3">';
        $html .= $this->renderCard('STARTING EVENTS', $events_start, 'events');
        $html .= $this->renderCard('ENDING EVENTS', $events_end, 'events');
        $html .= '</div>';
        
        return $html;
    }
    
    /**
     * Render the projects column
     * 
     * @return string HTML for the projects column
     */
    protected function renderProjectsColumn() {
        $projects_start = $this->getData('projects_start', []);
        $projects_end = $this->getData('projects_end', []);
        
        $html = '<div class="col-3">';
        $html .= $this->renderCard('STARTING PROJECTS', $projects_start, 'projects');
        $html .= $this->renderCard('ENDING PROJECTS', $projects_end, 'projects');
        $html .= '</div>';
        
        return $html;
    }
    
    /**
     * Render the content section
     * 
     * @return string HTML for the content section
     */
    protected function renderContent() {
        $html = $this->renderNavbarWithThemeSwitch();
        
        $html .= '<div class="row d-flex m-4 mt-2 justify-content-center">';
        $html .= '<h2 class="col-12 text-center mb-4 text-primary">HAPPENING TODAY</h2>';
        
        // Render columns
        $html .= $this->renderEventsColumn();
        $html .= $this->renderProjectsColumn();
        $html .= $this->renderTaskColumn();
        
        $html .= '</div>';
        
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
        
        // Footer
        $html .= $this->renderFooter();
        
        // Scripts
        $html .= $this->renderScripts();
        
        $html .= '</body>';
        $html .= '</html>';
        
        return $html;
    }
}