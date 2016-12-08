<?php
	require('../../functions/check_login_admin.php');
	include '../../functions/php_functions.php';

	//Get all information of all standard

	$conn = get_connexion();
	$query = '
	SELECT std_id, std_name, std_genius, std_species, std_genotype, std_nature, std_owner, std_comment 
	FROM standard
	ORDER BY std_id';

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
