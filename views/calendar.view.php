<!DOCTYPE html>
<html lang="en">
	
<head>
	<?php $title= "Calendar"; ?>
	<?php require 'parts/header.php'; ?>

	<!-- Import štýlov pre FullCalendar -->
	<link href='css/fullcalendar.css' rel='stylesheet' />
</head>

<body  class="bg">
<!-- Navigačný panel -->
<header class="m-0 p-0">
	<nav class="navbar navbar-expand-lg pt-3 text-dark">
		<div class="menu container">
			<a href="index.php" class="navbar-brand">
			<!-- Logo obrázok -->
			<img src="img/logo1.png" width="45" alt="Kalendar" class="d-inline-block align-middle mr-2">
			<!-- Logo text -->
			<span class="logo_text align-middle">Productivity Hub</span>
			</a>
            
			<button type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation" class="navbar-toggler"><span class="navbar-toggler-icon"></span></button>
			<div id="navbarSupportedContent" class="collapse navbar-collapse">
				<ul class="navbar-nav ml-auto">
                    <li><a href="content.php" class="btn text-primary mr-2"><i class="fas fa-home pr-2"></i>Home</a></li>	
					<li><a href="logout.php" class="btn text-primary mr-2">Log out</a></li>				
				</ul>
			</div>
		</div>
	</nav>
</header>

<!-- Hlavný obsah stránky - kalendár -->
<div class="container bg-light text-dark rounded mt-4">
	<div class="row m-0 p-0">
		<div class="col-lg-12 text-center">
			<p class="lead"></p>
			<div id="calendar" class="col-centered mb-4">
			</div>
		</div>
	</div>


<!-- Modálne okná pre prácu s udalosťami -->
<script type="text/javascript" class="d-print-none">
	function validaForm(erro) {
		if(erro.inicio.value>erro.termino.value){
			alert('The start date has to be before the end date.');
			return false;
		}else if(erro.inicio.value==erro.termino.value){
			alert('Start time and end time has to be defined');
			return false;
		}
	}
</script>

<?php include ('events/modals/modalAdd.php'); ?>
<?php include ('events/modals/modalEdit.php'); ?>
</div>
<!-- Tlačidlo pre tlač kalendára -->
<div class="row m-0 p-0">
	<div class="col sm-3 d-flex justify-content-center d-print-none">
		<button onclick="javascript:window.print()" class="btn btn-primary m-4 hiddenprint">Print</button>   
	</div>
</div>
<!-- Päta stránky -->
<?php require 'parts/footer.php'; ?>


	<!-- Import jQuery knižnice -->
	<script src="js/jquery.js"></script>

	<!-- Import Bootstrap JavaScriptu -->
	<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>

	<!-- Import Moment.js pre prácu s dátumami -->
	<script src="js/moment.min.js"></script>

	<!-- Import FullCalendar skriptov -->
	<script src="js/fullcalendar.min.js"></script>

	<!-- Načítanie konfigurácie kalendára -->
	<?php include ('calendar2.php'); ?>

</body>
</html>