<?php
namespace App\Views\Events\Modals;

require_once(__DIR__ . '/Modal.php');

/**
 * Trieda ProjectAddModal pre pridávanie nových projektov
 * 
 * Táto trieda vytvára modálne okno pre pridávanie nových projektov
 * do systému. Formulár obsahuje všetky potrebné polia pre vytvorenie
 * projektu, ako názov, popis, farba, dátumy začiatku a konca.
 */
class ProjectAddModal extends Modal {
    /**
     * Konštruktor
     * 
     * Inicializuje modálne okno s ID, titulkom a akciou
     * pre vytvorenie nového projektu.
     */
    public function __construct() {
        parent::__construct('new-project-modal', 'Create a New Project', '/project/create');
        
        // Inicializácia polí formulára
        $this->initializeFields();
    }
    
    /**
     * Vlastná metóda na vykreslenie hlavičky, aby zodpovedala pôvodnému štýlu
     * 
     * Prepíšeme rodičovskú metódu, aby hlavička vyzerala presne
     * tak, ako potrebujeme pre projekty.
     */
    protected function renderHeader() {
        return '<div class="modal-header">
                    <h3 class="lead text-primary">Create a New Project</h3>
                    <a class="close text-dark btn" data-dismiss="modal">×</a>
                </div>';
    }
    
    /**
     * Vlastná metóda na vykreslenie dátumových polí
     * 
     * Táto metóda vytvára špeciálne usporiadanie pre dátumové polia
     * začiatku a konca projektu, s dnešným dátumom a dátumom o týždeň
     * ako predvolené hodnoty.
     */
    private function renderDateFields() {
        $html = '<div class="form-group d-flex justify-content-between mt-2">';
        
        // Pole pre dátum začiatku
        $html .= '<div class="col-6 mt-0 p-1">';
        $html .= '<label class="text-dark">Start Date<span class="text-danger pl-1">*</span></label>';
        
        // Použitie správne formátovaného dátumového vstupu s dnešným dátumom ako predvolenou hodnotou
        $today = date('Y-m-d');
        $html .= '<input type="date" class="form-control" id="start_date" name="start_date" value="' . $today . '" required/>';
        $html .= '</div>';
        
        // Pole pre dátum konca
        $html .= '<div class="col-6 m-0 p-1">';
        $html .= '<label class="text-dark">End date<span class="text-danger pl-1">*</span></label>';
        
        // Použitie správne formátovaného dátumového vstupu s predvolenou hodnotou o týždeň
        // Toto je fajn defaultná hodnota, lebo väčšina projektov trvá aspoň týždeň
        $nextWeek = date('Y-m-d', strtotime('+1 week'));
        $html .= '<input type="date" class="form-control" id="end_date" name="end_date" value="' . $nextWeek . '" required/>';
        $html .= '</div>';
        
        $html .= '</div>';
        
        return $html;
    }
    
    /**
     * Prepísanie metódy renderBody pre zahrnutie vlastných polí
     * 
     * Táto metóda prepíše štandardné vykreslenie tela modálneho okna,
     * aby zahrnula všetky potrebné polia a validačné prvky.
     */
    protected function renderBody() {
        $html = '<div class="modal-body">';
        
        // Pridanie kontajnera pre chybové hlášky validácie
        // To je super na zobrazenie chýb užívateľovi
        $html .= '<div class="alert alert-danger validation-errors" style="display:none;"></div>';
        
        // Pridanie skrytého poľa s debug informáciami
        // Toto je fajn pre debugging, keď sa niečo pokazí
        $html .= '<input type="hidden" name="debug_info" value="Modal rendered at: ' . date('Y-m-d H:i:s') . '">';
        
        // Vykreslenie štandardných polí
        foreach ($this->fields as $field) {
            $html .= $this->renderField($field);
        }
        
        // Pridanie dátumových polí
        $html .= $this->renderDateFields();
        
        // Pridanie skrytého poľa pre ID užívateľa
        // Toto je dôležité, aby sme vedeli, komu projekt patrí
        $html .= '<div class="form-group">';
        $html .= '<input hidden id="id_user" name="id_user" value="' . $_SESSION['user_id'] . '">';
        $html .= '</div>';
        
        $html .= '</div>';
        
        return $html;
    }

    /**
     * Prepísanie metódy renderFooter pre úpravu tlačidiel
     * 
     * Táto metóda zabezpečuje, že tlačidlá v päte modálneho okna
     * majú správne texty a štýly pre vytváranie projektu.
     */
    protected function renderFooter($submitText = 'Save') {
        return '<div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">' . $submitText . '</button>
                </div>';
    }
    
    /**
     * Prepísanie metódy render, aby sme zaistili, že formulár
     * obopína aj telo aj pätu modálneho okna
     */
    public function render($submitText = 'Create Project') {
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
     * nového projektu, ako názov, popis a farba.
     */
    private function initializeFields() {
        // Pridanie poľa pre názov projektu - povinné pole
        $this->addTextField('project_name', 'Project Name<span class="text-danger pl-1">*</span>', '', true);
        
        // Pridanie poľa pre popis projektu - nepovinné
        $this->addTextareaField('project_description', 'Description', '', false);
        
        // Možnosti farieb so správnym štýlovaním
        $colorOptions = [
            '#0275d8' => ['text' => '<span style="color:#0275d8; font-weight:bold;">&#9724;</span> Blue', 'style' => 'color:#0275d8; background-color: #fff; font-weight:bold;'],
            '#5bc0de' => ['text' => '<span style="color:#5bc0de; font-weight:bold;">&#9724;</span> Tile', 'style' => 'color:#5bc0de; background-color: #fff; font-weight:bold;'],
            '#5cb85c' => ['text' => '<span style="color:#5cb85c; font-weight:bold;">&#9724;</span> Green', 'style' => 'color:#5cb85c; background-color: #fff; font-weight:bold;'],
            '#f0ad4e' => ['text' => '<span style="color:#f0ad4e; font-weight:bold;">&#9724;</span> Orange', 'style' => 'color:#f0ad4e; background-color: #fff; font-weight:bold;'],
            '#d9534f' => ['text' => '<span style="color:#d9534f; font-weight:bold;">&#9724;</span> Red', 'style' => 'color:#d9534f; background-color: #fff; font-weight:bold;'],
            '#292b2c' => ['text' => '<span style="color:#292b2c; font-weight:bold;">&#9724;</span> Black', 'style' => 'color:#292b2c; background-color: #fff; font-weight:bold;']
        ];
        
        // Pridanie výberu farby so zelenou ako predvolenou
        $this->addSelectField('project_colour', 'Colour', $colorOptions, '#5cb85c');
    }
} 