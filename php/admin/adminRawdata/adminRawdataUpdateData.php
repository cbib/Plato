<?php
	require('../../functions/check_login.php');
	include '../../functions/php_functions.php';
	header('Content-Type: application/json');
	$conn = get_connexion();

	// delete rawdata

	$expID=-1;
	$ezID=-1;
	$batchID=-1;
	$status = "success";


	if (isset($_POST['expID'])) {
		$expID = $_POST['expID'];
	}
	if (isset($_POST['ezID'])) {
		$ezID = $_POST['ezID'];
	}
	if (isset($_POST['batchID'])) {
		$batchID = $_POST['batchID'];
	}


	$queryBatchData ="SELECT 
		rawdata_batch_data_FK
	FROM
		rawdata, batch_data 
	WHERE
		data_enzyme_fk = ".$ezID." AND
		bat_data_batch_FK = ".$batchID." AND
		rawdata_batch_data_FK = bat_data_id;";;

		$sth2 = $conn->prepare($queryBatchData);
		$sth2->execute();
		$resultBatchData = $sth2->fetchAll();


	$query ="SELECT 
		rawdata_id 
	FROM
		rawdata, batch_data 
	WHERE
		data_enzyme_fk = ".$ezID." AND
		bat_data_batch_FK = ".$batchID." AND
		rawdata_batch_data_FK = bat_data_id;";

		$sth = $conn->prepare($query);
		$sth->execute();
		$result = $sth->fetchAll();
        $sth->closeCursor();



		$deleteQuery = "DELETE FROM rawdata WHERE rawdata_id IN (";
		//$deleteBatchDataQuery = "DELETE FROM batch_data WHERE bat_data_id IN (";

		foreach  ($result as $row) {
			$deleteQuery.=" ".$row['rawdata_id']." ,";
		}


//		foreach  ($resultBatchData as $row) {
//			$deleteBatchDataQuery.=" ".$row['rawdata_batch_data_FK']." ,";
//		}

		$deleteQuery = rtrim($deleteQuery, ",");
		$deleteQuery.=");";

//		$deleteBatchDataQuery = rtrim($deleteBatchDataQuery, ",");
//		$deleteBatchDataQuery.=");";
        error_log($deleteQuery);
//        error_log($deleteBatchDataQuery);
    try {
		$conn->beginTransaction();
			$conn->exec($deleteQuery);
//			$conn->exec($deleteBatchDataQuery);
			$status="success";
		$conn->commit();
	}
	catch ( Exception $e ) {
		error_log("ERRORDelete ".$e);
		$status="error";
		$conn->rollBack();
	}

$response_array['status']=$status;
$response_array['action']="delete";
echo json_encode($response_array);

?>