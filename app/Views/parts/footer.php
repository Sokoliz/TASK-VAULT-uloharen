<?php
// Kontrola, či je používateľ prihlásený
if (isset($_SESSION['user'])) {
?>	
<!-- Päta stránky s informáciami o autorských právach -->
<footer>
	<div class="row m-0 p-0 d-flex justify-content-center">
		<div class="col-12">
			<div class="container d-flex justify-content-center">
				<ul class="list-unstyled list-inline text-center d-flex justify-content-center align-items-center">				 
					<small><span class="ml-2">Productivity Hub © 2025 All Rights Reserved.</span></small>               
				</ul>
			</div>
		</div>
	</div>
</footer>

<?php 
} else {
	// Presmerovanie neprihlásených používateľov na hlavnú stránku
	header('Location: /login');
	die();
}
?>

