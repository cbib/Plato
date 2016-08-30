<?php
	require('../functions/check_login.php');
	include '../functions/php_functions.php';
	$conn = get_connexion();
	$status="success";
	$boolSucces=true;
	$action="Update";

	$processDataMap = $_POST['procdatas'];
	$excludMap = $_POST['excludMap'];
	$rawIDMap = $_POST['rawIDMap'];
	$batchID = $_POST['batchID'];
	$ezID = $_POST['ezID'];
	$colIdx = 0;
	$rowIdx = 0;

	foreach ($excludMap as $row) {
		$colIdx=0;
		foreach ($row as $col) {
			$queryUpdateExclud =" UPDATE  `rawdata` SET `data_is_excluded`=".$excludMap[$rowIdx][$colIdx]." WHERE `rawdata_id` = ".$rawIDMap[$rowIdx][$colIdx].";";
			// error_log($queryUpdateExclud);
			try {
				$conn->beginTransaction();
				$conn->exec($queryUpdateExclud);
				$conn->commit();
			}
			catch ( Exception $e ) {
				$conn->rollback();
				error_log("Une erreur est survenue lors de update exlude map");
				error_log("Erreur : ".$e->getMessage()."");
				error_log("N° : ".$e->getCode());
				$status="error";
				$boolSucces=false;
				$action="update exclud status";
			}
			$colIdx++;
		}
		$rowIdx++;
	}

	$colIdx = 0;
	$rowIdx = 0;

	foreach ($processDataMap as $row) {
		$colIdx=0;
		foreach ($row as $col) {
			if ($processDataMap[$rowIdx][$colIdx] =="NA"){
				$valueToUpdate = "";
			}
			else {
				$valueToUpdate = $processDataMap[$rowIdx][$colIdx];
			}

			$queryUpdateProcData="
			UPDATE 
				processdata p
			JOIN 
				raw_equ_proc r ON (p.pro_id = r.raw_equ_proc_processdata_FK) 
			SET 
				p.pro_value = '".$valueToUpdate."'
			WHERE 
				r.raw_equ_proc_rawdata_FK = ".$rawIDMap[$rowIdx][$colIdx].";";

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
			$colIdx++;
		}
		$rowIdx++;
	}

$response_array['status']=$status;
$response_array['action']=$action;
echo json_encode($response_array);
?>