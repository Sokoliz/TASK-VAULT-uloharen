<?php
namespace App\Views\Events\Modals;

require_once(__DIR__ . '/Modal.php');

class TaskEditModal extends Modal {
    
    private $taskData;
    
    private $taskIndex;
    
    private $formAction;
    
    public function __construct($taskData, $index) {
        // Inicializácia s predvolenými hodnotami, aby sme predišli warningom pre neexistujúce kľúče
        // To je dôležité, lebo PHP inak vyhodí warning, keď sa snažíme pristúpiť k neexistujúcemu kľúču v poli
        $this->taskData = array_merge([
            'task_name' => '',
            'task_description' => '',
            'task_colour' => '#5cb85c',
            'id_project' => 0,
            'id_task' => 0,
            'deadline' => ''
        ], is_array($taskData) ? $taskData : []);
        
        $this->taskIndex = $index;
        $this->formAction = '/task/edit';
        
        // Zavoláme konštruktor rodičovskej triedy s potrebnými parametrami
        // Toto je vlastne ako super() v Jave, len v PHP je to parent::
        parent::__construct('task-edit-' . $index, 'Edit your task', '/task/edit');
    }
    
    public function render($submitButtonText = 'Submit') {
        // Tu generujeme hlavný div pre modálne okno
        $html = '<div id="task-edit-' . $this->taskIndex . '" class="modal fade" role="dialog">';
        $html .= '<div class="modal-dialog">';
        $html .= '<div class="modal-content">';
        
        // Hlavička modálu - titul a tlačidlo na zatvorenie
        $html .= '<div class="modal-header">';
        $html .= '<h3 class="lead text-primary" >Edit your task</h3>';
        $html .= '<a class="close text-dark btn" data-dismiss="modal">×</a>';
        $html .= '</div>';
        
        // Formulár - všetko musí byť vo vnútri form tagu, aby to fungovalo správne
        $html .= '<form name="task" action="' . $this->formAction . '" method="POST" role="form">';
        
        // Telo modálu, kde sú všetky polia formulára
        $html .= '<div class="modal-body">';
        
        // Kontajner pre chybové hlášky validácie - spočiatku skrytý
        // Toto je fajn pre UX, lebo chyby sa zobrazia na vrchu formulára
        $html .= '<div class="alert alert-danger validation-errors" style="display:none;"></div>';
        
        // Pole pre názov úlohy - povinné pole, preto ten hviezdička
        $html .= '<div class="form-group">';
        $html .= '<label class="text-dark" for="edit_name">Task Name<span class="text-danger pl-1">*</span></label>';
        $html .= '<input class="form-control" type="text" name="task_name" value="' . $this->taskData['task_name'] . '" required>';
        $html .= '</div>';
        
        // Pole pre popis úlohy - nepovinné
        $html .= '<div class="form-group">';
        $html .= '<label class="text-dark" for="edit_description">Description</label>';
        $html .= '<textarea class="form-control" type="text" name="task_description">' . $this->taskData['task_description'] . '</textarea>';
        $html .= '</div>';
        
        // Výber farby/priority - nízka, stredná, vysoká, reprezentované farbami
        // Toto je celkom dobrý nápad, používať farby na označenie priority
        $html .= '<div class="form-group">';
        $html .= '<label for="edit_colour" class="text-dark">Colour</label>';
        
        // Nastavenie štýlu select boxu podľa aktuálnej farby
        $colorStyle = 'style="color:' . $this->taskData['task_colour'] . ' !important; font-weight:bold;"';
        
        $html .= '<select name="task_colour" class="form-control" ' . $colorStyle . '>';
        
        // Možnosti farieb v pevnom poradí: nízka, stredná, vysoká
        // Toto sú vlastne priority úloh reprezentované farbami
        $html .= '<option value="#5cb85c" ' . ($this->taskData['task_colour'] == '#5cb85c' ? 'selected ' : '') . 'style="color:#5cb85c !important; font-weight:bold;"><span style="color:#5cb85c">&#9724;</span> Low</option>';
        $html .= '<option value="#f0ad4e" ' . ($this->taskData['task_colour'] == '#f0ad4e' ? 'selected ' : '') . 'style="color:#f0ad4e !important; font-weight:bold;"><span style="color:#f0ad4e">&#9724;</span> Medium</option>';
        $html .= '<option value="#d9534f" ' . ($this->taskData['task_colour'] == '#d9534f' ? 'selected ' : '') . 'style="color:#d9534f !important; font-weight:bold;"><span style="color:#d9534f">&#9724;</span> High</option>';
        
        $html .= '</select>';
        $html .= '</div>';
        
        // Pole pre deadline úlohy - povinné
        $html .= '<div class="form-group d-flex justify-content-between mt-2">';
        $html .= '<div class="col-12 m-0 p-1">';
        $html .= '<label class="text-dark">Deadline<span class="text-danger pl-1">*</span></label>';
        
        // Špeciálna logika pre deadline - nechceme zobrazovať 1970-01-01, čo je default hodnota
        // keď dátum nie je nastavený (UNIX epoch time 0)
        $deadlineValue = '';
        if (isset($this->taskData['deadline']) && $this->taskData['deadline'] !== '1970-01-01') {
            $deadlineValue = $this->taskData['deadline'];
        }
        
        $html .= '<input type="date" class="form-control" runat="server" name="deadline" value="' . $deadlineValue . '" data-date-format="yyyy-mm-dd" required/>';
        $html .= '</div>';
        $html .= '</div>';
        
        // Skryté polia - potrebné pre controller, aby vedel, ktorú úlohu aktualizovať
        // id_task je ID úlohy, ktorú editujeme
        $html .= '<div class="form-group">';
        $html .= '<input hidden id="id_task" name="id_task" value="' . $this->taskData['id_task'] . '">';
        $html .= '</div>';
        
        // id_project je ID projektu, ku ktorému úloha patrí - potrebné pre presmerovanie
        // späť na správny projekt po editácii
        $html .= '<div class="form-group">';
        $html .= '<input hidden id="id_project" name="id_project" value="' . $this->taskData['id_project'] . '">';
        $html .= '</div>';
        
        $html .= '</div>'; // Koniec tela modálu
        
        // Päta modálu s tlačidlami
        $html .= '<div class="modal-footer">';
        $html .= '<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>';
        $html .= '<button type="submit" class="btn btn-primary">Update</button>';
        $html .= '</div>';
        
        $html .= '</form>';
        $html .= '</div>'; // Koniec obsahu modálu
        $html .= '</div>'; // Koniec dialógu modálu
        $html .= '</div>'; // Koniec modálu
        
        return $html;
    }
} 