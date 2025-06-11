<?php

// Táto trieda sa stará o vykreslenie hlavičky stránky - obsahuje všetko čo treba v <head>
class Header {
    // Title stránky, bude v tagu <title>
    private $title;
    
    // Extra CSS súbory, ktoré sa pridávajú do hlavičky
    private $cssFiles = [];
    
    // JS súbory, ktoré budeme pridávať do stránky
    private $jsFiles = [];
    
    // Flag či použijeme skombinované súbory pre lepší výkon - načíta to len jeden veľký súbor namiesto viacerých malých
    private $useCombinedFiles = true;
    
    // Konštruktor triedy, nastaví základný title a pridá potrebné CSS a JS súbory
    // title - nadpis stránky, defaultne 'Productivity Hub'
    public function __construct($title = 'Productivity Hub') {
        $this->title = $title;
        
        // Default CSS files (external CDN)
        $this->addCssFile('https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css');
        $this->addCssFile('https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css', true);
        $this->addCssFile('https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600&family=Open+Sans:wght@300;400;600&display=swap');
        $this->addCssFile('https://fonts.googleapis.com/css2?family=Comfortaa&display=swap');
        
        // Local CSS files - we'll combine these for better performance
        if (!$this->useCombinedFiles) {
            $this->addCssFile('/public/css/style.css');
            $this->addCssFile('/public/css/dynamic-theme.css');
            $this->addCssFile('/public/css/components.css');
            $this->addCssFile('/public/css/theme-icons.css');
            $this->addCssFile('/public/css/select-colors.css');
        }
        
        // Local JavaScript files - we'll combine these for better performance
        if (!$this->useCombinedFiles) {
            $this->addJsFile('/public/js/theme-switch.js');
            $this->addJsFile('/public/js/dynamic-theme.js');
            $this->addJsFile('/public/js/event-modal.js');
        }
        
        // Add username validator for registration page
        if (strpos($_SERVER['REQUEST_URI'], '/register') !== false) {
            $this->addJsFile('/public/js/username-validator.js');
        }
    }
    
    // Nastav title stránky - užitočné keď potrebuješ zmeniť nadpis dynamicky
    // title - nový nadpis stránky
    // vráti $this aby sa dalo reťaziť volanie metód
    public function setTitle($title) {
        $this->title = $title;
        return $this;
    }
    
    // Pridá CSS súbor do zoznamu súborov na načítanie
    // url - cesta k CSS súboru
    // integrity - či treba pridať integrity check (pre CDN súbory)
    // vráti $this aby sa dalo reťaziť volanie metód
    public function addCssFile($url, $integrity = false) {
        $this->cssFiles[] = [
            'url' => $url,
            'integrity' => $integrity
        ];
        return $this;
    }
    
    // Pridá JavaScript súbor do zoznamu súborov na načítanie
    // url - cesta k JS súboru
    // defer - či sa má súbor načítať neskôr (neblokuje vykreslenie stránky)
    // vráti $this aby sa dalo reťaziť volanie metód
    public function addJsFile($url, $defer = false) {
        $this->jsFiles[] = [
            'url' => $url,
            'defer' => $defer
        ];
        return $this;
    }
    
    // Vygeneruje meta tagy pre HTML hlavičku
    // fakt dôležité pre správne zobrazenie na mobiloch a encoding
    protected function renderMetaTags() {
        $html = '<!-- Meta tagy pre správne zobrazenie stránky -->' . PHP_EOL;
        $html .= '<meta charset="UTF-8">' . PHP_EOL;
        $html .= '<meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">' . PHP_EOL;
        return $html;
    }
    
    // Vygeneruje HTML kód pre všetky CSS linky
    // najprv načíta externé CDN knižnice a potom naše vlastné súbory
    protected function renderCssLinks() {
        $html = '';
        
        
        $html .= '<link href="' . $this->cssFiles[0]['url'] . '" rel="stylesheet">' . PHP_EOL;
        
        
        $html .= '<!-- Bootstrap framework pre štýly -->' . PHP_EOL;
        $html .= '<link rel="stylesheet" href="' . $this->cssFiles[1]['url'] . '" media="all"';
        if ($this->cssFiles[1]['integrity']) {
            $html .= ' integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous"';
        }
        $html .= '>' . PHP_EOL;
        
        
        $html .= '<!-- Importované písma -->' . PHP_EOL;
        $html .= '<link href="' . $this->cssFiles[2]['url'] . '" rel="stylesheet">' . PHP_EOL;
        $html .= '<link href="' . $this->cssFiles[3]['url'] . '" rel="stylesheet">' . PHP_EOL;
        
        
        $html .= '<!-- Vlastné CSS štýly -->' . PHP_EOL;
        
        if ($this->useCombinedFiles) {
            
            $version = filemtime(__DIR__ . '/../../../public/css/style.css') . 
                      filemtime(__DIR__ . '/../../../public/css/dynamic-theme.css');
            $html .= '<link rel="stylesheet" href="/public/css/combine.php?v=' . $version . '">' . PHP_EOL;
        } else {
            
            for ($i = 4; $i < count($this->cssFiles); $i++) {
                $html .= '<link rel="stylesheet" href="' . $this->cssFiles[$i]['url'] . '">' . PHP_EOL;
            }
        }
        
        return $html;
    }
    
    // Generuje HTML tag pre title stránky
    // jednoduchá vec ale dôležitá pre SEO aj UX
    protected function renderTitle() {
        return '<title>' . $this->title . '</title>' . PHP_EOL;
    }
    
    // Pridá JavaScript pre prepínanie svetlej/tmavej témy
    // musí byť skoro na začiatku, aby sme predišli "flash of unstyled content"
    protected function renderThemeSwitchScript() {
        $html = '<!-- JavaScript pre tmavý režim -->' . PHP_EOL;
        
        if ($this->useCombinedFiles) {
            // We'll include the theme switch script in the combined JS file
            return '';
        }
        
        $html .= '<script src="/public/js/theme-switch.js"></script>' . PHP_EOL;
        return $html;
    }
    
    // Pridá zvyšné JavaScript súbory
    // niektoré načítame s atribútom defer, aby neblokovali vykresľovanie stránky
    protected function renderJsIncludes() {
        $html = '';
        
        if ($this->useCombinedFiles) {
            
            $version = filemtime(__DIR__ . '/../../../public/js/theme-switch.js') . 
                      filemtime(__DIR__ . '/../../../public/js/dynamic-theme.js');
            $html .= '<script src="/public/js/combine.php?v=' . $version . '"></script>' . PHP_EOL;
            
            
            $skip = ['/public/js/theme-switch.js', '/public/js/dynamic-theme.js', '/public/js/event-modal.js',
                    '/public/js/theme.js', '/public/js/clock.js', '/public/js/form-validation.js', 
                    '/public/js/modal-handler.js', '/public/js/delete-task-handler.js', 
                    '/public/js/delete-project-handler.js', '/public/js/datepicker-init.js'];
            
            foreach ($this->jsFiles as $jsFile) {
                if (!in_array($jsFile['url'], $skip)) {
                    $html .= '<script src="' . $jsFile['url'] . '"';
                    if ($jsFile['defer']) {
                        $html .= ' defer';
                    }
                    $html .= '></script>' . PHP_EOL;
                }
            }
        } else {
            // Skip the theme-switch.js which is already included
            $skip = ['/public/js/theme-switch.js'];
            
            foreach ($this->jsFiles as $jsFile) {
                // Skip if already included by renderThemeSwitchScript
                if (in_array($jsFile['url'], $skip)) {
                    continue;
                }
                
                $html .= '<script src="' . $jsFile['url'] . '"';
                if ($jsFile['defer']) {
                    $html .= ' defer';
                }
                $html .= '></script>' . PHP_EOL;
            }
        }
        
        return $html;
    }
    
    // Vykreslí kompletný obsah hlavičky <head>
    // vlastne len spojí výstupy všetkých renderovacích metód
    public function render() {
        $html = $this->renderMetaTags();
        $html .= $this->renderCssLinks();
        $html .= $this->renderTitle();
        $html .= $this->renderThemeSwitchScript();
        $html .= $this->renderJsIncludes();
        
        return $html;
    }
    
    // Vypíše hlavičku priamo na výstup
    // praktická metóda keď nechceme len vrátiť HTML, ale rovno ho aj vypísať
    public function display() {
        echo $this->render();
    }
}

// For backward compatibility with existing code
if (!isset($header) && isset($title)) {
    $header = new Header($title);
    $header->display();
}
?>