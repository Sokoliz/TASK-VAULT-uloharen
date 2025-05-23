<!DOCTYPE html>
<html lang="en">
	
<head>
	<?php $title= "Content"; ?>
	<?php require 'parts/header.php'; ?>
</head>

<body class="bg">

<!-- Navigačný panel -->
<header class="m-0 p-0">
	<nav class="navbar navbar-expand-lg pt-3 text-dark">
		<div class="menu container">
			<a href="index.php" class="navbar-brand">
			<!-- Logo obrázok -->
			<?php echo displayImage('logo', 'main', 'Kalendar', 'd-inline-block align-middle mr-2', ['width' => '45']); ?>
			<!-- Logo text -->
			<span class="logo_text align-middle">Productivity Hub</span>
			</a>
			
            <ul class="navbar-nav ml-auto">
                <li><span class="btn text-primary mx-2"><?php echo displayIcon('user', 'pr-2'); ?>Welcome <?php echo strtoupper($_SESSION['user']);?>!</span></li>
                <li><span class="btn text-primary mx-2"><?php echo displayIcon('calendar', 'pr-2'); ?>Date:<span class="pl-2 date"></span></span></li>	
                <li><span class="btn text-primary mr-2"><?php echo displayIcon('clock', 'pr-2'); ?>Time:<span class="pl-2 clock"></span></span></li>
                <!-- Prepínač tmavého režimu -->
                <li class="nav-item theme-switch-wrapper">
                    <a href="#" class="mode-text btn text-primary">Mode</a>
                    <?php echo displayIcon('moon', 'mode-icon fa-lg'); ?>
                </li>
            </ul>	

			<button type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation" class="navbar-toggler"><span class="navbar-toggler-icon"></span></button>
			<div id="navbarSupportedContent" class="collapse navbar-collapse">
				<ul class="navbar-nav ml-auto">
					<li><a href="logout.php" class="btn text-primary mr-2">Log out</a></li>				
				</ul>
			</div>
		</div>
	</nav>
</header>

<!-- Hlavné menu s kartami pre navigáciu -->
<div class="container mt-0 mb-4">
    <div class="row d-flex m-2 mt-0">
        <!-- Karta pre dnešné udalosti -->
        <div class="col-sm-4 col-md-4">
            <a class="card my-card text-dark" href="today.php">
                <?php echo displayImage('pages', 'today', 'Today', 'card-img-top img-rounded', ['id' => 'Panel_Image']); ?>
               <div class="card-body d-flex justify-content-center">
                    <h3 class="card-title"></i>TODAY</h3>
               </div>
            </a>                   
        </div>

        <!-- Karta pre projekty -->
        <div class="col-sm-4 col-md-4">
            <a class="card my-card text-dark" href="projects.php">
                <?php echo displayImage('pages', 'projects', 'Projects', 'card-img-top img-rounded', ['id' => 'Panel_Image']); ?>
               <div class="card-body d-flex justify-content-center">
                    <h3 class="card-title"></i>PROJECTS</h3>
               </div>
            </a>                   
        </div>

        <!-- Karta pre kalendár -->
        <div class="col-sm-4 col-md-4">
            <a class="card my-card text-dark" href="calendar.php">
                <?php echo displayImage('pages', 'calendar', 'Calendar', 'card-img-top img-rounded', ['id' => 'Panel_Image']); ?>
               <div class="card-body d-flex justify-content-center">
                    <h3 class="card-title"></i>CALENDAR</h3>
               </div>
            </a>                   
        </div>        
    </div>
</div>

<!-- Footer stránky -->
<?php require 'parts/footer.php'; ?>

<!-- Import JavaScriptových knižníc -->
<script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>
<script src="js/theme.js"></script>

<!-- Skript pre aktuálny čas a dátum -->
<script type="text/javascript">
    function clock() {
    var time = new Date(),          
        hours = time.getHours(),    
        minutes = time.getMinutes(),
        seconds = time.getSeconds();
    var date = time.getFullYear()+'-'+(time.getMonth()+1)+'-'+time.getDate();
        

    document.querySelectorAll('.clock')[0].innerHTML = harold(hours) + ":" + harold(minutes) + ":" + harold(seconds);
    document.querySelectorAll('.date')[0].innerHTML = date;
    
    function harold(standIn) {
        if (standIn < 10) {
        standIn = '0' + standIn
        }
        return standIn;
    }
    }
    setInterval(clock, 1000);
</script>

<script>
	// Zapneme tooltips
	$(function () {
	  $('[data-toggle="tooltip"]').tooltip()
	})
</script>
  
</body>
</html>

