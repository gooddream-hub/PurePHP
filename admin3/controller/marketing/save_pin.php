<?php
include('./pinterest.php');
// error_reporting(0);
$response = array();

$img_url = empty($_POST['img_url']) ? null : $_POST['img_url'];
if(!empty($_POST['img_url'])){
    $size = getimagesize($img_url);
}
echo $img_url;

if(strpos($size['mime'], 'image') === false){
    $response['error'] = 'The Image url must be URL to image.';
} elseif(filter_var($img_url, FILTER_VALIDATE_URL)){
    $title = empty($_POST['title']) ? null : $_POST['title'];
    $desc  = empty($_POST['desc']) ? null : $_POST['desc'];
    $name  = empty($_POST['name']) ? null : $_POST['name'];

    $post_instantly = empty($_POST['post_instantly']) ? null : $_POST['post_instantly'];
    
    $board_id  = empty($_POST['pinterest_board']) ? null : $_POST['pinterest_board'];

    $is_add_to_facebook = empty($_POST['add_to_facebook']) ? null : $_POST['add_to_facebook'];
    $is_add_to_twitter = empty($_POST['add_to_twitter']) ? null : $_POST['add_to_twitter'];
    $is_add_to_flickr = empty($_POST['add_to_flickr']) ? null : $_POST['add_to_flickr'];
    $is_add_to_googleplus = empty($_POST['add_to_googleplus']) ? null : $_POST['add_to_googleplus'];

    $is_patterns = empty($_POST['product_is_pattern']) ? null : $_POST['product_is_pattern'];
    // pattern quantity validation
    if ($is_patterns) {
        $patterns_quantity = 0;
        foreach ($is_patterns as $item) {
            if ($item == 1) {
                $patterns_quantity++;
            }
        }
        if ($patterns_quantity > 1) {
            echo json_encode(["error" => "You cannot add more than one pattern in product list."]);
            return;
        }
    }
    $parts = pathinfo($name);
    
    $i = 1;
    while (file_exists($config['pin']['img_path'].'/'.$name)) {
        $name = $parts['filename'].'_'.$i.'.'.$parts['extension'];
        $i++;
    }
////////////////////////////////////////////////////////////////////////////
    if (file_put_contents($config['pin']['img_path'].'/'.$name, file_get_contents($img_url))) {
        $cdn_files = array($name);
        
        $thumb_name = create_thumb($config['pin']['img_path'].'/'.$name, $config['pin']['img_path'], 150);
        if ($thumb_name) {
            $cdn_files[] = $thumb_name;
        }
        
        $r = CopyFileToFTPDir($cdn_files, 'pin');
        
        foreach ($cdn_files as $file) {
            if (!file_get_contents($config['cdn_url'].$config['pin']['img_url'].$file)) {
                $response['error'] = 'Can not upload to CDN';
                break;
            }
        }

        if (!$response) {
            include('../../models/pin_model.php');
            $row = array(
                'title' => $title,
                '`desc`'  => $desc,
                'name'  => $name,
            );

            if ($prod_id = empty($_POST['product_id']) ? null : $_POST['product_id']) {
                $quantities = empty($_POST['product_quantity']) ? null : $_POST['product_quantity'];
                $products = array();
                if ($prod_id) {
                    foreach ($prod_id as $k => $id) {
                        $products[] = array('product' => $id, 'quantity' => $quantities[$k], 'is_patterns' => $is_patterns[$k]);
                    }
                }
                $row['invid'] = json_encode($products);
            }
            if ($cat = empty($_POST['product_cat_id']) ? null : $_POST['product_cat_id']) {
                $row['cat'] = $cat;
            }

            $response = pin_model_save($row);

            $pin_link = $config['frontend_url'].'pins.php?name='.pathinfo($name, PATHINFO_FILENAME);
            $desc .= ". DIY the look yourself: ".$pin_link;

            // $p = new myPinterest($config['pin']['username'], $config['pin']['password']);
            // $p->login();
            // $p->post_img($url, $board, $desc, $pin_link);
            

            if ($post_instantly) {
                $pinterest = new PinterestAPI($appid, $secret);
                // $pinterest->auth->setOAuthToken( $auth_token );
                // $resp = $pinterest->pins->create(array(
                //     "note"          => $desc,
                //     "image_url"     => $img_url,
                //     "board"         => $board_id,
                //     "link"          => $pin_link
                // ));
                $response['resp'] = $resp;
            } else {
                delay_pinterest_posting([
                    'type'    => 'pinterest',
                    'img'     => $img_url,
                    'link'    => $pin_link,
                    "board_id"   => $board_id,
                    'message' => $desc
                ]);
            }

            $title_with_link = $title.". DIY the look yourself: ".$pin_link;
            
            if($is_add_to_facebook) {
                publish_to_social($img_url, $title_with_link, 'facebook');
            }

            if($is_add_to_twitter) {
                publish_to_social($img_url, $title_with_link, 'twitter');
            }

            if($is_add_to_flickr) {
                publish_to_social($img_url, $title_with_link, 'flickr');
            }

            if($is_add_to_googleplus) {
                publish_to_social($img_url, $title_with_link, 'googleplus');
            }
        }
    } else {
        $response['error'] = 'Can\'t push image in path: '. $config['pin']['img_path'].'/'.$name;
    }
}


?>