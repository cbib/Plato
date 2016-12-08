<?php
	require('../../functions/check_login_admin.php');
	include '../../functions/php_functions.php';
	header('Content-Type: application/json');
	$conn = get_connexion();
	//update informations about an analyte, create a new one, or delete an existing one. The action to do is in $action

	$ezId="";
	$ezName="";
	$ezSlope="";
	$ezCode="";
	$ezType="";
	$action ="";
	$status="";

	if (isset($_POST['id'])) {
		$ezId = $_POST['id'];
	}
	if (isset($_POST['analyte'])) {
		$ezName = $_POST['analyte'];
	}
	if (isset($_POST['slope'])) {
		$ezSlope = $_POST['slope'];
	}
	if (isset($_POST['code'])) {
		$ezCode = $_POST['code'];
	}
	if (isset($_POST['nature'])) {
		$ezType = $_POST['nature'];
	}
	if (isset($_POST['action'])) {
		$action = $_POST['action'];
	}

	// 0 si metabolite

	if ($ezType == 'Metabolite') {
		$ezBool =0;
	}
	elseif ($ezType == 'Enzyme') {
		$ezBool=1;
	}
	elseif ($ezType== 'Other') {
		$ezBool=2;
	}

	if($action=="create"){
		$query = "
		INSERT INTO `enzyme`(`ez_id`, `ez_analyte`, `ez_slope`, `ez_code`, `ez_is_enzyme`) VALUES ('', '$ezName', '$ezSlope', '$ezCode', '$ezBool');";
		try {
			$sth=$conn->prepare($query);
			$sth -> setFetchMode(PDO::FETCH_ASSOC);
			$sth->execute();
			$status="success";
		}
		catch ( Exception $e ) {
			error_log("ERRORCreate ".$e);
			$status="error";
		}
	}
	elseif ($action=="edit") {
		$query ="UPDATE `enzyme` SET`ez_analyte`='$ezName',`ez_slope`='$ezSlope',`ez_code`='$ezCode',`ez_is_enzyme`='$ezBool' WHERE `ez_id`='$ezId';";
		try {
			$entree = $conn->exec($query);
			$status="success";
		}
		catch ( Exception $e ) {
			error_log("ERROREdit ".$e);
			$status="error";
		}
	}
	elseif ($action=="delete") {
		$query ="DELETE FROM `enzyme` WHERE `ez_id` = '$ezId';";
		try {
			$entree = $conn->exec($query);
			$status="success";
		}
		catch ( Exception $e ) {
			error_log("ERRORDelete ".$e);
			$status="error";
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