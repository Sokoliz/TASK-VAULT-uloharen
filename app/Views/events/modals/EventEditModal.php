<?php
namespace App\Views\Events\Modals;

require_once(__DIR__ . '/Modal.php');

/**
 * Trieda EventEditModal pre editáciu existujúcich udalostí
 * 
 * Táto trieda vytvára modálne okno pre úpravu už existujúcich udalostí
 * v kalendári. Okrem editácie umožňuje aj vymazanie udalosti.
 * Rozširuje základnú triedu Modal.
 */
class EventEditModal extends Modal {
	/**
	 * Dáta udalosti pre predvyplnenie polí
	 * Tu sa ukladajú informácie o existujúcej udalosti, ktoré sa
	 * potom použijú ako predvolené hodnoty vo formulári
	 */
	private $eventData;
	
	/**
	 * Konštruktor
	 * 
	 * @param array $eventData Voliteľné dáta udalosti pre predvyplnenie formulára
	 */
	public function __construct($eventData = null) {
		// Aktualizácia akcie formulára, aby smerovala na metódu edit v controlleri kalendára
		parent::__construct('ModalEdit', 'Event', '/calendar/edit');
		
		$this->eventData = $eventData;
		
		// Inicializácia polí formulára
		$this->initializeFields();
	}
	
	/**
	 * Inicializácia polí formulára
	 * 
	 * Pridáva všetky potrebné polia pre editáciu udalosti
	 * a predvyplní ich hodnotami z existujúcej udalosti
	 */
	private function initializeFields() {
		// Získanie hodnôt z dát udalosti, ak sú k dispozícii
		// Pre každé pole skontrolujeme, či existuje v dátach, a ak áno, použijeme ho
		$title = isset($this->eventData['title']) ? $this->eventData['title'] : '';
		$description = isset($this->eventData['description']) ? $this->eventData['description'] : '';
		$colour = isset($this->eventData['colour']) ? $this->eventData['colour'] : '';
		$start_date = isset($this->eventData['start_date']) ? $this->eventData['start_date'] : '';
		$end_date = isset($this->eventData['end_date']) ? $this->eventData['end_date'] : '';
		$id_event = isset($this->eventData['id_event']) ? $this->eventData['id_event'] : '';
		
		// Pridanie poľa pre názov udalosti - povinné
		$this->addTextField('title', 'Title', $title, true, 'Title');
		
		// Pridanie poľa pre popis udalosti - nepovinné
		$this->addTextareaField('description', 'Description', $description, false, 'Description');
		
		// Pridanie výberu farby udalosti
		// Každá farba má svoj štýl a text so symbolom pre lepšiu vizualizáciu
		$colorOptions = [
			'#0275d8' => ['text' => '<span style="color:#0275d8; font-weight:bold;">&#9724;</span> Blue', 'style' => 'color:#0275d8; font-weight:bold;'],
			'#5bc0de' => ['text' => '<span style="color:#5bc0de; font-weight:bold;">&#9724;</span> Tile', 'style' => 'color:#5bc0de; font-weight:bold;'],
			'#5cb85c' => ['text' => '<span style="color:#5cb85c; font-weight:bold;">&#9724;</span> Green', 'style' => 'color:#5cb85c; font-weight:bold;'],
			'#f0ad4e' => ['text' => '<span style="color:#f0ad4e; font-weight:bold;">&#9724;</span> Orange', 'style' => 'color:#f0ad4e; font-weight:bold;'],
			'#d9534f' => ['text' => '<span style="color:#d9534f; font-weight:bold;">&#9724;</span> Red', 'style' => 'color:#d9534f; font-weight:bold;'],
			'#292b2c' => ['text' => '<span style="color:#292b2c; font-weight:bold;">&#9724;</span> Black', 'style' => 'color:#292b2c; font-weight:bold;']
		];
		
		$this->addSelectField('colour', 'Colour', $colorOptions, $colour, true);
		
		// Pridanie dátumových polí pre začiatok a koniec udalosti
		$this->addDateField('start_date', 'Start date', $start_date, true);
		$this->addDateField('end_date', 'End date', $end_date, true);
		
		// Pridanie skrytého poľa pre ID udalosti - toto je kľúčové pre identifikáciu
		// ktorú udalosť vlastne chceme upraviť
		$this->addHiddenField('id_event', $id_event);
	}
	
	/**
	 * Prepísanie metódy render pre pridanie odkazu na skript
	 * 
	 * Táto metóda rozširuje štandardné vykreslenie o pridanie
	 * odkazu na externý JavaScript súbor, ktorý zabezpečuje
	 * funkcionalitu modálneho okna pre udalosti
	 */
	public function render($submitText = 'Save') {
		// Použitie rodičovskej metódy render
		$html = parent::render($submitText);
		
		// Pridanie odkazu na externý JavaScript súbor
		// Toto je kľúčové, aby fungovala validácia a interaktivita
		$html .= '<script src="/public/js/event-modal.js"></script>';
		
		return $html;
	}
	
	/**
	 * Vlastná metóda renderField na spracovanie vlastného typu poľa
	 * 
	 * Umožňuje pridať vlastný HTML kód pre niektoré polia,
	 * namiesto použitia štandardných typov polí
	 */
	protected function renderField($field) {
		if (isset($field['type']) && $field['type'] === 'custom') {
			return $field['html'];
		}
		
		return parent::renderField($field);
	}
	
	/**
	 * Prepísanie renderFooter pre pridanie tlačidla na vymazanie
	 * 
	 * V päte modálneho okna pre editáciu udalosti pridávame
	 * aj tlačidlo na vymazanie, ktoré má hodnotu delete=1
	 */
	protected function renderFooter($submitText = 'Save') {
		return '<div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
					<button type="submit" name="delete" value="1" class="btn btn-danger mr-2">Delete</button>
					<button type="submit" class="btn btn-primary">' . $submitText . '</button>
				</div>';
	}
} 