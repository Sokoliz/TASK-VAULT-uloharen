<?php
require_once('View.php');

// Trieda TodayView pre zobrazenie stránky s dnešnými udalosťami a úlohami
// Táto stránka ukazuje používateľovi, čo má dnes na pláne
class TodayView extends View {
    
    public function __construct($data = []) {
        parent::__construct('Today\'s Activities', false, $data);
    }
    
    
    protected function renderHead() {
        $html = parent::renderHead();
        $html .= '<link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.standalone.min.css" rel="stylesheet" type="text/css" />';
        $html .= '<link href="/public/css/theme-icons.css" rel="stylesheet" />';
        $html .= '<link href="/public/css/dynamic-theme.css" rel="stylesheet" />';
        $html .= '<script src="/public/js/theme.js"></script>';
        $html .= '<script src="/public/js/dynamic-theme.js"></script>';
        return $html;
    }
    
    
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
    
    
    protected function renderEventsColumn() {
        $events_start = $this->getData('events_start', []);
        $events_end = $this->getData('events_end', []);
        
        $html = '<div class="col-3">';
        $html .= $this->renderCard('STARTING EVENTS', $events_start, 'events');
        $html .= $this->renderCard('ENDING EVENTS', $events_end, 'events');
        $html .= '</div>';
        
        return $html;
    }
    
    
    protected function renderProjectsColumn() {
        $projects_start = $this->getData('projects_start', []);
        $projects_end = $this->getData('projects_end', []);
        
        $html = '<div class="col-3">';
        $html .= $this->renderCard('STARTING PROJECTS', $projects_start, 'projects');
        $html .= $this->renderCard('ENDING PROJECTS', $projects_end, 'projects');
        $html .= '</div>';
        
        return $html;
    }
    
    
    protected function renderContent() {
        // Nastavenie premenných pre navbar.php
        $isPublic = false;
        $showHomeButton = true;
        $showThemeSwitch = true;
        $navbarType = 'standard';
        
        // Include navbar.php
        ob_start();
        include_once __DIR__.'/../parts/navbar.php';
        $html = ob_get_clean();
        
        $html .= '<div class="row d-flex m-4 mt-2 justify-content-center">';
        $html .= '<h2 class="col-12 text-center mb-4 text-primary">HAPPENING TODAY</h2>';
        
        // Renderujeme stĺpce
        $html .= $this->renderEventsColumn();
        $html .= $this->renderProjectsColumn();
        $html .= $this->renderTaskColumn();
        
        $html .= '</div>';
        
        return $html;
    }
    
    /**
     * Renderuje kompletnú stránku
     * 
     * @return string Complete HTML for the page
     */
    public function render() {
        $html = '<!DOCTYPE html>';
        $html .= '<html lang="en">';
        
        // Sekcia head
        $html .= '<head>';
        $html .= $this->renderHead();
        $html .= '</head>';
        
        // Sekcia body s triedou pozadia
        $html .= '<body class="bg">';
        
        // Obsah
        $html .= $this->renderContent();
        
        // Pätička
        $html .= $this->renderFooter();
        
        // Skripty
        $html .= $this->renderScripts();
        
        $html .= '</body>';
        $html .= '</html>';
        
        return $html;
    }
}