<?php
	require('../functions/check_login.php');
	include '../functions/php_functions.php';
	$conn = get_connexion();


	$standardID = $_POST['standardID'];

	/**
	 * Select all analytes linked to a standard, even if no rawdata are associated to this analytes
	 * In the db 1 means Enzyme; 0 means Metabolite
	 */
	$query = 'SELECT enzyme.ez_analyte, enzyme.ez_code, enzyme.ez_slope, REPLACE(REPLACE(enzyme.ez_is_enzyme,1,"Enzyme"),0,"Metabolite") AS ez_is_enzyme, standard_enzyme.std_ez_value, unit.unit_name, standard_enzyme.std_ez_dilution
	FROM enzyme, standard_enzyme, unit
	WHERE standard_enzyme.std_ez_standard_FK = '.$standardID.' AND 
	enzyme.ez_id = standard_enzyme.std_ez_enzyme_FK AND
	unit.unit_id = standard_enzyme.std_ez_unit_FK
	ORDER BY enzyme.ez_analyte;';

	$sth=$conn->prepare($query);
	$sth -> setFetchMode(PDO::FETCH_ASSOC);
	$sth->execute();
	$count = $sth->rowCount();
	$rows=$sth->fetchAll();

	/**
	 * Format query result 
	 * Datatables understands this format to create automatically a new table.
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