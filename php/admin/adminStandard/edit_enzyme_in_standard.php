<?php
	require('../../functions/check_login_admin.php');
	include '../../functions/php_functions.php';
	$conn = get_connexion();

	$stdEzID="";
	$slope="";
	$amount="";
	$standardID="";
	$status="success";
	$unit = "";
	$dilutionValue="";

	if (isset($_POST['stdezid'])) {
		$stdEzID = $_POST['stdezid'];
	}
	if (isset($_POST['amount'])) {
		$amount = $_POST['amount'];
	}
	if (isset($_POST['unit'])) {
		$unit = $_POST['unit'];
	}
	if (isset($_POST['dilutionValue'])) {
		$dilutionValue = $_POST['dilutionValue'];
	}
    if (isset($_POST['nature'])) {
        $unit = $_POST['nature'];
    }

	$query = "UPDATE `standard_enzyme` SET `std_ez_value`='$amount', `std_ez_unit_FK` ='$unit', `std_ez_dilution` ='$dilutionValue' WHERE `std_ez_id`= '$stdEzID';";
error_log($query);
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