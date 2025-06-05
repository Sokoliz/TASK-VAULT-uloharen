<?php 
// Ochrana stránky - neprihlásených presmerujeme preč
if (isset($_SESSION['user'])) {
} else {
	header('Location: main.php');
	die();
}

// Pripravíme dáta udalostí vo formáte JSON
$eventsArray = array();
foreach($events as $event) {
    $start = explode(" ", $event['start_date']);
    $end = explode(" ", $event['end_date']);
    
    if($start[1] == '00:00:00'){
        $start = $start[0];
    } else {
        $start = $event['start_date'];
    }
    
    if($end[1] == '00:00:00'){
        $end = $end[0];
    } else {
        $end = $event['end_date'];
    }

	// $end = $event['end_date'];
    
    $eventsArray[] = array(
        'id' => $event['id_event'],
        'title' => $event['title'],
        'description' => $event['description'],
        'start' => $start,
        'end' => $end,
        'color' => $event['colour']
    );

	
}

// Konvertujeme PHP pole na JSON
$eventsJson = json_encode($eventsArray);

?>

<script>
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
        defaultDate: '<?php echo date('Y-m-d'); ?>',
        editable: true,
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
            console.log(event);
            element.bind('click', function() {
                $('#ModalEdit #id_event').val(event.id);
                $('#ModalEdit #title').val(event.title);
                $('#ModalEdit #description').val(event.description);
                $('#ModalEdit #colour').val(event.color);
                $('#ModalEdit #start_date').val(event.start.format('DD-MM-YYYY HH:mm:ss'));
                $('#ModalEdit #end_date').val(event.end ? event.end.format(
                    'DD-MM-YYYY HH:mm:ss') : event.start.format(
                    'DD-MM-YYYY HH:mm:ss'));
                $('#ModalEdit').modal('show');
            });
        },

        // Keď užívateľ presunie udalosť na iný dátum
        eventDrop: function(event, delta, revertFunc) {
            edit(event);
        },

        // Keď užívateľ zmení dĺžku udalosti
        eventResize: function(event, dayDelta, minuteDelta, revertFunc) {
            edit(event);
        },

        // Načítanie udalostí z PHP poľa do kalendára
        events: <?php echo $eventsJson; ?>
    });

    // Funkcia na úpravu udalosti po presune alebo zmene veľkosti
    function edit(event) {
        start = event.start.format('DD-MM-YYYY HH:mm:ss');
        if (event.end) {
            end = event.end.format('DD-MM-YYYY HH:mm:ss');
        } else {
            end = start;
        }

        id_event = event.id;

        // Príprava dát pre odoslanie
        Event = [];
        Event[0] = id_event;
        Event[1] = start;
        Event[2] = end;

        // AJAX volanie na aktualizáciu dát v databáze
        $.ajax({
            url: 'events/actions/eventEditData.php',
            type: "POST",
            data: {
                Event: Event
            },
            success: function(rep) {
                if (rep == 'OK') {
                    alert('Data successfully updated');
                } else {
                    alert('There was a problem while saving, please try again!');
                }
            }
        });
    }
});
</script>