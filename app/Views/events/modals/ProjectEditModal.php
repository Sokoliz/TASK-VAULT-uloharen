<?php
namespace App\Views\Events\Modals;

require_once(__DIR__ . '/Modal.php');

/**
 * Trieda ProjectEditModal pre editáciu projektov
 * 
 * Táto trieda vytvára modálne okno pre editáciu existujúcich projektov.
 * Umožňuje zmeniť názov, popis, farbu a dátumy projektu.
 */
class ProjectEditModal extends Modal {
    /**
     * Dáta projektu pre predvyplnenie polí
     * Tu máme uložené aktuálne hodnoty projektu, ktoré sa načítajú do formulára
     */
    private $projectData;
    
    /**
     * Index projektu pre generovanie unikátneho ID modálneho okna
     * Potrebujeme to, aby každý modál mal svoje vlastné ID, keď ich máme viac na stránke
     */
    private $projectIndex;
    
    /**
     * URL akcie formulára
     * Endpoint, kam sa pošlú dáta po odoslaní formulára
     */
    private $formAction;
    
    /**
     * Konštruktor
     * 
     * @param array $projectData Dáta projektu pre predvyplnenie formulára
     * @param int $index Index projektu pre generovanie unikátneho ID modálneho okna
     */
    public function __construct($projectData, $index) {
        // Inicializácia s predvolenými hodnotami, aby sme predišli warningom pre neexistujúce kľúče
        // Toto je užitočné, lebo inak by mohli nastať chyby, keď niektoré hodnoty v $projectData chýbajú
        $this->projectData = array_merge([
            'project_name' => '',
            'project_description' => '',
            'project_colour' => '#0275d8',
            'start_date' => date('Y-m-d'),
            'end_date' => date('Y-m-d'),
            'completed' => 0,
            'id_project' => 0
        ], is_array($projectData) ? $projectData : []);
        
        $this->projectIndex = $index;
        $this->formAction = '/project/edit';
        
        parent::__construct('project-edit-' . $index, 'Edit your project', '/project/edit');
    }
    
    /**
     * Metóda na vykreslenie celého modálneho okna
     * 
     * Táto metóda vytvára kompletný HTML kód modálneho okna
     * s formulárom pre editáciu projektu.
     */
    public function render($submitButtonText = 'Submit') {
        $html = '<div id="project-edit-' . $this->projectIndex . '" class="modal fade" role="dialog">';
        $html .= '<div class="modal-dialog">';
        $html .= '<div class="modal-content">';
        
        // Hlavička modálu
        $html .= '<div class="modal-header">';
        $html .= '<h3 class="lead text-primary">Edit your project</h3>';
        $html .= '<a class="close text-dark btn" data-dismiss="modal">×</a>';
        $html .= '</div>';
        
        // Formulár
        $html .= '<form name="project" action="' . $this->formAction . '" method="POST" role="form">';
        
        // Telo modálu s poliami formulára
        $html .= '<div class="modal-body">';
        
        // Pole pre názov projektu - povinné pole
        $html .= '<div class="form-group">';
        $html .= '<label class="text-dark" for="edit_name">Project Name<span class="text-danger pl-1">*</span></label>';
        $html .= '<input class="form-control" type="text" name="project_name" value="' . $this->projectData['project_name'] . '" required>';
        $html .= '</div>';
        
        // Pole pre popis projektu - nepovinné
        $html .= '<div class="form-group">';
        $html .= '<label class="text-dark" for="edit_description">Description</label>';
        $html .= '<textarea class="form-control" type="text" name="project_description">' . $this->projectData['project_description'] . '</textarea>';
        $html .= '</div>';
        
        // Pole pre farbu projektu - vizuálne odlíšenie v kalendári
        $html .= '<div class="form-group">';
        $html .= '<label for="edit_colour" class="text-dark">Colour</label>';
        
        // Nastavenie triedy a atribútov pre farebný select box
        $colorClass = 'form-control color-select';
        $colorStyle = 'style="color:' . $this->projectData['project_colour'] . ' !important; background-color: #fff !important; font-weight:bold;"';
        $dataColor = 'data-color="' . $this->projectData['project_colour'] . '"';
        
        $html .= '<select name="project_colour" class="' . $colorClass . '" id="edit_colour" ' . $colorStyle . ' ' . $dataColor . '>';
        
        // Možnosti farieb s príslušným štýlom pre každú možnosť
        // Používame farebné štvorčeky pre vizuálne odlíšenie
        $colors = [
            '#0275d8' => 'Blue',
            '#5bc0de' => 'Tile',
            '#5cb85c' => 'Green',
            '#f0ad4e' => 'Orange',
            '#d9534f' => 'Red',
            '#292b2c' => 'Black'
        ];
        
        foreach ($colors as $colorValue => $colorName) {
            $selected = ($this->projectData['project_colour'] == $colorValue) ? ' selected' : '';
            $optionStyle = 'style="color:' . $colorValue . ' !important; font-weight:bold;"';
            
            $html .= '<option value="' . $colorValue . '"' . $selected . ' ' . $optionStyle . '>';
            $html .= '<span style="color:' . $colorValue . '; font-weight:bold;">&#9724;</span> ' . $colorName;
            $html .= '</option>';
        }
        
        $html .= '</select>';
        $html .= '</div>';
        
        // Dátumové polia - začiatok a koniec projektu
        $html .= '<div class="form-group d-flex justify-content-between mt-2">';
        
        // Dátum začiatku
        $html .= '<div class="col-6 mt-0 p-1">';
        $html .= '<label class="text-dark">Start Date<span class="text-danger pl-1">*</span></label>';
        $html .= '<input type="text" class="form-control" runat="server" id="startAdd1" name="start_date" value="' . $this->projectData['start_date'] . '" required data-date-format="yyyy-mm-dd"/>';
        $html .= '</div>';
        
        // Dátum konca
        $html .= '<div class="col-6 m-0 p-1">';
        $html .= '<label class="text-dark">End date<span class="text-danger pl-1">*</span></label>';
        $html .= '<input type="text" class="form-control" runat="server" id="endAdd1" name="end_date" value="' . $this->projectData['end_date'] . '"required data-date-format="yyyy-mm-dd"/>';
        $html .= '</div>';
        
        $html .= '</div>';
        
        // Skryté pole pre ID projektu - potrebné, aby controller vedel, ktorý projekt aktualizovať
        $html .= '<div class="form-group">';
        $html .= '<input hidden id="edit_id_project" name="id_project" value="' . $this->projectData['id_project'] . '" >';
        $html .= '</div>';
        
        $html .= '</div>'; // Koniec tela modálu
        
        // Päta modálu s tlačidlami
        $html .= '<div class="modal-footer">';
        $html .= '<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>';
        $html .= '<button type="submit" class="btn btn-primary">' . $submitButtonText . '</button>';
        $html .= '</div>';
        
        $html .= '</form>';
        $html .= '</div>'; // Koniec obsahu modálu
        $html .= '</div>'; // Koniec dialógu modálu
        $html .= '</div>'; // Koniec modálu
        
        return $html;
    }
} 