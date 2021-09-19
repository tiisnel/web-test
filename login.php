<?php	
	require_once "navbar.php";
	require_once "config.php";
	$username = $password ="";
	$username_err = $password_err = $login_err="";
	
	
	if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true){
		header("location: avaleht.php");
		exit;
	}
	
    
	
	if($_SERVER["REQUEST_METHOD"] == "POST"){
		if(empty($_POST["kasutaja"])){
			$username_err = "Palun sisesta oma kasutajanimi.";
			} else{
			$username = trim($_POST["kasutaja"]);
		}
		if(empty($_POST["parool1"])){
			$password_err = "Parool on kohustuslik!.";
			} else{
			$password = trim($_POST["parool1"]);		
			
			if(empty($username_err) && empty($password_err)){
				$sql = "SELECT ID, Kasutajanimi, Parool FROM kasutajad WHERE Kasutajanimi = ?";
				
				if($stmt = mysqli_prepare($link, $sql)){
					mysqli_stmt_bind_param($stmt, "s", $param_username);
					$param_username = $username;
					if(mysqli_stmt_execute($stmt)){
						mysqli_stmt_store_result($stmt);
						if(mysqli_stmt_num_rows($stmt) == 1){
							mysqli_stmt_bind_result($stmt, $id, $username, $hashed_password);
							if(mysqli_stmt_fetch($stmt)){
								if(password_verify($password, $hashed_password)){
									session_start();
									$_SESSION["loggedin"] = true;
									$_SESSION["id"] = $id;
									$_SESSION["username"] = $username;
									header("location: avaleht.php");
									} else{
									$login_err = "Vale parool";
								}
							}
							} else{
							$login_err = "Sellise nimega kasutajat ei eksisteeri.";
						}
						} else{
						echo "Oops! Something went wrong. Please try again later.";
					}
					
					mysqli_stmt_close($stmt);
				}
			}
			mysqli_close($link);
		}
		}
	?>
	
	<!DOCTYPE html>
	<html lang="en">
		<head>
			<meta charset="UTF-8">
			<title>Logi sisse</title>
			<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
			<style>
				body{ font: 14px sans-serif; }
				.wrapper{ width: 360px; padding: 20px; }
			</style>
		</head>
		<body>
			<div class="wrapper">
				<h2>Logi sisse</h2>
				<?php 
					if(!empty($login_err)){
						echo '<div class="alert alert-danger">' . $login_err . '</div>';
					}        
				?>
				<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
					
					
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
						<input type="submit" class="btn btn-primary" value="Logi sisse">
					</div>
					<p>Kasutajakonto puudub? <a href="registeeri.php">Loo uus kasutaja</a>.</p>
				</form>
			</div>    
		</body>
	</html>				