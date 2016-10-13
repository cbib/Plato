<?php
	require('../functions/check_login.php');
	header('Content-Type: text/xml'); 
	include '../functions/php_functions.php';
	$conn = get_connexion();

	$status="success";
	$message ="";
	$layout="";

	$rawdatas="";
	$batchID="";
	$expID="";
	$ezID="";

	if (isset($_POST['rawdatas'])) {
		$rawdatas = $_POST['rawdatas'];
	}
	else {
		$message.=" - missing rawdatas - ";
		$status="error";
	}

	if (isset($_POST['batchID'])) {
		$batchID = $_POST['batchID'];
	}
	else {
		$message.=" - missing batchID - ";
		$status="error";
	}

	if (isset($_POST['expID'])) {
		$expID = $_POST['expID'];
	}
	else {
		$message.=" - missing expID - ";
		$status="error";
	}

	if (isset($_POST['ezID'])) {
		$ezID = $_POST['ezID'];
	}
	else {
		$message.=" - missing ezID - ";
		$status="error";
	}

	if($status !="error"){
		$queryLayout="
		SELECT 
			batch.bat_layout
		FROM 
			batch
		WHERE 
			batch.bat_id = ".$batchID.";";

		try {
			$layout = $conn->query($queryLayout)->fetchColumn();
		}
		catch ( Exception $e ) {
			error_log("FAILURE est survenue lors de $queryLayout".$e->getMessage());
			$message.=" - missing layout - ";
			$status="error";
		}


		if($status != "error"){
			$pos = 0;
			$row = 0;
			$col = 0;
			if($layout == "FULL"){
				foreach ($rawdatas as $line) {
					$col = $pos%12;
					$row = floor($pos/12);
					$querySelectBatch_dataID="
					SELECT 
						batch_data.bat_data_id
					FROM 
						batch_data
					WHERE 
						batch_data.bat_data_batch_FK = ".$batchID." AND
						batch_data.bat_data_row =".$row." AND
						batch_data.bat_data_col = ".$col.";";
					try {
						$req = $conn->query($querySelectBatch_dataID)->fetchColumn();
					}
					catch ( Exception $e ) {
						error_log("FAILURE est survenue lors de $querySelectBatch_dataID".$e->getMessage());
						$req="";
						$message.=" - error in full select batch - ";
						$status="error";
					}
					$insertRawDataQuery = "
						INSERT INTO 
						`rawdata`(`rawdata_id`, `data_value`, `data_is_excluded`, `data_velocity`, `data_is_proved`, `data_enzyme_FK`, `rawdata_batch_data_FK`) 
						VALUES 
						('', '$line' , 0, 'NA', 'false', '$ezID', '$req');
						";
					$insertProcDataQuery = "INSERT INTO `processdata`(`pro_id`, `pro_value`) VALUES ('','');";
					try {
						$conn->beginTransaction();
						$conn->exec($insertRawDataQuery);
						$lastRawdataID = $conn->lastInsertId();
						$conn->exec($insertProcDataQuery);
						$lastProcdataID = $conn->lastInsertId();
						$insertRawEquProc = "INSERT 
						INTO `raw_equ_proc`(`id_raw_equ_proc`, `raw_equ_proc_rawdata_FK`, `raw_equ_proc_equation_FK`, `raw_equ_proc_processdata_FK`) 
						VALUES ('', ".$lastRawdataID.", 1, ".$lastProcdataID.");";
						$conn->exec($insertRawEquProc);
						$conn->commit();
					}
					catch ( Exception $e ) {
						$conn->rollback();
						error_log("Une erreur est survenue");
						error_log("Erreur : ".$e->getMessage());
						error_log("N° : ".$e->getCode());
						$message.=" - error in rawdata full insert - ";
						$status = "error";
					}
					$pos++;
				}
			}
			else {
				foreach ($rawdatas as $line) {
					$col = $pos%12;
					$row = floor($pos/12);
					if ($row >= 4){
						$demirow = $row - 4;
					}
					else {
						$demirow = $row; // Robert ?
					}
					$querySelectBatch_dataID="
					SELECT 
						batch_data.bat_data_id
					FROM 
						batch_data
					WHERE 
						batch_data.bat_data_batch_FK = ".$batchID." AND
						batch_data.bat_data_row =".$demirow." AND
						batch_data.bat_data_col = ".$col.";";
					try {
						$req = $conn->query($querySelectBatch_dataID)->fetchColumn();
					}
					catch ( Exception $e ) {
						error_log("FAILURE est survenue lors de $querySelectBatch_dataID".$e->getMessage());
						$req="";
						$message.=" - error in split select batch - ";
						$status="error";
					}

					if($row >= 4){
						$part="max";
					}
					else {
						$part="blank";
					}

					$insertRawDataQuery = "
					INSERT INTO 
					`rawdata`(`rawdata_id`, `data_value`, `data_is_excluded`, `data_velocity`, `data_is_proved`, `data_enzyme_FK`, `rawdata_batch_data_FK`) 
					VALUES 
					('', '$line' , 0, '$part', 'false', '$ezID', '$req');";

					$insertProcDataQuery = "INSERT INTO `processdata`(`pro_id`, `pro_value`) VALUES ('','');";

					try {
						$conn->beginTransaction();
							$conn->exec($insertRawDataQuery);
							$lastRawdataID = $conn->lastInsertId();
							$conn->exec($insertProcDataQuery);
							$lastProcdataID = $conn->lastInsertId();
							$insertRawEquProc = "INSERT 
							INTO `raw_equ_proc`(`id_raw_equ_proc`, `raw_equ_proc_rawdata_FK`, `raw_equ_proc_equation_FK`, `raw_equ_proc_processdata_FK`) 
							VALUES ('', ".$lastRawdataID.", 1, ".$lastProcdataID.");";
							$conn->exec($insertRawEquProc);
						$conn->commit();
					}
					catch ( Exception $e ) {
						$conn->rollback();
						error_log("Une erreur est survenue");
						error_log("Erreur : ".$e->getMessage());
						error_log("N° : ".$e->getCode());
						$message.=" - error in rawdata full insert - ";
						$status = "error";
					}
					$pos++;
				}
			}
		}
	}

$response_array['status']=$status;
$response_array['message']=$message;

echo json_encode($response_array);

?>