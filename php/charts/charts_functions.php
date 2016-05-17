<?php
	include '../functions/php_functions.php';


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

	// error_log($query);
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


function analyte_distribution(){
	$conn=get_connexion();
	$nb =0;
	$query ="
		SELECT enzyme.ez_analyte, COUNT(rawdata.data_enzyme_FK) AS ez_number from enzyme LEFT JOIN rawdata ON(enzyme.ez_id = rawdata.data_enzyme_FK ) GROUP BY enzyme.ez_id;";

	$output = array();

	//echo $query."\n\n";
	$req=$conn->prepare($query);
	$req -> setFetchMode(PDO::FETCH_ASSOC);
	$req->execute();
	$rows=$req->fetchAll();
	foreach($rows as $row){
		$output[] = [$row['ez_analyte'], intval($row["ez_number"])];
	}
	$json=json_encode($output);
	echo $json;
}


if($_GET["fonction"] == "analyte_distribution"){
	analyte_distribution();
}
if($_GET["fonction"] == "sizeOfDb"){
	sizeOfDb();
}
// if($_GET["fonction"] == "batch_number_per_date"){
// 	batch_number_per_date();
// }
// if($_GET["fonction"] == "batch_cumul_per_date"){
// 	batch_cumul_per_date();
// }

?>