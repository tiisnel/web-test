<?php
	require_once "navbar.php";
	require_once "config.php";
	
	
	if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true){
		header("location: avaleht.php");
		exit;
	}
	
	$username = $password = $password2 = $firstname = $lastname = $date = $email = $num ="";
	$username_err = $password_err = $password2_err = $firstname_err = $lastname_err = $date_err = $email_err = $num_err ="";
	
	if($_SERVER["REQUEST_METHOD"] == "POST"){
		
		
		// kasutajanime kontroll
		if(empty(trim($_POST["kasutaja"]))){
			$username_err = "Palun vali kasutajanimi";
			} elseif(!preg_match('/^[a-zA-Z0-9_]+$/', trim($_POST["kasutaja"]))){
			$username_err = "Viga: keelatud sümbol kasutajanimes";
			} else{
			// Prepare a select statement
			$sql = "SELECT ID FROM kasutajad WHERE Kasutajanimi = ?";
			
			if($stmt = mysqli_prepare($link, $sql)){
				// Bind variables to the prepared statement as parameters
				mysqli_stmt_bind_param($stmt, "s", $param_username);
				
				// Set parameters
				$param_username = trim($_POST["kasutaja"]);
				
				// Attempt to execute the prepared statement
				if(mysqli_stmt_execute($stmt)){
					/* store result */
					mysqli_stmt_store_result($stmt);
					
					if(mysqli_stmt_num_rows($stmt) == 1){
						$username_err = "See kasutajanimi on juba kasutusel.";
						} else{
						$username = trim($_POST["kasutaja"]);
					}
					} else{
					echo "Oops! Something went wrong. Please try again later.";
				}
				
				// Close statement
				mysqli_stmt_close($stmt);
			}
		}
		
		// parooli kontroll
		if(empty(trim($_POST["parool1"]))){
			$password_err = "Palun sisesta parool.";     
			} elseif(strlen(trim($_POST["parool1"])) < 8){
			$password_err = "Parool peab olema pikem!";
			} else{
			$password = trim($_POST["parool1"]);
		}
		
		// Validate confirm password
		if(empty(trim($_POST["parool2"]))){
			$confirm_password_err = "Palun korda parooli";     
			} else{
			$confirm_password = trim($_POST["parool2"]);
			if(empty($password_err) && ($password != $confirm_password)){
				$password2_err = "Paroolid ei kattu!";
			}
		}
		
		
		if(empty(trim($_POST["eesnimi"]))){
			$firstname_err = "Palun sisesta oma nimi";
			} elseif(!preg_match('/^[a-zA-Z-õäöüÕÄÖÜ]+$/', trim($_POST["eesnimi"]))){
			$firstname_err = "Viga: keelatud sümbol nimes";
			} else{
			$firstname = trim($_POST["eesnimi"]);
		}
		
		
		if(empty(trim($_POST["perekonnanimi"]))){
			$lastname_err = "Palun sisesta oma perekonnanimi";
			} elseif(!preg_match('/^[a-zA-Z-õäöüÕÄÖÜ]+$/', trim($_POST["perekonnanimi"]))){
			$lastname_err = "Viga: keelatud sümbol perekonnanimes";
			} else{
			$lastname = trim($_POST["perekonnanimi"]);
		}
		
		
		if (!filter_var($_POST["email"], FILTER_VALIDATE_EMAIL)){
			$email_err = "Palun sisesta korrektne meiliaadress";
			} else{
			// Prepare a select statement
			$sql = "SELECT ID FROM kasutajad WHERE Epost = ?";
			
			if($stmt = mysqli_prepare($link, $sql)){
				// Bind variables to the prepared statement as parameters
				mysqli_stmt_bind_param($stmt, "s", $param_email);
				
				// Set parameters
				$param_email = trim($_POST["email"]);
				
				// Attempt to execute the prepared statement
				if(mysqli_stmt_execute($stmt)){
					/* store result */
					mysqli_stmt_store_result($stmt);
					
					if(mysqli_stmt_num_rows($stmt) == 1){
						$email_err = "See meiliaadress on juba kasutusel.";
						} else{
						$email = trim($_POST["email"]);
					}
					} else{
					echo "Oops! Something went wrong. Please try again later.";
				}
				
				// Close statement
				mysqli_stmt_close($stmt);
			}
		}
		
		if(strlen($_POST["telefon"])<7 || strlen($_POST["telefon"]) > 12){
			$num_err = "Palun sisesta korrektne telefoninumber";
			} else{
			// Prepare a select statement
			$sql = "SELECT ID FROM kasutajad WHERE Telefon = ?";
			
			if($stmt = mysqli_prepare($link, $sql)){
				// Bind variables to the prepared statement as parameters
				mysqli_stmt_bind_param($stmt, "s", $param_phone);
				
				// Set parameters
				$param_phone = trim($_POST["telefon"]);
				
				// Attempt to execute the prepared statement
				if(mysqli_stmt_execute($stmt)){
					/* store result */
					mysqli_stmt_store_result($stmt);
					
					if(mysqli_stmt_num_rows($stmt) == 1){
						$num_err = "Antud telefoninumber on juba kasutusel.";
						} else{
						$num = trim($_POST["telefon"]);
					}
					} else{
					echo "Oops! Something went wrong. Please try again later.";
				}
				
				// Close statement
				mysqli_stmt_close($stmt);
			}
		}
		
		if(!preg_match('/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/', trim($_POST["vanus"]))){
			$date_err = "Palun kasuta yyyy-mm-dd formaati";
			} else{
			$date = trim($_POST["vanus"]);
		}
		
		
		// Sisestatakse vaid veatu info 
		if(empty($username_err) && empty($password_err) && empty($password2_err) && empty($firstname_err) && empty($lastname_err) && empty($date_err) && empty($email_err) && empty($num_err)){
			
			// Prepare an insert statement
			$sql = "INSERT INTO kasutajad (Kasutajanimi, Parool, Eesnimi, Perenimi, Sünniaeg, Epost, Telefon) VALUES (?, ?, ?, ?, ?, ?, ?)";
			
			if($stmt = mysqli_prepare($link, $sql)){
				
				
				
				// Bind variables to the prepared statement as parameters
				mysqli_stmt_bind_param($stmt, "sssssss", $param_username, $param_password, $firstname, $param_lastname, $param_date, $param_email, $param_phone);
				
				
				
				// Set parameters
				$param_username = $username;
				$param_password = password_hash($password, PASSWORD_DEFAULT); // Creates a password hash
				$param_firstname = $firstname;
				$param_lastname = $lastname;
				$param_date = $date;
				$param_email = $email;
				$param_phone = $num;
				
				// Attempt to execute the prepared statement
				if(mysqli_stmt_execute($stmt)){
					// Redirect to login page
					header("location: login.php");
					} else{
					echo "Oops! Something went wrong. Please try again later.";
					print_r ($stmt);
				}
				
				// Close statement
				mysqli_stmt_close($stmt);
			}
		}
		
		// Close connection
		mysqli_close($link);
	}
?>

<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="UTF-8">
		<title>Registreeru</title>
		<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
		<style>
			body{ font: 14px sans-serif; }
			.wrapper{ width: 360px; padding: 20px; }
		</style>
	</head>
	<body>
		<div class="wrapper">
			<h2>Loo uus kasutaja</h2>
			<p>Kasutajakonto loomiseks täida allolev ankeet.</p>
			<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
				
				<div class="form-group">
					<label>Eesnimi</label>
					<input type="text" name="eesnimi" placeholder="Eesnimi" class="form-control <?php echo (!empty($firstname_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $firstname; ?>">
					<span class="invalid-feedback"><?php echo $firstname_err; ?></span>
				</div> 
				
				<div class="form-group">
					<label>Perekonnanimi</label>
					<input type="text" name="perekonnanimi" placeholder="Perekonnanimi" class="form-control <?php echo (!empty($lastname_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $lastname; ?>">
					<span class="invalid-feedback"><?php echo $lastname_err; ?></span>
				</div> 
				
				<div class="form-group">
					<label>Sünniaeg</label>
					<input type="text" name="vanus" placeholder="yyyy-mm-dd" class="form-control <?php echo (!empty($date_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $date; ?>">
					<span class="invalid-feedback"><?php echo $date_err; ?></span>
				</div> 
				
				<div class="form-group">
					<label>Email</label>
					<input type="text" name="email" placeholder="email@aadress.com" class="form-control <?php echo (!empty($email_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $email; ?>">
					<span class="invalid-feedback"><?php echo $email_err; ?></span>
				</div> 
				
				<div class="form-group">
					<label>Telefoninumber</label>
					<input type="text" name="telefon" class="form-control <?php echo (!empty($num_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $num; ?>">
					<span class="invalid-feedback"><?php echo $num_err; ?></span>
				</div> 
				
				<div class="form-group">
					<label>Kasutajanimi</label>
					<input type="text" name="kasutaja" class="form-control <?php echo (!empty($username_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $username; ?>">
					<span class="invalid-feedback"><?php echo $username_err; ?></span>
				</div>    
				
				<div class="form-group">
					<label>Parool</label>
					<input type="password" name="parool1" class="form-control <?php echo (!empty($password_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $password; ?>">
					<span class="invalid-feedback"><?php echo $password_err; ?></span>
				</div>
				<div class="form-group">
					<label>Korda parooli</label>
					<input type="password" name="parool2" class="form-control <?php echo (!empty($password2_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $password2; ?>">
					<span class="invalid-feedback"><?php echo $password2_err; ?></span>
				</div>
				<div class="form-group">
					<input type="submit" class="btn btn-primary" value="Kinnita andmed">
				</div>
				<p>Kasutaja juba olemas? <a href="login.php">Logi sisse</a>.</p>
			</form>
		</div>    
	</body>
</html>		