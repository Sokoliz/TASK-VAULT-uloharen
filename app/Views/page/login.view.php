<?php 
require_once('View.php');

/**
 * LoginView class for rendering the login page
 */
class LoginView extends View {
	/**
	 * Constructor
	 * 
	 * @param array $data Data to be passed to the view
	 */
	public function __construct($data = []) {
		parent::__construct('Productivity Hub', true, $data);
	}
	
	/**
	 * Render the simplified navbar for login page (no login buttons)
	 * 
	 * @return string HTML for the navbar
	 */
	protected function renderSimpleNavbar() {
		return '<header class="m-0 p-0">
	<nav class="navbar navbar-expand-lg pt-3 text-dark">
		<div class="menu container">
			<a href="index.php" class="navbar-brand">
					<img src="/public/img/logo1.png" width="45" alt="Kalendar" class="d-inline-block align-middle mr-2">
			<span class="logo_text align-middle">Productivity Hub</span>
			</a>
		</div>
	</nav>
		</header>';
	}
	
	/**
	 * Render the login form
	 * 
	 * @return string HTML for the login form
	 */
	protected function renderLoginForm() {
		$errors = $this->getData('errors', '');
		
		$html = '<div class="col-6 p-5 justify-content-center">';
		$html .= '<p class="text-center h1 fw-bold m-5">LOG IN</p>';
		$html .= '<form id="login" class="px-5" name="login" action="/login" method="POST">';
		
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
		$html .= '<input class="form-control" type="password" name="password" placeholder="Password" required>';
		$html .= '</div>';
		$html .= '</div>';
		
		// Submit button
		$html .= '<div class="d-flex justify-content-center mx-4 mb-3 mb-lg-4">';
		$html .= '<button type="submit" class="btn btn-primary">LOG IN</button>';
		$html .= '</div>';
		
		// Display errors if any
		if (!empty($errors)) {
			$html .= '<div class="err">';
			$html .= '<ul>' . $errors . '</ul>';
			$html .= '</div>';
		}
		
		$html .= '</form>';
		
		// Link to registration
		$html .= '<span class="d-flex justify-content-center">Don\'t you have an account?<a class="nav-link text-primary m-0 p-0 pl-2" href="/register">Sign Up</a></span>';
		$html .= '</div>';
		
		return $html;
	}
	
	/**
	 * Render the image column
	 * 
	 * @return string HTML for the image column
	 */
	protected function renderImageColumn() {
		return '<div class="col-6 p-3 mt-5">
			 <img class="img-fluid" src="/public/img/login.jpg">
		</div>';
	}
	
	/**
	 * Render the content section
	 * 
	 * @return string HTML for the content section
	 */
	protected function renderContent() {
		$html = $this->renderSimpleNavbar();
		
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