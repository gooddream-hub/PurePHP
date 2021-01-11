<?php

function paymentSave($data = null) {
    include('../../common/database.php');
    
    // $index_custid = (int) $data['custid'];
    // $sql = "SELECT * FROM payment WHERE custid = ('$index_custid')";
    // if(!$mysqli->query($sql)){
        $sql = "INSERT INTO payment (custid, payType, shipping, ship_type, grandtotal) values ";
        if($data){
            $valuesArr = array();
            $custid = (int) $data['custid'];
            $payType = mysql_real_escape_string( $data['payType'] );
            $shipping = $data['shipping'];
            $ship_type = mysql_real_escape_string( $data['ship_type'] );
            $grandtotal = $data['grandtotal'];
    
            $valuesArr[] = "('$custid', '$payType', '$shipping', '$ship_type', '$grandtotal')";
    
            $sql .= implode(',', $valuesArr);
            if($mysqli->query($sql))return true;
            return false;
        }
    // }
}
