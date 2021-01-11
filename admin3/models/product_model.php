<?php
include('../../common/database.php');

function getProductsListByTitle($search_term, $limit = 1000) {
    include('../../common/database.php');
    $fields = array(
        "title",
        "color",
        "type",
        "CONCAT_WS(' ',color, type)",
        "teeth_type"
    );
    $search_term = addslashes(trim($search_term));
    $keywords = explode(' ', $search_term);
    foreach($keywords as $index=>$keyword) {
        $keywords[$index] = "like '%$keyword%'";
    }
    $condition = "";
    foreach($fields as $field) {
        if ($condition != "") {
            $condition .= ' OR ';
        }
        foreach($keywords as $index=>$keyword) {
            if ($index == 0) {
                $condition .= "$field $keyword";
            } else {
                $condition .= " AND $field $keyword";
            }
        }
    }
    $sql = "SELECT it.*, im.*
            FROM inven_mj im
            LEFT JOIN inven_traits it ON im.invid = it.invid
            WHERE $condition
            LIMIT 0,$limit";
    $result = $mysqli->query($sql);
    if($result) {
        while($row = $result->fetch_assoc() ){
            $res[] = $row;
        }
    }
    return $res;
}

function prepare_featured_products_block($fpblock)
{

    include('../../common/database.php');
    include('../../config/config.php');
    $product_ids = array();
    foreach($fpblock as $block) {
        if(!empty($block['item_id'])) {
            $product_ids[] = $block['item_id'];
        }
    }

    $sql = "SELECT * FROM inven_mj WHERE invid IN (".implode(',',$product_ids).") AND active = '1'";

    $prods = array();
    $result = $mysqli->query($sql);
    if($result) {
        while($row = $result->fetch_assoc() ){
            $prods[$row['invid']] = $row;
        }
    }

    foreach ($fpblock as $in => $block) {
        if(!empty($block['item_id'])) {
            $item_id = $block['item_id'];
            if(!empty($prods) && $prods[$item_id]) {
                $prod = $prods[$item_id];
                $fpblock[$in]['cat'] = ($prod['cat']) ? $prod['cat'] : 'None';
                $fpblock[$in]['retail'] = ($prod['retail']) ? $prod['retail'] : 'N/A';
                $fpblock[$in]['saleprice'] = ($prod['saleprice']) ? $prod['saleprice'] : '';
                $fpblock[$in]['is_out_of_stock'] = ($prod['invamount'] <= 0);
                $fpblock[$in]['link'] = $config['frontend_url'].'products.'.$prod['color'].','.$prod['type'].','.$prod['cat'];
            }
        }
    }
    return $fpblock;
}

function prepare_posts_block($posts_block)
{
    // get posts
    include('../../common/database.php');
    $post_ids = array();
    foreach($posts_block as $block) {
        if (!empty($block['post_id'])) {
            $post_ids[] = $block['post_id'];
        }
    }

    $sql = "SELECT wp_posts.*, COUNT(`wp_comments`.comment_ID) as comments_cnt 
            FROM wp_posts 
            LEFT JOIN wp_comments ON wp_comments.comment_post_ID = wp_posts.ID 
            WHERE ID IN (".implode(',',$post_ids).")
            GROUP BY wp_posts.ID";

    $posts = array();
    $result = $mysqli->query($sql);
    if($result) {
        while($row = $result->fetch_assoc() ){
            $posts[$row['ID']] = $row;
        }
    }

    foreach($posts_block as $index => $block) {
        if (!empty($block['post_id'])) {
            $post_id = $block['post_id'];
            if(!empty($posts) && !empty($posts[$post_id]) && $posts[$post_id]) {
                $post = $posts[$post_id];
                $posts_block[$index]['post_date_day'] = ($post['post_date']) ? date('d', strtotime($post['post_date'])) : '';
                $posts_block[$index]['post_date_month'] = ($post['post_date']) ? date('M', strtotime($post['post_date'])) : '';
                $posts_block[$index]['post_url'] = ($post['guid']) ? $post['guid'] : '#';
                $posts_block[$index]['post_title'] = ($post['post_title']) ? $post['post_title'] : 'No title';
                $posts_block[$index]['post_preview'] = ($post['post_content']) ? substr(strip_tags($post['post_content']), 0, 90) . '[...]' : '';
                $posts_block[$index]['comments_cnt'] = ($post['comments_cnt']) ? $post['comments_cnt'] : '0';
            }
        }
    }
    return $posts_block;
}

function prepare_shop_the_look_block($shop_block)
{
    include('../../config/config.php');
    foreach($shop_block as $index=>$block) {
        $parts = explode('/',$block['src']);
        $shop_block[$index]['link'] = $config['frontend_url'].'pins.php?name='.end($parts);
    }

    return $shop_block;
}

function getProductsById($id)
{
    include('../../common/database.php');
    $sql = "Select * from inven_mj where invid =" . $id;
    $result = $mysqli->query($sql);
    if($result) {
        while($row = $result->fetch_assoc() ){
            $res[] = $row;
        }
    }
    return $res;
}

function getTypeCatsList($search_term = '', $limit = 1000) {
    include('../../common/database.php');
    if ($search_term) {
        $sql = "SELECT CONCAT(`im`.`type`, ' ', `im`.`cat`) as category
                FROM `inven_mj` as im
                WHERE CONCAT(`im`.`type`, ' ', `im`.`cat`) like '%$search_term%'
                GROUP BY category
                LIMIT 0, $limit";
    } else {
        $sql = "SELECT CONCAT(`im`.`type`, ' ', `im`.`cat`) as category
                FROM `inven_mj` im
                GROUP BY `category`
                LIMIT 0, $limit";
    }

    $result = $mysqli->query($sql);
    if($result) {
        while($row = $result->fetch_assoc() ){
            $res[] = $row;
        }
    }
    return $res;
}

function getProductsByCat($category, $limit=40, $offset=0) {
    include('../../common/database.php');
    $sql = "SELECT * FROM `inven_mj` as im WHERE CONCAT(`im`.`type`, ' ', `im`.`cat`) = '" . $category ."'ORDER BY `im`.`color` LIMIT " . $offset . " ," . $limit;
    
    $result = $mysqli->query($sql);
    if($result) {
        while($row = $result->fetch_assoc() ){
            $res[] = $row;
        }
    }
    return $res;
}

function getProductById($id, $action_param= '')
{
    include('../../common/database.php');
    $sql = "Select * from inven_mj where invid =" . $id;

    $res = array();
    $result = $mysqli->query($sql);
    if($result) {
        while($row = $result->fetch_assoc() ){
            $res[] = $row;
        }
    }

    if (!empty($res[0])) {
        if ( $action_param == "img_into_struct" ) {
            $ImgArray= json_decode($res[0]['img']);
            return $ImgArray;
        }
        return $res[0];
    }
    return array();
}

function UpdateProduct($id, $DataArray, $current_is_insert) {
    include('../../common/database.php');
    if ($current_is_insert == 1) {
        $sql = "INSERT INTO inven_mj (invamount) values " . $DataArray['invamount'];
        if ($mysqli->query($sql)) {
            return $id;
        }
    } else {
        $invamount = $DataArray['invamount'];
        $sql = "UPDATE inven_mj SET `invamount` = ('$invamount') WHERE invid = ('$id')";
        if ($mysqli->query($sql)) {
            return $id;
        }
    }
}
