<?php
header('Content-Type: application/json');
	require('../functions/check_login.php');
	include '../functions/php_functions.php';
	$conn = get_connexion();

	$expName="";
	$status="success";

	if(isset($_POST['expName'])){
		$expName = $_POST['expName'];
	}
	else {
		$status="error";
	}

	if($status=="success"){
		$query = "INSERT INTO `experiment`(`exp_id`, `exp_name`) VALUES ('','".$expName."');";
		error_log($query);
		try {
			$conn->beginTransaction();

			$conn->exec($query);
			$lastExpId=$conn->lastInsertId();

			$queryexpStd = "INSERT INTO `experiment_standard`(`exp_std_id`, `exp_std_standard_FK`, `exp_std_experiment_FK`) VALUES ('', 1, '".$lastExpId."');";
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

$response_array['status']=$status;
$response_array['action']="Insertion";
echo json_encode($response_array);
?>