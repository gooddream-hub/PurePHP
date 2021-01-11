<?php

include('../../common/database.php');
include('../../config/config.php');
include('../../libraries/Pinterestnew/autoload.php');
include('../../models/product_model.php');
ini_set('max_execution_time', 300);
include('../../libraries/etsy/MY_Etsy.php');

if($_POST['type'] == 'products') {

    $my_etsy = new MY_Etsy();
    $data = array();
    $my_etsy->getAccessToken(SITE_URL . 'admin3/view/' . 'inventory/etsy_cat/products');
    $etsy_cats = $my_etsy->getTaxanomy();
    $data['etsy_cats'] = $etsy_cats;

    if(isSet($_POST['category']) && $_POST['category']) {
        $category = $_POST['category'];
        $products = getProductsByCat($category);
        $data['products'] = $products;
        $data['categories'] = $category;
    }
    echo json_encode($data);
}

if($_POST['type'] == 'getcatslist') {
    $type = $_POST['title'];
    $cat_list = getTypeCatsList($type);
    $suggest = array();
    if(count($cat_list) > 0) {
        $is_zippers = 0;
        foreach($cat_list as $cat) {
            $suggest[] = array(
                'name' => $cat['category'],
                'id'   => $cat['category']
            );
        }
    }
    
    $data['suggest'] = $suggest;
    echo json_encode($data);
}

if($_POST['type'] == 'category') {
    $data = array();
    if($category = $_POST['category']) {
        $data['category'] = $category;
    }
    echo json_encode($data);
}

if($_POST['type'] == 'import') {
    $listings = array();
    include('../../libraries/etsy/etsy_helper.php');
    foreach ($_POST['listings'] as $invid => $listing) {
        $listing_data = array(
            'category_id'           => 0,
            'taxonomy_id'           => $_POST['category_id'],
            'title'                 => $listing['title'],
            'description'           => etsy_descr_text($listing['descr'], $listing['features']),
            'price'                 => (float)$listing['saleprice'] ? (float)$listing['saleprice'] : (float)$listing['retail'],
            'quantity'              => $listing['invamount'] ? (int)$listing['invamount'] : 1,
            "who_made"              => "collective", //collective i_did
            "item_weight"           => round($listing['weight'] * 16),
            "item_weight_units"     => 'oz',
            "item_length"           => $listing['minlength'],
            "item_width"            => $listing['minwidth'],
            "item_height"           => $listing['minheight'],
            "item_dimensions_unit"  => 'in',
            "is_supply"             => 'true',
            'is_customizable'		=> 'false',
            "state"                 => 'active',
            "when_made"             => date("Y")."_".date("Y"),// 'made_to_order',
            'shipping_template_id'  => !empty($listing['shipping_templates']) ? $listing['shipping_templates'] : "9580938680",
        );
        $listings[$invid]['listing_data'] = $listing_data;
        $listings[$invid]['listing_img'] = (isset($listing['img'])?$listing['img']:array());
    }

    $my_etsy = new MY_Etsy();
    $my_etsy->getAccessToken(SITE_URL . 'admin3/view/' . 'inventory/etsy_cat/import');

    if($listings) {
        $resp['access'] = 'success';
        $resp['msg'] = 'Start add products';
        foreach ($listings as $invid => $value) {
            $valid = $my_etsy->validateListing($value);
            if($valid['is_valid']) {                
                $res = $my_etsy->postListing($invid, $value);                
                $resp['info'][$invid] = $res[$invid];                
                usleep(400000);
            } else {
                $resp['info'][$invid]['data'] = 'error';
                $resp['info'][$invid]['msg'] = 'Please check fields: '. implode(', ', $valid['errors']);
                $resp['info'][$invid]['errors'] = $valid['errors'];
                print_r('error in here');exit;
            }
        }        
    } else {
        $resp['access'] = 'error';
        $resp['msg'] = 'No any products found for add to etsy.';
    }
    
    echo json_encode($resp);
    exit;
}