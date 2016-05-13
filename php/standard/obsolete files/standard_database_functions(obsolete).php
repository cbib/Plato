<?php

/**
 * Get all enzymes
 *
 * @return     <type>
 */
function get_all_enzymes() {
	$conn=get_connexion();
	$query ='
	SELECT 
		*
	FROM 
		enzyme 
	';
	//error_log($query."\n\n");
	$req=$conn->prepare($query);
	$req -> setFetchMode(PDO::FETCH_ASSOC);
	$req->execute();
	$rows=$req->fetchAll();

	return $rows;
}

function get_all_unit($conn) {
	$query ='
	SELECT 
		*
	FROM 
		unit 
	';
	//echo $query."\n\n";
	$req=$conn->prepare($query);
	$req -> setFetchMode(PDO::FETCH_ASSOC);
	$req->execute();
	$rows=$req->fetchAll();

	return $rows;
}



?>
