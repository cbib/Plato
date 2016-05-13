<?php
	require('../functions/check_login.php');
	include '../functions/php_functions.php';
	$conn = get_connexion();

	$stdEzIdToRemove="";
	$status="success";

	if (isset($_POST['stdEzIdToRemove'])) {
		$stdEzIdToRemove = $_POST['stdEzIdToRemove'];
	}


	$query = 'DELETE FROM `standard_enzyme` WHERE  `std_ez_id`= '.$stdEzIdToRemove.';';

	try 
	{
		$conn->beginTransaction();
		$req = $conn->exec($query);
		$conn->commit();
	}
	catch ( Exception $e ) {
		$conn->rollBack();
		$status="error";
		error_log($e);
	}

$response_array['status']=$status;
echo json_encode($response_array);
?>