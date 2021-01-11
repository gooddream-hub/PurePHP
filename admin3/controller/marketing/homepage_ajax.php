<?php
include('../../common/database.php');
include('../../config/config.php');
include('../../common/constants.php');

$DocumentRoot= $config['DocumentRoot'];
$_SESSION['homepage_preview'] = '';

if( isset($_FILES['homepage_img']['name']) && $_FILES['homepage_img']['name']) {

    $filename = $_FILES['homepage_img']['name'];
    $fileArray = array();
    $fileArray[] = $filename;

    $return = array(
        'files' => array(),
    );

    if( move_uploaded_file( $_FILES["homepage_img"]["tmp_name"], $DocumentRoot . "/images/homepage_images/uploads/" . $filename )) {
    // if( move_uploaded_file( $_FILES["homepage_img"]["tmp_name"], "../../assets/img/marketing/upload/" . $filename )) {
        $return['files'][] = array(
            "name" => $filename,
            "size" => $_FILES['homepage_img']['size'] *1024,
            // "url"  => '../../assets/img/marketing/upload/'.$filename,
            "url" => $config['domain'] . '/images/homepage_images/uploads/' . $filename,
        );
        if(!CopyFileToFTPDir(array($filename))) {
            $return['files'][]['error'] = 'CDN upload error';
        }
    }
    else {
        $return['files'][]['error'] = 'error';
    }
    header('Content-type: application/json');
    echo json_encode($return);
    die;
}

function CopyFileToFTPDir($fileArray) {

    include('../../config/config.php');
    $DocumentRoot= $config['DocumentRoot'];

    $ftp_server= $config['ftp_site'];
    $ftp_user_name= $config['ftp_user'];
    $ftp_user_pass= $config['ftp_pwd'];

    $conn_id = ftp_connect($ftp_server); // set up basic connection
    $login_result = @ftp_login($conn_id, $ftp_user_name, $ftp_user_pass);
    ftp_pasv($conn_id, true);
    $i = 0;

    $get_current_path_to_front = str_replace('\\', '/', realpath(dirname(__FILE__))) . '/';
    $set_new_path_to_front = str_replace('\\', '/', realpath($get_current_path_to_front . '../')) . '/';

    foreach($fileArray as $src_filename){

        $src = $DocumentRoot . "/images/homepage_images/uploads/" . $src_filename;
        // $src = "../../assets/img/marketing/upload/" . $src_filename;

        if ( !file_exists( $src ) ) {
            return false;
        }
        
        $res = ftp_put($conn_id, '/mjtrends/images/homepage_images/uploads/' . $src_filename, $src, FTP_BINARY);
        $i++;
    }

    ftp_close($conn_id);

    return $res !== FALSE;
}

if($_POST['type'] == "block2") {
    include('../../models/product_model.php');
    $title = $_POST['title'];
    $product_list = getProductsListByTitle($title);
    $suggest = array();
    if(count($product_list) > 0)
    {
        foreach($product_list as $product) {
            $name = $product['cat']." ".$product['type']." ".$product['color'];
            if ($product['type'] == 'Zippers') {
                $name .= ' '.$product['length'].' '.$product['teeth_size'].' '.$product['teeth_type'].' '.
                    ($product['seperating'] ? 'separating' : 'non-separating').
                    ($product['hidden'] ? ' hidden' : '').
                    ($product['3_way'] ? ' 3-way' : '');
            }
            if ($product['type'] == 'Busks') {
                $name .= ' '.$product['length'];
            }
            $product_img = $config['cdn_url'].'/assets/images/email_template/fashion-news/source_06.png';
            if ( !empty($product['img']) ) {
                $imgs = json_decode($product['img'], true);
                $product_img = $config['cdn_url'].'/images/product/'.$product['invid'].'/'.$imgs[0]['path'].'_370x280.jpg';
            }
            $suggest[] = array(
                'id'    => $product['invid'],
                'img'   => $product_img,
                'name'  => $name,
                'title' => $product['title'],
            );
        }
    }
    
    $data['suggest'] = $suggest;

    header('Content-type: application/json');
    echo json_encode($data);
}

if($_POST['type']  == 'block3') {

    include('../../common/database.php');
    $limit = 20;
    $sql = "SELECT ID, post_title "
        . "FROM wp_posts "
        . "WHERE post_status = 'publish' AND (post_type = 'revision' OR post_type = 'post') "
        . "ORDER BY ID DESC "
        . "LIMIT ".$limit;
    $result = $mysqli->query($sql);
    while($row = $result->fetch_assoc() ){
        $res[] = $row;
    }

    header('Content-type: application/json');
    echo json_encode($res);
}

if($_POST['type'] == 'block4') {

    include('../../models/pin_model.php');
    $title = $_POST['title'];
    $pin_list = getProductsListByTitle($title);
    $suggest = array();
    if(count($pin_list) > 0)
    {
        foreach($pin_list as $pin) {
            $name = $pin['title'];

            $pin_img = $config['cdn_url'].'/assets/images/email_template/fashion-news/source_06.png';
            if ( !empty($pin['name']) ) {
                $pin_img = $config['cdn_url'].'/images/pins/'.$pin['name'];
            }
            $suggest[] = array(
                    'id'    => $pin['id'],
                    'img'   => $pin_img,
                    'name'  => $name,
                    'title' => $pin['title'],
            );
        }
    }

    $data['suggest'] = $suggest;
    header('Content-type: application/json');
    echo json_encode($data);
}

if($_POST['type'] == 'getpostimg') {

    $id = (int)$_POST['id'];
    $limit = 1;
    $res = '';
    
    $sql = "SELECT ID, post_title, guid, post_parent "
        . "FROM wp_postmeta "
        . "INNER JOIN wp_posts ON wp_postmeta.meta_value = wp_posts.ID "
        . "WHERE meta_key = '_thumbnail_id' AND post_id = " . $id . " "
        . "ORDER BY meta_value ASC "
        . "LIMIT ".$limit;

    $result = $mysqli->query($sql);
    $rows = array();
    while($row = $result->fetch_assoc() ){
        $rows[] = $row;
    }

    if($rows) {
        $res = array(
            'title' => $rows[0]['post_title'],
            'img' => $rows[0]['guid'],
            'post_id' => $rows[0]['post_parent']
        );
    }
    header('Content-type: application/json');
    echo json_encode($res);
}

if($_POST['type'] == 'preview') {
    
    $_SESSION['homepage_preview'] = 'preview';
    include('../../models/product_model.php');
    $blocks = $_POST['data'];
    $block1 = $blocks['block1'];
    $block2 = prepare_featured_products_block($blocks['block2']);
    $block3 = prepare_posts_block($blocks['block3']);
    $block4 = prepare_shop_the_look_block($blocks['block4']);
    $block5 = $blocks['block5'];
    $block6 = $blocks['block6'];

    $_SESSION['block1'] = $block1;
    $_SESSION['block2'] = $block2;
    $_SESSION['block3'] = $block3;
    $_SESSION['block4'] = $block4;
    $_SESSION['block5'] = $block5;
    $_SESSION['block6'] = $block6;
    
    header('Content-type: application/json');
    echo json_encode(true);
}

$user_id = $_SESSION['user_id'];

if($_POST['type'] == 'pushlive') {
    $_SESSION['homepage_preview'] = 'pushlive';
    include('../../models/product_model.php');
    $blocks = $_POST['data'];
    $block1 = $blocks['block1'];
    $block2 = $blocks['block2'];
    $block3 = $blocks['block3'];
    $block4 = $blocks['block4'];
    $block5 = $blocks['block5'];
    $block6 = $blocks['block6'];

    $json_block1 = json_encode($block1);
    $json_block2 = json_encode($block2);
    $json_block3 = json_encode($block3);
    $json_block4 = json_encode($block4);
    $json_block5 = json_encode($block5);
    $json_block6 = json_encode($block6);
    /* check */
    $exist_sql = "SELECT * FROM homepage WHERE user_id = '$user_id'";
    $exist_result = $mysqli->query($exist_sql);
    
    header('Content-type: application/json');
    if($exist_result->num_rows) {
        $sql = "UPDATE homepage SET block1 = ('$json_block1'), block2 = ('$json_block2'), block3 = ('$json_block3'), block4 = ('$json_block4'), block5 = ('$json_block5'), block6 = ('$json_block6') WHERE user_id = " . $user_id;
        $result = $mysqli->query($sql);
        if($result)echo json_encode(true);
        else json_decode(false);
    } else {
        $sql = "INSERT INTO homepage(user_id, block1, block2, block3, block4) VALUES ('$user_id', '$json_block1', '$json_block2', '$json_block3', '$json_block4')";
        $result = $mysqli->query($sql);
        if($result)echo json_encode(true);
        else json_decode(false);
    }
}

if($_POST['type'] == 'clear') {

    $sql  = "
    DELETE FROM
        homepage
    WHERE user_id = " . $user_id;

    $result = $mysqli->query($sql);
    
    header('Content-type: application/json');
    echo json_encode(true);
}

if($_POST['type'] == 'getlatestpostswithimg') {
    $limit = 8;

    $sql = "SELECT ID, post_title, guid, post_parent "
        . "FROM wp_postmeta "
        . "INNER JOIN wp_posts ON wp_postmeta.meta_value = wp_posts.ID "
        . "WHERE meta_key = '_thumbnail_id' "
        . "ORDER BY post_id DESC, meta_value ASC "
        . "LIMIT ".$limit;

    $result = $mysqli->query($sql);
    $rows = array();
    while($row = $result->fetch_assoc() ){
        $rows[] = $row;
    }

    header('Content-type: application/json');
    echo json_encode($rows);
}

if($_POST['type'] == 'getlatestpins') {

    include('../../models/pin_model.php');
    $pin_list = getLatestPins();

    $pins = array();
    if(count($pin_list) > 0)
    {
        foreach($pin_list as $pin) {
            $pin_img = $config['cdn_url'].'/assets/images/email_template/fashion-news/source_06.png';
            if ( !empty($pin['name']) ) {
                $pin_img = $config['cdn_url'].'/images/pins/'.$pin['name'];
            }
            $pins[] = array(
                    'id'    => $pin['id'],
                    'img'   => $pin_img,
                    'title' => $pin['title'],
            );
        }
    }
    
    header('Content-type: application/json');
    echo json_encode($pins);
}

?>