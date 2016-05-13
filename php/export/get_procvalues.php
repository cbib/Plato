<?php
	require('../functions/check_login.php');
	include '../functions/php_functions.php';
	$conn = get_connexion();

	$hash=Array();

	$batchID =-1;
	$ezID = -1;

	if (isset($_POST['batchID'])){
		$batchID = $_POST['batchID'];
	}
	if (isset($_POST['ezID'])){
		$ezID = $_POST['ezID'];
	}

	if (($batchID != -1) && ($ezID != -1)){
		$query ='SELECT 
					DISTINCT experiment.*, rawdata.data_is_excluded, processdata.pro_value
				FROM
					experiment, batch_data, rawdata, raw_equ_proc, processdata
				WHERE
					bat_data_batch_FK = '.$batchID.' AND
					experiment.exp_id = bat_data_experiment_FK AND 
					rawdata.rawdata_batch_data_FK = batch_data.bat_data_id AND
					rawdata.data_enzyme_FK = '.$ezID.' AND
					raw_equ_proc.raw_equ_proc_rawdata_FK = rawdata.rawdata_id AND
					processdata.pro_id = raw_equ_proc.raw_equ_proc_processdata_FK;';

		$sth=$conn->prepare($query);
		$sth -> setFetchMode(PDO::FETCH_ASSOC);
		$sth->execute();
		$count = $sth->rowCount();
		$rows=$sth->fetchAll();

		foreach($rows as $row){
			if(($row['exp_name'] !="EB") && ($row['exp_name'] !="?")){
				$hash[$row['exp_name']]["number"] =0;
			}
		} 

		foreach($rows as $row){
			if(($row['exp_name'] !="EB") && ($row['exp_name'] !="?")){
				$hash[$row['exp_name']]["number"]++;
			}
		} 

		$min = 96;
		$minname="";
		foreach($hash as $expname){
			if ($expname["number"]<$min){
				$minname = key($hash);
			}
		}

		$procValues=[];
		foreach($rows as $row){
			if($row['exp_name'] ==$minname){
				array_push($procValues, $row['pro_value']);
			}
		}
	}

	$response_array['procValues']=$procValues;
	echo json_encode($response_array);
?>