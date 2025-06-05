<!DOCTYPE html>
<html lang="en">
<head>
	<?php 
	$title= "Productivity Hub";
	$public_page = true; // Označenie, že ide o verejnú stránku bez potreby prihlásenia
	// require_once './../parts/header2.php'; 
	
	require_once __DIR__."/../parts/header.php";
	?>
</head>

<body>
<!-- Navigačný panel -->
<header>
	<nav class="navbar navbar-expand-lg py-3 text-dark">
		<div class="menu container">
			<a href="index.php" class="navbar-brand">
			<!-- Logo obrázok -->
			
			<img src="/public/img/logo1.png" width="45" alt="Kalendar" class="d-inline-block align-middle mr-2">
			<!-- Logo text -->
			<span class="logo_text align-middle">Productivity Hub</span>
			</a>	

			<button type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation" class="navbar-toggler"><span class="navbar-toggler-icon"></span></button>
			<div id="navbarSupportedContent" class="collapse navbar-collapse">
				<ul class="navbar-nav ml-auto">
					<li><a href="/login" class="btn text-primary mr-2"></i>Log in</a></li>
					<li><a href="/register" class="btn btn-primary"></i>Sign Up</a></li>					
				</ul>
			</div>
		</div>
	</nav>
</header>

<!-- Hlavný obsah stránky -->
<div class="row m-0 p-0">
	<!-- Ľavá strana s textom a výhodami -->
	<div class="col-6 p-5">
		<div class="container mx-5 mt-3">
			<h2 class="display-4"> <small>Make your life <span class="text-primary">memorable</span></small> </h2>
			<p class="lead">Keep your life organized. We are the solution that you need.</p>
		</div>
		<div class="container mx-5 mr-5 mt-3 d-inline-block">
			<h4 class="text-primary pr-5"><i class="fas fa-rocket pr-3"></i>TAKE OFF YOUR BUSSINESS</h3>
			<p class="text-muted pr-5">Keep all your projects in one place. We offer you a simple Kanban board where you will be able to add as many projects and tasks as you want.</p>
			<h4 class="text-primary pr-5"><i class="far fa-calendar-check pr-3"></i>FORGET ABOUT FORGETTING</h3>
			<p class="text-muted pr-5">Always late? Let us take your agenda for you. We offer you a completely scalable calendar where you can schedule all your events and see them easily. </p>
				
		</div>
		<!-- Tlačidlo pre začatie používania -->
		<div class="container d-flex justify-content-center mt-4">
			<a href="/register" class="btn btn-sign-up">GET STARTED <i class="fas fa-arrow-circle-right pl-2"></i></a>
		</div>
	</div>
	<!-- Pravá strana s obrázkom -->
	<div class="col-4 ml-5 mt-5">
    <img class="img-fluid" src="/public/img/main.jpg">
	</div>
	
</div>

<!-- Footer stránky -->
<footer>
	<div class="row m-0 p-0">
		<div class="col-12">
			<div class="container d-flex justify-content-center">
				<ul class="list-unstyled list-inline text-center d-flex justify-content-center align-items-center">				
										<small><span class="ml-2">Productivity Hub © 2025 All Rights Reserved.</span></small>               
               
				</ul>
			</div>
		</div>
	</div>
</footer>

<!-- Import JavaScriptových knižníc -->
<script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>

</body>
</html>