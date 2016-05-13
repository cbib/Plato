<?php
	require('../functions/check_login.php');
	include '../functions/php_functions.php';
	$conn = get_connexion();

	$stdName="";
	$stdGenius="";
	$stdSpecies="";
	$stdNature="";
	$stdGenotype="";
	$stdOwner="";
	$stdComment="";
	$status="success";


	if (isset($_POST['stdName'])) {
		$stdName = $_POST['stdName'];
	}
	if (isset($_POST['stdGenius'])) {
		$stdGenius = $_POST['stdGenius'];
	}
	if (isset($_POST['stdSpecies'])) {
		$stdSpecies = $_POST['stdSpecies'];
	}
	if (isset($_POST['stdGenotype'])) {
		$stdGenotype = $_POST['stdGenotype'];
	}
	if (isset($_POST['stdNature'])) {
		$stdNature = $_POST['stdNature'];
	}
	if (isset($_POST['stdOwner'])) {
		$stdOwner = $_POST['stdOwner'];
	}
	if (isset($_POST['stdComment'])) {
		$stdComment = $_POST['stdComment'];
	}



	// error_log($stdName);
	// error_log($stdGenius);
	// error_log($stdSpecies);
	// error_log($stdNature);
	// error_log($stdOwner);
	// error_log($stdComment);
	
	$query = "INSERT INTO `standard`(`std_id`, `std_name`, `std_genius`, `std_species`, `std_genotype`, `std_nature`, `std_owner`, `std_comment`) 
		VALUES ('', '$stdName', '$stdGenius', '$stdSpecies', '$stdGenotype', '$stdNature', '$stdOwner', '$stdComment');";
	try {
		$sth=$conn->prepare($query);
		$sth -> setFetchMode(PDO::FETCH_ASSOC);
		$sth->execute();
	}
	catch ( Exception $e ) {
		error_log("ERRORCreate ".$e);
		$status="error";
	}

$response_array['status']=$status;
echo json_encode($response_array);
?>