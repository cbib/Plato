<?php
	require('../functions/check_login.php');
	include '../functions/php_functions.php';
	$conn = get_connexion();
	$batchID = $_POST['batchID'];

	$query ='
	SELECT
	bat_layout
	FROM 
	batch
	WHERE 
	batch.bat_id = '.$batchID.';';
		
	$req = $conn->query($query)->fetchColumn();
	$output= $req;

	header('Content-Type: application/json');
	echo json_encode($output);
?>

