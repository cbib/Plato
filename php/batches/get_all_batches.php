<?php
	require('../functions/check_login.php');
	include '../functions/php_functions.php';
	$conn = get_connexion();
	// $query ='SELECT experiment.*, std_name FROM experiment, experiment_standard, standard WHERE exp_id = exp_std_experiment_FK AND std_id = exp_std_standard_FK';

	
	$query ='
	SELECT
		*
	FROM
		batch;';

	$sth=$conn->prepare($query);
	$sth -> setFetchMode(PDO::FETCH_ASSOC);
	$sth->execute();
	$count = $sth->rowCount();
	$rows=$sth->fetchAll();

	$output = array(
	    "iTotalRecords" => $count,
	    "iTotalDisplayRecords" => $count,
	    "aaData" => array()
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