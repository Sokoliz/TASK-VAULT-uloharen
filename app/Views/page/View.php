<?php
// Základná trieda View pre renderovanie stránok
// Táto trieda slúži ako základ pre všetky ostatné view triedy
class View {
    // Titul stránky, zobrazuje sa v hlavičke prehliadača
    protected $title;
    
    // Určuje, či je stránka verejná (nepotrebuje prihlásenie)
    // Ak je true, tak sa používateľ nemusí prihlasovať
    protected $isPublic;
    
    // Dáta, ktoré sa budú posielať do view
    // Môže obsahovať rôzne informácie z modelu alebo kontroléra
    protected $data;
    
    // Konštruktor triedy - inicializuje základné vlastnosti
    public function __construct($title = 'Productivity Hub', $isPublic = false, $data = []) {
        $this->title = $title;
        $this->isPublic = $isPublic;
        $this->data = $data;
    }
    
    // Renderuje sekciu hlavičky stránky
    // Táto metóda načíta header.php a vráti vygenerované HTML
    protected function renderHead() {
        ob_start();
        $title = $this->title; // Pre použitie v includovanom súbore
        $public_page = $this->isPublic; // Pre použitie v includovanom súbore
        require_once __DIR__."/../parts/header.php";
        return ob_get_clean();
    }
    
    // Renderuje pätičku stránky
    // Načíta footer.php a vráti jeho obsah
    protected function renderFooter() {
        // Includneme footer.php a zachytíme jeho výstup
        ob_start();
        include_once __DIR__.'/../parts/footer.php';
        return ob_get_clean();
    }
    
    // Renderuje JavaScript importy
    // Tieto skripty sú potrebné pre fungovanie Bootstrap komponentov
    protected function renderScripts() {
        return '<script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>';
    }
    
    // Renderuje navigačný panel
    // Rôzne typy navbarov - štandardný, s info o používateľovi, jednoduchý
    protected function renderNavbar($type = 'standard', $showHomeButton = true, $showThemeSwitch = true) {
        // Nastavíme premenné pre navbar.php
        $isPublic = $this->isPublic;
        $navbarType = $type;
        
        // Includneme navbar.php a zachytíme jeho výstup
        ob_start();
        include_once __DIR__.'/../parts/navbar.php';
        return ob_get_clean();
    }
    
    // Renderuje celú stránku
    // Táto metóda poskladá dokopy všetky časti stránky - hlavičku, obsah, pätičku
    public function render() {
        $html = '<!DOCTYPE html>';
        $html .= '<html lang="en">';
        
        // Sekcia head
        $html .= '<head>';
        $html .= $this->renderHead();
        $html .= '</head>';
        
        // Sekcia body
        $html .= '<body>';
        
        // Obsah implementovaný v potomkovských triedach
        $html .= $this->renderContent();
        
        // Sekcia footer
        $html .= $this->renderFooter();
        
        // JavaScript importy
        $html .= $this->renderScripts();
        
        $html .= '</body>';
        $html .= '</html>';
        
        return $html;
    }
    
    // Renderuje obsah stránky (má byť implementovaný v potomkovských triedach)
    // Táto metóda je prázdna, pretože každá stránka má iný obsah
    protected function renderContent() {
        return '';
    }
    
    // Zobrazí stránku - vypíše výsledný HTML kód
    // Jednoduchá helper metóda, ktorá zavolá render() a echne výsledok
    public function display() {
        echo $this->render();
    }
    
    // Získa hodnotu z poľa dát
    // Pomáha bezpečne pristupovať k dátam, aj keď kľúč neexistuje
    protected function getData($key, $default = null) {
        return isset($this->data[$key]) ? $this->data[$key] : $default;
    }
}
?>