<?php
	include '../functions/php_functions.php';

function user_ctp(){
	$conn=get_connexion();
	$query ='
		SELECT  *
		FROM user
	';

	//echo $query."\n\n";
	$req=$conn->prepare($query);
	$req -> setFetchMode(PDO::FETCH_ASSOC);
	$req->execute();
	$rows=$req->fetchAll();

	echo sizeof($rows);
}



function batch_ctp(){
	$conn=get_connexion();
	$query ='
		SELECT  *
		FROM batch
	';

	//echo $query."\n\n";
	$req=$conn->prepare($query);
	$req -> setFetchMode(PDO::FETCH_ASSOC);
	$req->execute();
	$rows=$req->fetchAll();

	echo sizeof($rows);
}


function sizeOfDb(){
	$conn=get_connexion();
	$query ="SELECT 
		table_schema 'plato', 
		Round(Sum(data_length + index_length) / 1024 / 1024, 1) 'dbsize'
	FROM
		information_schema.tables 
	GROUP BY
		table_schema;";

	$output = array();

	//echo $query."\n\n";
	$req=$conn->prepare($query);
	$req -> setFetchMode(PDO::FETCH_ASSOC);
	$req->execute();
	$rows=$req->fetchAll();
	foreach($rows as $row){
		$output[$row['plato']] = $row['dbsize'];
	}
	$json=json_encode($output);
	echo $json;
}


function batch_cumul_per_date(){
	$conn=get_connexion();
	$nb =0;
	$query ="
		SELECT DATE_FORMAT(bat_date,'%Y-%m-%d') AS bat_date, count(bat_date) as nb FROM batch GROUP BY bat_date;";

	$output = array();

	//echo $query."\n\n";
	$req=$conn->prepare($query);
	$req -> setFetchMode(PDO::FETCH_ASSOC);
	$req->execute();
	$rows=$req->fetchAll();
	foreach($rows as $row){
		//$timestamp = strtotime($row['bat_date']);
		$nb += intval($row['nb']);
		//error_log($nb);
		$output[] = [$row['bat_date'], $nb];
	}
	$json=json_encode($output);
	echo $json;
}




if($_GET["fonction"] == "sizeOfDb"){
	sizeOfDb();
}
if($_GET["fonction"] == "batch_ctp"){
	batch_ctp();
}
if($_GET["fonction"] == "batch_number_per_date"){
	batch_number_per_date();
}
if($_GET["fonction"] == "batch_cumul_per_date"){
	batch_cumul_per_date();
}

?>