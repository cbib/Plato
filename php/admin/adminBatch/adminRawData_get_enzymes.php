<?php
	require('../../functions/check_login.php');
	include '../../functions/php_functions.php';
	$conn = get_connexion();

	//Get list of analytes used for a batch
	$batchID = $_POST['batchID'];
	//error_log($batchID);
	$query ='
	SELECT 	
		distinct rawdata.data_enzyme_FK, enzyme.ez_analyte, enzyme.ez_code
	FROM
		batch_data, rawdata, enzyme 
	WHERE 
		batch_data.bat_data_batch_FK = '.$batchID.' AND 
		rawdata.rawdata_batch_data_FK = batch_data.bat_data_id AND
		enzyme.ez_id = rawdata.data_enzyme_FK
	ORDER BY 
		enzyme.ez_code;
	';

	try {
		//error_log($query);
		$sth=$conn->prepare($query);
		$sth->setFetchMode(PDO::FETCH_ASSOC);
		$sth->execute();
		$rows=$sth->fetchAll();
		$status="success";
	}
	catch ( Exception $e ) {
		//error_log("ERROREdit ".$e);
		$status="error";
	}

	$output = array();

		foreach($rows as $row){
			$line="";
		    foreach($row as $key=>$value){
	        	$line.="@".$value;
	        	//error_log($line);
		    }
		    $output[]=$line;
		} 

	header('Content-Type: application/json');
	echo json_encode($output);
?>