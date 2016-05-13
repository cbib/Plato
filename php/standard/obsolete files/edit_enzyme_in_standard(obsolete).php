<?php
	require('../functions/check_login.php');
	include '../functions/php_functions.php';
	$conn = get_connexion();

	$stdEzID="";
	$slope="";
	$amount="";
	$standardID="";
	$status="success";

	if (isset($_POST['stdezid'])) {
		$stdEzID = $_POST['stdezid'];
	}
	// if (isset($_POST['slope'])) {
	// 	$slope = $_POST['slope'];
	// }
	// if (isset($_POST['nature'])) {
	// 	$nature = $_POST['nature'];
	// }
	// if (isset($_POST['standardID'])) {
	// 	$standardID = $_POST['standardID'];
	// }
	if (isset($_POST['amount'])) {
		$amount = $_POST['amount'];
	}

	$query = 'UPDATE `standard_enzyme` SET `std_ez_value`='.$amount.' WHERE `std_ez_id`= '.$stdEzID.';';

	//error_log($query);
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