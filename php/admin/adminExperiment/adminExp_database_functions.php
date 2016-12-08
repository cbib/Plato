<?php
	require('../../functions/check_login.php');
	include '../../functions/php_functions.php';
	$conn = get_connexion();
	// Get all experiment and their associated standard

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
