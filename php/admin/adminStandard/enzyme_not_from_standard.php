<?php
	require('../../functions/check_login_admin.php');
	include '../../functions/php_functions.php';
	$conn = get_connexion();

	$standardID = $_POST['standardID'];

	$query = '
	SELECT DISTINCT
		enzyme.ez_id, 
		enzyme.ez_analyte, 
		enzyme.ez_code, 
		enzyme.ez_slope, 
		REPLACE(REPLACE(enzyme.ez_is_enzyme,0,"Enzyme"),1,"Metabolite") AS ez_is_enzyme
	FROM 
		enzyme
	WHERE 
		ez_id NOT IN (
			SELECT 
				enzyme.ez_id 
			FROM 
				enzyme, 
				standard_enzyme 
			WHERE 
				standard_enzyme.std_ez_standard_FK = '.$standardID.' AND 
				enzyme.ez_id = standard_enzyme.std_ez_enzyme_FK
		)
	ORDER BY 
		enzyme.ez_analyte;';

	try {
		$sth=$conn->prepare($query);
		$sth -> setFetchMode(PDO::FETCH_ASSOC);
		$sth->execute();
		$count = $sth->rowCount();
		$rows=$sth->fetchAll();

	}
	catch ( Exception $e ) {
		$status="error";
		error_log($e);
	}

	header('Content-Type: application/json');
	echo json_encode($rows);
?>