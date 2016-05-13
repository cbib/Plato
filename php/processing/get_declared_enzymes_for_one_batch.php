<?php
	require('../functions/check_login.php');
	include '../functions/php_functions.php';
	$conn = get_connexion();
	$batchID = $_POST['batchID'];

	$query = '
		SELECT 
			DISTINCT 
			enzyme.ez_id, 
			enzyme.ez_analyte, 
			enzyme.ez_code
		FROM 
			enzyme, batch_data, experiment, experiment_standard, standard_enzyme
		WHERE 
			batch_data.bat_data_batch_FK ='.$batchID.'
			AND exp_id = bat_data_experiment_FK
			AND exp_std_experiment_FK = bat_data_experiment_FK
			AND std_ez_standard_FK = exp_std_standard_FK
			AND ez_id = std_ez_enzyme_FK
			ORDER BY ez_analyte;';

	try {
		$sth=$conn->prepare($query);
		$sth -> setFetchMode(PDO::FETCH_ASSOC);
		$sth->execute();
		$count = $sth->rowCount();
		$rows=$sth->fetchAll();
	}
	catch ( Exception $e ) {
		error_log($e);
	}

	header('Content-Type: application/json');
	echo json_encode($rows);
?>