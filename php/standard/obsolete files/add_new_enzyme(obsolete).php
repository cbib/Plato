<?php
	require('../functions/check_login.php');
	include '../functions/php_functions.php';
	$conn = get_connexion();

	$ezName="";
	$ezSlope="";
	$ezCode="";
	$ezType="";

	if (isset($_POST['ezName'])) {
		$ezName = $_POST['ezName'];
	}

	if (isset($_POST['ezSlope'])) {
		$ezSlope = $_POST['ezSlope'];
	}

	if (isset($_POST['ezCode'])) {
		$ezCode = $_POST['ezCode'];
	}

	if (isset($_POST['ezType'])) {
		$ezType = $_POST['ezType'];
	}

	error_log($ezName);
	error_log($ezSlope);
	error_log($ezCode);
	error_log($ezType);

	// 0 si metabolite
	$ezBool = ($ezType == 'metabolite') ? 1 : 0 ;

	$query = "
	INSERT INTO `enzyme`(`ez_id`, `ez_analyte`, `ez_slope`, `ez_code`, `ez_is_enzyme`) VALUES ('', '$ezName', '$ezSlope', '$ezCode', '$ezBool');";

	//error_log($query);
		$sth=$conn->prepare($query);
		$sth -> setFetchMode(PDO::FETCH_ASSOC);
		$sth->execute();
?>