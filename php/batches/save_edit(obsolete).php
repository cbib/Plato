<?php
header('Content-Type: application/json');
	require('../functions/check_login.php');
	include '../functions/php_functions.php';
	$conn = get_connexion();

	$status="success";
	$batchID = "";
	$rawdatas = "";

	if(isset($_POST['batchID'])){
		$batchID = $_POST['batchID'];
	}
	if(isset($_POST['rawdatas'])){
		$rawdatas = $_POST['rawdatas'];
	}





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







	try {
		$conn->beginTransaction();
		$conn->exec($query);
		$conn->commit();
	}
	catch ( Exception $e ) {
		$conn->rollback();
		error_log("Une erreur est survenue lors de edition");
		error_log("Erreur : ".$e->getMessage()."<br/>");
		error_log("N° : ".$e->getCode());
		$status="error";
	}

$response_array['status']=$status;
$response_array['action']="Insertion";
echo json_encode($response_array);



?>