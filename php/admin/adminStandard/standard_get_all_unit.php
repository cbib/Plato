<?php
	require('../../functions/check_login_admin.php');
	include '../../functions/php_functions.php';
	$conn = get_connexion();
	$query ='
	SELECT 
		unit_id,
		unit_name
	FROM 
		unit
	ORDER BY
		unit_name;';
	$sth=$conn->prepare($query);
	$sth -> setFetchMode(PDO::FETCH_ASSOC);
	$sth->execute();
	$count = $sth->rowCount();
	$rows=$sth->fetchAll();

	header('Content-Type: application/json');
	echo json_encode($rows);
?>