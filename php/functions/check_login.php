<?php
	session_save_path('/tmp');
	session_start();
	// On teste si la variable de session existe et contient une valeur
	if(empty($_SESSION['login']))
	{
	  // Si inexistante ou nulle, on redirige vers le formulaire de login
	  header('Location: /plato/index.php');
	  exit();
	}

?>

