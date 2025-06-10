<?php
namespace App\Views\Shared;

// Import triedy Utility
require_once __DIR__.'/../../../config/Utility.php';
use App\Utility\Utility;

/**
 * DynamicStyles class
 * 
 * Generuje dynamické CSS štýly na základe konfigurácie
 */
class DynamicStyles 
{
    /**
     * Konfigurácia obrázkov
     */
    private $imagesConfig;
    
    /**
     * Konštruktor
     */
    public function __construct() 
    {
        // Nastavenie typu obsahu na CSS
        header('Content-Type: text/css');

        // Získanie konfigurácie priamo z Utility triedy
        $this->imagesConfig = Utility::getImagesConfig();
    }
    
    /**
     * Generuje CSS pre pozadie
     * 
     * @return string CSS pre pozadie
     */
    protected function generateBackgroundCSS() 
    {
        $bgImagePath = $this->imagesConfig['backgrounds']['main'];
        
        $css = ".bg {\n";
        $css .= "    background-image: url('/$bgImagePath');\n";
        $css .= "}\n\n";
        
        return $css;
    }
    
    /**
     * Generuje CSS pre tmavý režim
     * 
     * @return string CSS pre tmavý režim
     */
    protected function generateDarkModeCSS() 
    {
        $bgImagePath = $this->imagesConfig['backgrounds']['main'];
        
        $css = ".dark-mode .bg {\n";
        $css .= "    background-image: linear-gradient(rgba(0, 0, 0, 0.7), rgba(0, 0, 0, 0.7)), url('/$bgImagePath');\n";
        $css .= "}\n";
        
        return $css;
    }
    
    /**
     * Generuje CSS pre stránky
     * 
     * @return string CSS pre stránky
     */
    protected function generatePagesCSS() 
    {
        $css = "";
        
        // Generujeme CSS pre každú stránku
        if (isset($this->imagesConfig['pages'])) {
            foreach ($this->imagesConfig['pages'] as $page => $imagePath) {
                $css .= ".bg-$page {\n";
                $css .= "    background-image: url('/$imagePath');\n";
                $css .= "    background-size: cover;\n";
                $css .= "    background-position: center;\n";
                $css .= "}\n\n";
                
                // CSS pre tmavý režim
                $css .= ".dark-mode .bg-$page {\n";
                $css .= "    background-image: linear-gradient(rgba(0, 0, 0, 0.7), rgba(0, 0, 0, 0.7)), url('/$imagePath');\n";
                $css .= "}\n\n";
            }
        }
        
        return $css;
    }
    
    /**
     * Generuje kompletné CSS
     * 
     * @return string Kompletné CSS
     */
    public function generate() 
    {
        $css = $this->generateBackgroundCSS();
        $css .= $this->generateDarkModeCSS();
        $css .= $this->generatePagesCSS();
        
        return $css;
    }
    
    /**
     * Zobrazí CSS
     */
    public function render() 
    {
        echo $this->generate();
    }
}

// Inicializácia a spustenie
$dynamicStyles = new DynamicStyles();
$dynamicStyles->render(); 