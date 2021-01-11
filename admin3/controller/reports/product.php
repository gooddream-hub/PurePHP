<?php
include('../../config/config.php');

function product() {
    $mysqli = mysqliConn();
    $data = array();
    $cust_data = array();
    $type = array();
    $cat1 = array();
    $cat2 = array();
    $cat3 = array();
    $cat4 = array();

    $sql = "SELECT invid, cat, type, color FROM inven_mj WHERE cat != 'Clearance' ORDER BY cat, type, color";
    if($result = $mysqli->query($sql)){
        while($row = $result->fetch_assoc() ){
            $product[] = array_map('utf8_encode', $row);
        } 
    }	
    $data['product'] = $product;

    $sql1 = "SELECT DISTINCT type FROM inven_mj WHERE cat != 'Clearance' ORDER BY type asc";
    if($result1 = $mysqli->query($sql1)){
        while($row1 = $result1->fetch_assoc() ){
            $type[] = array_map('utf8_encode', $row1);
        } 
    }	
    $data['type'] = $type;

    $sql2 = "SELECT MAX(invid) AS invid,cat FROM inven_mj WHERE cat IN ('Latex-Applique','Leather-Hides','Notions','Latex-Sheeting') GROUP BY cat  ORDER BY cat asc";
    if($result2 = $mysqli->query($sql2)){
        while($row2 = $result2->fetch_assoc() ){
            $cat1[] = array_map('utf8_encode', $row2);
        } 
    }	
    $data['cat1'] = $cat1;

    $sql3 = "SELECT MAX(invid) AS invid,cat FROM inven_mj WHERE cat IN ('Latex-Applique','Leather-Hides','Notions','Latex-Sheeting') GROUP BY cat  ORDER BY cat asc";
    //$sql3 = "SELECT * FROM inven_mj WHERE cat = 'Leather-Hides'";
    if($result3 = $mysqli->query($sql3)){
        while($row3 = $result3->fetch_assoc() ){
            $cat2[] = array_map('utf8_encode', $row3);
        } 
    }	
    $data['cat2'] = $cat2;

    $sql4 = "SELECT MAX(invid) AS invid,cat FROM inven_mj WHERE cat IN ('Latex-Applique','Leather-Hides','Notions','Latex-Sheeting') GROUP BY cat  ORDER BY cat asc";
    //$sql4 = "SELECT * FROM inven_mj WHERE cat = 'Notions'";
    if($result4 = $mysqli->query($sql4)){
        while($row4 = $result4->fetch_assoc() ){
            $cat3[] = array_map('utf8_encode', $row4);
        } 
    }	
    $data['cat3'] = $cat3;

    $sql5 = "SELECT MAX(invid) AS invid,cat FROM inven_mj WHERE cat IN ('Latex-Applique','Leather-Hides','Notions','Latex-Sheeting') GROUP BY cat  ORDER BY cat asc";
    //$sql5 = "SELECT * FROM inven_mj WHERE cat = 'Latex-Sheeting'";
    if($result5 = $mysqli->query($sql5)){
        while($row5 = $result5->fetch_assoc() ){
            $cat4[] = array_map('utf8_encode', $row5);
        } 
    }	
    $data['cat4'] = $cat4;

    return $data;
}

?>















