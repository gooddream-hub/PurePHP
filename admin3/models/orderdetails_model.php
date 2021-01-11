<?php

function orderdetailsSave($data = null) {

    include('../../common/database.php');

    $sql = "INSERT INTO `orderdetails` (`custid`, `invid`, `cat`, `type`, `color`, `price`, `quant`, `total`) values ";

    if($data){
        $valuesArr = array();
        $custid = (int) $data['custid'];
        $invid = (int) $data['invid'];
        $cat = mysql_real_escape_string( $data['cat'] );
        $type = mysql_real_escape_string( $data['type'] );
        $color = mysql_real_escape_string( $data['color'] );
        $price = $data['price'];
        $quant = (int) $data['quant'];
        $total = $data['total'];

        $valuesArr[] = "('$custid', '$invid', '$cat', '$type', '$color', '$price', '$quant', '$total')";

        $sql .= implode(',', $valuesArr);
        if($mysqli->query($sql))return true;
        return false;
    }

}