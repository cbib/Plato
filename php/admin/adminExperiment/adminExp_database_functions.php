<?php
	require('../../functions/check_login.php');
	include '../../functions/php_functions.php';
	#COUNT(DISTINCT batch_data.bat_data_id) AS nb_batch 
	$conn = get_connexion();
	// $query = 'SELECT 
	// 	experiment.exp_id, 
	// 	experiment.exp_name, 
	// 	standard.std_name,
	// 	COUNT(DISTINCT experiment_freshweight.exp_fw_id) AS nb_fw
	// FROM 
	// 	experiment, 
	// 	experiment_standard,
	// 	standard,
	// 	experiment_freshweight
	// WHERE
	// 	experiment.exp_id = experiment_standard.exp_std_experiment_FK AND
	// 	standard.std_id = experiment_standard.exp_std_standard_FK AND
	// 	experiment_freshweight.exp_fw_experiment_FK = experiment.exp_id
	// ORDER BY 
	// 	exp_name';


	$query ='SELECT e.*, s.std_name 
	FROM experiment e
	LEFT JOIN experiment_standard es
	ON e.exp_id = es.exp_std_experiment_FK
	LEFT JOIN standard s 
	ON s.std_id = es.exp_std_standard_FK;';

	$sth=$conn->prepare($query);
	$sth -> setFetchMode(PDO::FETCH_ASSOC);
	$sth->execute();
	$count = $sth->rowCount();
	$rows=$sth->fetchAll();

	$output = array(
		"iTotalRecords" => $count,
		"iTotalDisplayRecords" => $count,
		"aaData" => Array()
	);

	foreach($rows as $row){
		$r = Array();
		foreach($row as $key=>$value){
			$r[] = "$value";
		}
		$output['aaData'][] = $r;
	}

	header('Content-Type: application/json');
	echo json_encode($output);

?>
