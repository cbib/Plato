<?php
	include '../functions/php_functions.php';
	
	$conn = get_connexion();

	/* Controls on login info */
	// error_log($_POST['user']);
	// error_log($_POST['pass']);

	if(get_magic_quotes_gpc())
	{
		$_POST['user'] = stripslashes($_POST['user']);
		$_POST['pass'] = stripslashes($_POST['pass']);
	}

			// error_log($_POST['user']);
			// error_log($_POST['pass']);

	if (($_POST['user']!="") &&  ($_POST['pass']!="")){
		$pass_hache = hash( 'sha256', $_POST['pass']);
		$userName = $_POST['user'];
		// Vérification des identifiants
		$query ="
		SELECT
			usr_status
		FROM
			user
		WHERE
			usr_name like '%".$userName."%' AND 
			usr_pwd like '%".$pass_hache."%' ;
		";

		try 
		{
			$req = $conn->query($query)->fetchColumn();
			show_array($req);
			if (!$req)
			{
			    error_log('Mauvais identifiant ou mot de passe !');
			}
			else
			{
				session_save_path('/tmp');
			    session_start();
			    $_SESSION['login'] = $req;
			    // error_log('Vous etes connecte comme : '.$req);
			}
		}
		catch ( Exception $e ) {
			error_log("failure est survenue lors de $query".$e->getMessage());
		}
	}
	header('Location:../../index.php');

?>
