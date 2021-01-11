<?php

function get_net_custinfo()
{
    include('../../common/database.php');
    $sql = "SELECT custid FROM getid";

    $res = array();
    if($result = $mysqli->query($sql)){
        while($row = $result->fetch_assoc() ){
            $res[] = $row;
        }

        $update_id = $res[0]->custid + 2;

        $sql = "UPDATE getid SET custid = ".$update_id;
        $mysqli->query($sql);
        
        return $res[0]->custid+1;
    }

}

function invoiceSave($data = null)
{
    include('../../common/database.php');

    if($data){

        $row = $data;
        $index_custid = (int) $data['custid'];

        $sql = "SELECT * FROM custinfo WHERE custid = ('$index_custid')";
        if(!$mysqli->query($sql)){

            // If not exist
            $sql = "INSERT INTO custinfo (custid, shiplast, shipfirst, shipadone, shipadtwo, shipcity, shipstate, shipzip, shipco, billlast, billfirst, billadone, billadtwo, billcity, billstate, billzip, billco, order_date, email, newsletter) values ";

            $valuesArr = array();
    
            $custid = (int) $row['custid'];
            $shiplast = mysql_real_escape_string( $row['shiplast'] );
            $shipfirst = mysql_real_escape_string( $row['shipfirst'] );
            $shipadone = mysql_real_escape_string( $row['shipadone'] );
            $shipadtwo = mysql_real_escape_string( $row['shipadtwo'] );
            $shipcity = mysql_real_escape_string( $row['shipcity'] );
            $shipstate = mysql_real_escape_string( $row['shipstate'] );
            $shipzip = mysql_real_escape_string( $row['shipzip'] );
            $shipco = $row['shipzip'];
            $billlast = $row['billlast'];
            $billfirst = $row['billfirst'];
            $billadone = mysql_real_escape_string( $row['billadone'] );
            $billadtwo = mysql_real_escape_string( $row['billadtwo'] );
            $billcity = mysql_real_escape_string( $row['billcity'] );
            $billstate = mysql_real_escape_string( $row['billstate'] );
            $billzip = mysql_real_escape_string( $row['billzip'] );
            $billco = mysql_real_escape_string( $row['billco'] );
            $order_date = $row['order_date'];
            $email = mysql_real_escape_string( $row['email'] );
            $newsletter = mysql_real_escape_string( $row['newsletter'] );
    
            $valuesArr[] = "('$custid', '$shiplast', '$shipfirst', '$shipadone', '$shipadtwo', '$shipcity', '$shipstate', '$shipzip', '$shipco', '$billlast', '$billfirst', '$billadone', '$billadtwo', '$billcity', '$billstate', '$billzip', '$billco', '$order_date', '$email', '$newsletter')";
    
            $sql .= implode(',', $valuesArr);
            if($mysqli->query($sql))return true;
            return false;
        }
    }
}