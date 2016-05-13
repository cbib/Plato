<?php

//------------------------------------------------------------------------------//
//*fonction de connexion a la bdd
//------------------------------------------------------------------------------//
function get_connexion(){
	$PARAM_hote='your_host'; // chemin vers le serveur
	$PARAM_nom_bd='your_db_name'; // nom de la base de données 
	$PARAM_utilisateur='your_username'; // nom d'utilisateur pour se connecter
	$PARAM_mot_passe='your_passwordname'; // mot de passe de l'utilisateur pour se connecter
	
	try {
			$connexion = new PDO('mysql:host='.$PARAM_hote.';dbname='.$PARAM_nom_bd, $PARAM_utilisateur, $PARAM_mot_passe, array(PDO::ATTR_PERSISTENT => true)); // Persistent connection
			$connexion->setAttribute  (PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			//print_r($connexion->errorInfo());
			//print ($connexion->errorCode());
			//mysql_set_charset('utf8');
	}
	catch(Exception $e)
	{
		echo 'Une erreur est survenue lors de la connexion!'. $e->getMessage();
		echo $e->getCode();
		
        die();
	}
	return $connexion;
}



//affiche un tableau dans la console
function show_array($array){
	error_log("<pre>");
	for($i = 0; $i< sizeof($array); $i++){
		error_log($array[$i]);
	}	
	error_log("</pre>");
}
























?>
