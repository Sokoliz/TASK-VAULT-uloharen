/**
 * Calendar functionality
 * 
 * JavaScript pre ovládanie kalendára
 */

// Funkcia na zobrazenie modálneho okna
function modalShow() {
    $('#modalShow').modal('show');
}

$(document).ready(function() {
    // Inicializácia fullCalendar pluginu
    $('#calendar').fullCalendar({

    // Nastavenie hlavičky kalendára
    header: {
        left: 'prev,next today',
        center: 'title',
        right: 'month,agendaWeek,agendaDay,listYear'
    },

    // Základné nastavenia kalendára
    defaultDate: currentDate, // Definované v PHP ako globálna premenná
    editable: false, // Zakázanie drag and drop
    navLinks: true,
    eventLimit: true,
    selectable: true,
    selectHelper: true,
    
    // Keď užívateľ vyberie dátum, otvoríme modálne okno pre pridanie udalosti
    select: function(start, end) {
        $('#ModalAdd #start_date').val(moment(start).format('DD-MM-YYYY HH:mm:ss'));
        $('#ModalAdd #end_date').val(moment(end).format('DD-MM-YYYY HH:mm:ss'));
        $('#ModalAdd').modal('show');
    },
    
    // Nastavenie akcie pri kliknutí na udalosť - otvorí sa modálne okno pre úpravu
    eventRender: function(event, element) {
        element.bind('click', function() {
            // Clear previous form data
            $('#ModalEdit form')[0].reset();
            
            // Debug výpis na sledovanie vlastností udalosti
            console.log("Event clicked:", event);
            console.log("Event ID:", event.id, "Type:", typeof event.id);
            
            // Set form fields with event data
            if (event.id) {
                $('#ModalEdit #id_event').val(event.id);
                console.log("Setting ID field to:", event.id);
            } else {
                // Pre udalosti bez ID nastavíme aspoň prázdny string namiesto null/undefined
                $('#ModalEdit #id_event').val('');
                console.log("Warning: Event missing ID", event);
            }
            
            // Pridáme kontrolu, či sa ID správne nastavilo
            setTimeout(function() {
                var currentId = $('#ModalEdit #id_event').val();
                console.log("ID after setting:", currentId, "Type:", typeof currentId);
                
                // Ak sa ID nenastavilo správne, skúsime alternatívny prístup
                if (!currentId && event.id) {
                    console.log("ID didn't set properly, trying alternative approach");
                    // Pridáme nové hidden pole pre ID
                    var hiddenIdField = $('<input type="hidden" name="id_event" id="id_event_backup" value="' + event.id + '">');
                    $('#ModalEdit form').append(hiddenIdField);
                }
            }, 100);
            
            $('#ModalEdit #title').val(event.title);
            $('#ModalEdit #description').val(event.description);
            
            // Handle color - use event.color first, fallback to event.colour
            var eventColor = event.color || event.colour || '#000000';
            $('#ModalEdit #colour').val(eventColor);
            
            // Format start date
            var startDate = moment(event.start).format('DD-MM-YYYY HH:mm:ss');
            $('#ModalEdit #start_date').val(startDate);
            
            // Handle end date - try all possible properties
            var endDate;
            if (event.end) {
                endDate = moment(event.end);
            } else if (event.ends) {
                endDate = moment(event.ends);
            } else {
                endDate = moment(event.start);
            }
            $('#ModalEdit #end_date').val(endDate.format('DD-MM-YYYY HH:mm:ss'));
            
            // Reset delete checkbox
            $('#ModalEdit #delete').prop('checked', false);
            
            // Show modal
            $('#ModalEdit').modal('show');
        });
    },

    // Načítanie udalostí z PHP premennej
    events: calendarEvents // Definované v PHP ako globálna premenná
    });
}); 