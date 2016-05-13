<?php
	require('../functions/check_login.php');
	include '../functions/php_functions.php';
	$conn = get_connexion();
	$status="success";
	$boolSucces=true;
	$action="Update";

	$procdatas = $_POST['procdatas'];
	$excludMap = $_POST['excludMap'];
	$rawIDMap = $_POST['rawIDMap'];
	$batchID = $_POST['batchID'];
	$ezID = $_POST['ezID'];


	$colIdx = 0;
	$rowIdx = 0;
	$rawdataID = -1;

	foreach ($procdatas as $row) {
		$colIdx=0;
		foreach ($row as $col) {
			$querySelectBatch_dataID="
			SELECT 
				batch_data.bat_data_id
			FROM 
				batch, batch_data
			WHERE 
				batch.bat_id = ".$batchID." AND
				batch_data.bat_data_batch_FK = batch.bat_id AND
				batch_data.bat_data_row =".$rowIdx." AND
				batch_data.bat_data_col = ".$colIdx.";";
			try {
				$batchDataID = $conn->query($querySelectBatch_dataID)->fetchColumn();
			}
			catch ( Exception $e ) {
				error_log("FAILURE est survenue lors de $querySelectBatch_dataID".$e->getMessage());
				$batchDataID="";
				$boolSucces=false;
				$status="error";
				$action="select batch_data id";
			}

			if($boolSucces == true){
				$queryRawdataID ="
				SELECT 
					rawdata.rawdata_id 
				FROM 
					rawdata 
				WHERE 
					rawdata.rawdata_batch_data_FK = ".$batchDataID." AND
					rawdata.data_enzyme_FK = ".$ezID.";";
				try {
					$rawdataID = $conn->query($queryRawdataID)->fetchColumn();
				}
				catch ( Exception $e ) {
					error_log("FAILURE est survenue lors de $queryRawdataID".$e->getMessage());
					$rawdataID="";
					$boolSucces=false;
					$status="error";
					$action="select rawdata id";
				}
			}
			if($boolSucces == true){
				$queryProcessDataExist ="
				SELECT 
					raw_equ_proc.id_raw_equ_proc
				FROM 
					raw_equ_proc 
				WHERE 
					raw_equ_proc.raw_equ_proc_rawdata_FK = ".$rawdataID.";";

				try {
					$procDataExists = $conn->query($queryProcessDataExist)->fetchColumn();
				}
				catch ( Exception $e ) {
					error_log("FAILURE est survenue lors de $queryProcessDataExist".$e->getMessage());
					$procDataExists="";
					$boolSucces=false;
					$status="error";
					$action="select rawdata id";
				}
			}

			if($boolSucces == true){

				if ($procdatas[$rowIdx][$colIdx] =="NA"){
					$valueToUpdate = "";
				}
				else {
					$valueToUpdate = $procdatas[$rowIdx][$colIdx];
				}

				$queryUpdateExclud =" UPDATE  `rawdata` SET `data_is_excluded`=".$excludMap[$rowIdx][$colIdx]." WHERE `rawdata_id` = ".$rawdataID.";";
				try {
					$conn->beginTransaction();
					$conn->exec($queryUpdateExclud);
					$conn->commit();
				}
				catch ( Exception $e ) {
					$conn->rollback();
					error_log("Une erreur est survenue lors de edition");
					error_log("Erreur : ".$e->getMessage()."");
					error_log("N° : ".$e->getCode());
					$status="error";
					$boolSucces=false;
					$action="update exclud status";
				}

				if($procDataExists != "") {

					$queryUpdateProcData = "
					UPDATE 
						processdata, raw_equ_proc 
					SET 
						processdata.pro_value = '".$valueToUpdate."' 
					WHERE 
						raw_equ_proc.raw_equ_proc_rawdata_FK = ".$rawdataID." AND 
						processdata.pro_id = raw_equ_proc.raw_equ_proc_processdata_FK;";
					try {
						$conn->beginTransaction();
						$conn->exec($queryUpdateProcData);
						$conn->commit();
					}
					catch ( Exception $e ) {
						$conn->rollback();
						error_log("Une erreur est survenue lors de edition");
						error_log("Erreur : ".$e->getMessage()."");
						error_log("N° : ".$e->getCode());
						$status="error";
						$boolSucces=false;
						$action="update process data";
					}
				}
				else{
					$insertProcDataQuery = "INSERT INTO `processdata`(`pro_id`, `pro_value`) VALUES ('',".$valueToUpdate.");";
					try {
						$conn->beginTransaction();
						$conn->exec($insertProcDataQuery);
						$lastProcInsertID = $conn->lastInsertId();
						$insertRawEquProc = "INSERT 
						INTO `raw_equ_proc`(`id_raw_equ_proc`, `raw_equ_proc_rawdata_FK`, `raw_equ_proc_equation_FK`, `raw_equ_proc_processdata_FK`) 
						VALUES ('', ".$rawdataID.", 1, ".$lastProcInsertID.");";
						$conn->exec($insertRawEquProc);
						$conn->commit();
					}
					catch ( Exception $e ) {
						$conn->rollback();
						error_log("Une erreur est survenue lors de".$insertProcDataQuery);
						error_log("Erreur : ".$e->getMessage());
						error_log("N° : ".$e->getCode());
						$statut="error";
					}
				}
			}
			$colIdx++;
		}
		$rowIdx++;
	}

$response_array['status']=$status;
$response_array['action']=$action;
echo json_encode($response_array);



?>