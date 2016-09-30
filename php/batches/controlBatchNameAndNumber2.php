<?php
	require('../functions/check_login.php');
	header('Content-Type: text/xml'); 
	include '../functions/php_functions.php';
	$conn = get_connexion();
	$output="";

	$status = "success";
	$value =0;


	$batchName =$_POST["batchName"];
	$batchNumber =$_POST["batchNumber"];

	$query = "
	SELECT 
		bat_id
	FROM
		batch
	WHERE
		bat_name regexp '^$batchName$' AND
		bat_number = $batchNumber
	;";

	error_log($query);
	$req = $conn->query($query)->fetchColumn();

$response_array['status']=$status;
$response_array['value']=$req;
echo json_encode($response_array);

?>