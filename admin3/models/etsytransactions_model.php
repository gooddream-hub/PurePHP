<?php

function getAllTransations($start_period = false)
{
    include('../../common/database.php');
    $sql = "Select * FROM etsy_transactions WHERE transaction_id > 0";
    if ($start_period) {
        $sql .= ' AND added >= '.$start_period;
    }

    $res = array();
    if($result = $mysqli->query($sql)){
        while($row = $result->fetch_assoc() ){
            $res[] = $row;
        }
    }

    $transactions = array();
    if ($res) {
        foreach ($res as $row) {
            $transactions[$row['transaction_id']] = $row;
        }
    }
    return $transactions;
}

function getByEtsy($etsy_id)
{
    include('../../common/database.php');

    $sql = "Select * FROM etsy_transactions WHERE transaction_id = " . $etsy_id;
    $res = array();
    if($result = $mysqli->query($sql)){
        while($row = $result->fetch_assoc() ){
            $res[] = $row;
        }
    }

    if(!empty($res)){
        return $res[0];
    } else {
        return false;
    }
}

function insert_transaction($transaction_id, $listing_id, $inv_id, $time, $receipt_id, $invoice_id)
{
    include('../../common/database.php');
    
    $sql_delete = "DELETE FROM etsy_transactions WHERE listing_id = ('$listing_id') AND transaction_id = ('$transaction_id') AND invid = ('$inv_id')";
    $mysqli->query($sql_delete);

    $sql = "INSERT INTO etsy_transactions (listing_id, invid, transaction_id, added, receipt_id, invoice_id) VALUES (('$listing_id'),('$inv_id'),('$transaction_id'),('$time'),('$receipt_id'),('$invoice_id')";
    if($mysqli->query($sql))return true;
    return false;
}