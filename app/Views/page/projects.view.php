<?php
require_once('View.php');
require_once(__DIR__ . '/../events/modals/DeleteTaskModal.php');

// Trieda ProjectsView pre zobrazenie stránky s projektami
// Táto stránka umožňuje používateľovi spravovať projekty a úlohy v štýle Kanban boardu
class ProjectsView extends View {
    // Konštruktor - inicializuje základné vlastnosti
    public function __construct($data = []) {
        parent::__construct('Projects', false, $data);
    }
    
    // Renderuje sekciu hlavičky s dodatočnými štýlmi a skriptami
    // Tu pridávame všetky potrebné JavaScripty a CSS súbory pre projekty
    protected function renderHead() {
        $html = parent::renderHead();
        $html .= '<script src="/public/js/theme.js"></script>';
        $html .= '<script src="/public/js/form-validation.js"></script>';
        $html .= '<script src="/public/js/modal-handler.js"></script>';
        $html .= '<script src="/public/js/dynamic-theme.js"></script>';
        $html .= '<script src="/public/js/delete-task-handler.js"></script>';
        $html .= '<script src="/public/js/delete-project-handler.js"></script>';
        $html .= '<link href="/public/css/dynamic-theme.css" rel="stylesheet" />';
        $html .= '<link href="/public/css/theme-icons.css" rel="stylesheet" />';
        $html .= '<link href="/public/css/modal-colors.css" rel="stylesheet" />';
        $html .= '<link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.standalone.min.css" rel="stylesheet" type="text/css" />';
        
        
        return $html;
    }
    
    // Renderuje zoznam projektov
    // V tejto časti sa zobrazujú všetky projekty používateľa v ľavom stĺpci
    protected function renderProjectsList() {
        $projects = $this->getData('projects', []);
        
        $html = '<div class="col-3 p-0 pl-3 pr-1">';
        $html .= '<div class="card-hover-shadow-2x mb-3 card text-dark">';
        $html .= '<div class="card-header-tab card-header d-flex flex-nowrap justify-content-between">';
        $html .= '<h4 class="card-header-title font-weight-normal"><i class="fa fa-suitcase mr-3"></i>' . strtoupper($_SESSION['user']) . '\'S PROJECTS</h4>';
        $html .= '<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#new-project-modal">+</button>';
        $html .= '</div>';
        $html .= '<div class="scroll-area">';
        $html .= '<perfect-scrollbar class="ps-show-limits">';
        $html .= '<div style="position: static;" class="ps ps--active-y">';
        $html .= '<div class="ps-content">';
        $html .= '<ul class="list-group list-group-flush">';
        
        if (!empty($projects)) {
            $i = 1;
            foreach ($projects as $p) {
                $html .= '<li class="accordion list-group-item pe-auto" id="project-p-' . $i . '">';
                $html .= '<div class="todo-indicator" style="background-color:' . $p['project_colour'] . ';"></div>';
                $html .= '<div class="widget-content p-0">';
                $html .= '<div class="widget-content-wrapper">';
                $html .= '<form name="id_project_task" action="/projects" method="GET" role="form">';
                $html .= '<input hidden name="idProject" value=' . $p['id_project'] . '>';
                $html .= '<button class="btn" type="submit">';
                $html .= '<div class="widget-content-left">';
                $html .= '<div class="text-left widget-heading text-primary">' . $p['project_name'] . '</div>';
                $html .= '<div class="widget-subheading text-muted"><i>Start: ' . $p['start_date'] . '</i></div>';
                $html .= '<div class="widget-subheading text-muted"><i>End: ' . $p['end_date'] . '</i></div>';
                $html .= '</div>';
                $html .= '</button>';
                $html .= '</form>';
                
                $html .= '<div class="widget-content-right ml-auto d-flex flex-nowrap">';
                $html .= '<button type="button" class="border-0 btn-transition btn btn-outline-success" data-toggle="modal" data-target="#project-edit-' . $i . '">';
                $html .= '<i class="fas fa-pencil-alt"></i></button>';
                
                // Includujeme modál pre editáciu projektu
                ob_start();
                require_once __DIR__ . '/../../Controllers/ProjectEditModalController.php';
                $html .= ob_get_clean();
                
                $html .= '<button type="button" class="border-0 btn-transition btn btn-outline-danger" data-toggle="modal" data-target="#project-delete-' . $i . '">';
                $html .= '<i class="fas fa-trash-alt"></i></button>';
                
                // Includujeme modál pre vymazanie projektu
                ob_start();
                require_once __DIR__ . '/../../Controllers/ProjectDeleteModalController.php';
                $html .= ob_get_clean();
                
                $html .= '</div>';
                $html .= '</div>';
                
                // Popis projektu (ak je k dispozícii)
                if ($p['project_description'] !== '') {
                    $html .= '<a class="d-flex justify-content-center nav-link text-primary p-0" data-toggle="collapse" data-target="#collapse-p-' . $i . '" aria-expanded="true">';
                    $html .= '<span class="accicon"><i class="fa fa-angle-down rotate-icon pl-2 pr-2"></i></span>';
                    $html .= '<div id="collapse-p-' . $i . '" class="collapse" data-parent="#project-p-' . $i . '">';
                    $html .= '<p class="font-small text-dark pt-1">' . $p['project_description'] . '</p>';
                    $html .= '</div>';
                    $html .= '</a>';
                }
                
                $html .= '</div>';
                $html .= '</li>';
                
                $i++;
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
    
    // Renderuje stĺpec úloh pre konkrétny status
    // Táto metóda generuje jeden zo stĺpcov Kanban boardu (To Do, In Progress, Complete)
    protected function renderTaskColumn($tasks, $status, $title, $icon, $statusCode, $allowLeft = true, $allowRight = true) {
        $html = '<div class="col-4">';
        $html .= '<div class="card-hover-shadow-2x mb-3 card text-dark">';
        $html .= '<div class="card-header-tab card-header">';
        $html .= '<h5 class="card-header-title font-weight-normal"><i class="' . $icon . ' mr-3"></i>' . $title . '</h5>';
        $html .= '</div>';
        $html .= '<div class="scroll-area-sm">';
        $html .= '<perfect-scrollbar class="ps-show-limits">';
        $html .= '<div style="position: static;" class="ps ps--active-y">';
        $html .= '<div class="ps-content">';
        $html .= '<ul class="list-group list-group-flush">';
        
        $i = ($statusCode === 1) ? 1 : (($statusCode === 2) ? 1000000 : 2000000);
        
        if (!empty($tasks)) {
            foreach ($tasks as $task) {
                if ((int)$task['task_status'] === $statusCode) {
                    $html .= '<li class="accordion list-group-item pe-auto" id="task-' . $status . '-' . $i . '">';
                    $html .= '<div class="todo-indicator" style="background-color:' . $task['task_colour'] . ';"></div>';
                    $html .= '<div class="widget-content p-0">';
                    $html .= '<div class="widget-content-wrapper">';
                    $html .= '<a class="col-8 nav-link text-primary p-0" data-toggle="collapse" data-target="#collapse-' . $status . '-' . $i . '" aria-expanded="true">';
                    $html .= '<div class="widget-content-left p-2 pl-3">';
                    $html .= '<div class="widget-heading d-flex">';
                    $html .= $task['task_name'];
                    $html .= '<span class="accicon"><i class="fa fa-angle-down rotate-icon pl-2"></i></span>';
                    $html .= '</div>';
                    $html .= '<div id="collapse-' . $status . '-' . $i . '" class="collapse" data-parent="#task-' . $status . '-' . $i . '">';
                    if ($task['deadline'] !== '1970-01-01') {
                        $html .= '<div class="widget-subheading text-muted"><i>Deadline: ' . $task['deadline'] . '</i></div>';
                    }
                    $html .= '<p class="font-small text-dark pt-1">' . $task['task_description'] . '</p>';
                    $html .= '</div>';
                    $html .= '</div>';
                    $html .= '</a>';
                    
                    // Tlačidlá pre editáciu a vymazanie úlohy
                    $html .= '<div class="widget-content-right ml-auto">';
                    $html .= '<button type="button" class="border-0 btn-transition btn btn-outline-success" data-toggle="modal" data-target="#task-edit-' . $i . '">';
                    $html .= '<i class="fas fa-pencil-alt"></i></button>';
                    
                    // Includujeme modál pre editáciu úlohy
                    ob_start();
                    $s = $task; // Nastavíme aktuálnu úlohu pre editTask modál
                    require_once __DIR__ . '/../../Controllers/TaskEditModalController.php';
                    $html .= ob_get_clean();
                    
                    $html .= '<button type="button" class="border-0 btn-transition btn btn-outline-danger" data-toggle="modal" data-target="#task-delete-' . $i . '">';
                    $html .= '<i class="fas fa-trash-alt"></i></button>';
                    
                    // Používame OOP modál namiesto include
                    ob_start();
                    $deleteTaskModal = new \App\Views\Modals\DeleteTaskModal($task, $i);
                    $html .= $deleteTaskModal->render();
                    
                    $html .= '</div>';
                    $html .= '</div>';
                    $html .= '</div>';
                    
                    // Navigačné tlačidlá pre presun úlohy medzi stĺpcami
                    $html .= '<div class="d-flex justify-content-center">';
                    
                    // Tlačidlo vľavo
                    if ($allowLeft) {
                        $html .= '<form name="id_task_left-' . $i . '" action="/task/left" method="POST" role="form">';
                        $html .= '<input hidden name="id_task" value=' . $task['id_task'] . '>';
                        $html .= '<input hidden name="left" type="1" value="1">';
                        $html .= '<input hidden name="task_status" value=' . $task['task_status'] . '>';
                        $html .= '<input hidden name="id_project" value=' . $task['id_project'] . '>';
                        $html .= '<button type="submit" class="border-0 btn-transition btn btn-outline-primary"><i class="fa fa-arrow-left"></i></button>';
                        $html .= '</form>';
                    } else {
                        $html .= '<button type="submit" class="border-0 btn-transition btn btn-outline-secondary" disabled><i class="fa fa-arrow-left"></i></button>';
                    }
                    
                    // Tlačidlo vpravo
                    if ($allowRight) {
                        $html .= '<form name="id_task_right-' . $i . '" action="/task/right" method="POST" role="form">';
                        $html .= '<input hidden name="id_task" value=' . $task['id_task'] . '>';
                        $html .= '<input hidden name="right" type="int" value="1">';
                        $html .= '<input hidden name="task_status" value=' . $task['task_status'] . '>';
                        $html .= '<input hidden name="id_project" value=' . $task['id_project'] . '>';
                        $html .= '<button type="submit" class="border-0 btn-transition btn btn-outline-primary"><i class="fa fa-arrow-right"></i></button>';
                        $html .= '</form>';
                    } else {
                        $html .= '<button type="submit" class="border-0 btn-transition btn btn-outline-secondary" disabled><i class="fa fa-arrow-right"></i></button>';
                    }
                    
                    $html .= '</div>';
                    $html .= '</li>';
                    
                    $i++;
                }
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
    
    // Renderuje zoznam úloh
    // Tu sa generuje hlavný Kanban board s tromi stĺpcami úloh
    protected function renderTasksList() {
        $showTasks = $this->getData('show_tasks', []);
        
        $html = '<div class="col-9 p-0 pr-3 pl-1">';
        $html .= '<div class="card-hover-shadow-2x mb-3 card text-dark">';
        $html .= '<div class="card-header-tab card-header d-flex justify-content-between">';
        $html .= '<h4 class="card-header-title font-weight-normal"><i class="fas fa-clipboard-list pr-3"></i>TASKS</h4>';
        
        // Tlačidlo "New task" sa zobrazí len ak je vybraný projekt
        if (isset($_GET['idProject'])) {
            $html .= '<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#new-task-modal">New task</button>';
            
            // Includujeme modál pre pridanie novej úlohy
            ob_start();
            require_once __DIR__ . '/../../Controllers/TaskAddModalController.php';
            $html .= ob_get_clean();
        }
        
        $html .= '</div>';
        $html .= '<div class="scroll-area">';
        $html .= '<perfect-scrollbar class="ps-show-limits">';
        $html .= '<div style="position: static;" class="ps ps--active-y">';
        $html .= '<div class="ps-content">';
        $html .= '<div class="row m-2 mt-4">';
        
        // Stĺpec To Do
        $html .= $this->renderTaskColumn($showTasks, 'todo', 'TO DO', 'fas fa-list', 1, false, true);
        
        // Stĺpec In Progress
        $html .= $this->renderTaskColumn($showTasks, 'ip', 'IN PROGRESS', 'fas fa-cogs', 2, true, true);
        
        // Stĺpec Complete
        $html .= $this->renderTaskColumn($showTasks, 'c', 'COMPLETE', 'fas fa-check', 3, true, false);
        
        $html .= '</div>';
        $html .= '</div>';
        $html .= '</div>';
        $html .= '</perfect-scrollbar>';
        $html .= '</div>';
        $html .= '</div>';
        $html .= '</div>';
        
        return $html;
    }
    
    // Renderuje dodatočné skripty pre funkcionalitu datepickera
    // Tieto skripty sú potrebné pre prácu s dátumami v projektoch a úlohách
    protected function renderAdditionalScripts() {
        $html = parent::renderScripts();
        $html .= '<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js"></script>';
        $html .= '<script src="https://cdn.jsdelivr.net/npm/sweetalert2@9"></script>';
        
        // Vytvorili sme datepicker.js súbor pre spracovanie tejto funkcionality
        $html .= '<script src="/public/js/datepicker-init.js"></script>';
        
        return $html;
    }
    
    // Renderuje upozornenia zo session
    // Zobrazuje chybové a úspešné hlášky z predchádzajúcich akcií
    protected function renderAlerts() {
        $html = '';
        
        // Kontrola chybovej hlášky pre projekt
        if (isset($_SESSION['project_error'])) {
            $html .= '<div class="alert alert-danger alert-dismissible fade show" role="alert">';
            $html .= $_SESSION['project_error'];
            $html .= '<button type="button" class="close" data-dismiss="alert" aria-label="Close">';
            $html .= '<span aria-hidden="true">&times;</span>';
            $html .= '</button>';
            $html .= '</div>';
            
            // Vymažeme hlášku
            unset($_SESSION['project_error']);
        }
        
        // Hláška o úspešnom projekte bola odstránená podľa požiadavky
        
        // Kontrola chybovej hlášky pre úlohu
        if (isset($_SESSION['task_error'])) {
            $html .= '<div class="alert alert-danger alert-dismissible fade show" role="alert">';
            $html .= $_SESSION['task_error'];
            $html .= '<button type="button" class="close" data-dismiss="alert" aria-label="Close">';
            $html .= '<span aria-hidden="true">&times;</span>';
            $html .= '</button>';
            $html .= '</div>';
            
            // Vymažeme hlášku
            unset($_SESSION['task_error']);
        }
        
        // Hláška o úspešnej úlohe bola odstránená podľa požiadavky
        
        return $html;
    }
    
    // Renderuje sekciu obsahu
    // Tu poskladáme celú stránku s projektami a úlohami
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

        // Includujeme modál pre pridanie nového projektu
        ob_start();
        require_once __DIR__ . '/../../Controllers/ProjectAddModalController.php';
        $modalContent = ob_get_clean();
        
        // Zobrazíme upozornenia bez kontajnera
        $html .= $this->renderAlerts();
        
        $html .= '<div class="row d-flex m-0 p-0 mt-4">';
        $html .= $this->renderProjectsList();
        $html .= $this->renderTasksList();
        $html .= '</div>';
        
        // Pätička
        ob_start();
        require __DIR__ . '/../parts/footer.php';
        $html .= ob_get_clean();
        
        // Pridáme obsah modálu na koniec
        $html .= $modalContent;
        
        return $html;
    }
    
    // Renderuje kompletnú stránku
    // Poskladá všetky časti do výsledného HTML
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
        
        // Dodatočné skripty pre datepicker
        $html .= $this->renderAdditionalScripts();
        
        $html .= '</body>';
        $html .= '</html>';
        
        return $html;
    }
}