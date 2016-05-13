<?php
	require('../functions/check_login.php');
	include '../functions/php_functions.php';
	$conn = get_connexion();


	$batchID = $_POST['batchID'];

	$query ='
	SELECT
	experiment.exp_name, sample.spl_number, aliquot.alq_number, batch_data.bat_data_row, batch_data.bat_data_col  
	FROM 
	experiment, batch_data, sample_aliquot, sample, aliquot 
	WHERE 
	batch_data.bat_data_batch_FK = '.$batchID.' AND
	sample_aliquot.spl_alq_id = batch_data.bat_data_sample_aliquot_FK AND
	sample.spl_id  = sample_aliquot.spl_alq_sample_FK AND 
	aliquot.alq_id = sample_aliquot.spl_alq_aliquot_FK AND
	experiment.exp_id = batch_data.bat_data_experiment_FK;
	';
		
	//error_log($query);
	$sth=$conn->prepare($query);
	$sth -> setFetchMode(PDO::FETCH_ASSOC);
	$sth->execute();
	$rows=$sth->fetchAll();

	$output = array();

	foreach($rows as $row){
		$line="";
	    foreach($row as $key=>$value){
        	$line.="-".$value;
	    }
	    $output[]=$line;
	} 

	header('Content-Type: application/json');
	echo json_encode($output);
?>

