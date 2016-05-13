<?php
	require('../../functions/check_login.php');
	include '../../functions/php_functions.php';
	header('Content-Type: application/json');
	$conn = get_connexion();

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

	try {
		$deleteQuery = "DELETE FROM rawdata WHERE rawdata_id IN (";
		foreach  ($result as $row) {
			$deleteQuery.=" ".$row['rawdata_id']." ,";
		}
		$deleteQuery = rtrim($deleteQuery, ",");
		$deleteQuery.=");";

		$conn->beginTransaction();		
			error_log($deleteQuery);
			$conn->query($deleteQuery);
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