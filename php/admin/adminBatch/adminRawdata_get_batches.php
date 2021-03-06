<?php
	require('../../functions/check_login.php');
	include '../../functions/php_functions.php';
	$conn = get_connexion();


	// get the list of batches for an experiment
	$expID = $_POST['expID'];
	//error_log($expID);
	$query ='
		SELECT  distinct batch.bat_id AS ID, CONCAT(batch.bat_number, "	-	", batch.bat_name, "	-	", batch.bat_date) AS numbname
		FROM batch_data, batch
		WHERE batch_data.bat_data_experiment_FK = '.$expID.' AND
		batch.bat_id = batch_data.bat_data_batch_FK
		ORDER BY batch.bat_number;
	';
		
	$sth=$conn->prepare($query);
	$sth -> setFetchMode(PDO::FETCH_ASSOC);
	$sth->execute();
	$rows=$sth->fetchAll();

	$output = array();

	foreach($rows as $row){
		//$r=Array();
	   	$output[$row['ID']]= $row['numbname'];
	    //$output[] = $r;
	} 

	header('Content-Type: application/json');
	echo json_encode($output);
?>