<?php
	session_start();
?>

<!doctype html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<title>Vaibla </title>
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<!-- Bootstrap -->
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css"> 
		<style>
			body {
			padding-top:70px
			}
		</style>
	</head>
	<body>
		<nav class="navbar fixed-top navbar-expand-lg navbar-dark bg-dark"> <a class="navbar-brand" href="avaleht.php">Avaleht</a>
			<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent1" aria-controls="navbarSupportedContent1" aria-expanded="false" aria-label="Toggle navigation"> <span class="navbar-toggler-icon"></span> </button>
			<div class="collapse navbar-collapse" id="navbarSupportedContent1">
				<ul class="navbar-nav mr-auto">
				</ul>
				<form class="form-inline my-2 my-lg-0">
					<?php
						if(!isset($_SESSION["loggedin"])){
							echo'
							<span class="d-inline-block" tabindex="0" data-toggle="tooltip" title="Selle funktsiooni kasutamiseks logi palun sisse">
							<button class="btn btn-warning mr-sm-4" style="pointer-events: none;" type="button" disabled>Otsing</button>
							</span>
							
							<span class="d-inline-block" tabindex="0" data-toggle="tooltip" title="Selle funktsiooni kasutamiseks logi palun sisse">
							<button class="btn btn-warning mr-sm-4" style="pointer-events: none;" type="button" disabled>Statistika</button>
							</span>
							<a href="login.php" class="btn btn-outline-primary mr-sm-2" >Logi sisse</a>
							<a href="registeeri.php" class="btn btn-outline-success" >Registeeru</a>
							
							';
						}
						else echo'
						<a href="otsing.php" class="btn btn-info mr-sm-4" >Otsing</a>
						<a href="statistika.php" class="btn btn-info mr-sm-4" >Statistika</a>
						<a href="andmed.php" class="btn btn-info mr-sm-4" >Kasutaja '.$_SESSION["username"].' andmed</a>
						<a href="logout.php" class="btn btn-info mr-sm-4" >Logi välja </a>
						';
					?>
					
				</form>
			</div>
		</nav>
		
		<!--Supporting scripts. First jQuery, then popper, then Bootstrap JS--> 
		<script src="https://code.jquery.com/jquery-3.2.1.min.js" integrity="sha256-hwg4gsxgFZhOsEEamdOYGBf13FyQuiTwlAQgxVSNgt4=" crossorigin="anonymous"></script> 
		<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
		<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
	</body>
</html>