<?php
	require('../../functions/check_login.php');
	include '../../functions/php_functions.php';
	header('Content-Type: application/json');
	$conn = get_connexion();

	$batchID = -1;
	$status = "success";

	if (isset($_POST['batchID'])) {
		$batchID = $_POST['batchID'];
	}


	$query ="SELECT 
		bat_data_id 
	FROM
		batch_data 
	WHERE
		bat_data_batch_FK = ".$batchID.";";

		$sth = $conn->prepare($query);
		$sth->execute();
		$result = $sth->fetchAll();

	$querySample ="SELECT 
		bat_data_sample_aliquot_FK 
	FROM
		batch_data 
	WHERE
		bat_data_batch_FK = ".$batchID.";";

		$sth2 = $conn->prepare($querySample);
		$sth2->execute();
		$resultSample = $sth2->fetchAll();

        $deleteBatch = "DELETE FROM batch where bat_id = ".$batchID.";";
        $deleteQuery = "DELETE FROM batch_data WHERE bat_data_id IN (";
        $deleteRawDataQuery = "DELETE FROM rawdata WHERE rawdata_batch_data_FK IN (";
        foreach  ($result as $row) {
            $deleteQuery.=" ".$row['bat_data_id']." ,";
            $deleteRawDataQuery.=" ".$row['bat_data_id']." ,";
        }

        $updateStateAliquotQuery = "UPDATE sample_aliquot SET spl_alq_state = 'free' WHERE spl_alq_id IN (";
        foreach  ($resultSample as $row) {
            $updateStateAliquotQuery.=" ".$row['bat_data_sample_aliquot_FK']." ,";
        }

        $deleteQuery = rtrim($deleteQuery, ",");
        $deleteQuery.=");";

        $deleteRawDataQuery = rtrim($deleteRawDataQuery, ",");
        $deleteRawDataQuery.=");";

        $updateStateAliquotQuery = rtrim($updateStateAliquotQuery, ",");
        $updateStateAliquotQuery.=");";
        // error_log($deleteQuery);
        // error_log($deleteRawDataQuery);
        // error_log($updateStateAliquotQuery);
        // error_log($deleteBatch);

	try {
		$conn->beginTransaction();
			$conn->exec($deleteQuery);
			$conn->exec($deleteRawDataQuery);
			$conn->exec($updateStateAliquotQuery);
			$conn->exec($deleteBatch);
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