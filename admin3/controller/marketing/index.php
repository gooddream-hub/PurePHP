<?php 
include('../../common/database.php');
include('../../config/config.php');
include('../../models/product_model.php');

$block1 = array();
$block2 = array();
$block3 = array();
$block4 = array();
$block5 = array();
$block6 = array();
$user_id = $_SESSION['user_id'];
$homepage_preview = $_SESSION['homepage_preview'];

if(!strcmp($homepage_preview, 'preview')) {

    $block1 = $_SESSION['block1'];
    $block2 = $_SESSION['block2'];
    $block3 = $_SESSION['block3'];
    $block4 = $_SESSION['block4'];
    $block5 = $_SESSION['block5'];
    $block6 = $_SESSION['block6'];
    $_SESSION['homepage_preview'] = '';
} else if(!strcmp($homepage_preview, 'pushlive')) {

    // $user_id = 19;
    $sql = "SELECT * FROM homepage WHERE user_id  = ".$user_id;
    if($result = $mysqli->query($sql)){
        while($row = $result->fetch_assoc() ){
            $block1 = json_decode($row['block1'],true);
            $block2 = json_decode($row['block2'],true);
            $block3 = json_decode($row['block3'],true);
            $block4 = json_decode($row['block4'],true);
            $block5 = json_decode($row['block5'],true);
            $block6 = json_decode($row['block6'],true);
        }
        $block2 = prepare_featured_products_block($block2);
        $block3 = prepare_posts_block($block3);
        $block4 = prepare_shop_the_look_block($block4);
    }
    $_SESSION['homepage_preview'] = '';
} else {
    // $user_id = 19;
    $sql = "SELECT * FROM homepage WHERE user_id  = ".$user_id;
    
    if($result = $mysqli->query($sql)){
        while($row = $result->fetch_assoc() ){
            $block1 = json_decode($row['block1'],true);
            $block2 = json_decode($row['block2'],true);
            $block3 = json_decode($row['block3'],true);
            $block4 = json_decode($row['block4'],true);
            $block5 = json_decode($row['block5'],true);
            $block6 = json_decode($row['block6'],true);
        }
    }
}
