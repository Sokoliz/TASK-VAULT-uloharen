/**
 * Calendar Events Handler
 * 
 * Inicializuje udalosti pre kalendár a zobrazuje diagnostické informácie
 */
document.addEventListener('DOMContentLoaded', function() {
    // Zobraziť počet udalostí v konzole (bude doplnené PHP kódom)
    // Toto je len placeholder, pri každom volaní sa nahradí skutočným počtom z PHP
    console.log("Events count from PHP: [COUNT_PLACEHOLDER]");
    
    // Inicializácia tlačidla tlače
    const printButton = document.getElementById('print-button');
    if (printButton) {
        printButton.addEventListener('click', printPage);
    }
}); 