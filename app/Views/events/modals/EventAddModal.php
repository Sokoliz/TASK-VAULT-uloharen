<?php
namespace App\Views\Events\Modals;

require_once(__DIR__ . '/Modal.php');

/**
 * Trieda EventAddModal pre pridávanie nových udalostí
 * 
 * Táto trieda vytvára modálne okno pre pridanie novej udalosti do kalendára.
 * Rozširuje základnú triedu Modal a pridáva špecifické polia a funkcie
 * pre formulár na vytváranie udalostí.
 */
class EventAddModal extends Modal {
    /**
     * Konštruktor
     * 
     * Inicializuje modálne okno s ID 'ModalAdd', titulkom 'New event'
     * a akciou, ktorá smeruje na '/calendar/create' endpoint.
     */
    public function __construct() {
        parent::__construct('ModalAdd', 'New event', '/calendar/create');
        
        // Inicializácia polí formulára
        $this->initializeFields();
    }
    
    /**
     * Inicializácia polí formulára
     * 
     * Pridáva všetky potrebné polia pre vytvorenie novej udalosti,
     * ako názov, popis, farba, dátum začiatku a konca.
     */
    private function initializeFields() {
        // Pridanie poľa pre názov udalosti - povinné pole
        $this->addTextField('title', 'Title', '', true, 'title');
        
        // Pridanie poľa pre popis udalosti - nepovinné
        $this->addTextareaField('description', 'Description', '', false, 'Description');
        
        // Pridanie výberu farby udalosti - vizuálne odlíšenie v kalendári
        // Každá farba má svoj vlastný kód a text so symbolom
        $colorOptions = [
            '#0275d8' => ['text' => '&#9724; Blue', 'style' => 'color:#0275d8'],
            '#5bc0de' => ['text' => '&#9724; Tile', 'style' => 'color:#5bc0de'],
            '#5cb85c' => ['text' => '&#9724; Green', 'style' => 'color:#5cb85c'],
            '#f0ad4e' => ['text' => '&#9724; Orange', 'style' => 'color:#f0ad4e'],
            '#d9534f' => ['text' => '&#9724; Red', 'style' => 'color:#d9534f'],
            '#292b2c' => ['text' => '&#9724; Black', 'style' => 'color:#292b2c']
        ];
        
        $this->addSelectField('colour', 'Colour', $colorOptions, '', true);
        
        // Pridanie dátumových polí pre začiatok a koniec udalosti
        $this->addDateField('start_date', 'Start date', '', true);
        $this->addDateField('end_date', 'End date', '', true);
    }
    
    /**
     * Prepísanie metódy render, aby sme zaistili, že formulár
     * obopína aj telo aj pätu modálneho okna
     * 
     * Toto je dôležité, lebo potrebujeme, aby všetky polia boli
     * v jednom formulári, ktorý sa odošle na server.
     */
    public function render($submitText = 'Save') {
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
} 