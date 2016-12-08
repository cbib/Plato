<?php
	require('../../functions/check_login_admin.php');
	include '../../functions/php_functions.php';
	$conn = get_connexion();

	// get all analyte associated to a standard

	$standardID = $_POST['standardID'];

	//$dataArr['aaData'] = Array();
	$query = 'SELECT 
		standard_enzyme.std_ez_id, 
		enzyme.ez_id, 
		enzyme.ez_analyte, 
		enzyme.ez_code, 
		enzyme.ez_slope, 
		REPLACE(REPLACE(enzyme.ez_is_enzyme,1,"Enzyme"),0,"Metabolite") AS ez_is_enzyme,
		standard_enzyme.std_ez_value, 
		unit.unit_name, 
		standard_enzyme.std_ez_dilution
	FROM 
		enzyme, standard_enzyme, unit
	WHERE 
		standard_enzyme.std_ez_standard_FK = '.$standardID.' AND 
		enzyme.ez_id = standard_enzyme.std_ez_enzyme_FK AND
		unit.unit_id = standard_enzyme.std_ez_unit_FK
		ORDER BY enzyme.ez_analyte;';

	$sth=$conn->prepare($query);
	$sth -> setFetchMode(PDO::FETCH_ASSOC);
	$sth->execute();
	$count = $sth->rowCount();
	$rows=$sth->fetchAll();

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