<?php
function get_all_experiment($conn) {
	$query ='
		SELECT *
		FROM 
			experiment ;';
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

	$req=$conn->prepare($query);
	$req -> setFetchMode(PDO::FETCH_ASSOC);
	$req->execute();
	$rows=$req->fetchAll();

	return $rows;
}







?>
