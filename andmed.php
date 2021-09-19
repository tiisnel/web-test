<?php
	require_once "navbar.php";
	require_once "config.php";
	
			if(!isset($_SESSION["loggedin"])){
				header("location: avaleht.php");
		exit;
		}
	
$sql=mysqli_query($link, "SELECT * FROM kasutajad WHERE Kasutajanimi = '".$_SESSION["username"]."'");
$data=mysqli_fetch_array($sql);
?>

<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="UTF-8">
		<title>Sinu andmed</title>
		<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
		<style>
			body{ font: 14px sans-serif; }
			.wrapper{ width: 360px; padding: 20px; }
		</style>
	</head>
	<body>
		<div class="wrapper">
			<h2>Kasutaja andmed</h2>
				
				<div class="form-group">
					<label>Eesnimi</label><br>
					<input type="text" name="eesnimi" value=" <?php echo $data["Eesnimi"]; ?> "disabled>
				</div> 
				
				<div class="form-group">
					<label>Perekonnanimi</label><br>
					<input type="text" name="perekonnanimi" value=" <?php echo $data["Perenimi"]; ?> " disabled>
				</div> 
				
				<div class="form-group">
					<label>Sünniaeg</label><br>
					<input type="text" name="vanus" value=" <?php echo $data["Sünniaeg"]; ?> " disabled>
				</div> 
				
				<div class="form-group">
					<label>Email</label><br>
					<input type="text" name="email" value=" <?php echo $data["Epost"]; ?> " disabled>
				</div> 
				
				<div class="form-group">
					<label>Telefoninumber</label><br>
					<input type="text" name="telefon" value=" <?php echo $data["Telefon"]; ?> " disabled>
				</div> 
				
		</div>    
	</body>
</html>		
