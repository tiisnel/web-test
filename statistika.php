<?php
	require_once "navbar.php";
	require_once "config.php";
	
	if(!isset($_SESSION["loggedin"])){
		header("location: avaleht.php");
		exit;
	}
	
	
	if($_SERVER["REQUEST_METHOD"] == "POST"){
		if(!empty($_POST["liik"])){
		}
	}
?>


<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="UTF-8">
		<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
		<style>
			body{ font: 14px sans-serif; }
			.wrapper{ width: 360px; padding: 20px; }
		</style>
	</head>
	<body>
		
		<div class="container my-4">
			<div class="col-sm-10 col-sm-offset-10">
				<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
					<div class="input-group">
						<select class="mdb-select md-form mr-sm-1" name="liik" searchable="liik..">
							<option value="" disabled selected>Vali liik</option>
							<?php
								
								$result = mysqli_query($link, "SELECT DISTINCT `Liik` From linnud");
								while($data = mysqli_fetch_array($result)){  
									echo "<option value='". $data['Liik'] ."'>" .$data['Liik'] ."</option>";
								}
								
								
							?>  
						</select>
						<input type="submit" class="btn btn-primary" value="Uuri">
					</div>
				</form>
				<?php
					if(!empty($_POST["liik"])){
						
						echo '  <br> Liigi '.$_POST["liik"]. ' andmed <br><br>';
						echo'<table class="table table-striped">
						<thead>
						
						<tr>
						<th>Aasta</th>
						<th>Rõngastatud linde</th>
						<th>Miinimum <br> tiivapikkus</th>
						<th>Keskmine<br> tiivapikkus</th>
						<th>Maksimum<br> tiivapikkus</th>
						<th>Miinimum<br> kaal</th>
						<th>Keskmine<br> kaal</th>
						<th>Maksimum<br> kaal</th>
						</tr>
						
						</thead>
						<tbody>
						
						';
						
						$minyear=mysqli_query($link, "SELECT MIN(YEAR(STR_TO_DATE(`Kuupäev`,'%d.%m.%Y')))FROM `linnud` WHERE YEAR(STR_TO_DATE(`Kuupäev`,'%d.%m.%Y')) >0");
						$minyear=mysqli_fetch_row($minyear);
						
						$maxyear=mysqli_query($link, "SELECT MAX(YEAR(STR_TO_DATE(`Kuupäev`,'%d.%m.%Y')))FROM `linnud`");
						$maxyear=mysqli_fetch_row($maxyear);
						
						for($year = $minyear[0]; $year<=$maxyear[0]; $year++){
							echo "<tr> ";
							echo"<th>$year</th>";
							
							$linde = mysqli_query($link, "SELECT COUNT(*) FROM `linnud` WHERE `Liik` = '".$_POST["liik"]."' AND YEAR(STR_TO_DATE(`Kuupäev`,'%d.%m.%Y'))=".$year);
							$linde = mysqli_fetch_row($linde);
							echo"<td>$linde[0]</td>";
							
							$mintiib = mysqli_query($link, "SELECT MIN(`Tiiva pikkus`) FROM `linnud` WHERE `Liik` = '".$_POST["liik"]."' AND `Tiiva pikkus` >0 AND YEAR(STR_TO_DATE(`Kuupäev`,'%d.%m.%Y'))=".$year);
							$mintiib = mysqli_fetch_row($mintiib);		
							echo"<td>$mintiib[0]</td>";
							
							$tiib = mysqli_query($link, "SELECT AVG(`Tiiva pikkus`) FROM `linnud` WHERE `Liik` = '".$_POST["liik"]."' AND `Tiiva pikkus` >0 AND YEAR(STR_TO_DATE(`Kuupäev`,'%d.%m.%Y'))=".$year);
							$tiib = mysqli_fetch_row($tiib);
							echo"<td>$tiib[0]</td>";
							
							$maxtiib = mysqli_query($link, "SELECT MAX(`Tiiva pikkus`) FROM `linnud` WHERE `Liik` = '".$_POST["liik"]."' AND `Tiiva pikkus` >0 AND YEAR(STR_TO_DATE(`Kuupäev`,'%d.%m.%Y'))=".$year);
							$maxtiib = mysqli_fetch_row($maxtiib);
							echo"<td>$maxtiib[0]</td>";
							
							$minmass = mysqli_query($link, "SELECT MIN(`Mass`) FROM `linnud` WHERE `Liik` = '".$_POST["liik"]."' AND `Mass` >0 AND YEAR(STR_TO_DATE(`Kuupäev`,'%d.%m.%Y'))=".$year);
							$minmass = mysqli_fetch_row($minmass);
							echo"<td>$minmass[0]</td>";
							
							$mass = mysqli_query($link, "SELECT AVG(`Mass`) FROM `linnud` WHERE `Liik` = '".$_POST["liik"]."' AND `Mass` >0 AND YEAR(STR_TO_DATE(`Kuupäev`,'%d.%m.%Y'))=".$year);
							$mass = mysqli_fetch_row($mass);
							echo"<td>$mass[0]</td>";
							
							$maxmass = mysqli_query($link, "SELECT MAX(`Mass`) FROM `linnud` WHERE `Liik` = '".$_POST["liik"]."' AND `Mass` >0 AND YEAR(STR_TO_DATE(`Kuupäev`,'%d.%m.%Y'))=".$year);
							$maxmass = mysqli_fetch_row($maxmass);
							echo"<td>$maxmass[0]</td>";
							echo"</tr>";
							
						}
						echo "<tr> ";
						echo"<th>Kokku</th>";
						
						$linde = mysqli_query($link, "SELECT COUNT(*) FROM `linnud` WHERE `Liik` = '".$_POST["liik"]."'");
						$linde = mysqli_fetch_row($linde);
						echo"<td>$linde[0]</td>";
						
						$mintiib = mysqli_query($link, "SELECT MIN(`Tiiva pikkus`) FROM `linnud` WHERE `Liik` = '".$_POST["liik"]."' AND `Tiiva pikkus` >0");
						$mintiib = mysqli_fetch_row($mintiib);		
						echo"<td>$mintiib[0]</td>";
						
						$tiib = mysqli_query($link, "SELECT AVG(`Tiiva pikkus`) FROM `linnud` WHERE `Liik` = '".$_POST["liik"]."' AND `Tiiva pikkus` >0");
						$tiib = mysqli_fetch_row($tiib);
						echo"<td>$tiib[0]</td>";
						
						$maxtiib = mysqli_query($link, "SELECT MAX(`Tiiva pikkus`) FROM `linnud` WHERE `Liik` = '".$_POST["liik"]."' AND `Tiiva pikkus` >0");
						$maxtiib = mysqli_fetch_row($maxtiib);
						echo"<td>$maxtiib[0]</td>";
						
						$minmass = mysqli_query($link, "SELECT MIN(`Mass`) FROM `linnud` WHERE `Liik` = '".$_POST["liik"]."' AND `Mass` >0");
						$minmass = mysqli_fetch_row($minmass);
						echo"<td>$minmass[0]</td>";
						
						$mass = mysqli_query($link, "SELECT AVG(`Mass`) FROM `linnud` WHERE `Liik` = '".$_POST["liik"]."' AND `Mass` >0");
						$mass = mysqli_fetch_row($mass);
						echo"<td>$mass[0]</td>";
						
						$maxmass = mysqli_query($link, "SELECT MAX(`Mass`) FROM `linnud` WHERE `Liik` = '".$_POST["liik"]."' AND `Mass` >0");
						$maxmass = mysqli_fetch_row($maxmass);
						echo"<td>$maxmass[0]</td>";
						echo"</tr>";
						
						mysqli_close($link); 
						echo "</tbody>";
						
					}		
				?>
			</div>
		</body>
	</html>
	
	
	
