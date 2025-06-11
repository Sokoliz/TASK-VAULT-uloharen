<?php
namespace App\Views\Events\Modals;

require_once(__DIR__ . '/Modal.php');

/**
 * Trieda ProjectDeleteModal pre potvrdenie vymazania projektu
 * 
 * Táto trieda vytvára modálne okno na potvrdenie vymazania projektu.
 * Je dôležitá, lebo vymazanie projektu je vážna vec - spolu s projektom
 * sa vymažú aj všetky úlohy, ktoré k nemu patria.
 */
class ProjectDeleteModal extends Modal {
    /**
     * Dáta projektu
     * Obsahujú informácie o projekte, ktorý sa má vymazať
     */
    private $projectData;
    
    /**
     * Index projektu pre generovanie unikátneho ID modálneho okna
     * Potrebujeme to, aby sme mali unikátne ID pre každý modál na stránke
     */
    private $projectIndex;
    
    /**
     * Konštruktor
     * 
     * @param array $projectData Dáta projektu
     * @param int $index Index projektu pre generovanie unikátneho ID modálneho okna
     */
    public function __construct($projectData, $index) {
        // Inicializácia s predvolenými hodnotami, aby sme predišli warningom pre neexistujúce kľúče
        // To je celkom fajn trik - vždy dať nejaké predvolené hodnoty, aby sme sa vyhli chybám
        $this->projectData = array_merge([
            'project_name' => 'Unnamed Project',
            'id_project' => 0
        ], is_array($projectData) ? $projectData : []);
        
        $this->projectIndex = $index;
        
        parent::__construct('project-delete-' . $index, 'Delete Project', '/project/edit');
        
        // Inicializácia polí formulára - v tomto prípade ich veľa nie je
        $this->initializeFields();
    }
    
    /**
     * Vlastná metóda na vykreslenie hlavičky, aby zodpovedala pôvodnému štýlu
     * 
     * Prepíšeme rodičovskú metódu, aby hlavička mala červený nadpis
     * a vyzerala výstražne - predsa len, ideme mazať projekt.
     */
    protected function renderHeader() {
        return '<div class="modal-header">
                    <h3 class="lead text-danger">Delete Project</h3>
                <a class="close text-dark btn" data-dismiss="modal">×</a>
                </div>';
    }
    
    /**
     * Prepísanie metódy renderBody pre zahrnutie potvrdzujúcej správy
     * 
     * Telo modálneho okna obsahuje potvrdzujúcu správu a upozornenie,
     * že sa vymažú aj všetky úlohy v projekte.
     */
    protected function renderBody() {
        $html = '<div class="modal-body">';
        $html .= '<p>Are you sure you want to delete this project?</p>';
        $html .= '<p><strong>' . $this->projectData['project_name'] . '</strong></p>';
        $html .= '<p class="text-danger">All tasks in this project will also be deleted!</p>';
        
        // Pridanie skrytých polí - ID projektu a príznak pre vymazanie
        // Tie potrebujeme, aby controller vedel, čo má robiť
        $html .= '<input type="hidden" name="id_project" value="' . $this->projectData['id_project'] . '">';
        $html .= '<input type="hidden" name="delete" value="1">'; // Toto hovorí controlleru, že chceme vymazať
        $html .= '</div>';
        
        return $html;
    }
    
    /**
     * Prepísanie metódy renderFooter pre úpravu tlačidiel
     * 
     * V päte modálneho okna sú tlačidlá na zrušenie a potvrdenie vymazania.
     * Tlačidlo na vymazanie je červené, aby bolo jasné, že ide o deštruktívnu akciu.
     */
    protected function renderFooter($submitText = 'Delete') {
        return '<div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger">' . $submitText . '</button>
                </div>';
    }
    
    /**
     * Prepísanie metódy render pre pridanie vlastného ID formuláru
     * 
     * Táto metóda vytvára kompletný HTML kód modálneho okna
     * s formulárom na vymazanie projektu.
     */
    public function render($submitText = 'Save') {
        $html = '<div id="project-delete-' . $this->projectIndex . '" class="modal fade" role="dialog">';
        $html .= '<div class="modal-dialog">';
        $html .= '<div class="modal-content">';
        $html .= $this->renderHeader();
        $html .= '<form id="delete-project-form-' . $this->projectIndex . '" name="project" action="' . $this->action . '" method="POST" role="form">';
        $html .= $this->renderBody();
        $html .= $this->renderFooter($submitText);
        $html .= '</form>';
        $html .= '</div>';
        $html .= '</div>';
        $html .= '</div>';
        
        return $html;
    }
    
    /**
     * Inicializácia polí formulára (žiadne viditeľné polia v tomto modálnom okne)
     * 
     * V tomto prípade nepotrebujeme žiadne viditeľné polia, len skryté
     * polia pre ID projektu a príznak vymazania, ktoré sú pridané v renderBody.
     */
    private function initializeFields() {
        // Žiadne viditeľné polia nie sú potrebné
    }
} 