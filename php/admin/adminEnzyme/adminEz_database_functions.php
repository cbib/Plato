<?php
	require('../../functions/check_login_admin.php');
	include '../../functions/php_functions.php';
	//get all analytes

	$conn = get_connexion();
	$query = '
	SELECT enzyme.ez_id, enzyme.ez_analyte, enzyme.ez_code, enzyme.ez_slope, REPLACE(REPLACE(REPLACE(enzyme.ez_is_enzyme,1,"Enzyme"),0,"Metabolite"),2,"Other") AS ez_is_enzyme
	FROM enzyme
	ORDER BY ez_id';

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
