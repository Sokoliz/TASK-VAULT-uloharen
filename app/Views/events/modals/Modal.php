<?php
namespace App\Views\Events\Modals;

/**
 * Základná trieda Modal na prácu s modálnymi oknami
 * 
 * Táto trieda slúži ako základ pre všetky modálne okná v aplikácii.
 * Je to také "matka všetkých modálov" - ostatné triedy ju rozširujú
 * a pridávajú vlastnú funkcionalitu. Takto nemusíme opakovať ten istý kód.
 */
class Modal {
    
    protected $modalId;
    
    
    protected $title;
    
    
    protected $action;
    
    
    protected $fields = [];
    
    
    public function __construct($modalId, $title, $action) {
        $this->modalId = $modalId;
        $this->title = $title;
        $this->action = $action;
    }
    
    
    protected function renderHeader() {
        return '<div class="modal-header d-flex justify-content-between">
                    <h4 class="modal-title" id="myModalLabel">' . $this->title . '</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                </div>';
    }
    
    /**
     * Vygeneruje pätu modálneho okna s tlačidlom odoslania
     * 
     * @param string $submitText Text pre tlačidlo odoslania
     * @return string HTML kód päty modálneho okna
     */
    protected function renderFooter($submitText = 'Save') {
        return '<div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">' . $submitText . '</button>
                </div>';
    }
    
    /**
     * Pridá textové pole do modálneho okna
     * 
     * Najčastejšie používané pole - pre krátke textové vstupy
     * ako mená, názvy atď.
     * 
     * @param string $name Názov poľa
     * @param string $label Popisok poľa
     * @param string $value Predvolená hodnota
     * @param bool $required Či je pole povinné
     * @param string $placeholder Zástupný text
     */
    public function addTextField($name, $label, $value = '', $required = false, $placeholder = '') {
        $this->fields[] = [
            'type' => 'text',
            'name' => $name,
            'label' => $label,
            'value' => $value,
            'required' => $required,
            'placeholder' => $placeholder
        ];
    }
    
    /**
     * Pridá viacriadkové textové pole do modálneho okna
     * 
     * Používa sa pre dlhšie texty, ako popisy alebo komentáre
     * 
     * @param string $name Názov poľa
     * @param string $label Popisok poľa
     * @param string $value Predvolená hodnota
     * @param bool $required Či je pole povinné
     * @param string $placeholder Zástupný text
     */
    public function addTextareaField($name, $label, $value = '', $required = false, $placeholder = '') {
        $this->fields[] = [
            'type' => 'textarea',
            'name' => $name,
            'label' => $label,
            'value' => $value,
            'required' => $required,
            'placeholder' => $placeholder
        ];
    }
    
    /**
     * Pridá dátumové pole do modálneho okna
     * 
     * Na zadávanie dátumov ako deadliny, začiatky projektov atď.
     * 
     * @param string $name Názov poľa
     * @param string $label Popisok poľa
     * @param string $value Predvolená hodnota
     * @param bool $required Či je pole povinné
     */
    public function addDateField($name, $label, $value = '', $required = false) {
        $this->fields[] = [
            'type' => 'date',
            'name' => $name,
            'label' => $label,
            'value' => $value,
            'required' => $required
        ];
    }
    
    /**
     * Pridá skryté pole do modálneho okna
     * 
     * Super užitočné pre posielanie ID a podobných vecí,
     * ktoré užívateľ nemusí vidieť ale potrebujeme ich poslať
     * 
     * @param string $name Názov poľa
     * @param string $value Hodnota poľa
     */
    public function addHiddenField($name, $value) {
        // Špeciálna kontrola pre id_event, aby sme zabezpečili, že nie je prázdne
        if ($name === 'id_event' && (empty($value) || $value === 'null' || $value === 'undefined')) {
            // Logujeme chybu
            error_log('Warning: Attempting to add empty or invalid ID: ' . var_export($value, true));
            // Pre ID polia použijeme aspoň placeholder, aby sme zabránili úplne prázdnym hodnotám
            $value = '';
        }
        
        $this->fields[] = [
            'type' => 'hidden',
            'name' => $name,
            'value' => $value
        ];
    }
    
    /**
     * Pridá výberové pole (dropdown) do modálneho okna
     * 
     * Používa sa, keď má užívateľ vybrať jednu možnosť z viacerých,
     * napríklad farbu alebo prioritu
     * 
     * @param string $name Názov poľa
     * @param string $label Popisok poľa
     * @param array $options Možnosti pre výber
     * @param string $selectedValue Vybraná hodnota
     * @param bool $required Či je pole povinné
     */
    public function addSelectField($name, $label, $options, $selectedValue = '', $required = false) {
        $this->fields[] = [
            'type' => 'select',
            'name' => $name,
            'label' => $label,
            'options' => $options,
            'selected' => $selectedValue,
            'required' => $required
        ];
    }
    
    /**
     * Pridá zaškrtávacie pole (checkbox) do modálneho okna
     * 
     * Pre áno/nie voľby, ako napríklad "Dokončené" alebo "Aktívne"
     * 
     * @param string $name Názov poľa
     * @param string $label Popisok poľa
     * @param bool $checked Či je pole zaškrtnuté
     */
    public function addCheckboxField($name, $label, $checked = false) {
        $this->fields[] = [
            'type' => 'checkbox',
            'name' => $name,
            'label' => $label,
            'checked' => $checked
        ];
    }
    
    /**
     * Vygeneruje HTML kód pre jedno pole podľa jeho typu
     * 
     * Táto metóda je asi najdôležitejšia, lebo tu sa generuje
     * HTML pre všetky typy polí. Je to dosť rozsiahle, ale vlastne
     * len generuje správne HTML značky podľa typu poľa.
     * 
     * @param array $field Dáta poľa
     * @return string HTML kód poľa
     */
    protected function renderField($field) {
        $html = '';
        
        switch ($field['type']) {
            case 'text':
                // Obyčajné textové pole - input type="text"
                $html .= '<div class="form-group">';
                $html .= '<label for="' . $field['name'] . '" class="col-sm-4 control-label">' . $field['label'] . '</label>';
                $html .= '<div class="col-sm-12">';
                $html .= '<input type="text" name="' . $field['name'] . '" class="form-control" id="' . $field['name'] . '" ';
                
                if (!empty($field['placeholder'])) {
                    $html .= 'placeholder="' . $field['placeholder'] . '" ';
                }
                
                if (!empty($field['value'])) {
                    $html .= 'value="' . $field['value'] . '" ';
                }
                
                if ($field['required']) {
                    $html .= 'required';
                }
                
                $html .= '>';
                // Pridáme div pre validačné správy - fajn pre JavaScript validáciu
                if ($field['name'] === 'title') {
                    $html .= '<div class="validation-message"></div>';
                }
                $html .= '</div>';
                $html .= '</div>';
                break;
                
            case 'textarea':
                // Viacriadkové textové pole - pre dlhšie texty
                $html .= '<div class="form-group">';
                $html .= '<label for="' . $field['name'] . '" class="col-sm-4 control-label">' . $field['label'] . '</label>';
                $html .= '<div class="col-sm-12">';
                $html .= '<textarea name="' . $field['name'] . '" class="form-control" id="' . $field['name'] . '" ';
                
                if (!empty($field['placeholder'])) {
                    $html .= 'placeholder="' . $field['placeholder'] . '" ';
                }
                
                if ($field['required']) {
                    $html .= 'required';
                }
                
                $html .= '>';
                
                if (!empty($field['value'])) {
                    $html .= $field['value'];
                }
                
                $html .= '</textarea>';
                $html .= '</div>';
                $html .= '</div>';
                break;
                
            case 'select':
                // Výberové pole (dropdown) - pre výber z viacerých možností
                $html .= '<div class="form-group">';
                $html .= '<label for="' . $field['name'] . '" class="col-sm-4 control-label">' . $field['label'] . '</label>';
                $html .= '<div class="col-sm-12">';
                
                // Pre výberové polia farieb, nastav farbu výberového poľa podľa vybranej hodnoty
                $selectStyle = '';
                $selectClass = 'form-control';
                $dataColor = '';
                
                if ($field['name'] === 'colour') {
                    $selectClass .= ' colour-select';
                    
                    if (!empty($field['selected'])) {
                        $selectStyle = 'style="background-color:' . $field['selected'] . '; color: white;"';
                        $dataColor = 'data-color="' . $field['selected'] . '"';
                    }
                }
                
                $html .= '<select name="' . $field['name'] . '" class="' . $selectClass . '" id="' . $field['name'] . '" ' . $selectStyle . ' ' . $dataColor;
                
                if ($field['required']) {
                    $html .= ' required';
                }
                
                $html .= '>';
                
                foreach ($field['options'] as $value => $option) {
                    $selected = ($value === $field['selected']) ? 'selected' : '';
                    
                    // Spracovanie rôznych formátov možností - môžu byť buď reťazce alebo polia
                    if (is_array($option)) {
                        $optionStyle = isset($option['style']) ? $option['style'] : '';
                        $optionText = isset($option['text']) ? $option['text'] : $value;
                    } else {
                        $optionStyle = '';
                        $optionText = $option;
                    }
                    
                    $html .= '<option value="' . $value . '" ' . $selected . ' style="' . $optionStyle . '">' . $optionText . '</option>';
                }
                
                $html .= '</select>';
                $html .= '</div>';
                $html .= '</div>';
                break;
                
            case 'date':
                // Dátumové pole - používa sa špeciálny date-picker pre lepší výber dátumu
                $html .= '<div class="form-group">';
                $html .= '<label for="' . $field['name'] . '" class="col-sm-4 control-label">' . $field['label'] . '</label>';
                $html .= '<div class="col-sm-12">';
                $html .= '<input type="text" name="' . $field['name'] . '" class="form-control date-picker" id="' . $field['name'] . '" ';
                
                if (!empty($field['value'])) {
                    $html .= 'value="' . $field['value'] . '" ';
                }
                
                if ($field['required']) {
                    $html .= 'required';
                }
                
                $html .= '>';
                $html .= '</div>';
                $html .= '</div>';
                break;
                
            case 'hidden':
                // Skryté pole - užívateľ ho nevidí, ale je súčasťou formulára
                $html .= '<input type="hidden" name="' . $field['name'] . '" id="' . $field['name'] . '" value="' . $field['value'] . '">';
                break;
                
            case 'checkbox':
                // Zaškrtávacie pole - pre áno/nie voľby
                $checked = $field['checked'] ? 'checked' : '';
                $html .= '<div class="form-group">';
                $html .= '<div class="col-sm-offset-4 col-sm-12">';
                $html .= '<div class="checkbox">';
                $html .= '<label>';
                $html .= '<input type="checkbox" name="' . $field['name'] . '" id="' . $field['name'] . '" ' . $checked . '> ' . $field['label'];
                $html .= '</label>';
                $html .= '</div>';
                $html .= '</div>';
                $html .= '</div>';
                break;
        }
        
        return $html;
    }
    
    /**
     * Vygeneruje telo modálneho okna s formulárovými poliami
     * 
     * Prejde všetky polia a pre každé zavolá renderField
     * 
     * @return string HTML kód tela modálneho okna
     */
    protected function renderBody() {
        $html = '<div class="modal-body">';
        
        foreach ($this->fields as $field) {
            $html .= $this->renderField($field);
        }
        
        $html .= '</div>';
        
        return $html;
    }
    
    /**
     * Vygeneruje kompletné modálne okno
     * 
     * Spojí hlavičku, telo a pätu do jedného HTML kódu
     * Toto je metóda, ktorú zavolajú potomkovia, aby získali
     * kompletný HTML kód modálneho okna.
     * 
     * @param string $submitText Text pre tlačidlo odoslania
     * @return string HTML kód kompletného modálneho okna
     */
    public function render($submitText = 'Save') {
        $html = '<div class="modal fade" id="' . $this->modalId . '" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">';
        $html .= '<div class="modal-dialog">';
        $html .= '<div class="modal-content">';
        
        $html .= $this->renderHeader();
        
        // Otvoríme formulár tu, aby zahŕňal telo aj pätu
        $html .= '<form class="form-horizontal" method="post" action="' . $this->action . '">';
        
        $html .= $this->renderBody();
        $html .= $this->renderFooter($submitText);
        
        // Zatvoríme formulár po päte
        $html .= '</form>';
        
        $html .= '</div>';
        $html .= '</div>';
        $html .= '</div>';
        
        return $html;
    }
}
?>