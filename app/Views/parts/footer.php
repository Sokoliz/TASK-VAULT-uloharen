<?php


class Footer {
	
	private $copyrightText;
	
	
	private $year;
	
	
	private $checkLogin;
	
	
	public function __construct($copyrightText = 'Productivity Hub', $year = 2025, $checkLogin = true) {
		$this->copyrightText = $copyrightText;
		$this->year = $year;
		$this->checkLogin = $checkLogin;
	}
	
	
	public function setCopyrightText($text) {
		$this->copyrightText = $text;
		return $this;
	}
	
	
	public function setYear($year) {
		$this->year = $year;
		return $this;
	}
	
	
	public function setCheckLogin($check) {
		$this->checkLogin = $check;
		return $this;
	}
	
	
	protected function isUserLoggedIn() {
		return isset($_SESSION['user']);
	}
	
	
	protected function renderFooterContent() {
		$html = '<footer>' . PHP_EOL;
		$html .= '    <div class="row m-0 p-0 d-flex justify-content-center">' . PHP_EOL;
		$html .= '        <div class="col-12">' . PHP_EOL;
		$html .= '            <div class="container d-flex justify-content-center">' . PHP_EOL;
		$html .= '                <ul class="list-unstyled list-inline text-center d-flex justify-content-center align-items-center">' . PHP_EOL;
		$html .= '                    <small><span class="ml-2">' . $this->copyrightText . ' © ' . $this->year . ' All Rights Reserved.</span></small>' . PHP_EOL;
		$html .= '                </ul>' . PHP_EOL;
		$html .= '            </div>' . PHP_EOL;
		$html .= '        </div>' . PHP_EOL;
		$html .= '    </div>' . PHP_EOL;
		$html .= '</footer>' . PHP_EOL;
		
		return $html;
	}
	
	
	public function render() {
		// Check if user is logged in if required
		if ($this->checkLogin && !$this->isUserLoggedIn()) {
			return '<?php
// Presmerovanie neprihlásených používateľov na hlavnú stránku
header(\'Location: /login\');
die();
?>';
}

// Render regular footer
$html = '';
if ($this->checkLogin) {
$html .= '<?php
// Kontrola, či je používateľ prihlásený
if (isset($_SESSION[\'user\'])) {
?>' . PHP_EOL;
}

$html .= '
<!-- Päta stránky s informáciami o autorských právach -->' . PHP_EOL;
$html .= $this->renderFooterContent();

if ($this->checkLogin) {
$html .= PHP_EOL . '<?php 
} else {
	// Presmerovanie neprihlásených používateľov na hlavnú stránku
	header(\'Location: /login\');
	die();
}
?>';
}

return $html;
}


public function display() {
echo $this->render();
}
}

// For backward compatibility with existing code
if (!isset($footer)) {
$footer = new Footer();
$footer->display();
}
?>