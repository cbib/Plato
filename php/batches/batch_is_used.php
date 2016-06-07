<?php
	require('../functions/check_login.php');
	include '../functions/php_functions.php';
	$conn = get_connexion();

	$line = $_POST['line'];
	$infos= explode("-",$line);

	$status = "error";
	$req=0;

	if ($infos[0] == "EB"){
		$status="success";
	}
	elseif($infos[0] =="?"){
		$status="success";
	}
/*	else {
		$query = "
		SELECT 
		count(*) 
		FROM
		freshweight, freshweight_sample, sample, sample_aliquot, aliquot 
		WHERE 
		freshweight.fw_name like '%".$infos[0]."%' AND 
		freshweight_sample.fw_spl_freshweight_FK = freshweight.fw_id AND
		sample.spl_id = freshweight_sample.fw_spl_sample_FK AND
		sample_aliquot.spl_alq_sample_FK =sample.spl_id AND
		sample.spl_number = ".$infos[1]." AND 
		aliquot.alq_id = sample_aliquot.spl_alq_aliquot_FK AND
		sample_aliquot.spl_alq_state like '%free%' AND
		aliquot.alq_number = ".$infos[2].";";
		try 
		{
			$req = $conn->query($query)->fetchColumn();
			error_log("COUNT : ".$req);
			if ($req > 0) {
				$status="success";
			}
		}
		catch ( Exception $e ) {
			error_log("failure est survenue lors de $query".$e->getMessage());
			$status = "error";
		}
	}*/
	else{
		try 
		{
			$stmt = $conn->prepare("SELECT 
			count(*) 
			FROM
			freshweight, freshweight_sample, sample, sample_aliquot, aliquot 
			WHERE 
			freshweight.fw_name like '%:infos0%' AND 
			freshweight_sample.fw_spl_freshweight_FK = freshweight.fw_id AND
			sample.spl_id = freshweight_sample.fw_spl_sample_FK AND
			sample_aliquot.spl_alq_sample_FK =sample.spl_id AND
			sample.spl_number = :infos1 AND 
			aliquot.alq_id = sample_aliquot.spl_alq_aliquot_FK AND
			sample_aliquot.spl_alq_state like '%free%' AND
			aliquot.alq_number = :infos2");

			$stmt->bindValue(':infos0', $infos[0], PDO::PARAM_STR);
			$stmt->bindValue(':infos1', $infos[1], PDO::PARAM_INT);
			$stmt->bindValue(':infos2', $infos[2], PDO::PARAM_INT);
			$stmt->execute();

			$stmt->debugDumpParams();

			$result = $stmt->fetchColumn();
			if ($stmt->rowCount() > 0) {
				$status="success";
			}
		}
		catch ( Exception $e ) {
			error_log("failure est survenue lors de $query".$e->getMessage());
			$status = "error";
		}
	}


	$response_array['status']=$status;
	echo json_encode($response_array);
?>

