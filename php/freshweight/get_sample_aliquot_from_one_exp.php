<?php
	require('../functions/check_login.php');
	include '../functions/php_functions.php';
	$conn = get_connexion();

	$expID = $_POST['expID'];

	$query ='
		SELECT 
			sample.spl_id, 
			sample.spl_number,
			aliquot.alq_id,
			aliquot.alq_number,
			aliquot.alq_value, 
			location.loc_fridge,
			sample_aliquot.spl_alq_state,
			freshweight.fw_id, 
			location.loc_id
		FROM 
			experiment_freshweight, 
			freshweight, 
			freshweight_sample, 
			sample, 
			sample_aliquot, 
			aliquot, 
			location
		WHERE 
			experiment_freshweight.exp_fw_experiment_FK = '.$expID.' AND
			freshweight.fw_id = experiment_freshweight.exp_fw_freshweight_FK AND
			freshweight_sample.fw_spl_freshweight_FK = freshweight.fw_id AND
			sample.spl_id = freshweight_sample.fw_spl_sample_FK AND
			sample_aliquot.spl_alq_sample_FK = sample.spl_id AND
			aliquot.alq_id = sample_aliquot.spl_alq_aliquot_FK AND
			location.loc_id = aliquot.alq_location_FK
		ORDER BY sample.spl_number;
	';

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
			$r[] = " $value ";
		}
		$output['aaData'][] = $r;
	}

	header('Content-Type: application/json');
	echo json_encode($output);
?>