<?php
	require('../../functions/check_login_admin.php');
	include '../../functions/php_functions.php';
	header('Content-Type: application/json');
	$conn = get_connexion();

	$stdId="";
	$stdName ="";
	//$expNameLinkToStd="";
	$stdGenius ="";
	$stdSpecies ="";
	$stdGenotype ="";
	$stdNature ="";
	$stdOwner ="";
	$stdComment ="";
	$action="";
	$status="success";

	if (isset($_POST['id'])) {
		$stdId = $_POST['id'];
	}
	if (isset($_POST['stdName'])) {
		$stdName = $_POST['stdName'];
		//$expNameLinkToStd = $stdName."_exp";
	}
	if (isset($_POST['stdGenius'])) {
		$stdGenius = $_POST['stdGenius'];
	}
	if (isset($_POST['stdSpecies'])) {
		$stdSpecies = $_POST['stdSpecies'];
	}
	if (isset($_POST['stdGenotype'])) {
		$stdGenotype = $_POST['stdGenotype'];
	}
	if (isset($_POST['stdNature'])) {
		$stdNature = $_POST['stdNature'];
	}
	if (isset($_POST['stdOwner'])) {
		$stdOwner = $_POST['stdOwner'];
	}
	if (isset($_POST['stdComment'])) {
		$stdComment = $_POST['stdComment'];
	}
	if (isset($_POST['action'])) {
		$action = $_POST['action'];
	}

	if($action=="create"){

		$queryStd = "INSERT INTO `standard`(`std_id`, `std_name`, `std_genius`, `std_species`, `std_genotype`, `std_nature`, `std_owner`, `std_comment`) 
		VALUES ('', '$stdName', '$stdGenius', '$stdSpecies', '$stdGenotype', '$stdNature', '$stdOwner', '$stdComment');";
		$queryExp = "INSERT INTO `experiment`(`exp_id`, `exp_name`) VALUES ('','$stdName');";


		try {
			$conn->beginTransaction();

			$conn->exec($queryStd);
			$lastStdId = $conn->lastInsertId();
			$conn->exec($queryExp);

			$lastExpID= $conn->lastInsertId();

			$queryExpStd = "INSERT INTO `experiment_standard`(`exp_std_id`, `exp_std_standard_FK`, `exp_std_experiment_FK`) VALUES ('','$lastStdId','$lastExpID');";
			$conn->exec($queryExpStd);
			
			$conn->commit();
			$status="success";
		}
		catch ( Exception $e ) {
			error_log("ERRORCreate ".$e);
			$conn->rollBack();
			$status="error";
		}
	}
	elseif ($action=="edit") {
		$query ="UPDATE `standard` 
		SET`std_name`='$stdName',`std_genius`='$stdGenius',`std_species`='$stdSpecies',`std_genotype`='$stdGenotype',`std_nature`='$stdNature', `std_owner`='$stdOwner', `std_comment`='$stdComment'
		WHERE `std_id`='$stdId';";
		try {
			$conn->beginTransaction();
			$conn->exec($query);
			$conn->commit();
			$status="success";
		}
		catch ( Exception $e ) {
			$conn->rollBack();
			error_log("ERROREdit ".$e);
			$status="error";
		}
	}
	elseif ($action=="delete") {
		$query ="DELETE FROM `standard` WHERE `std_id` = '$stdId';";
		try {
			$conn->beginTransaction();
			$req = $conn->exec($query);
			$conn->commit();
			$status="success";
		}
		catch ( Exception $e ) {
			error_log("ERRORDelete ".$e);
			$status="error";
			$conn->rollBack();
		}
	}
	else {
		$status="error";
		$action = "Unknown";
	} 

$response_array['status']=$status;
$response_array['action']=$action;
echo json_encode($response_array);

?>