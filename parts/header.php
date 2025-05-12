<?php
// Spustenie session pre správu prihláseného používateľa
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Kontrola, či je stránka verejná (voliteľný parameter)
$public_page = isset($public_page) ? $public_page : false;

// Presmerovanie neprihlásených používateľov na hlavnú stránku
if (!$public_page && !isset($_SESSION['user'])) {
    header('Location: main.php');
    die();
}
?>	
	
<!-- Meta tagy pre správne zobrazenie stránky -->
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">

<!-- Bootstrap framework pre štýly -->
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" media='all' integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">

<!-- Importované písma -->
<link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600&family=Open+Sans:wght@300;400;600&display=swap" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Comfortaa&display=swap" rel="stylesheet">

<!-- Vlastné CSS štýly -->
<link rel="stylesheet" href="css/style.css">
<title><?php echo isset($title) ? $title : 'Productivity Hub'; ?></title>

