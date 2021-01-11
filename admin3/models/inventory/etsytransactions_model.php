<?php

function ConnectToEtsy($inv_id, $etsy_id) {
    include('../../../common/database.php');

    $sql = "Insert INTO etsy_transactions (invid, listing_id) VALUES ('$inv_id', '$etsy_id')";
    $result = $mysqli->query($sql);
    return (bool)$result;
}
