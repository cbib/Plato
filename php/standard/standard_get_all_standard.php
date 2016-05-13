<?php
	require('../functions/check_login.php');
	include '../functions/php_functions.php';
	$conn = get_connexion();
	$query ='SELECT * FROM standard ORDER BY std_name;';

	$sth=$conn->prepare($query);
	$sth -> setFetchMode(PDO::FETCH_ASSOC);
	$sth->execute();
	$count = $sth->rowCount();
	$rows=$sth->fetchAll();

	/**
	 * Format query result for datatables library
	 *
	 * @var        array
	 */
	$output = array(
	    "iTotalRecords" => $count,
	    "iTotalDisplayRecords" => $count,
	    "aaData" => array()
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