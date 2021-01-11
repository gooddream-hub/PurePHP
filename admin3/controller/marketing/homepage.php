<?php 
include('../../common/database.php');
include('../../config/config.php');

// $user_id = 19;
$user_id = $_SESSION['user_id'];
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
