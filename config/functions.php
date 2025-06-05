<?php
/**
 * Funkcie pre prácu s konfiguráciou obrázkov
 */

//Získa konfiguráciu obrázkov z JSON súboru

function getImagesConfig() {
    static $imagesConfig = null;
    
    // Načítame konfiguráciu len raz
    if ($imagesConfig === null) {
        $jsonStr = file_get_contents(__DIR__ . "/images.json");
        $imagesConfig = json_decode($jsonStr, true);
    }
    
    return $imagesConfig;
}

// Získa cestu k obrázku na základe kategórie a názvu
 
function getImagePath($category, $imageName) {
    $imagesConfig = getImagesConfig();
    
    // Kontrola existencie kategórie a obrázku
    if (isset($imagesConfig[$category]) && isset($imagesConfig[$category][$imageName])) {
        return $imagesConfig[$category][$imageName];
    }
    
    // Ak obrázok neexistuje, vrátime placeholder
    return $imagesConfig['placeholders']['default'];
}

//Vygeneruje HTML tag img na základe kategórie a názvu obrázku
 
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

//Vygeneruje HTML tag s FontAwesome ikonou
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

//Vygeneruje CSS pre pozadie na základe kategórie a názvu obrázku

function getBackgroundStyle($category, $imageName) {
    $imagePath = getImagePath($category, $imageName);
    return "background-image: url('" . $imagePath . "');";
} 