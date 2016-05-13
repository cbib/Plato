<?php
	require('../../functions/check_login_admin.php');
	include '../../functions/php_functions.php';
	$conn = get_connexion();

	$selectAnalyte="";
	$selectUnit="";
	$analyteValue="";
	$standardID="";
	$status="success";
	$dilutionValue="";

	if (isset($_POST['selectAnalyte'])) {
		$selectAnalyte = $_POST['selectAnalyte'];
	}

	if (isset($_POST['selectUnit'])) {
		$selectUnit = $_POST['selectUnit'];
	}

	if (isset($_POST['analyteValue'])) {
		$analyteValue = $_POST['analyteValue'];
	}

	if (isset($_POST['standardID'])) {
		$standardID = $_POST['standardID'];
	}

	if (isset($_POST['dilutionValue'])) {
		$dilutionValue = $_POST['dilutionValue'];
	}

	$query = "
	INSERT INTO 
	`standard_enzyme`
	(`std_ez_id`, `std_ez_unit_FK`, `std_ez_standard_FK`, `std_ez_value`, `std_ez_enzyme_FK`, `std_ez_dilution`) 
	VALUES 
	('', '$selectUnit', '$standardID', '$analyteValue', '$selectAnalyte', '$dilutionValue');
	";

	try 
	{
		$conn->beginTransaction();
		$req = $conn->exec($query);
		$conn->commit();
	}
	catch ( Exception $e ) {
		$conn->rollBack();
		$status="error";
	}


$response_array['status']=$status;
echo json_encode($response_array);


		// $sth=$conn->prepare($query);
		// $sth -> setFetchMode(PDO::FETCH_ASSOC);
		// $sth->execute();
?>