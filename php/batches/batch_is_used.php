<?php
require('../functions/check_login.php');
include '../functions/php_functions.php';
$conn = get_connexion();

$data = $_POST['data'];


$response_array = array();
$response_array['result'] = array();
$status = "error";
$response_array['status'] = $status;

$expNames =[];
$fullResult=[];


foreach ($data as $key => $row) {
    $cases = preg_split("/[\t]/", $row);
    foreach ($cases as $case) {
        $infos = explode("-", $case);
        if ($infos[0] == "EB") {
        }
        elseif ($infos[0] == "?") {
        }
        else {
            $expNames[$infos[0]]='present';
        }
    }
}


$stmt = $conn->prepare("
    SELECT
      fw_name, spl_alq_state, spl_number, alq_number
    FROM 
      freshweight, freshweight_sample, sample_aliquot, sample, aliquot
    WHERE 
        fw_name like :infos0 AND
        fw_spl_freshweight_FK = fw_id AND
        spl_id = fw_spl_sample_FK AND
        spl_alq_sample_FK = spl_id AND
        alq_id = spl_alq_aliquot_FK;");

foreach ($expNames as $key => $value){
    error_log($key);
    try {
        $stmt->bindParam(':infos0', $key, PDO::PARAM_STR);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $fullResult[$key] = $result;
        $stmt->closeCursor();
    }
    catch ( Exception $e ) {
        error_log("failure est survenue ".$e->getMessage());
        $status = "error";
    }
}

$newResult = [];
foreach($fullResult as $key => $values){
    foreach($values as $idx => $value) {
        $newResult[$value['fw_name']][$value['spl_number']][$value['alq_number']] = $value['spl_alq_state'];
    }
}


$i = 0;
foreach ($data as $key => $row){
    $j = 0;
    $cases = preg_split("/[\t]/", $row);
    foreach ($cases as $case) {
        $infos = explode("-", $case);
        if ($infos[0] == "EB") {
            $status = "success";
            $response_array['result'][$i][$j] ="free";
        } elseif ($infos[0] == "?") {
            $status = "success";
            $response_array['result'][$i][$j] = "free";
        } else {
            $response_array['result'][$i][$j] = $newResult[$infos[0]][$infos[1]][$infos[2]];
            error_log($newResult[$infos[0]][$infos[1]][$infos[2]]);
        }
        $j++;
    }
    $i++;
}

$response_array['status']=$status;
echo json_encode($response_array);
?>

