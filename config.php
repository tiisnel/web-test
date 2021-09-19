<?php
	$link = mysqli_connect('localhost', 'root','','vaibla');
	if($link === false){
		die("Tekkis viga. Ühendus puudub " . mysqli_connect_error());
	}
?>