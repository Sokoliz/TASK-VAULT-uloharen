<?php
namespace App\Views\Events\Modals;

require_once(__DIR__ . '/Modal.php');

/**
 * Trieda TaskAddModal pre vytváranie nových úloh
 * 
 * Táto trieda vytvára modálne okno pre pridávanie nových úloh
 * do existujúceho projektu. Obsahuje formulár s poliami pre
 * status, názov, popis, prioritu a deadline úlohy.
 */
class TaskAddModal extends Modal {
    /**
     * ID projektu, ku ktorému úloha patrí
     * Toto potrebujeme, aby sme úlohu priradili k správnemu projektu
     */
    private $project_id;
    
    /**
     * Konštruktor
     * 
     * @param int $project_id ID projektu, ku ktorému úloha patrí
     */
    public function __construct($project_id) {
        parent::__construct('new-task-modal', 'Create a New Task', '/task/create');
        
        $this->project_id = $project_id;
        
        // Inicializácia polí formulára
        $this->initializeFields();
    }
    
    /**
     * Vlastná metóda na vykreslenie hlavičky, aby zodpovedala pôvodnému štýlu
     * 
     * Prepíšeme rodičovskú metódu, aby hlavička vyzerala presne
     * tak, ako potrebujeme pre úlohy.
     */
    protected function renderHeader() {
        return '<div class="modal-header">
                    <h3 class="lead text-primary">Create a New Task</h3>
                    <a class="close text-dark btn" data-dismiss="modal">×</a>
                </div>';
    }
    
    /**
     * Vlastná metóda na pridanie radio buttonov pre status úlohy
     * 
     * Táto metóda vytvára trojicu radio buttonov pre nastavenie
     * statusu úlohy: To do, In progress, alebo Complete.
     */
    private function addStatusRadioButtons() {
        $html = '<label class="text-dark">Status<span class="text-danger pl-1">*</span></label>';
        $html .= '<div class="form-group d-flex justify-content-around">';
        
        $html .= '<div class="form-check">';
        $html .= '<input class="form-check-input btn" type="radio" name="task_status" id="task_status_1" value="1" checked>';
        $html .= '<label class="form-check-label" for="task_status_1">To do</label>';
        $html .= '</div>';
        
        $html .= '<div class="form-check">';
        $html .= '<input class="form-check-input btn" type="radio" name="task_status" id="task_status_2" value="2">';
        $html .= '<label class="form-check-label" for="task_status_2">In progress</label>';
        $html .= '</div>';
        
        $html .= '<div class="form-check">';
        $html .= '<input class="form-check-input btn" type="radio" name="task_status" id="task_status_3" value="3">';
        $html .= '<label class="form-check-label" for="task_status_3">Complete</label>';
        $html .= '</div>';
        
        $html .= '</div>';
        
        return $html;
    }
    
    /**
     * Vlastná metóda na vykreslenie poľa pre deadline
     * 
     * Táto metóda vytvára pole pre zadanie termínu dokončenia úlohy,
     * s dnešným dátumom ako minimálnou hodnotou.
     */
    private function renderDeadlineField() {
        $html = '<div class="form-group d-flex justify-content-between mt-2">';
        $html .= '<div class="col-12 m-0 p-1">';
        $html .= '<label class="text-dark">Deadline<span class="text-danger pl-1">*</span></label>';
        $html .= '<input type="date" class="form-control" runat="server" name="deadline" min="' . date('Y-m-d') . '" data-date-format="yyyy-mm-dd" required/>';
        $html .= '</div>';
        $html .= '</div>';
        
        return $html;
    }
    
    /**
     * Prepísanie metódy renderBody pre zahrnutie vlastných polí
     * 
     * Táto metóda upravuje telo modálneho okna, aby obsahovalo
     * všetky potrebné polia pre vytvorenie novej úlohy.
     */
    protected function renderBody() {
        $html = '<div class="modal-body">';
        
        // Pridanie kontajnera pre chybové hlášky validácie
        // Toto pomáha zobrazovať validačné chyby pekne formátované
        $html .= '<div class="alert alert-danger validation-errors" style="display:none;"></div>';
        
        // Pridanie radio buttonov pre status úlohy
        $html .= $this->addStatusRadioButtons();
        
        // Vykreslenie štandardných polí
        foreach ($this->fields as $field) {
            $html .= $this->renderField($field);
        }
        
        // Pridanie poľa pre deadline
        $html .= $this->renderDeadlineField();
        
        // Pridanie skrytých polí
        // ID projektu - kam úloha patrí
        $html .= '<div class="form-group">';
        $html .= '<input hidden id="id_task_project" name="id_project" value="' . $this->project_id . '">';
        $html .= '</div>';
        
        // ID užívateľa - kto úlohu vytvoril
        $html .= '<div class="form-group">';
        $html .= '<input hidden id="id_user" name="id_user" value="' . $_SESSION['user_id'] . '">';
        $html .= '</div>';
        
        $html .= '</div>';
        
        return $html;
    }
    
    /**
     * Prepísanie metódy render, aby sme zaistili, že formulár
     * obopína aj telo aj pätu modálneho okna
     * 
     * Táto metóda vytvára kompletný HTML kód modálneho okna
     * vrátane formulára, ktorý obopína všetky polia.
     */
    public function render($submitText = 'Create Task') {
        $html = '<div class="modal fade" id="' . $this->modalId . '" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">';
        $html .= '<div class="modal-dialog">';
        $html .= '<div class="modal-content">';
        
        $html .= $this->renderHeader();
        
        // Otvorenie formulára pred telom, aby zahŕňal aj telo aj pätu
        $html .= '<form class="form-horizontal" method="post" action="' . $this->action . '">';
        
        $html .= $this->renderBody();
        $html .= $this->renderFooter($submitText);
        
        // Zatvorenie formulára po päte
        $html .= '</form>';
        
        $html .= '</div>';
        $html .= '</div>';
        $html .= '</div>';
        
        return $html;
    }
    
    /**
     * Inicializácia polí formulára
     * 
     * Táto metóda pridáva všetky potrebné polia pre vytvorenie
     * novej úlohy, ako názov, popis a priorita.
     */
    private function initializeFields() {
        // Pridanie poľa pre názov úlohy - povinné pole
        $this->addTextField('task_name', 'Task Name<span class="text-danger pl-1">*</span>', '', true);
        
        // Pridanie poľa pre popis úlohy - nepovinné
        $this->addTextareaField('task_description', 'Description', '', false);
        
        // Pridanie výberu priority úlohy - nízka, stredná, vysoká
        // Každá priorita má svoju farbu pre lepšiu prehľadnosť
        $priorityOptions = [
            '#5cb85c' => ['text' => '<span style="color:#5cb85c; font-weight:bold;">&#9724;</span> Low', 'style' => 'color:#5cb85c; font-weight:bold;'],
            '#f0ad4e' => ['text' => '<span style="color:#f0ad4e; font-weight:bold;">&#9724;</span> Medium', 'style' => 'color:#f0ad4e; font-weight:bold;'],
            '#d9534f' => ['text' => '<span style="color:#d9534f; font-weight:bold;">&#9724;</span> High', 'style' => 'color:#d9534f; font-weight:bold;']
        ];
        
        $this->addSelectField('task_colour', 'Priority<span class="text-danger pl-1">*</span>', $priorityOptions, '', true);
    }
} 