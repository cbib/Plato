<?php
	require('../functions/check_login.php');
	include '../functions/php_functions.php';
	$conn = get_connexion();

	$expID = $_POST['expID'];
	$expName = $_POST['expName'];
	$output = array();

	$query ='SELECT bat_data_batch_FK, bat_data_id, spl_number, alq_number FROM batch_data, sample_aliquot, sample, aliquot WHERE bat_data_experiment_FK = '.$expID.' AND
	sample_aliquot.spl_alq_id = batch_data.bat_data_sample_aliquot_FK AND
	sample.spl_id = sample_aliquot.spl_alq_sample_FK AND
	aliquot.alq_id = sample_aliquot.spl_alq_aliquot_FK
	ORDER BY sample.spl_number, aliquot.alq_number ;';

	$results = $conn->query($query)->fetchAll();
	$ezNameQuery ='SELECT distinct (ez_analyte), ez_id
	FROM 
		enzyme, rawdata, batch_data
	WHERE
		bat_data_experiment_FK = '.$expID.' AND 
		rawdata_batch_data_FK = bat_data_id AND 
		ez_id = data_enzyme_FK 
	ORDER BY
		enzyme.ez_analyte;';
	$ezNames = $conn->query($ezNameQuery)->fetchAll();
	$lineName="";
	foreach($ezNames as $name){
		$lineName.=$name[0]."#".$name[1].">";
	}
	$output[]=$lineName;


	foreach($results as $row) {
		$line="";
		$batchNameQuery = 'SELECT CONCAT(bat_name, "-", bat_number, "-", bat_date) 
		FROM 
			batch, batch_data 
		WHERE 
			bat_data_id = '.$row[1].' AND 
			bat_id = bat_data_batch_FK;';

		$batchName = $conn->query($batchNameQuery)->fetchColumn();
		$splAlqNbQuery = 'SELECT CONCAT(spl_number, "#", alq_number) 
		FROM 
			batch_data, sample_aliquot, sample, aliquot 
		WHERE 
			bat_data_id = '.$row[1].' AND 
			sample_aliquot.spl_alq_id = batch_data.bat_data_sample_aliquot_FK AND
			aliquot.alq_id = sample_aliquot.spl_alq_aliquot_FK AND
			sample.spl_id = sample_aliquot.spl_alq_sample_FK;';

		$spl_alq = $conn->query($splAlqNbQuery)->fetchColumn();
		$ezQuery ='
		SELECT 	
			distinct rawdata.data_enzyme_FK, enzyme.ez_analyte
		FROM
			rawdata, enzyme 
		WHERE 
			rawdata.rawdata_batch_data_FK = '.$row[1].' AND
			enzyme.ez_id = rawdata.data_enzyme_FK;
		';
		$line=$row[0]."#".$row[1]."#".$batchName."#".$expName."#".$spl_alq;
		$enzymes = $conn->query($ezQuery)->fetchAll();

		foreach($ezNames as $name){
			$enzymeID=$name[1];
			$procValuesQuery='SELECT pro_value 
			FROM 
				rawdata, raw_equ_proc, processdata 
			WHERE 
				rawdata_batch_data_FK = '.$row[1].' AND 
				data_enzyme_FK = '.$enzymeID.' AND 
				raw_equ_proc_rawdata_FK = rawdata_id AND 
				pro_id = raw_equ_proc_processdata_FK;';
			$procvalue = $conn->query($procValuesQuery)->fetchColumn();
			if($procvalue ==""){
				$procvalue = "NA";
			}
			else {
				$procvalue = round($procvalue, 2, PHP_ROUND_HALF_EVEN);
			}
			$line.="#".$procvalue;
		}
		$output[]=$line;
	}
	header('Content-Type: application/json');
	echo json_encode($output);
?>


