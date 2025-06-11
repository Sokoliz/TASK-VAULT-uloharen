<?php 
require_once('View.php');

// Trieda LoginView pre zobrazenie prihlasovacej stránky
// Táto stránka umožňuje používateľom prihlásiť sa do systému
class LoginView extends View {
	
	public function __construct($data = []) {
		parent::__construct('Productivity Hub', true, $data);
	}
	
	
	protected function renderLoginForm() {
		$errors = $this->getData('errors', '');
		
		$html = '<div class="col-6 p-5 justify-content-center">';
		$html .= '<p class="text-center h1 fw-bold m-5">LOG IN</p>';
		$html .= '<form id="login" class="px-5" name="login" action="/login" method="POST">';
		
		// Pole pre používateľské meno
		// Ikona používateľa pekne vizuálne dopĺňa formulár
		$html .= '<div class="mb-4">';
		$html .= '<div class="input-group">';
		$html .= '<div class="input-group-prepend">';
		$html .= '<span class="btn-sign-up text-light input-group-text"><small><i class="fas fa-user"></i></small></span>';
		$html .= '</div>';
		$html .= '<input class="form-control" type="text" name="username" placeholder="Username" required>';
		$html .= '</div>';
		$html .= '</div>';
		
		// Pole pre heslo
		// Ikona zámku symbolizuje bezpečnosť
		$html .= '<div class="mb-4">';
		$html .= '<div class="input-group">';
		$html .= '<div class="input-group-prepend">';
		$html .= '<span class="btn-sign-up text-light input-group-text"><small><i class="fas fa-lock"></i></small></span>';
		$html .= '</div>';
		$html .= '<input class="form-control" type="password" name="password" placeholder="Password" required>';
		$html .= '</div>';
		$html .= '</div>';
		
		// Tlačidlo pre odoslanie formulára
		$html .= '<div class="d-flex justify-content-center mx-4 mb-3 mb-lg-4">';
		$html .= '<button type="submit" class="btn btn-primary">LOG IN</button>';
		$html .= '</div>';
		
		// Zobrazenie chybových hlášok, ak existujú
		// Ak validácia zlyhá, tu sa zobrazia chyby
		if (!empty($errors)) {
			$html .= '<div class="err">';
			$html .= '<ul>' . $errors . '</ul>';
			$html .= '</div>';
		}
		
		$html .= '</form>';
		
		// Odkaz na registráciu
		// Pre nových používateľov, ktorí ešte nemajú účet
		$html .= '<span class="d-flex justify-content-center">Don\'t you have an account?<a class="nav-link text-primary m-0 p-0 pl-2" href="/register">Sign Up</a></span>';
		$html .= '</div>';
		
		return $html;
	}
	
	/**
	 * Renderuje stĺpec s obrázkom
	 * Obrázok vizuálne vylepšuje prihlasovaciu stránku
	 * 
	 * @return string HTML for the image column
	 */
	protected function renderImageColumn() {
		return '<div class="col-6 p-3 mt-5">
			 <img class="img-fluid" src="/public/img/login.jpg">
		</div>';
	}
	
	/**
	 * Renderuje sekciu obsahu
	 * Tu poskladáme celú prihlasovaciu stránku
	 * 
	 * @return string HTML for the content section
	 */
	protected function renderContent() {
		require_once __DIR__.'/../parts/navbar.php';
		$navbar = new Navbar(true, false, false, 'simple');
		$html = $navbar->render();
		$html .= '<div class="container">';
		$html .= '<div class="row m-0 p-0">';
		$html .= $this->renderLoginForm();
		$html .= $this->renderImageColumn();
		$html .= '</div>';
		$html .= '</div>';
		return $html;
	}
}
?>