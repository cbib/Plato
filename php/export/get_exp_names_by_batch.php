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
					experiment.*, rawdata.data_is_excluded
				FROM
					experiment, batch_data, rawdata
				WHERE
					bat_data_batch_FK = '.$batchID.' AND
					experiment.exp_id = bat_data_experiment_FK AND 
					rawdata.rawdata_batch_data_FK = batch_data.bat_data_id AND
					rawdata.data_enzyme_FK = '.$ezID.';';

		$sth=$conn->prepare($query);
		$sth -> setFetchMode(PDO::FETCH_ASSOC);
		$sth->execute();
		$count = $sth->rowCount();
		$rows=$sth->fetchAll();

		foreach($rows as $row){
			if(($row['exp_name'] !="EB") && ($row['exp_name'] !="?")){
				$hash[$row['exp_name']]["number"] =0;
				$hash[$row['exp_name']]["excluded"] =0;
			}
		} 
		foreach($rows as $row){
			if(($row['exp_name'] !="EB") && ($row['exp_name'] !="?")){
				$hash[$row['exp_name']]['number']++;
				if($row['data_is_excluded']==1){
					$hash[$row['exp_name']]['excluded']++;
				}
			}
		} 

		$min = 96;
		$minname="";
		foreach($hash as $expname){
			if ($expname["number"]<$min){
				$minname = key($hash);
			}
		}
	}

	$response_array['MaxStd']=$hash[$minname]["number"];
	$response_array['StdUsed']=($hash[$minname]["number"]-$hash[$minname]["excluded"]);
	echo json_encode($response_array);
?>