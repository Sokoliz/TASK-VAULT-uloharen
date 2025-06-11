<?php
require_once('View.php');
require_once(__DIR__ . '/../events/modals/DeleteTaskModal.php');

/**
 * ProjectsView class for rendering the projects page
 */
class ProjectsView extends View {
    /**
     * Constructor
     * 
     * @param array $data Data to be passed to the view
     */
    public function __construct($data = []) {
        parent::__construct('Projects', false, $data);
    }
    
    /**
     * Render the head section with additional styles and scripts
     * 
     * @return string HTML for the head section
     */
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
        
        // Remove inline styles and use the CSS file instead
        return $html;
    }
    
    /**
     * Render the projects list
     * 
     * @return string HTML for the projects list
     */
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
                
                // Include edit project modal
                ob_start();
                require_once __DIR__ . '/../../Controllers/ProjectEditModalController.php';
                $html .= ob_get_clean();
                
                $html .= '<button type="button" class="border-0 btn-transition btn btn-outline-danger" data-toggle="modal" data-target="#project-delete-' . $i . '">';
                $html .= '<i class="fas fa-trash-alt"></i></button>';
                
                // Include delete project modal
                ob_start();
                require_once __DIR__ . '/../../Controllers/ProjectDeleteModalController.php';
                $html .= ob_get_clean();
                
                $html .= '</div>';
                $html .= '</div>';
                
                // Project description (if available)
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
    
    /**
     * Render the task column for a specific status
     * 
     * @param array $tasks Tasks to display
     * @param string $status Status identifier ('todo', 'progress', 'complete')
     * @param string $title Column title
     * @param string $icon Column icon
     * @param int $statusCode Status code (1, 2, 3)
     * @param bool $allowLeft Whether to allow left movement
     * @param bool $allowRight Whether to allow right movement
     * @return string HTML for the task column
     */
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
                    
                    // Task edit and delete buttons
                    $html .= '<div class="widget-content-right ml-auto">';
                    $html .= '<button type="button" class="border-0 btn-transition btn btn-outline-success" data-toggle="modal" data-target="#task-edit-' . $i . '">';
                    $html .= '<i class="fas fa-pencil-alt"></i></button>';
                    
                    // Include edit task modal
                    ob_start();
                    $s = $task; // Set the current task for the editTask modal
                    require_once __DIR__ . '/../../Controllers/TaskEditModalController.php';
                    $html .= ob_get_clean();
                    
                    $html .= '<button type="button" class="border-0 btn-transition btn btn-outline-danger" data-toggle="modal" data-target="#task-delete-' . $i . '">';
                    $html .= '<i class="fas fa-trash-alt"></i></button>';
                    
                    // Use OOP modal instead of include
                    ob_start();
                    $deleteTaskModal = new \App\Views\Modals\DeleteTaskModal($task, $i);
                    $html .= $deleteTaskModal->render();
                    
                    $html .= '</div>';
                    $html .= '</div>';
                    $html .= '</div>';
                    
                    // Navigation buttons
                    $html .= '<div class="d-flex justify-content-center">';
                    
                    // Left button
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
                    
                    // Right button
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
    
    /**
     * Render the tasks list
     * 
     * @return string HTML for the tasks list
     */
    protected function renderTasksList() {
        $showTasks = $this->getData('show_tasks', []);
        
        $html = '<div class="col-9 p-0 pr-3 pl-1">';
        $html .= '<div class="card-hover-shadow-2x mb-3 card text-dark">';
        $html .= '<div class="card-header-tab card-header d-flex justify-content-between">';
        $html .= '<h4 class="card-header-title font-weight-normal"><i class="fas fa-clipboard-list pr-3"></i>TASKS</h4>';
        
        // Only show "New task" button if a project is selected
        if (isset($_GET['idProject'])) {
            $html .= '<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#new-task-modal">New task</button>';
            
            // Include new task modal
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
        
        // To Do column
        $html .= $this->renderTaskColumn($showTasks, 'todo', 'TO DO', 'fas fa-list', 1, false, true);
        
        // In Progress column
        $html .= $this->renderTaskColumn($showTasks, 'ip', 'IN PROGRESS', 'fas fa-cogs', 2, true, true);
        
        // Complete column
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
    
    /**
     * Render additional scripts for datepicker functionality
     * 
     * @return string HTML for the additional scripts
     */
    protected function renderAdditionalScripts() {
        $html = parent::renderScripts();
        $html .= '<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js"></script>';
        $html .= '<script src="https://cdn.jsdelivr.net/npm/sweetalert2@9"></script>';
        
        // Create a datepicker.js file to handle this functionality
        $html .= '<script src="/public/js/datepicker-init.js"></script>';
        
        return $html;
    }
    
    /**
     * Render alert messages from session
     * 
     * @return string HTML for the alert messages
     */
    protected function renderAlerts() {
        $html = '';
        
        // Check for project error message
        if (isset($_SESSION['project_error'])) {
            $html .= '<div class="alert alert-danger alert-dismissible fade show" role="alert">';
            $html .= $_SESSION['project_error'];
            $html .= '<button type="button" class="close" data-dismiss="alert" aria-label="Close">';
            $html .= '<span aria-hidden="true">&times;</span>';
            $html .= '</button>';
            $html .= '</div>';
            
            // Clear the message
            unset($_SESSION['project_error']);
        }
        
        // Project success message has been removed as requested
        
        // Check for task error message
        if (isset($_SESSION['task_error'])) {
            $html .= '<div class="alert alert-danger alert-dismissible fade show" role="alert">';
            $html .= $_SESSION['task_error'];
            $html .= '<button type="button" class="close" data-dismiss="alert" aria-label="Close">';
            $html .= '<span aria-hidden="true">&times;</span>';
            $html .= '</button>';
            $html .= '</div>';
            
            // Clear the message
            unset($_SESSION['task_error']);
        }
        
        // Task success message has been removed as requested
        
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
        $showHomeButton = true;
        $showThemeSwitch = true;
        $navbarType = 'standard';
        
        // Include navbar.php
        ob_start();
        include_once __DIR__.'/../parts/navbar.php';
        $html = ob_get_clean();

        // Include new project modal
        ob_start();
        require_once __DIR__ . '/../../Controllers/ProjectAddModalController.php';
        $modalContent = ob_get_clean();
        
        // Display alert messages without container
        $html .= $this->renderAlerts();
        
        $html .= '<div class="row d-flex m-0 p-0 mt-4">';
        $html .= $this->renderProjectsList();
        $html .= $this->renderTasksList();
        $html .= '</div>';
        
        // Footer
        ob_start();
        require __DIR__ . '/../parts/footer.php';
        $html .= ob_get_clean();
        
        // Add modal content at the end
        $html .= $modalContent;
        
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
        
        // Additional scripts for datepicker
        $html .= $this->renderAdditionalScripts();
        
        $html .= '</body>';
        $html .= '</html>';
        
        return $html;
    }
}