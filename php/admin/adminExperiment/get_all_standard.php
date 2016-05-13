<?php
	require('../../functions/check_login.php');
	include '../../functions/php_functions.php';
	$conn = get_connexion();
	$query ='SELECT std_id, std_name FROM standard ORDER BY std_name;';
	//error_log($query);
	$sth=$conn->prepare($query);
	$sth -> setFetchMode(PDO::FETCH_ASSOC);
	$sth->execute();
	$count = $sth->rowCount();
	$rows=$sth->fetchAll();

	$output = array();

	foreach($rows as $row){
	    $r = Array();
	    foreach($row as $key=>$value){
			$r[] = "$value";
		}
		$output[] = $r;
	} 
	header('Content-Type: application/json');
	echo json_encode($output);
?>