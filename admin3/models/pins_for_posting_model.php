<?php 
function pins_for_posting_model_insert_pin($row){
    include('../../common/database.php');
    $keys = "";
    $values = "";
    foreach ($row as $key => $value) {
        $keys .= $key . ",";
        $values .= " '" . $value . "',";
    }
    $keys = rtrim($keys, ',');
    $values = rtrim($values, ',');

    $sql = "INSERT INTO pins_for_posting(" . $keys . ") VALUES (" . $values ." )";
    $result = $mysqli->query($sql);
}
?>