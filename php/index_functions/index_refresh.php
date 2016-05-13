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


function batch_number_per_date(){
	$conn=get_connexion();
	$query ="
		SELECT  DATE_FORMAT(bat_date,'%Y,%m,%d') AS bat_date, count(bat_date) as nb
		FROM batch
		GROUP BY bat_date;";



	$output = array();

	//echo $query."\n\n";
	$req=$conn->prepare($query);
	$req -> setFetchMode(PDO::FETCH_ASSOC);
	$req->execute();
	$rows=$req->fetchAll();
	foreach($rows as $row){
		$timestamp = strtotime($row['bat_date']);
		$nb = intval($row['nb']);
		$output[] = [$timestamp, $nb];
	}
	$json=json_encode($output);
	echo $json;
}

if($_GET["fonction"] == "user_ctp"){
	user_ctp();
}
if($_GET["fonction"] == "batch_ctp"){
	batch_ctp();
}
if($_GET["fonction"] == "batch_number_per_date"){
	batch_number_per_date();
}
?>