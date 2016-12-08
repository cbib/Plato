<?php
	require('../../functions/check_login_admin.php');
	include '../../functions/php_functions.php';
	header('Content-Type: application/json');
	$conn = get_connexion();

	//Create, edit, delete user

	$userID="";
	$userName="";
	$userLevel="";
	$adminPassword="";
	$userPassword="";
	$action="";
	$status="success";
	$boolOk=false;

	if (isset($_POST['userID'])) {
		$userID = $_POST['userID'];
	}
	if (isset($_POST['userName'])) {
		$userName = $_POST['userName'];
	}
	if (isset($_POST['adminPassword'])) {
		$adminPassword = $_POST['adminPassword'];
	}
	if (isset($_POST['userPassword'])) {
		$userPassword = $_POST['userPassword'];
	}
	if (isset($_POST['action'])) {
		$action = $_POST['action'];
	}
	if (isset($_POST['userLevel'])) {
		$userLevel = $_POST['userLevel'];
	}


	$pass_hache = hash( 'sha256', $adminPassword);
	$query ="
	SELECT
		count(*)
	FROM
		user
	WHERE
		usr_status like '%admin%' AND 
		usr_pwd like '%".$pass_hache."%' ;
	";

	try 
	{
		$req = $conn->query($query)->fetchColumn();
		//show_array($req);
		if (!$req)
		{
		    error_log('Mauvais identifiant ou mot de passe !');
		}
		else
		{
			$boolOk = true;
		}
	}
	catch ( Exception $e ) {
		error_log("failure est survenue lors de $query".$e->getMessage());
	}
	

	if($boolOk == true){
		if($action=="create"){
			$query = "
			INSERT INTO `user` VALUES ('', '$userName', '$userPassword', '$userLevel');";
			try {
				$sth=$conn->prepare($query);
				$sth -> setFetchMode(PDO::FETCH_ASSOC);
				$sth->execute();
				$status="success";
			}
			catch ( Exception $e ) {
				error_log("ERRORCreate ".$e);
				$status="error";
			}
		}
		elseif ($action=="edit") {
				if (($userPassword !="") && (!defined($userPassword))) {

					$userpass_hache = hash( 'sha256', $userPassword);
					$query ="UPDATE `user` SET`usr_name`='$userName',`usr_pwd`='$userpass_hache',`usr_status`='$userLevel' WHERE `usr_id`='$userID';";
				}
				else {
					$query ="UPDATE `user` SET`usr_name`='$userName',`usr_status`='$userLevel' WHERE `usr_id`='$userID';";
				}
			try {
				$entree = $conn->exec($query);
				$status="success";
			}
			catch ( Exception $e ) {
				error_log("ERROREdit ".$e);
				$status="error";
			}
		}
		elseif ($action=="delete") {
			$query ="DELETE FROM `user` WHERE `usr_id` = '$userID';";
			try {
				$entree = $conn->exec($query);
				$status="success";
			}
			catch ( Exception $e ) {
				error_log("ERRORDelete ".$e);
				$status="error";
			}
		}
		else {
			$status="error";
			$action = "Unknown";
		} 
	}
	else {
		$status="You're not the administrator !";
	}
$response_array['status']=$status;
$response_array['action']=$action;
echo json_encode($response_array);

?>