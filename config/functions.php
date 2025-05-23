<?php
/**
 * Funkcie pre prácu s konfiguráciou obrázkov
 */

/**
 * Získa konfiguráciu obrázkov z JSON súboru
 * 
 * @return array Asociatívne pole s konfiguráciou obrázkov
 */
function getImagesConfig() {
    static $imagesConfig = null;
    
    // Načítame konfiguráciu len raz
    if ($imagesConfig === null) {
        $jsonStr = file_get_contents(__DIR__ . "/images.json");
        $imagesConfig = json_decode($jsonStr, true);
    }
    
    return $imagesConfig;
}

/**
 * Získa cestu k obrázku na základe kategórie a názvu
 * 
 * @param string $category Kategória obrázku (logo, pages, backgrounds, icons, placeholders)
 * @param string $imageName Názov obrázku
 * @return string Cesta k obrázku alebo CSS trieda pre ikonu
 */
function getImagePath($category, $imageName) {
    $imagesConfig = getImagesConfig();
    
    // Kontrola existencie kategórie a obrázku
    if (isset($imagesConfig[$category]) && isset($imagesConfig[$category][$imageName])) {
        return $imagesConfig[$category][$imageName];
    }
    
    // Ak obrázok neexistuje, vrátime placeholder
    return $imagesConfig['placeholders']['default'];
}

/**
 * Vygeneruje HTML tag img na základe kategórie a názvu obrázku
 * 
 * @param string $category Kategória obrázku (logo, pages, backgrounds)
 * @param string $imageName Názov obrázku
 * @param string $alt Alternatívny text pre obrázok
 * @param string $class CSS trieda pre obrázok
 * @param array $attributes Ďalšie atribúty pre tag img (width, height, atď.)
 * @return string HTML kód s tagom img
 */
function displayImage($category, $imageName, $alt = "", $class = "", $attributes = []) {
    $imagePath = getImagePath($category, $imageName);
    $altText = htmlspecialchars($alt);
    $className = $class ? ' class="' . htmlspecialchars($class) . '"' : '';
    
    // Pridanie ďalších atribútov
    $attrStr = '';
    foreach($attributes as $key => $value) {
        $attrStr .= ' ' . htmlspecialchars($key) . '="' . htmlspecialchars($value) . '"';
    }
    
    return '<img src="' . $imagePath . '" alt="' . $altText . '"' . $className . $attrStr . '>';
}

/**
 * Vygeneruje HTML tag s FontAwesome ikonou
 * 
 * @param string $iconName Názov ikony
 * @param string $class Dodatočná CSS trieda
 * @return string HTML kód s ikonou
 */
function displayIcon($iconName, $class = "") {
    $iconClass = getImagePath('icons', $iconName);
    $className = $class ? ' ' . htmlspecialchars($class) : '';
    
    // Pridáme štýl priamo pre ikonu mesiaca
    $style = '';
    if ($iconName === 'moon') {
        $style = ' style="color: #4169E1 !important;"';
    }
    
    return '<i class="' . $iconClass . $className . '"' . $style . '></i>';
}

/**
 * Vygeneruje CSS pre pozadie na základe kategórie a názvu obrázku
 * 
 * @param string $category Kategória obrázku (najčastejšie 'backgrounds')
 * @param string $imageName Názov obrázku
 * @return string CSS hodnota pre background-image
 */
function getBackgroundStyle($category, $imageName) {
    $imagePath = getImagePath($category, $imageName);
    return "background-image: url('" . $imagePath . "');";
} 