<?php
	require('../../functions/check_login.php');
	include '../../functions/php_functions.php';
	header('Content-Type: application/json');
	$conn = get_connexion();

	$action="";
	$expID="";
	$stdID=1;
	$expName="";
	$status = "success";


	if (isset($_POST['expID'])) {
		$expID = $_POST['expID'];
	}
	if (isset($_POST['stdID'])) {
		$stdID = $_POST['stdID'];
	}
	if (isset($_POST['expName'])) {
		$expName = $_POST['expName'];
	}
	if (isset($_POST['action'])) {
		$action = $_POST['action'];
	}

	if($action=="create"){
		$query = "INSERT INTO `experiment`(`exp_id`, `exp_name`) VALUES ('','".$expName."');";
		error_log($query);
		try {
			$conn->beginTransaction();

			$conn->exec($query);
			$lastExpId=$conn->lastInsertId();
			if ((isset($_POST['stdID'])) && ($_POST['stdID'] != -1)) {
				$queryexpStd = "INSERT INTO `experiment_standard`(`exp_std_id`, `exp_std_standard_FK`, `exp_std_experiment_FK`) VALUES ('', '".$stdID."', '".$lastExpId."');";
			}
			else{
				$queryexpStd = "INSERT INTO `experiment_standard`(`exp_std_id`, `exp_std_standard_FK`, `exp_std_experiment_FK`) VALUES ('', '1', '".$lastExpId."');";
			}
			$conn->exec($queryexpStd);
			
			$conn->commit();
		}
		catch ( Exception $e ) {
			$conn->rollback();
			error_log("Une erreur est survenue lors de edition");
			error_log("Erreur : ".$e->getMessage()."<br/>");
			error_log("N° : ".$e->getCode());
			$status="error";
		}
	}
	elseif ($action=="edit") {
		$queryExp ="UPDATE `experiment` SET`exp_name`= '".$expName."' WHERE `exp_id`= '".$expID."';";

		try {
			$conn->beginTransaction();
			$req = $conn->exec($queryExp);
			if($stdID != -1){
				$queryExpStd ="UPDATE `experiment_standard` SET`exp_std_standard_FK`= '".$stdID."' WHERE `exp_std_experiment_FK`= '".$expID."';";
				$req = $conn->exec($queryExpStd);
			}
			
			$conn->commit();
			$status="success";
		}
		catch ( Exception $e ) {
			$conn->rollBack();
			error_log("ERROREdit ".$e);
			$status="error";
		}
	}
	elseif ($action=="delete") {

		$queryDeleteExp ="DELETE FROM `experiment` WHERE `exp_id` = '".$expID."';";
		$queryDeleteSample ="DELETE FROM `sample` WHERE `spl_id` IN (";
		$queryDeleteAliquot ="DELETE FROM `aliquot` WHERE `alq_id` IN (";
		$queryDeleteFw ="DELETE FROM `freshweight` WHERE `fw_id` IN (";
		$queryDeleteLocaton ="DELETE FROM `location` WHERE `loc_id` IN (";

		$queryselectinfos ='
			SELECT 
				sample.spl_id, 
				aliquot.alq_id,
				freshweight.fw_id, 
				location.loc_id
			FROM 
				experiment_freshweight, 
				freshweight, 
				freshweight_sample, 
				sample, 
				sample_aliquot, 
				aliquot, 
				location
			WHERE 
				experiment_freshweight.exp_fw_experiment_FK = '.$expID.' AND
				freshweight.fw_id = experiment_freshweight.exp_fw_freshweight_FK AND
				freshweight_sample.fw_spl_freshweight_FK = freshweight.fw_id AND
				sample.spl_id = freshweight_sample.fw_spl_sample_FK AND
				sample_aliquot.spl_alq_sample_FK = sample.spl_id AND
				aliquot.alq_id = sample_aliquot.spl_alq_aliquot_FK AND
				location.loc_id = aliquot.alq_location_FK
			ORDER BY sample.spl_number;
		';
		try {
			$resultats=$conn->query($queryselectinfos);
			while( $resultat = $resultats->fetch(PDO::FETCH_OBJ) ){
				// error_log($resultat->spl_id);
				$queryDeleteSample .= " ".$resultat->spl_id.",";
				$queryDeleteAliquot .= " ".$resultat->alq_id.",";
				$queryDeleteFw .= " ".$resultat->fw_id.",";
				$queryDeleteLocaton .= " ".$resultat->loc_id.",";
			}
			$resultats->closeCursor();
			$queryDeleteSample = rtrim($queryDeleteSample, ",");
			$queryDeleteSample.=");";
			// error_log($queryDeleteSample);

			$queryDeleteAliquot = rtrim($queryDeleteAliquot, ",");
			$queryDeleteAliquot.=");";
			// error_log($queryDeleteAliquot);

			$queryDeleteFw = rtrim($queryDeleteFw, ",");
			$queryDeleteFw.=");";
			// error_log($queryDeleteFw);

			$queryDeleteLocaton = rtrim($queryDeleteLocaton, ",");
			$queryDeleteLocaton.=");";
			// error_log($queryDeleteLocaton);

			try {
				$conn->beginTransaction();
				$req = $conn->exec($queryDeleteSample);
				$req = $conn->exec($queryDeleteAliquot);
				$req = $conn->exec($queryDeleteFw);
				$req = $conn->exec($queryDeleteLocaton);
				$conn->commit();
				$status="success";
			}
			catch ( Exception $e ) {
				error_log("ERRORDelete ".$e);
				$status="error";
				$conn->rollBack();
			}
		}
		catch ( Exception $e ) {
			error_log("ERRORDelete ".$e);
			$status="error";
			$conn->rollBack();
		}
		try {
			$conn->beginTransaction();
			$req = $conn->exec($queryDeleteExp);
			$conn->commit();
			$status="success";
		}
		catch ( Exception $e ) {
			error_log("ERRORDelete ".$e);
			$status="error";
			$conn->rollBack();
		}
	}
	else {
		$status="error";
		$action = "Unknown";
	} 

$response_array['status']=$status;
$response_array['action']=$action;
echo json_encode($response_array);

?>

