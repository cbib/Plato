<?php
	require('../functions/check_login.php');
	include '../functions/php_functions.php';
	$conn = get_connexion();
	$output="error";

	if((isset($_POST['batchID'])) && (isset($_POST['ezID'])) ){
		$batchID = $_POST['batchID'];
		$ezID = $_POST['ezID'];

		$query ='
		SELECT 
			experiment.exp_name, batch_data.bat_data_row, batch_data.bat_data_col, rawdata.data_value , rawdata.data_velocity, rawdata.data_enzyme_FK, rawdata.data_is_excluded, rawdata.rawdata_id, batch.bat_layout
		FROM
			batch, batch_data, rawdata, experiment
		WHERE 
			batch.bat_id = "'.$batchID.'" AND 
			batch_data.bat_data_batch_FK = batch.bat_id AND 
			experiment.exp_id = batch_data.bat_data_experiment_FK AND
			rawdata.rawdata_batch_data_FK = batch_data.bat_data_id AND
			rawdata.data_enzyme_FK = "'.$ezID.'";';

		$sth=$conn->prepare($query);
		$sth -> setFetchMode(PDO::FETCH_ASSOC);
		$sth->execute();
		$rows=$sth->fetchAll();

		$output = array();

		foreach($rows as $row){
			$line="";
			$query2 ='
				SELECT 
					processdata.pro_value 
				FROM
					raw_equ_proc, processdata 
				WHERE 
					raw_equ_proc.raw_equ_proc_rawdata_FK = "'.$row['rawdata_id'].'" AND
					processdata.pro_id = raw_equ_proc.raw_equ_proc_processdata_FK;';

			$req = $conn->query($query2)->fetchColumn();

			$query3 ='
			SELECT 
				aliquot.alq_value 
			FROM
				batch_data, sample_aliquot, aliquot 
			WHERE
				bat_data_batch_FK = "'.$batchID.'" AND 
				bat_data_row = "'.$row['bat_data_row'].'" AND
				bat_data_col = "'.$row['bat_data_col'].'" AND
				sample_aliquot.spl_alq_id  = batch_data.bat_data_sample_aliquot_FK AND
				aliquot.alq_id = sample_aliquot.spl_alq_aliquot_FK ;';

			$alq_value = $conn->query($query3)->fetchColumn();

		    foreach($row as $key=>$value){
				$line.="#".$value;
		    }
		    $line.="#".$req;
		    $line.="#".$alq_value;
		    $output[]=$line;
		}
	}
	header('Content-Type: application/json');
	echo json_encode($output);
?>

