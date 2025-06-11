<?php
require_once('View.php');

// Trieda CalendarView pre zobrazenie stránky s kalendárom
// Kalendár nám umožňuje zobrazovať a manažovať všetky udalosti
class CalendarView extends View {
	
	public function __construct($data = []) {
		parent::__construct('Calendar', false, $data);
	}
	
	
	protected function renderHead() {
		$html = parent::renderHead();
		$html .= '<script src="/public/js/theme.js"></script>';
		$html .= '<script src="/public/js/form-validation.js"></script>';
		$html .= '<script src="/public/js/modal-handler.js"></script>';
		$html .= '<script src="/public/js/dynamic-theme.js"></script>';
		$html .= '<link href="/public/css/fullcalendar.css" rel="stylesheet" />';
		$html .= '<link href="/public/css/dynamic-theme.css" rel="stylesheet" />';
		$html .= '<link href="/public/css/modal-colors.css" rel="stylesheet" />';
		$html .= '<link href="/public/css/theme-icons.css" rel="stylesheet" />';
		return $html;
	}
	
	
	protected function renderCalendar() {
		$html = '<div class="container bg-light text-dark rounded mt-4">';
		
		// Zobrazenie flash správ
		if (isset($_SESSION['flash_message'])) {
			$html .= '<div class="row m-2">';
			$html .= '<div class="col-12">';
			$html .= '<div class="alert alert-info alert-dismissible fade show" role="alert">';
			$html .= $_SESSION['flash_message'];
			$html .= '<button type="button" class="close" data-dismiss="alert" aria-label="Close">';
			$html .= '<span aria-hidden="true">&times;</span>';
			$html .= '</button>';
			$html .= '</div>';
			$html .= '</div>';
			$html .= '</div>';
			
			// Vymažeme správu, aby sa nezobrazovala opakovane
			unset($_SESSION['flash_message']);
		}
		
		$html .= '<div class="row m-0 p-0">';
		$html .= '<div class="col-lg-12 text-center">';
		$html .= '<p class="lead"></p>';
		$html .= '<div id="calendar" class="col-centered mb-4"></div>';
		$html .= '</div>';
		$html .= '</div>';
		
		// Namiesto inline validácie používame externý skript
		
		// Includujeme modálne okná
		ob_start();
		require_once __DIR__ . '/../../Controllers/EventAddModalController.php';
		require_once __DIR__ . '/../../Controllers/EventEditModalController.php';
		$html .= ob_get_clean();
		
		$html .= '</div>';
		
		return $html;
	}
	
	
	protected function renderPrintButton() {
		return '<div class="row m-0 p-0">
	<div class="col sm-3 d-flex justify-content-center d-print-none">
		<button id="print-button" class="btn btn-primary m-4 hiddenprint">Print</button>   
	</div>
		</div>';
	}
	
	
	protected function renderCalendarScripts() {
		$html = '<script src="/public/js/jquery.js"></script>';
		$html .= '<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>';
		$html .= '<script src="/public/js/moment.min.js"></script>';
		$html .= '<script src="/public/js/fullcalendar.min.js"></script>';
		$html .= '<script src="/public/js/print.js"></script>';
		
		// Extrahujeme dáta z viewData pomocou metódy getData
		$events = $this->getData('events', []);
		
		// Pre diagnostiku vypíšeme do konzoly JavaScript počet udalostí
		$html .= '<script src="/public/js/calendar-debug.js"></script>';
		
		// Upraviť skript s počtom udalostí
		$eventsCountScript = file_get_contents(__DIR__ . '/../../../public/js/calendar-events-handler.js');
		$eventsCountScript = str_replace('[COUNT_PLACEHOLDER]', count($events), $eventsCountScript);
		$html .= '<script>' . $eventsCountScript . '</script>';
		
		// Includujeme konfiguráciu kalendára
		ob_start();
		// Odstránili sme require pre calendar2.php a priamo pridávame JavaScript inicializáciu
		// Táto časť je teraz obsluhovaná kontrolérom Calendar2View
		$html .= ob_get_clean();
		
		// Pridávame event-modal.js pre obsluhu modálnych okien
		$html .= '<script src="/public/js/event-modal.js"></script>';
		
		// Načítame debug skript ako posledný, aby sme skontrolovali, či je všetko správne načítané
		$html .= '<script src="/public/js/calendar-debug.js"></script>';
		
		return $html;
	}
	
	
	protected function renderContent() {
		require_once __DIR__.'/../parts/navbar.php';
		$navbar = new Navbar(false, true, true, 'standard');
		$html = $navbar->render();
		$html .= $this->renderCalendar();
		$html .= $this->renderPrintButton();
		return $html;
	}
	
	
	public function render() {
		$html = '<!DOCTYPE html>';
		$html .= '<html lang="en">';
		
		// Sekcia head
		$html .= '<head>';
		$html .= $this->renderHead();
		$html .= '</head>';
		
		// Sekcia body s triedou pozadia
		$html .= '<body class="bg">';
		
		// Obsah
		$html .= $this->renderContent();
		
		// Skripty kalendára
		$html .= $this->renderCalendarScripts();
		
		$html .= '</body>';
		$html .= '</html>';
		
		return $html;
	}
}
?>