<?php

/**
 * Header class for rendering page headers
 */
class Header {
    /**
     * Page title
     */
    private $title;
    
    /**
     * Additional CSS files
     */
    private $cssFiles = [];
    
    /**
     * Additional JavaScript files
     */
    private $jsFiles = [];
    
    /**
     * Flag to use combined files for better performance
     */
    private $useCombinedFiles = true;
    
    /**
     * Constructor
     * 
     * @param string $title Page title
     */
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
    
    /**
     * Set the page title
     * 
     * @param string $title Page title
     * @return self
     */
    public function setTitle($title) {
        $this->title = $title;
        return $this;
    }
    
    /**
     * Add a CSS file
     * 
     * @param string $url URL of the CSS file
     * @param bool $integrity Whether to add integrity attribute
     * @return self
     */
    public function addCssFile($url, $integrity = false) {
        $this->cssFiles[] = [
            'url' => $url,
            'integrity' => $integrity
        ];
        return $this;
    }
    
    /**
     * Add a JavaScript file
     * 
     * @param string $url URL of the JavaScript file
     * @param bool $defer Whether to add defer attribute
     * @return self
     */
    public function addJsFile($url, $defer = false) {
        $this->jsFiles[] = [
            'url' => $url,
            'defer' => $defer
        ];
        return $this;
    }
    
    /**
     * Render meta tags
     * 
     * @return string HTML for meta tags
     */
    protected function renderMetaTags() {
        $html = '<!-- Meta tagy pre správne zobrazenie stránky -->' . PHP_EOL;
        $html .= '<meta charset="UTF-8">' . PHP_EOL;
        $html .= '<meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">' . PHP_EOL;
        return $html;
    }
    
    /**
     * Render CSS links
     * 
     * @return string HTML for CSS links
     */
    protected function renderCssLinks() {
        $html = '';
        
        // Add Font Awesome
        $html .= '<link href="' . $this->cssFiles[0]['url'] . '" rel="stylesheet">' . PHP_EOL;
        
        // Add Bootstrap
        $html .= '<!-- Bootstrap framework pre štýly -->' . PHP_EOL;
        $html .= '<link rel="stylesheet" href="' . $this->cssFiles[1]['url'] . '" media="all"';
        if ($this->cssFiles[1]['integrity']) {
            $html .= ' integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous"';
        }
        $html .= '>' . PHP_EOL;
        
        // Add fonts
        $html .= '<!-- Importované písma -->' . PHP_EOL;
        $html .= '<link href="' . $this->cssFiles[2]['url'] . '" rel="stylesheet">' . PHP_EOL;
        $html .= '<link href="' . $this->cssFiles[3]['url'] . '" rel="stylesheet">' . PHP_EOL;
        
        // Add custom styles - either combined or individual
        $html .= '<!-- Vlastné CSS štýly -->' . PHP_EOL;
        
        if ($this->useCombinedFiles) {
            // Use the combined file with a version parameter for cache busting
            $version = filemtime(__DIR__ . '/../../../public/css/style.css') . 
                      filemtime(__DIR__ . '/../../../public/css/dynamic-theme.css');
            $html .= '<link rel="stylesheet" href="/public/css/combine.php?v=' . $version . '">' . PHP_EOL;
        } else {
            // Add individual CSS files
            for ($i = 4; $i < count($this->cssFiles); $i++) {
                $html .= '<link rel="stylesheet" href="' . $this->cssFiles[$i]['url'] . '">' . PHP_EOL;
            }
        }
        
        return $html;
    }
    
    /**
     * Render the title
     * 
     * @return string HTML for title
     */
    protected function renderTitle() {
        return '<title>' . $this->title . '</title>' . PHP_EOL;
    }
    
    /**
     * Render theme switch script reference
     * 
     * @return string HTML for including theme switch script
     */
    protected function renderThemeSwitchScript() {
        $html = '<!-- JavaScript pre tmavý režim -->' . PHP_EOL;
        
        if ($this->useCombinedFiles) {
            // We'll include the theme switch script in the combined JS file
            return '';
        }
        
        $html .= '<script src="/public/js/theme-switch.js"></script>' . PHP_EOL;
        return $html;
    }
    
    /**
     * Render additional JavaScript files
     * 
     * @return string HTML for JavaScript includes
     */
    protected function renderJsIncludes() {
        $html = '';
        
        if ($this->useCombinedFiles) {
            // Use the combined file with a version parameter for cache busting
            $version = filemtime(__DIR__ . '/../../../public/js/theme-switch.js') . 
                      filemtime(__DIR__ . '/../../../public/js/dynamic-theme.js');
            $html .= '<script src="/public/js/combine.php?v=' . $version . '"></script>' . PHP_EOL;
            
            // Include any additional JS files that aren't part of the combined set
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
    
    /**
     * Render the complete header
     * 
     * @return string Complete HTML for header
     */
    public function render() {
        $html = $this->renderMetaTags();
        $html .= $this->renderCssLinks();
        $html .= $this->renderTitle();
        $html .= $this->renderThemeSwitchScript();
        $html .= $this->renderJsIncludes();
        
        return $html;
    }
    
    /**
     * Display the header
     */
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