<meta charset="UTF-8">
<!-- Nastavenie viewportu pre responzívny dizajn na všetkých zariadeniach -->
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<!-- Dynamický titulok stránky - použije hodnotu premennej $title ak existuje, inak zobrazí 'Productivity Hub' -->
<title><?php echo isset(
    $title) ? $title : 'Productivity Hub'; ?></title>
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css"
    crossorigin="anonymous">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.13.0/css/all.min.css">
<link rel="stylesheet" href="/public/css/style.css">