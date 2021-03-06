<?php
	include '../functions/php_functions.php';

function user_ctp(){
	$conn=get_connexion();
	$query ='
		SELECT count(*)
		FROM user
	';
	$req = $conn->query($query)->fetchColumn();
	echo ($req);
}



function sample_cpt(){
	$conn=get_connexion();
	$query ='
		SELECT count(*)
		FROM sample
	';
	$req = $conn->query($query)->fetchColumn();
	echo ($req);
}


function batch_ctp(){
	$conn=get_connexion();
	$query ='
		SELECT count(*)
		FROM batch
	';
	$req = $conn->query($query)->fetchColumn();
	echo ($req);
}


function experiment_cpt(){
	$conn=get_connexion();
	$query ='
		SELECT count(*)
		FROM experiment
	';
	$req = $conn->query($query)->fetchColumn();
	echo ($req);
}


function rawdata_cpt(){
	$conn=get_connexion();
	$query ='
		SELECT  count(*)
		FROM rawdata
	';
	$req = $conn->query($query)->fetchColumn();
	echo ($req);
}

function batch_number_per_date(){
	$conn=get_connexion();
	$query ="
		SELECT  DATE_FORMAT(bat_date,'%Y-%m-%d') AS bat_date, count(bat_date) as nb
		FROM batch
		GROUP BY bat_date;";

	$output = array();

	//echo $query."\n\n";
	$req=$conn->prepare($query);
	$req -> setFetchMode(PDO::FETCH_ASSOC);
	$req->execute();
	$rows=$req->fetchAll();
	foreach($rows as $row){
		// $timestamp = strtotime($row['bat_date']);
		$nb = intval($row['nb']);
		$output[] = [$row['bat_date'], $nb];
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




if($_GET["fonction"] == "user_ctp"){
	user_ctp();
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
if($_GET["fonction"] == "sample_cpt"){
	sample_cpt();
}
if($_GET["fonction"] == "rawdata_cpt"){
	rawdata_cpt();
}
if($_GET["fonction"] == "experiment_cpt"){
	experiment_cpt();
}
?>