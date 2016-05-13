<?php
include '../functions/php_functions.php';
header('Content-Type: application/json');
$conn = get_connexion();

	$sampleID=""; //ID
	$sampleNB=""; //Valeur potentiellement  a changer
	$aliquotID=""; //ID
	$aliquotNB=""; //
	$value="";
	$state="";
	$location ="";
	$action="";
	$expID="";
	$fwID="";
	$locID ="";

	$status="success";
	if (isset($_POST['sampleID'])) {
		$sampleID = $_POST['sampleID'];
	}
	if (isset($_POST['sampleNB'])) {
		$sampleNB = $_POST['sampleNB'];
	}
	if (isset($_POST['aliquotID'])) {
		$aliquotID = $_POST['aliquotID'];
	}
	if (isset($_POST['aliquotNB'])) {
		$aliquotNB = $_POST['aliquotNB'];
	}
	if (isset($_POST['value'])) {
		$value = $_POST['value'];
	}
	if (isset($_POST['state'])) {
		$state = $_POST['state'];
	}
	if (isset($_POST['location'])) {
		$location = $_POST['location'];
	}
	if (isset($_POST['action'])) {
		$action = $_POST['action'];
	}
	if (isset($_POST['expID'])) {
		$expID = $_POST['expID'];
	}
	if (isset($_POST['fwID'])) {
		$fwID = $_POST['fwID'];
	}
	if (isset($_POST['locID'])) {
		$locID = $_POST['locID'];
	}

/**
 * différentes requetes en fonction de l'action a effectuer
 */
if ($action === 'edit') {
	$querySample ="UPDATE `sample` SET `spl_number`='".$sampleNB."' WHERE `spl_id`='".$sampleID."';";
	$queryAliquot ="UPDATE `aliquot` SET `alq_number`= '".$aliquotNB."', `alq_value`= '".$value."', `alq_state`= '".$state."' WHERE `alq_id`= '".$aliquotID."';";
	$queryLocation ="UPDATE `location` SET `loc_fridge`= '".$location."' WHERE `loc_id`= '".$locID."';";
	// error_log($querySample);
	// error_log($queryAliquot);
	try {
		$conn->beginTransaction();
		$conn->exec($querySample);
		$conn->exec($queryAliquot);
		$conn->exec($queryLocation);
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
elseif ($action === 'create') {

	$queryLocation="INSERT INTO `location`(`loc_id`, `loc_fridge`, `loc_etage`, `loc_box`, `loc_comment`) VALUES ('', '".$location."', '', '', '');";

	// error_log($querySample);
	// error_log($queryAliquot);
	try {
		$conn->beginTransaction();
		//Insertion sample & Aliquot
		$conn->exec($queryLocation);
		$lastLocationID = $conn->lastInsertId();

		$querySample ="INSERT INTO `sample`(`spl_id`, `spl_number`, `spl_location_FK`) VALUES ( '', '".$sampleNB."', '".$lastLocationID."');";
		$queryAliquot="INSERT INTO `aliquot`(`alq_id`, `alq_number`, `alq_value`, `alq_state`, `alq_location_FK`) VALUES ('', '".$aliquotNB."', '".$value."', '".$state."', '".$lastLocationID."');";

		$conn->exec($querySample);
		$lastSampleID=$conn->lastInsertId();
		$conn->exec($queryAliquot);
		$lastAliquotID=$conn->lastInsertId();

		//association sample et aliquot dans sample_aliquot...
		$querySplAlq = "INSERT INTO `sample_aliquot`(`spl_alq_id`, `spl_alq_sample_FK`, `spl_alq_aliquot_FK`) VALUES ('', '".$lastSampleID."', '".$lastAliquotID."');";
		//error_log($querySplAlq);
		$conn->exec($querySplAlq);


		//Creation d'un freshweight
		//$queryFw = "INSERT INTO `freshweight`(`fw_id`, `fw_name`) VALUES ('','SELECT exp_name FROM experiment WHERE exp_id = ".$expID."');";
		$queryFw = "INSERT INTO freshweight (fw_id, fw_name) 
		SELECT  '', exp_name FROM experiment WHERE exp_id = ".$expID.";";
		//error_log($queryFw);
		$conn->exec($queryFw);
		$lastFwId=$conn->lastInsertId();
		//error_log("FW : ".$lastFwId);

		//Association du freshweight au sample dans freshweight_sample
		$queryFwSpl = "INSERT INTO `freshweight_sample`(`fw_spl_id`, `fw_spl_freshweight_FK`, `fw_spl_sample_FK`) VALUES ('', '".$lastFwId."', '".$lastSampleID."');";
		//error_log($queryFwSpl);
		$conn->exec($queryFwSpl);

		//Association du freshweight a l'experiment
		$queryExpFw = "INSERT INTO `experiment_freshweight`(`exp_fw_id`, `exp_fw_experiment_FK`, `exp_fw_freshweight_FK`) VALUES ('', '".$expID."', '".$lastFwId."');";
		//error_log($queryExpFw);
		$conn->exec($queryExpFw);

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
elseif ($action === 'delete') {
	
	$querySample ="DELETE FROM `sample` WHERE `spl_id` = ".$sampleID." ;";
	$queryAliquot="DELETE FROM `aliquot` WHERE `alq_id` = $aliquotID ;";
	// error_log($querySample);
	// error_log($queryAliquot);
	try {
		$conn->beginTransaction();
		//Suppresion sample & Aliquot
		$conn->exec($querySample);
		$conn->exec($queryAliquot);

		//suppression de l'association sample et aliquot dans sample_aliquot...
		$querySplAlq = "DELETE FROM `sample_aliquot` WHERE `spl_alq_sample_FK` = ".$sampleID." AND `spl_alq_aliquot_FK` = ".$aliquotID.";";
		//error_log($querySplAlq);
		$conn->exec($querySplAlq);

		//suppression de l'association du freshweight au sample dans freshweight_sample
		$queryFwSpl = "DELETE FROM `freshweight_sample` WHERE `fw_spl_freshweight_FK` = ".$fwID." AND `fw_spl_sample_FK` = ".$sampleID.";";
		//error_log($queryFwSpl);
		$conn->exec($queryFwSpl);


		//Suppression d'un freshweight
		$queryFw = "DELETE FROM `freshweight` WHERE  `fw_id` = ".$fwID.";";
		//error_log($queryFw);
		$conn->exec($queryFw);

		//Suppression de l'association du freshweight a l'experiment
		$queryExpFw = "DELETE FROM `experiment_freshweight` WHERE `exp_fw_experiment_FK` = ".$expID." AND `exp_fw_freshweight_FK` = ".$fwID.";";
		//error_log($queryExpFw);
		$conn->exec($queryExpFw);

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
$response_array['action']=$action;
echo json_encode($response_array);
?>