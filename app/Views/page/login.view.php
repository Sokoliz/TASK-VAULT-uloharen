<!DOCTYPE html>
<html lang="en">

<head>
	<?php 
	$title= "Productivity Hub";
	$public_page = true; // Označenie, že ide o verejnú stránku bez potreby prihlásenia
	require_once __DIR__."/../parts/header.php";
	?>
</head>


<body>

<!-- Navigačný panel - len logo -->
<header class="m-0 p-0">
	<nav class="navbar navbar-expand-lg pt-3 text-dark">
		<div class="menu container">
			<a href="index.php" class="navbar-brand">
			<!-- Logo obrázok -->
<img src="/public/img/logo1.png" width="45" alt="Kalendar" class="d-inline-block align-middle mr-2">			<!-- Logo text -->
			<span class="logo_text align-middle">Productivity Hub</span>
			</a>
		</div>
	</nav>
</header>

<!-- Hlavný obsah stránky - prihlasovací formulár -->
<div class="container">
	<div class="row m-0 p-0">
		<!-- Ľavá strana s formulárom -->	
		<div class="col-6 p-5 justify-content-center">
			<p class="text-center h1 fw-bold m-5">LOG IN</p>
			<form class="px-5" name="login" action="/login" method="POST">
				<!-- Pole pre používateľské meno -->
				<div class="mb-4">					
					<div class="input-group">
						<div class="input-group-prepend">
						<span class="btn-sign-up text-light input-group-text"> <small><?php echo displayIcon('user'); ?></small></span>
						</div>											
						<input class="form-control" type="text" name="username" placeholder="Username" required>
					</div>
				</div>
				<!-- Pole pre heslo -->
				<div class="mb-4">					
					<div class="input-group">
						<div class="input-group-prepend">
						<span class="btn-sign-up text-light input-group-text"> <small><?php echo displayIcon('lock'); ?></small></span>
						</div>					
						<input class="form-control" type="password" name="password" placeholder="Password" required>						
					</div>
				</div>			

				<!-- Tlačidlo na odoslanie formulára -->
				<div class="d-flex justify-content-center mx-4 mb-3 mb-lg-4">
				<button type="button" class="btn btn-primary" onclick="login.submit()">LOG IN</button>
				</div>

				<!-- Zobrazenie chybových hlásení -->
				<?php if(!empty($errors)): ?>
					<div class="err">
						<ul>
							<?php echo $errors; ?>
						</ul>
					</div>
				<?php endif; ?>
			</form>
			<!-- Odkaz na registráciu pre nových používateľov -->
			<span class="d-flex justify-content-center">Don't you have an account?<a class="nav-link text-primary m-0 p-0 pl-2" href="/register">Sign Up</a></span>			
		</div>
		<!-- Pravá strana s obrázkom -->
		<div class="col-6 p-3 mt-5 " >
			<?php 
			// echo displayImage('pages', 'login', 'project_management', 'img-fluid');
			 ?>		
			 <img class="img-fluid" src="/public/img/login.jpg">
		</div>
	</div>
</div>

<!-- Päta stránky -->
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