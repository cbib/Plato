<?php
	require('../functions/check_login.php');
	include '../functions/php_functions.php';
	$conn = get_connexion();


	$batchID = $_POST['batchID'];
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
		$sth=$conn->prepare($query);
		$sth->setFetchMode(PDO::FETCH_ASSOC);
		$sth->execute();
		$rows=$sth->fetchAll();
		$status="success";
	}
	catch ( Exception $e ) {
		$status="error";
	}

	$output = array();

		foreach($rows as $row){
			$line="";
		    foreach($row as $key=>$value){
	        	$line.="|||".$value;
		    }
		    $output[]=$line;
		} 

	header('Content-Type: application/json');
	echo json_encode($output);
?>