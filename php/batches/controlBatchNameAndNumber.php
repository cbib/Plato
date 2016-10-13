<?php
	require('../functions/check_login.php');
	header('Content-Type: text/xml'); 
	include '../functions/php_functions.php';
	$conn = get_connexion();
	$output="";

	$status = "success";
	$value =0;


	$batchName =$_POST["batchName"];


	$query = "
	SELECT 
		MAX(bat_number)
	FROM
		batch
	WHERE
		bat_name regexp '^$batchName$'
	;";

//	error_log($query);
	$req = $conn->query($query)->fetchColumn();

$response_array['status']=$status;
$response_array['value']=$req;
echo json_encode($response_array);
?>