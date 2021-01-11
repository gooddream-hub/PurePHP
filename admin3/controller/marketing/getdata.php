<?php 
include('../../common/database.php');
include('../../config/config.php');
$suggest = array();
$pins = array();

$sql = "SELECT it.*, im.*
        FROM inven_mj im
        LEFT JOIN inven_traits it ON im.invid = it.invid
        LIMIT 0,1000";

if($result = $mysqli->query($sql)){
    while($row = $result->fetch_assoc() ){
        $name = $row['cat'] . " " . $row['type'] . " " . $row['color'];
        $label = $row['cat']." ".$row['type']." ".$row['color']." ".$row['cat']." ".$row['color']." ".$row['type']." ".$row['type']." ".$row['cat']." ".$row['color']." ".$row['type']." ".$row['color']." ".$row['cat']." ".$row['color']." ".$row['type']." ".$row['cat']." ".$row['color']." ".$row['cat']." ".$row['type'];
        $id = $row['invid'];
        $suggest[] = array(
            'name' => $name,
            'label' => $label,
            'id' => $id,
            'is_pattern' => 0
        );
    };
}

$sql = "SELECT * FROM pattern_style LIMIT 0,1000";

if($result = $mysqli->query($sql)){
    while($row = $result->fetch_assoc() ){
        $name = $row['gender'] . " " . $row['style'] ;
        $label = $row['gender']." ".$row['style']." ".$row['style']." ".$row['gender'];
        $id = $row['styleID'];
        $suggest[] = array(
            'name' => $name,
            'label' => $label,
            'id' => $id,
            'is_pattern' => 1
        );
    };
}

$sql = 'SELECT * FROM `image_pins` ORDER BY `id` DESC LIMIT 0, 6';
if($result = $mysqli->query($sql)){
    while($row = $result->fetch_assoc() ){
        $pins[] = array(
            'name' => $row['name'],
            'invid' => $row['invid'],
            'desc' => $row['desc']
        );
    };
}

$pins_suggest = array();
$pins_data = array();
$sql = 'SELECT * FROM `image_pins` ORDER BY `id` DESC LIMIT 0, 600';
if($result = $mysqli->query($sql)){
    while($row = $result->fetch_assoc() ){
        $pins_data[] = array(
            'name' => $row['name'],
            'invid' => $row['invid'],
            'desc' => $row['desc'],
            'id' => $row['id'],
            'title' => $row['title']
        );
    };
}

if (count($pins_data) > 0) {
    foreach ($pins_data as $pin) {
        if (is_numeric($pin['invid'])) {
            $pin['invid'] = array(
                array(
                    'product' => $pin['invid'],
                    'quantity' => '1',
                )
            );
        } else {
            $pin['invid'] = json_decode($pin['invid'],true);
        }
        if(is_array($pin['invid'])){
            foreach($pin['invid'] as $key=>$item) {
                $style_id = $item['product'];
                if (isset($item['is_patterns']) && $item['is_patterns']) {
                    // $this->load->model("Pattern_style_model");
                    // $pattern = $this->Pattern_style_model->getPatternById($item['product']);
                    $sql = 'SELECT * FROM `pattern_style` WHERE StyleID = "$style_id" LIMIT 0, 1';
                    if($result = $mysqli->query($sql)){
                        while($pattern = $result->fetch_assoc() ){
                            $name = $pattern['gender'] . " " . $pattern['style'];
                        };
                    }
                    
                } else {
                    // $product = $this->Product_model->getProductById($item['product']);
                    $sql = 'SELECT * FROM `inven_mj` WHERE invid = "$style_id" LIMIT 0, 1';
                    if($result = $mysqli->query($sql)){
                        while($product = $result->fetch_assoc() ){
                            $name = (isset($product['cat'])?$product['cat']:'') . " " . (isset($product['type'])?$product['type']:'') . " " . (isset($product['color'])?$product['color']:'');
                            if(isset($product['type']) && $product['type'] == 'Zippers'){
                                $name .= ' '. (isset($product['length'])?$product['length']:'') .' '. (isset($product['teeth_size'])?$product['teeth_size']:'') .' '. (isset($product['teeth_type'])?$product['teeth_type']:'') .' '.
                                        ((isset($product['seperating'])&&$product['seperating'])?'separating' : 'non-separating') .
                                        ((isset($product['hidden'])&&$product['hidden'])?' hidden' : '') .
                                        ((isset($product['3_way'])&&$product['3_way'])?' 3-way' : '');
                            }
                        };
                    }
                    
                }
                $pin['invid'][$key]['name'] = $name;
            }
        }

        $pins_suggest[] = array(
                'title' => $pin['title'],
                'label' => $pin['title'],
                'id' => $pin['id'],
                'desc' => $pin['desc'],
                'name' => $pin['name'],
                'invid' => $pin['invid'],
                'url' => $config['cdn_url'] . $config['pin']['img_url'] . strtolower($pin['name'])
        );
    }
}
// echo "<pre>";
// print_r($pins_suggest);
// die;

?>