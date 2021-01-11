<?php
    include('./MY_Etsy.php');
    $my_etsy = new MY_Etsy();
    if($my_etsy->etsyAccessToken(SITE_URL . 'admin3/view/inventory/etsy.php')) {
        // redirect($_GET['r']);
        header("Location: " . $_GET['r']);
    } else {
        die('Can\'t receive access token');
    }
?>