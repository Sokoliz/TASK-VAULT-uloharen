<?php
if (isset($_SESSION['user'])) {
?>	
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
	header('Location: main.php');
	die();
}
?>

