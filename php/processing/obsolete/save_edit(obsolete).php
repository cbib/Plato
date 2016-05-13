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
			$rawID = $rawIDMap[$rowIdx][$colIdx];

			$queryProcessDataExist ="
			SELECT 
				raw_equ_proc.id_raw_equ_proc
			FROM 
				raw_equ_proc 
			WHERE 
				raw_equ_proc.raw_equ_proc_rawdata_FK = ".$rawID.";";

			error_log($queryProcessDataExist);
			try {
				$procDataExists = $conn->query($queryProcessDataExist)->fetchColumn();
			}
			catch ( Exception $e ) {
				error_log("FAILURE est survenue lors de $queryProcessDataExist".$e->getMessage());
				$boolSucces=false;
				$status="error";
				$action="select rawdata id";
			}

			error_log("rawdata id : ".$rawID);
			$queryUpdateExclud =" UPDATE  `rawdata` SET `data_is_excluded`=".$excludMap[$rowIdx][$colIdx]." WHERE `rawdata_id` = ".$rawID.";";

			if ($procdatas[$rowIdx][$colIdx] =="NA"){
				$valueToUpdate = "";
			}
			else {
				$valueToUpdate = $procdatas[$rowIdx][$colIdx];
			}
			error_log("VALUE TO UPDATE : ".$procdatas[$rowIdx][$colIdx]);


			if($procDataExists != "") {
				error_log("PROC DATA EXISTS : ".$procDataExists);
				$queryUpdateProcData = "
				UPDATE 
					processdata, raw_equ_proc 
				SET 
					processdata.pro_value = '".$valueToUpdate."' 
				WHERE 
					raw_equ_proc.raw_equ_proc_rawdata_FK = ".$rawID." AND 
					processdata.pro_id = raw_equ_proc.raw_equ_proc_processdata_FK;";
				error_log($rawID);
				error_log($queryUpdateExclud);
				try {
					$conn->beginTransaction();
					$conn->exec($queryUpdateExclud);
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
				error_log("PROC DATA NON EXISTS : ".$procDataExists);
				$insertProcDataQuery = "INSERT INTO `processdata`(`pro_id`, `pro_value`) VALUES ('','".$valueToUpdate."');";
				error_log("INSERT PROCESS DATA : ".$insertProcDataQuery);
				try {
					$conn->beginTransaction();
					$conn->exec($queryUpdateExclud);
					$conn->exec($insertProcDataQuery);
					$lastProcInsertID = $conn->lastInsertId();

					$insertRawEquProc = "INSERT 
					INTO `raw_equ_proc`(`id_raw_equ_proc`, `raw_equ_proc_rawdata_FK`, `raw_equ_proc_equation_FK`, `raw_equ_proc_processdata_FK`) 
					VALUES ('', ".$rawID.", 1, ".$lastProcInsertID.");";

					error_log("INSERT RAWEQUPROC : ".$insertRawEquProc);
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
			$colIdx++;
		}
		$rowIdx++;
	}


$response_array['status']=$status;
$response_array['action']=$action;
echo json_encode($response_array);



?>