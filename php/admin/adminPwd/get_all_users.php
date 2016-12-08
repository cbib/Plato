<?php
	require('../../functions/check_login_admin.php');
	include '../../functions/php_functions.php';
	$conn = get_connexion();

	// Get all user informations

	$query ='SELECT usr_id, usr_name, usr_status FROM user ORDER BY usr_name;';
	// error_log($query);

	$sth=$conn->prepare($query);
	$sth -> setFetchMode(PDO::FETCH_ASSOC);
	$sth->execute();
	$count = $sth->rowCount();
	$rows=$sth->fetchAll();

	$output = array(
	    "iTotalRecords" => $count,
	    "iTotalDisplayRecords" => $count,
	    "aaData" => Array()
	);

	foreach($rows as $row){
	    $r = Array();
	    foreach($row as $key=>$value){
        	$r[] = "$value";
	    }
	    $output['aaData'][] = $r;
	} 
	
	header('Content-Type: application/json');
	echo json_encode($output);
?>
