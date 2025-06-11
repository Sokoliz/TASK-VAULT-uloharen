<?php 
require_once('View.php');

/**
 * RegisterView class for rendering the registration page
 */
class RegisterView extends View {
	/**
	 * Constructor
	 * 
	 * @param array $data Data to be passed to the view
	 */
	public function __construct($data = []) {
		parent::__construct('Productivity Hub', true, $data);
	}
	
	/**
	 * Render the registration form
	 * 
	 * @return string HTML for the registration form
	 */
	protected function renderRegisterForm() {
		$errors = $this->getData('errors', '');
		
		$html = '<div class="col-6 p-5 justify-content-center">';
		$html .= '<p class="text-center h1 fw-bold m-5">SIGN UP</p>';
		$html .= '<form class="px-5" name="signup" action="/register" method="POST">';
		
		// Username field
		$html .= '<div class="mb-4">';
		$html .= '<div class="input-group">';
		$html .= '<div class="input-group-prepend">';
		$html .= '<span class="btn-sign-up text-light input-group-text"><small><i class="fas fa-user"></i></small></span>';
		$html .= '</div>';
		$html .= '<input class="form-control" type="text" name="username" placeholder="Username" required>';
		$html .= '</div>';
		$html .= '</div>';
		
		// Password field
		$html .= '<div class="mb-4">';
		$html .= '<div class="input-group">';
		$html .= '<div class="input-group-prepend">';
		$html .= '<span class="btn-sign-up text-light input-group-text"><small><i class="fas fa-lock"></i></small></span>';
		$html .= '</div>';
		$html .= '<input class="form-control" type="password" id="password" name="password" placeholder="Password" required>';
		$html .= '</div>';
		$html .= '</div>';
		
		// Confirm password field
		$html .= '<div class="mb-4">';
		$html .= '<div class="input-group">';
		$html .= '<div class="input-group-prepend">';
		$html .= '<span class="btn-sign-up text-light input-group-text"><small><i class="fas fa-key"></i></small></span>';
		$html .= '</div>';
		$html .= '<input class="form-control" type="password" id="password2" name="password2" placeholder="Confirm password" required>';
		$html .= '</div>';
		$html .= '</div>';
		
		// Password match warning
		$html .= '<div class="mb-4 d-none" id="password-warning">';
		$html .= '<div class="alert alert-danger" role="alert">';
		$html .= 'Hesla sa nezhodujú. Prosím, zadajte rovnaké heslo.';
		$html .= '</div>';
		$html .= '</div>';
		
		// Submit button
		$html .= '<div class="d-flex justify-content-center mx-4 mb-3 mb-lg-4">';
		$html .= '<input type="submit" value="Register" class="btn btn-primary">';
		$html .= '</div>';
		
		// Display errors if any
		if (!empty($errors)) {
			$html .= '<div class="err">';
			$html .= '<ul>' . $errors . '</ul>';
			$html .= '</div>';
		}
		
		$html .= '</form>';
		
		// Link to login
		$html .= '<span class="d-flex justify-content-center">Already have an account?<a class="nav-link text-primary m-0 p-0 pl-2" href="/login">Log in</a></span>';
		
		// Add script references
		$html .= '<script src="/public/js/password-validation.js"></script>';
		// Username validator is now loaded in the header
		
		$html .= '</div>';
		
		return $html;
	}
	
	/**
	 * Render the image column
	 * 
	 * @return string HTML for the image column
	 */
	protected function renderImageColumn() {
		return '<div class="col-6 p-5">
			<img class="img-fluid pl-5" src="/public/img/register.jpg">		
		</div>';
	}
	
	/**
	 * Render the content section
	 * 
	 * @return string HTML for the content section
	 */
	protected function renderContent() {
		// Nastavenie premenných pre navbar.php
		$isPublic = true;
		$showHomeButton = false;
		$showThemeSwitch = false;
		$navbarType = 'simple';
		
		// Include navbar.php
		ob_start();
		include_once __DIR__.'/../parts/navbar.php';
		$html = ob_get_clean();
		
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
		
		// Head section
		$html .= '<head>';
		$html .= $this->renderHead();
		$html .= '</head>';
		
		// Body section with register-specific class
		$html .= '<body class="bg bg-register">';
		
		// Content
		$html .= $this->renderContent();
		
		// Footer section
		$html .= $this->renderFooter();
		
		// JavaScript imports
		$html .= $this->renderScripts();
		
		$html .= '</body>';
		$html .= '</html>';
		
		return $html;
	}
}
?>