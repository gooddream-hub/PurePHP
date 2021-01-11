<?php 
include('../../common/database.php');

function pin_model_save($row){
    include('../../common/database.php');
    $keys = "";
    $values = "";
    foreach ($row as $key => $value) {
        // $arr[3] will be updated with each value from $arr...
        $keys .= $key . ",";
        $values .= " '" . $value . "',";
    }
    $keys = rtrim($keys, ',');
    $values = rtrim($values, ',');

    $sql = "INSERT INTO image_pins(" . $keys . ") VALUES (" . $values ." )";
    $result = $mysqli->query($sql);
}

function pin_model_getPinById($id){
    include('../../common/database.php');
    $sql = "SELECT * FROM image_pins WHERE id = $id";
    $res_data = array();
    if($result = $mysqli->query($sql)){
        while($row = $result->fetch_assoc() ){
            $res_data[] = $row;
        }
    }
    return $res_data;
}

function pin_model_update($id, $row){
    include('../../common/database.php');
    $cc = "";
    foreach ($row as $key => $value) {
        $cc .= $key." = "."'".$value."'".", ";
    }
    $cc = substr($cc, 0, -2);
    $sql = "UPDATE image_pins SET ".$cc." WHERE id = $id";
	$result = $mysqli->query($sql);
}

function getProductsListByTitle($search_term,$limit=1000) {

    include('../../common/database.php');
    $sql = "SELECT * FROM image_pins WHERE title like '%$search_term%' ORDER BY id DESC LIMIT 0, $limit";
    $result = $mysqli->query($sql);
    while($row = $result->fetch_assoc() ){
        $res[] = $row;
    }
    return $res;
}

function getLatestPins($limit=20) {
    include('../../common/database.php');
    $sql = "SELECT * FROM image_pins ORDER BY id DESC LIMIT 0, $limit";
    $result = $mysqli->query($sql);
    while($row = $result->fetch_assoc() ){
        $res[] = $row;
    }
    return $res;
}

?>