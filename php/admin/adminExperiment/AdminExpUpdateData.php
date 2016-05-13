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

			$queryexpStd = "INSERT INTO `experiment_standard`(`exp_std_id`, `exp_std_standard_FK`, `exp_std_experiment_FK`) VALUES ('', '".$stdID."', '".$lastExpId."');";
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
		$query ="DELETE FROM `experiment` WHERE `exp_id` = '".$expID."';";
		try {
			$conn->beginTransaction();
			$req = $conn->exec($query);
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