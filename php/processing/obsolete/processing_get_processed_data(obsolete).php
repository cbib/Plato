<?php
	require('../functions/check_login.php');
	include '../functions/php_functions.php';
	$conn = get_connexion();

	$rawDataID = $_POST['rawDataID'];

	$query ='
	SELECT 
		processdata.pro_value 
	FROM
		raw_equ_proc, processdata 
	WHERE 
		raw_equ_proc.raw_equ_proc_rawdata_FK = "'.$rawDataID.'" AND
		processdata.pro_id = raw_equ_proc.raw_equ_proc_processdata_FK;';

	$req = $conn->query($query)->fetchColumn();

	header('Content-Type: application/json');
	echo json_encode($req);
?>

