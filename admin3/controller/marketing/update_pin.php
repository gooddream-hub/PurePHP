<?php
include('./pinterest.php');

error_reporting(E_ERROR);
$response = array();

$pin_id  = empty($_POST['pin_id']) ? null : $_POST['pin_id'];
$title = empty($_POST['title']) ? null : $_POST['title'];
$desc  = empty($_POST['desc']) ? null : $_POST['desc']; 
$name  = empty($_POST['name']) ? null : $_POST['name']; 
$is_patterns = empty($_POST['product_is_pattern']) ? null : $_POST['product_is_pattern']; 

echo "pin id is: ".$pin_id;

include('../../models/pin_model.php');
$pin = pin_model_getPinById($pin_id);
if (!$pin) {
    echo json_encode(array('error'=>'Pin not found'));
    return;
}

$url  = empty($_POST['img_url']) ? null : $_POST['img_url'];

$old_url = $config['cdn_url'] . $config['pin']['img_url'] . strtolower($pin['name']);
$same_pic = ($old_url == $url && $pin['name'] == $name) ? true : false;

if (!$same_pic) {
    $size = getimagesize($url);
    if (strpos($size['mime'], 'image') === false) {
        echo json_encode(array('error'=>'The Image url must be URL to image.'));
        return;
    }
}
if (filter_var($url, FILTER_VALIDATE_URL)) {
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

    if (!$same_pic) {
        $parts = pathinfo($name);

        $i = 1;
        while (file_exists($config['pin']['img_path'] . '/' . $name)) {
            $name = $parts['filename'] . '_' . $i . '.' . $parts['extension'];
            $i++;
        }
        if (file_put_contents($config['pin']['img_path'].'/'.$name, file_get_contents($url))) {
            $cdn_files = array($name);

            $thumb_name = create_thumb($config['pin']['img_path'] . '/' . $name, $config['pin']['img_path'], 150);
            if ($thumb_name) {
                $cdn_files[] = $thumb_name;
            }

            CopyFileToFTPDir($cdn_files, 'pin');

            foreach ($cdn_files as $file) {
                if (!file_get_contents($config['cdn_url'] . $config['pin']['img_url'] . $file)) {
                    $response['error'] = 'Can not upload to CDN';
                    break;
                }
            }
        }
    }

    if (!$response) {
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
        $response = pin_model_update($pin_id, $row);
    }
}
echo json_encode($response);

?>