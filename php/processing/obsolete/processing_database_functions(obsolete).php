<?php
function get_all_experiment($conn) {
	//$conn = get_connexion_pl();
	$query ='
select *
from 
	experiment 
';
	//echo $query."\n\n";
	$req=$conn->prepare($query);
	$req -> setFetchMode(PDO::FETCH_ASSOC);
	$req->execute();
	$rows=$req->fetchAll();

	return $rows;
}

function get_batches_from_one_exp($conn, $expID) {
	$query ='
		SELECT  distinct batch.bat_number, batch.bat_name
		FROM batch_data, batch
		WHERE batch_data.bat_data_experiment_FK = '.$expID.' AND
		batch.bat_id = batch_data.bat_data_batch_FK;
	';

	//echo $query."\n\n";
	$req=$conn->prepare($query);
	$req -> setFetchMode(PDO::FETCH_ASSOC);
	$req->execute();
	$rows=$req->fetchAll();

	return $rows;
}

function get_all_enzymes($conn) {
	//$conn = get_connexion_pl();
	$query ='
select *
from 
	enzyme 
';
	//echo $query."\n\n";
	$req=$conn->prepare($query);
	$req -> setFetchMode(PDO::FETCH_ASSOC);
	$req->execute();
	$rows=$req->fetchAll();

	return $rows;
}





?>
