<?php
	require('../functions/check_login.php');
	header('Content-Type: text/xml'); 
	include '../functions/php_functions.php';
	$conn = get_connexion();
	$output="";

	$status = "success";
	$action ="";

	$batchID="";
	$rawdatas = "";
	$batchName ="";
	$batchNumber = "";
	$batchLayout = "";
	$batchDate = date("Y/m/d");

	//check if there are rawdatas
	if(isset($_POST['rawdatas'])){
		$rawdatas = $_POST['rawdatas'];
	} 
	else{
		error_log("WRONG rawdata");
		$status="error";
	}

	// Check if there is a batchID provided (edition) or not (creation)
	if(isset($_POST['batchID'])){
		$batchID = $_POST['batchID'];
		$action ="edition";
		//error_log("EDITION");
	} 
	else{ //creation
		error_log("CREATION");
		//check if batchname is provided
		if(isset($_POST['batchName'])){
			$batchName = $_POST['batchName'];
		} 
		else{
			$status="error";
			error_log("WRONG batchname");
		}

		//check if layout is provided
		if(isset($_POST['batchLayout'])){
			$batchLayout = $_POST['batchLayout'];
		} 
		else{
			$status="error";
			error_log("WRONG layout");
		}

		//check if a batchNumber is provided
		if(isset($_POST['batchNumber'])){
			$batchNumber = $_POST['batchNumber'];
		} 
		else{
			$status="error";
			error_log("WRONG batchNumber");
		}
		$batchID = insert_batch_in_db($conn, $batchLayout, $batchName, $batchNumber, $batchDate);
		$action = "creation";
	}

	$pos_exist = -1; 
	$spl_alq_id = -1;
	$expID = "";

	$pos = 0;
	$row = 0;
	$col = 0;

	if ($status != "error"){
		$stateUpdateQuery="UPDATE sample_aliquot SET spl_alq_state = 'in use' WHERE spl_alq_id IN (";
		$insertBatchDataQuery = "
		INSERT INTO 
		`batch_data`(`bat_data_id`, `bat_data_batch_FK`, `bat_data_experiment_FK`, `bat_data_sample_aliquot_FK`, `bat_data_row`, `bat_data_col`) 
		VALUES ";
		foreach ($rawdatas as $line) {
			if ((preg_match("/^EB$/", $line)) || (preg_match("/^\?$/", $line))) {
				$expID = get_experiment_id_from_name($line, $conn);
				$spl_alq_id = 1;
				//error_log("EB : ".$line);
			}
			elseif (empty($line)){
				$expID = "1";
			}
			else {
				//error_log("OTHERS : ".$line);
				$pos_exist = batch_pos_exist_in_db($line, $conn);
				$spl_alq_id = get_sample_aliquot_id($line, $conn);
				$expID = get_experiment_id_from_name($line, $conn);
				//error_log("EXPID = ".$expID);
			}
			//if ($pos_exist!=0) { // if the sample and aliquot not exists
			
			if ($spl_alq_id == -1) {
				//error_log("No sample aliquot ID : ".$line);
				$status="error";
			}
			else {
				if ($expID==""){
					//error_log("No Experiment ID found : ".$line);
					$status="error";
				}
				else {
					
					$col = $pos%12;
					$row = floor($pos/12);
					
					if (($batchID != -1) || ($spl_alq_id != '')){
						$stateUpdateQuery .= "$spl_alq_id ,";
						$insertBatchDataQuery .="
						('','$batchID','$expID','$spl_alq_id','$row','$col'),";
					}
					else {
						error_log("failure on insert batch_data");
						$output.="Error on insert batch_data ";
					}
				}
			}
			// else{
			 	error_log("No sample aliquot ID : ".$line);
			// 	$status="error";
			// }
			$pos++;
		}
		$insertBatchDataQuery=rtrim($insertBatchDataQuery, ",");
		$insertBatchDataQuery .=";";

		$stateUpdateQuery=rtrim($stateUpdateQuery, ",");
		$stateUpdateQuery .=");";

		$status = lance_requete($insertBatchDataQuery, $conn);
		$status = lance_requete($stateUpdateQuery, $conn);
	}
	else{
		error_log("ERROR : ".$status);
	}

$response_array['status']=$status;
$response_array['action']=$action;
echo json_encode($response_array);
?>

<?php
function insert_batch_in_db($conn, $batchLayout, $batchName, $batchNumber ,$batchDate){
	$lastBatchInsertID="";
	$insertBatchQuery = "
	INSERT INTO 
	`batch`(`bat_id`, `bat_name`, `bat_number`, `bat_date`, `bat_layout`) 
	VALUES 
	('','$batchName','$batchNumber','$batchDate','$batchLayout');
	";
	try {
		$conn->beginTransaction();
		$conn->exec($insertBatchQuery);
		$lastBatchInsertID = $conn->lastInsertId();
		$conn->commit();
	}
	catch ( Exception $e ) {
		$conn->rollback();
		error_log("Une erreur est survenue lors de".$insertBatchQuery);
		error_log("Erreur : ".$e->getMessage());
		error_log("N° : ".$e->getCode());
		$statut="error";
	}
	return $lastBatchInsertID;
}

function get_experiment_id_from_name($line, $conn){
	$info = explode("-", $line);
	if ($info[0]== "?"){
		$selectIdQuery = "
		SELECT 
			exp_id
		FROM 
			`experiment`
		WHERE 
			`exp_name` LIKE '%?%';
		";
	}
	else {
		$selectIdQuery = "
		SELECT 
			exp_id
		FROM 
			`experiment`
		WHERE 
			`exp_name` REGEXP '^".$info[0]."$';
		";
	}
	//error_log($selectIdQuery);
	$req = $conn->query($selectIdQuery)->fetchColumn();
	//error_log("EXP ID  : ".$req);
	return $req;
}

function lance_requete($query, $conn){
	$status="success";
	try {
		$conn->beginTransaction();
		$conn->exec($query);
		$conn->commit();
	}
	catch ( Exception $e ) {
		$conn->rollback();
		error_log("Une erreur est survenue lors de ".$query);
		error_log("Erreur : ".$e->getMessage()."<br/>");
		error_log("N° : ".$e->getCode());
		$status="error";
	}
	return $status;
}


function get_sample_aliquot_id ($line, $conn){
	$infos= explode("-",$line);
	$query = "
	SELECT 
	sample_aliquot.spl_alq_id 
	FROM
	freshweight, freshweight_sample, sample, sample_aliquot, aliquot 
	WHERE 
	freshweight.fw_name like '%".$infos[0]."%' AND 
	freshweight_sample.fw_spl_freshweight_FK = freshweight.fw_id AND
	sample.spl_id = freshweight_sample.fw_spl_sample_FK AND
	sample_aliquot.spl_alq_sample_FK =sample.spl_id AND
	sample.spl_number = ".$infos[1]." AND 
	aliquot.alq_id = sample_aliquot.spl_alq_aliquot_FK AND
	aliquot.alq_number = ".$infos[2].";";

	try 
	{
		$req = $conn->query($query)->fetchColumn();
		return $req;
	}
	catch ( Exception $e ) {
		error_log("failure est survenue lors de $query".$e->getMessage());
		$output.="Error on get sample and aliquot id ";
	}
}

function batch_pos_exist_in_db ($line, $conn){
	$infos= explode("-",$line);

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
	aliquot.alq_number = ".$infos[2].";";

	try 
	{
		$req = $conn->query($query)->fetchColumn();
		return $req;
	}
	catch ( Exception $e ) {
		error_log("failure est survenue lors de $query".$e->getMessage());
		$output.="Error on finding batch position ";
	}
}
?>