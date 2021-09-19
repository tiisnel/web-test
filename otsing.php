
<?php
	
	require_once "navbar.php";
	require_once "config.php";
	
	if(!isset($_SESSION["loggedin"])){
		header("location: avaleht.php");
		exit;
	}
	$ronganumber = $kood = $err= "";
	$tulem = 25; //max tulemuste arv yhel lehel
	
	
    if (!isset ($_GET['page']) ) {  
        $page = 1;  
	}
	else {  
		$page = $_GET['page'];
	}  
	if($_SERVER["REQUEST_METHOD"] == "POST"){
		if(!empty($_POST["kood"]) and !empty($_POST["ronganumber"])){
			$_SESSION["kood"]= $_POST["kood"];
			$_SESSION["ronganumber"]= $_POST["ronganumber"];	
		}
		else {
			unset ($_SESSION["kood"]);
			unset ($_SESSION["ronganumber"]);
		}
	}
    
	
	if(!isset($_SESSION["kood"]) or !isset($_SESSION["ronganumber"])){
	    if($_SERVER["REQUEST_METHOD"] == "POST"){
			$err = "error";
		}
	}
	else{
		$sql=mysqli_query($link, "SELECT * FROM `linnud` WHERE `Rõngakood:tähed` = '".$_SESSION["kood"]."' AND `Rõngakood:numbrid` LIKE '%".$_SESSION["ronganumber"]."%' ");
		$num_results = mysqli_num_rows($sql);
		$num_pages = ceil($num_results / $tulem);
		$first = ($page-1) * $tulem;
		if ($page > $num_pages) $page = $num_pages; // ainult juhul kui kasutaja proovis ise hyperlinki muuta
		$sql_per_page = "SELECT * FROM `linnud` WHERE `Rõngakood:tähed` ='".$_SESSION["kood"]."' AND `Rõngakood:numbrid` LIKE '%".$_SESSION["ronganumber"]."%' LIMIT ".  $first ." , ". $tulem;
		$sql=mysqli_query($link, $sql_per_page);
	}
	//}
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
		<div class="container">
			<div class="col-sm-6 col-sm-offset-4">
				<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
					<div class="input-group">
						
						<select class="mdb-select md-form mr-sm-4" name="kood" >
							<option value="" disabled selected>Vali rõngakood</option>
							<?php
								$result = mysqli_query($link, "SELECT DISTINCT `Rõngakood:tähed` From linnud");
								while($data = mysqli_fetch_array($result)){ 
									echo "<option value='". $data['Rõngakood:tähed'] ."'>" .$data['Rõngakood:tähed'] ."</option>";
								}
								
								
								mysqli_close($link); 
							?>  
						</select>
						
						<input type="number" name="ronganumber" class="form-control md-form mr-sm-2" placeholder="Sisesta rõnganumber või selle osa"/>
						<input type="submit" class="btn btn-primary" value="Otsi">
						
					</div>
				</form>
	            <?php if(!empty(trim($err))){
					echo '<div class="alert alert-danger mt-4" role="alert"> Päringu tegemiseks täida palun mõlemad väljad! </div>';
				}
				elseif(isset($_SESSION["kood"]) and isset($_SESSION["ronganumber"])){
					if($num_results > 0){
						echo
						'<table class="table table-striped">
						<thead>
						<tr>
						<th scope="col">#</th>
						<th scope="col">Liik</th>
						<th scope="col">Sugu</th>
						<th scope="col">Vanus</th>
						<th scope="col">Kuupäev</th>
						</tr>
						</thead>
						<tbody>'
						;
						$jrk = $first;
						while ($row = mysqli_fetch_array($sql)){
							$jrk++;
							$liik = $row['Liik'];
							$sugu = $row['Vanus'];
							$vanus = $row['Sugu'];
							$kuupaev = $row['Kuupäev'];
							echo"				
							<tr>
							<th>$jrk</th>
							<td>$liik</td>
							<td>$vanus</td>
							<td>$sugu</td>
							<td>$kuupaev</td>
							</tr>";
						}
						echo '</tbody>
						</table>';
					}
					else echo '<div class="alert alert-danger mt-4" role="alert"> Kahjuks ei leitud ühtegi antud päringule vastavat tulemust </div>';
				}
				?>
				
			</div>
		    <?php
				if(isset($_SESSION["kood"]) and isset($_SESSION["ronganumber"])){
					if($num_results > 0){
						$first +=1;
						echo "&nbsp Kuvatakse tulemused ". $first. " kuni ". $jrk." ". $num_results ."-st <br>";
						if(!isset($num_pages)){
							$num_pages=0;
						}
						$last = $page-1;
						$next = $page+1;
						echo'<ul class="pagination">';
						if($page==1) $disabled =" disabled";
						else $disabled="";
						
						echo'<li class="page-item'.$disabled.'"><a class="page-link" href="otsing.php?page=1">Esimene</a></li>';
						echo'<li class="page-item'.$disabled.'"><a class="page-link" href="otsing.php?page='. $last. '">Eelmine</a></li>';
						
						if($page==$num_pages) $disabled=" disabled";
						else $disabled="";
						
						echo'<li class="page-item'.$disabled.'"><a class="page-link" href="otsing.php?page='. $next. '">Järgmine</a></li>';
						echo'<li class="page-item'.$disabled.'"><a class="page-link" href="otsing.php?page='. $num_pages. '">Viimane</a></li>';
						echo'</ul>';
						
					}}
					
			?>
		</div>
	</body>
</html>
