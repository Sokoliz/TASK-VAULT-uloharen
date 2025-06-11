	<?php 
require_once('View.php');

/**
 * MainView class for rendering the main (home) page
 */
class MainView extends View {
	/**
	 * Constructor
	 * 
	 * @param array $data Data to be passed to the view
	 */
	public function __construct($data = []) {
		parent::__construct('Productivity Hub', true, $data);
	}
	
	/**
	 * Render the content with features
	 * 
	 * @return string HTML for the features content
	 */
	
	protected function renderFeatures() {
		$html = '<div class="col-6 p-5">';
		$html .= '<div class="container mx-5 mt-3">';
		$html .= '<h2 class="display-4"><small>Make your life <span class="text-primary">memorable</span></small></h2>';
		$html .= '<p class="lead">Keep your life organized. We are the solution that you need.</p>';
		$html .= '</div>';
		
		$html .= '<div class="container mx-5 mr-5 mt-3 d-inline-block">';
		$html .= '<h4 class="text-primary pr-5"><i class="fas fa-rocket pr-3"></i>TAKE OFF YOUR BUSSINESS</h4>';
		$html .= '<p class="text-muted pr-5">Keep all your projects in one place. We offer you a simple Kanban board where you will be able to add as many projects and tasks as you want.</p>';
		$html .= '<h4 class="text-primary pr-5"><i class="far fa-calendar-check pr-3"></i>FORGET ABOUT FORGETTING</h4>';
		$html .= '<p class="text-muted pr-5">Always late? Let us take your agenda for you. We offer you a completely scalable calendar where you can schedule all your events and see them easily.</p>';
		$html .= '</div>';
		
		$html .= '<div class="container d-flex justify-content-center mt-4">';
		$html .= '<a href="/register" class="btn btn-sign-up">GET STARTED <i class="fas fa-arrow-circle-right pl-2"></i></a>';
		$html .= '</div>';
		
		$html .= '</div>';
		
		return $html;
	}
	
	/**
	 * Render the image column
	 * 
	 * @return string HTML for the image column
	 */
	protected function renderImageColumn() {
		return '<div class="col-4 ml-5 mt-5">
    <img class="img-fluid" src="/public/img/main.jpg">
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
		$navbarType = 'standard';
		
		// Include navbar.php
		ob_start();
		include_once __DIR__.'/../parts/navbar.php';
		$html = ob_get_clean();
		
		$html .= '<div class="row m-0 p-0">';
		$html .= $this->renderFeatures();
		$html .= $this->renderImageColumn();
		$html .= '</div>';
		
		return $html;
	}
}

// Instantiate and display the view
$mainView = new MainView(isset($viewData) ? $viewData : []);
$mainView->display();
?>