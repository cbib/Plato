<?php
	require('../functions/check_login.php');
	include '../functions/php_functions.php';
	$conn = get_connexion();
	$output="error";

if (isset($_POST['ezID'])){
	$ezID = $_POST['ezID'];
	$name = $_POST['minname']; 

	$query = "
	SELECT 
		standard_enzyme.std_ez_value 
	FROM
		standard_enzyme, experiment_standard, experiment
	WHERE
		experiment.exp_name like '%".$name."%' AND
		experiment_standard.exp_std_experiment_FK = experiment.exp_id AND
		standard_enzyme.std_ez_standard_FK = experiment_standard.exp_std_standard_FK AND
		standard_enzyme.std_ez_enzyme_FK = ".$ezID.";";

	try {
		$req = $conn->query($query)->fetchColumn();
		$output=$req;
	}
	catch ( Exception $e ) {
		$req="";
	}
}

	header('Content-Type: application/json');
	echo json_encode($output);
?>
