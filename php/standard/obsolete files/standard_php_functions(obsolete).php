<?php


function calcul_std_confiance ($values){
	$return = -1;
	if ($values['std_id']=="" || $values['std_name']==""){
		$return = "error";
	}
	else {
		if ($values['std_genius']=="" || $values['std_nature']=="" || $values['std_uri']==""){
			$return="warning";
		}
		else {
			$return = "success";
		}	 
	}
	return $return;
}

?>
