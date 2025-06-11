<?php 
require_once('View.php');

// Trieda RegisterView pre zobrazenie registračnej stránky
// Táto stránka umožňuje novým používateľom vytvoriť si účet
class RegisterView extends View {
	
	public function __construct($data = []) {
		parent::__construct('Productivity Hub', true, $data);
	}
	
	
	protected function renderRegisterForm() {
		$errors = $this->getData('errors', '');
		
		$html = '<div class="col-6 p-5 justify-content-center">';
		$html .= '<p class="text-center h1 fw-bold m-5">SIGN UP</p>';
		$html .= '<form class="px-5" name="signup" action="/register" method="POST">';
		
		// Pole pre používateľské meno
		// Toto musí byť unikátne v systéme
		$html .= '<div class="mb-4">';
		$html .= '<div class="input-group">';
		$html .= '<div class="input-group-prepend">';
		$html .= '<span class="btn-sign-up text-light input-group-text"><small><i class="fas fa-user"></i></small></span>';
		$html .= '</div>';
		$html .= '<input class="form-control" type="text" name="username" placeholder="Username" required>';
		$html .= '</div>';
		$html .= '</div>';
		
		// Pole pre heslo
		// Heslo by malo byť dostatočne silné
		$html .= '<div class="mb-4">';
		$html .= '<div class="input-group">';
		$html .= '<div class="input-group-prepend">';
		$html .= '<span class="btn-sign-up text-light input-group-text"><small><i class="fas fa-lock"></i></small></span>';
		$html .= '</div>';
		$html .= '<input class="form-control" type="password" id="password" name="password" placeholder="Password" required>';
		$html .= '</div>';
		$html .= '</div>';
		
		// Pole pre potvrdenie hesla
		// Musí sa zhodovať s prvým heslom
		$html .= '<div class="mb-4">';
		$html .= '<div class="input-group">';
		$html .= '<div class="input-group-prepend">';
		$html .= '<span class="btn-sign-up text-light input-group-text"><small><i class="fas fa-key"></i></small></span>';
		$html .= '</div>';
		$html .= '<input class="form-control" type="password" id="password2" name="password2" placeholder="Confirm password" required>';
		$html .= '</div>';
		$html .= '</div>';
		
		// Upozornenie na nezhodujúce sa heslá
		// Zobrazí sa pomocou JavaScriptu, keď sa heslá nezhodujú
		$html .= '<div class="mb-4 d-none" id="password-warning">';
		$html .= '<div class="alert alert-danger" role="alert">';
		$html .= 'Hesla sa nezhodujú. Prosím, zadajte rovnaké heslo.';
		$html .= '</div>';
		$html .= '</div>';
		
		// Tlačidlo pre odoslanie formulára
		$html .= '<div class="d-flex justify-content-center mx-4 mb-3 mb-lg-4">';
		$html .= '<input type="submit" value="Register" class="btn btn-primary">';
		$html .= '</div>';
		
		// Zobrazenie chybových hlášok, ak existujú
		// Ak validácia zlyhá, tu sa zobrazia chyby
		if (!empty($errors)) {
			$html .= '<div class="err">';
			$html .= '<ul>' . $errors . '</ul>';
			$html .= '</div>';
		}
		
		$html .= '</form>';
		
		// Odkaz na prihlásenie
		// Pre existujúcich používateľov, ktorí už majú účet
		$html .= '<span class="d-flex justify-content-center">Already have an account?<a class="nav-link text-primary m-0 p-0 pl-2" href="/login">Log in</a></span>';
		
		// Pridanie referencií na skripty
		// Tieto skripty zabezpečujú validáciu formulára
		$html .= '<script src="/public/js/password-validation.js"></script>';
		// Validator pre používateľské meno sa načítava v headeri
		
		$html .= '</div>';
		
		return $html;
	}
	
	/**
	 * Renderuje stĺpec s obrázkom
	 * Vizuálne vylepšuje registračnú stránku
	 * 
	 * @return string HTML for the image column
	 */
	protected function renderImageColumn() {
		return '<div class="col-6 p-5">
			<img class="img-fluid pl-5" src="/public/img/register.jpg">		
		</div>';
	}
	
	/**
	 * Renderuje sekciu obsahu
	 * Tu poskladáme celú registračnú stránku
	 * 
	 * @return string HTML for the content section
	 */
	protected function renderContent() {
		require_once __DIR__.'/../parts/navbar.php';
		$navbar = new Navbar(true, false, false, 'simple');
		$html = $navbar->render();
		$html .= '<div class="container">';
		$html .= '<div class="row m-0 p-0">';
		$html .= $this->renderImageColumn();
		$html .= $this->renderRegisterForm();
		$html .= '</div>';
		$html .= '</div>';
		return $html;
	}
	
	/**
	 * Override render method to add register-specific body class
	 * 
	 * @return string Complete HTML for the page
	 */
	public function render() {
		$html = '<!DOCTYPE html>';
		$html .= '<html lang="en">';
		
		// Sekcia head
		$html .= '<head>';
		$html .= $this->renderHead();
		$html .= '</head>';
		
		// Sekcia body so špeciálnou triedou pre register
		$html .= '<body class="bg bg-register">';
		
		// Obsah
		$html .= $this->renderContent();
		
		// Sekcia footer
		$html .= $this->renderFooter();
		
		// JavaScript importy
		$html .= $this->renderScripts();
		
		$html .= '</body>';
		$html .= '</html>';
		
		return $html;
	}
}
?>