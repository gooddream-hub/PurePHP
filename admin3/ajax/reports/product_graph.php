<?php
include('../../../logic/global.php');
include('../../common/constants.php');
function product_graph()
{
    $mysqli = mysqliConn();
    $data = array();
    $errors = array();
    $products_found = FALSE;
    $cats_found = FALSE;
    $type_found = FALSE;

    $data['start'] = $_POST['start'];

    if ($_POST['start'] AND $_POST['end']) {
        /* if (!valid_mysql_date($_POST['start'])) {
            $errors[] = 'Wrong start date';
        }
        if (!valid_mysql_date($_POST['end'])) {
            $errors[] = 'Wrong end date';
        } */

        if($_POST['start'] > $_POST['end']) {
            $errors[] = 'Wrong date range';
        }

        if(count($errors) == 0) {
            
            // =====================================================================================================
            if ($_POST['products_list']) {
                $data['products_list'] = $_POST['products_list'] ? $_POST['products_list'] : '';
                $products_list = preg_split('/,/', $_POST['products_list'], -1, PREG_SPLIT_NO_EMPTY);

                /* $sql  = "
                    SELECT
                        invid, color, type, cat, sum(quant) AS quant, order_date AS dd
                    FROM orderdetails
                    LEFT JOIN custinfo ON orderdetails.custid = custinfo.custid
                    WHERE 
                        custinfo.order_date > '".$_POST['start']."'
                        AND custinfo.order_date < '".$_POST['end']."'
                        AND invid IN (".implode(',', $products_list).")
                    GROUP BY dd, invid
                    ORDER BY invid ASC
                "; */
                $sql  = "
                    SELECT
                        invid, color, type, cat, sum(quant) AS quant, order_date AS dd
                    FROM orderdetails
                    LEFT JOIN custinfo ON orderdetails.custid = custinfo.custid
                    WHERE 
                        custinfo.order_date > '".$_POST['start']."'
                        AND custinfo.order_date < '".$_POST['end']."'
                        AND invid IN (".implode(',', $products_list).")
                    GROUP BY dd, invid
                    ORDER BY dd ASC
                ";
                /* $query = $this->db->query($sql, array(
                    $_POST['start']
                    ,$_POST['end']
                    
                )); */
                $array_data = array();
                if($result = $mysqli->query($sql)){
                    while($row = $result->fetch_assoc() ){
                        $array_data[] = array_map('utf8_encode', $row);
                    } 
                }	
                
                if (count($array_data) > 0) {
                    $products = array();
                    $products_names = array();
                    $products_colors_list = array(
                        1 => '#33FF00',
                        2 => '#FF0000',
                        3 => '#FF9900',
                        4 => '#33CCFF',
                        5 => '#990033',
                        6 => '#000000',
                        7 => '#6600FF',
                        8 => '#FF3300'
                    );
                    
                    foreach ( $array_data as $k => $v) {
                        $products[$v['invid']][$v['dd']] = $v;
                        $products_names[$v['invid']] = $v['color']." ".$v['type'];
                    }

                    $data['products'] = $products;
                    $data['products_names'] = $products_names;
                    $data['products_colors_list'] = $products_colors_list;

                    $products_found = TRUE;
                } 
            }

            // =====================================================================================================

           if ($_POST['type_list']) {
                $data['type_list'] = $_POST['type_list'] ? $_POST['type_list'] : '';
                $type_list = preg_split('/,/', $_POST['type_list'], -1, PREG_SPLIT_NO_EMPTY);

                $filter = 'AND ( ';
                $i = 0;
                foreach ($type_list as $k) {
                  //$k = $this->db->escape_like_str(trim($k));
                  if(++$i == 1) {
                        $filter .= " ";
                  } else {
                        $filter .= " OR ";
                  }
                  $filter .= " orderdetails.type = '{$k}' ";
                }
                $filter .= ' )';

                $sql1  = "
                    SELECT
                        color, type, cat, sum(quant) AS quant, order_date AS dd
                    FROM orderdetails
                    LEFT JOIN custinfo ON orderdetails.custid = custinfo.custid
                    WHERE
                        custinfo.order_date > '".$_POST['start']."'
                        AND custinfo.order_date < '".$_POST['end']."'
                        
                            ".str_replace("-", " ", $filter)."
                        
                    GROUP BY dd, type
                    ORDER BY type ASC
                ";

               /*  $query = $this->db->query($sql, array(
                    $_POST['start']
                    ,$_POST['end']

                )); */

                //echo $this->db->last_query();
                $array_data1 = array();
                if($result1 = $mysqli->query($sql1)){
                    while($row1 = $result1->fetch_assoc() ){
                        $array_data1[] = array_map('utf8_encode', $row1);
                    } 
                }	
                if (count($array_data1) > 0) {

                    $type = array();
                    $type_names = array();
                    $type_colors_list = array(
                        1 => '#66AAFF',
                        2 => '#BA3F50',
                        3 => '#F3BB00',
                        4 => '#3300FF'
                    );

                    foreach ( $array_data1 as $k => $v) {
                        $type[$v['type']][$v['dd']] = $v;
                        $type_names[$v['type']] = $v['type'];
                    }

                    $data['type'] = $type;
                    $data['type_names'] = $type_names;
                    $data['type_colors_list'] = $type_colors_list;
                    $data['start'] = $_POST['start'];
               
                    $type_found = TRUE;
                }
            }
            if ($_POST['cats_list']) {
                $data['cats_list'] = $_POST['cats_list'] ? $_POST['cats_list'] : '';
                $cats_list = preg_split('/,/', $_POST['cats_list'], -1, PREG_SPLIT_NO_EMPTY);

                $filter = 'AND ( ';
                $i = 0;
                foreach ($cats_list as $k) {
                  //$k = $this->db->escape_like_str(trim($k));
                  if(++$i == 1) {
                        $filter .= " ";
                  } else {
                        $filter .= " OR ";
                  }
                  $filter .= " orderdetails.cat = '{$k}' ";
                }
                $filter .= ' )';

                $sql2  = "
                    SELECT
                        color, type, cat, sum(quant) AS quant, order_date AS dd
                    FROM orderdetails
                    LEFT JOIN custinfo ON orderdetails.custid = custinfo.custid
                    WHERE
                        custinfo.order_date > '".$_POST['start']."'
                        AND custinfo.order_date < '".$_POST['end']."'
                        
                            ".str_replace("-", " ", $filter)."
                        
                    GROUP BY dd, cat
                    ORDER BY cat ASC
                ";

                /* $sql2  = "
                    SELECT
                        invid, color, type, cat, sum(quant) AS quant, order_date AS dd
                    FROM orderdetails
                    LEFT JOIN custinfo ON orderdetails.custid = custinfo.custid
                    WHERE 
                        custinfo.order_date > '".$_POST['start']."'
                        AND custinfo.order_date < '".$_POST['end']."'
                        AND invid IN (".implode(',', $cats_list).")
                    GROUP BY dd, invid
                    ORDER BY invid ASC
                "; */

               /*  $query = $this->db->query($sql, array(
                    $_POST['start']
                    ,$_POST['end']

                )); */

                //echo $this->db->last_query();
                $array_data2 = array();
                if($result2 = $mysqli->query($sql2)){
                    while($row2 = $result2->fetch_assoc() ){
                        $array_data2[] = array_map('utf8_encode', $row2);
                    } 
                }	
                if (count($array_data2) > 0) {

                    $cats = array();
                    $cats_names = array();
                    $cats_colors_list = array(
                        1 => '#66AAFF',
                        2 => '#BA3F50',
                        3 => '#F3BB00',
                        4 => '#3300FF'
                    );

                    foreach ( $array_data2 as $k => $v) {
                        $cats[$v['cat']][$v['dd']] = $v;
                        $cats_names[$v['cat']] = $v['cat'];
                    }

                    $data['cats'] = $cats;
                    $data['cats_names'] = $cats_names;
                    $data['cats_colors_list'] = $cats_colors_list;
                    $data['start'] = $_POST['start'];
               
                    $cats_found = TRUE;
                }
            }

            if ($cats_found == FALSE AND $products_found == FALSE AND $type_found == FALSE)
            {
                $errors[] = 'Nothing Found.';
            }
            // ============================
        }
    }
    else {
        $errors[] = 'Please select date range';
    }
    $data['errors'] = implode('<br />', $errors);
    echo json_encode($data);
}
product_graph();
?>