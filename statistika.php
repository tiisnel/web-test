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
						
						
						$stat = mysqli_query($link, "SELECT  DISTINCT YEAR(`Kuupäev`), COUNT(`Liik`), MIN(`Tiiva pikkus`), AVG(`Tiiva pikkus`), MAX(`Tiiva pikkus`), MIN(`Mass`), AVG(`Mass`), MAX(`Mass`) 
						FROM `linnud` WHERE `Liik` = '".$_POST["liik"]. "' GROUP BY YEAR(`Kuupäev`) WITH ROLLUP" ); 
						
						$nr = mysqli_num_rows($stat);
						
						while($uuri = mysqli_fetch_array($stat)){ 
							
							
						    echo "<tr> ";
						    $uuri[0] = !empty($uuri[0]) ? $uuri[0] : 'Kokku'; 
							echo"<th>" .$uuri[0]. "</th>";
							echo"<td>" .$uuri[1]. "</td>"; // lindude arv
							
							echo"<td>$uuri[2]</td>"; // tiivapikkus min/avg/max
							$uuri[3] = !empty($uuri[3]) ? round($uuri[3],2) : ''; 
							echo"<td>$uuri[3]</td>"; 
							echo"<td>$uuri[4]</td>"; 
							
							echo"<td>$uuri[5]</td>"; //Kaal min/avg/max
							$uuri[6] = !empty($uuri[6]) ? round($uuri[6],2) : ''; 
							echo"<td>$uuri[6]</td>"; 
							echo"<td>$uuri[7]</td>"; 
							
							echo"</tr>";
							
						    
						}
						echo "</tbody>";
						mysqli_close($link); 
						
					}
				?>
			</div>
		</body>
	</html>
	
	
	
