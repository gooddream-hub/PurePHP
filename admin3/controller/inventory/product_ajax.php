<?php 
include('../../common/database.php');
include('../../config/config.php');
include('../../common/constants.php');


//setProd function
if($_POST['type'] == "setProd"){
    $id = $_POST['id'];
    $res = array();
    $sql = "SELECT * FROM inven_purch2 WHERE invid = '$id'";
    $result = $mysqli->query($sql);
    
    while($row = $result->fetch_assoc() ){
        $res[] = $row;
    }
    
    echo json_encode($res);
}

//CopyImagesFromSrcProductToNewProduct function
if($_POST['type'] == "CopyImagesFromSrcProductToNewProduct"){
    $src_invid = $_POST['src_invid'];
    $session_id = $_POST['session_id']; //oflkl1veqbrrqkmvonq272vgg1

    $thumbs_sizes_list= $config['thumbs_sizes_list'];
    $DocumentRoot= $config['DocumentRoot'];
    $config_images_ext= $config['images_ext'];

    $temp_src_config_uploads_products_dir= '/images/product/' . $src_invid;
    $dst_config_uploads_products_dir= '/images/product/temp/product-' . $session_id;

    // $product_images_array= $this->product_model->getProductById($src_invid, "img_into_struct");

    $product_images_array= getProductById($src_invid);

    if ( !file_exists( $DocumentRoot . '/images/product/temp' ) ) {
        mkdir( $DocumentRoot . '/images/product/temp' );
    }
    if ( !file_exists( $DocumentRoot . $dst_config_uploads_products_dir ) ) {
        mkdir( $DocumentRoot . $dst_config_uploads_products_dir );
    }

    $filesMovedCount= 0;
    if(!empty($product_images_array)){
        foreach( $product_images_array as $product_image ) {
            foreach( $thumbs_sizes_list as $thumbs_size_title => $thumbs_size ) {
                $srcThumbnailFilename= $DocumentRoot . $temp_src_config_uploads_products_dir . DIRECTORY_SEPARATOR . urldecode($product_image->path) . getThumbnailExt( $thumbs_size['width'], $thumbs_size['height'] ) . '.' . $config_images_ext;
                $dstThumbnailFilename= $DocumentRoot . $dst_config_uploads_products_dir . DIRECTORY_SEPARATOR . urldecode($product_image->path) . getThumbnailExt( $thumbs_size['width'], $thumbs_size['height'] ) . '.' . $config_images_ext;
                if ( file_exists($srcThumbnailFilename) ) {
                    $Res= copy( $srcThumbnailFilename, $dstThumbnailFilename);
                }
            }
            $filesMovedCount++;
        }
    }

    $_SESSION['newproduct_images_list']= $product_images_array;
    header('Content-type: application/json');
    echo json_encode(array('ErrorMessage' => '', 'ErrorCode' => 0, 'src_invid'=> $src_invid, 'filesMovedCount'=> $filesMovedCount, 'product_images_array'=>$_SESSION['newproduct_images_list'] ) );
}

//imagesload function
if($_POST['type'] == "image_load"){
    $id = $_POST['current_inv_id'];
    $session_id = $_POST['session_id'];

    $config_images_ext = $config['images_ext'];
    if ( strtolower($id) == "new") {
        $config_uploads_products_dir= '/images/product/temp/product-' . $session_id;
    } else {
        $config_uploads_products_dir= '/images/product/' . $id;
    }
    $config_images_base_url= SITE_URL;
    // $config_images_base_url= $config['DocumentRoot'];
    // $this->load->model('product_model');
    
    if ( strtolower($id) == "new") {
        if($_POST['new_reload'] == "new") {
            $product_images_array= array();
            $A= isset($_POST['newproduct_images_list']) ? $_POST['newproduct_images_list'] : array();
            foreach( $A as $key=> $product_name ) {
                $NewImage = new stdClass;
                $NewImage->path= $product_name;
                $pattern = '/-/';
                $NewImage->alt= preg_replace($pattern, ' ', $product_name);
                $product_images_array[]= $NewImage;
            }
        } else {
            $product_images_array= array();
            $A= isset($_POST['newproduct_images_list']) ? $_POST['newproduct_images_list'] : array();
            foreach( $A as $key=> $product_image ) {
                $NewImage = new stdClass;
                if ( is_object($product_image) ) {
                  $NewImage->path= $product_image->path;
                  $NewImage->alt= $product_image->alt;
                }
                if ( is_array($product_image) ) {
                    $NewImage->path= $product_image['path'];
                    $NewImage->alt= $product_image['alt'];
                }
                $product_images_array[]= $NewImage;
            }
        }
    } else {
        $product_images_array= getProductById($id);
    }
    
    echo json_encode(array('product_images_array'=>$product_images_array, 'rows_count' => count($product_images_array),
    'config_uploads_products_dir'=> $config_uploads_products_dir, 'config_images_base_url'=>$config_images_base_url, 'config_images_ext'=>$config_images_ext));
}

if($_POST['type'] == "save_image_path_alt"){

    $config_images_ext= $config['images_ext'];
    $session_id = $_POST['session_id'];
    $original_filename = $_POST['original_file_name'];
    $new_path_value = $_POST['pathValue'];
    $new_alt_value = $_POST['altValue'];
    $id = $_POST['current_id'];

    $oldArray = array();
    $newArray = array();

    if ( strtolower($id) == "new") {
        $config_uploads_products_dir= 'images/product/temp/product-' . $session_id;
        $product_images_array= isset($_SESSION['newproduct_images_list']) ? $_SESSION['newproduct_images_list'] : array();
    } else {
        $product_images_array= getProductById($id);
        $config_uploads_products_dir= 'images/product/' . $id;
    }
    foreach( $product_images_array as $key=> $product_image ) {
        if ( is_object($product_image) ) {
            if ( $product_image->path == urldecode($original_filename) ) {
                $product_images_array[$key]->path = urldecode($new_path_value);
                $product_images_array[$key]->alt = urldecode($new_alt_value);
                break;
            }
        }
        if ( is_array($product_image) ) {
            if ( $product_image['path'] == urldecode($original_filename) ) {
                $product_images_array[$key]['path'] = urldecode($new_path_value);
                $product_images_array[$key]['alt'] = urldecode($new_alt_value);
                break;
            }
        }
    }
    $DocumentRoot= $config['DocumentRoot'];
    $full_directory_path= $DocumentRoot . DIRECTORY_SEPARATOR . $config_uploads_products_dir. DIRECTORY_SEPARATOR;

    if ( strtolower($id) == "new") {
        $_SESSION['newproduct_images_list']= $product_images_array;
    } else {
        UpdateProduct($id, array('img'=>json_encode(  $product_images_array )) ,0);
        // $Res= $this->product_model->UpdateProduct( $id, array('img'=>json_encode(  $product_images_array )) ,0); //invid, dataArray, current_is_insert
    }

    if ( file_exists($full_directory_path . urldecode($original_filename) . '.' . $config_images_ext) ) {
        $Res= rename( $full_directory_path . urldecode($original_filename) . '.' . $config_images_ext, $full_directory_path . urldecode($new_path_value) . '.' . $config_images_ext );
        $src_filename= urldecode($original_filename) . '.' . $config_images_ext;
        $dest_filename= urldecode($new_path_value) . '.' . $config_images_ext;
        
        if ( strtolower($id) != "new") { // Do not copy temporary files to ftp
            // $fileArray[] = array("src_filename" => $src_filename, "dest_filename" => $dest_filename);
            $oldArray[] = $src_filename;
            $newArray[] = $dest_filename;
        }
    }

    $thumbs_sizes_list= $config['thumbs_sizes_list'];
    
    foreach( $thumbs_sizes_list as $thumbs_size_title => $thumbs_size ) { //update local files first
        if ( file_exists($full_directory_path . urldecode($original_filename) . getThumbnailExt( $thumbs_size['width'], $thumbs_size['height'] ) . '.' . $config_images_ext) ) {
            $Res= rename( $full_directory_path . urldecode($original_filename) . getThumbnailExt( $thumbs_size['width'], $thumbs_size['height'] ) . '.' . $config_images_ext,
            $full_directory_path . urldecode($new_path_value) . getThumbnailExt( $thumbs_size['width'], $thumbs_size['height'] ) . '.' . $config_images_ext );

            $src_filename= urldecode($original_filename) . getThumbnailExt( $thumbs_size['width'], $thumbs_size['height'] ) . '.' . $config_images_ext;
            $dest_filename= urldecode($new_path_value) . getThumbnailExt( $thumbs_size['width'], $thumbs_size['height'] ) . '.' . $config_images_ext;	

            // $fileArray[] = array("src_filename" => $src_filename, "dest_filename" => $dest_filename);
            $oldArray[] = $src_filename;
            $newArray[] = $dest_filename;
        }
    }
    
    if ( strtolower($id) != "new") { // Do not copy temporary files to ftp
            $FtpRes= RenameFileToFTPDir( $id, $oldArray, $newArray);
    }
    echo $FtpRes;
    header('Content-type: application/json');
    echo json_encode(array('ErrorMessage' => '', 'ErrorCode' => 0 ) );
}

if($_POST['type'] == "delete_image"){
    $id = $_POST['_id'];
    $filename = $_POST['file_name'];
    $session_id = $_POST['session_id'];
    $config_images_ext= $config['images_ext'];

    $fileArray = array();

    $product_images_array= array();
    if ( strtolower($id) == "new") {
        $Arr= array();
        $A= isset($_POST['newproduct_images_list']) ? $_POST['newproduct_images_list'] : array();
        foreach( $A as $key=> $product_image ) {
            $NewImage = new stdClass;
            if ( is_object($product_image) ) {
              $NewImage->path= $product_image->path;
              $NewImage->alt= $product_image->alt;
            }
            if ( is_array($product_image) ) {
                $NewImage->path= $product_image['path'];
                $NewImage->alt= $product_image['alt'];
            }
            $Arr[]= $NewImage;
        }

    } else {
      $Arr= getProductById($id);
    }

    foreach( $Arr as $key=> $product_image ) {
        if ( $product_image->path != urldecode($filename) ) {
            $product_images_array[]= $product_image;
        }
    }
    if ( strtolower($id) == "new") {
        $_SESSION['newproduct_images_list']= $product_images_array;
    } else {
      $Res= ImageUpdateProduct( $id, json_encode(  $product_images_array ));//id, data, current_is_insert
    }

    if ( strtolower($id) == "new") {
        $config_uploads_products_dir= 'images/product/temp/product-' . $session_id;
    } else {
      $config_uploads_products_dir= 'images/product/' . $id;
    }
    $DocumentRoot= $config['DocumentRoot'];

    $full_directory_path= $DocumentRoot . DIRECTORY_SEPARATOR . $config_uploads_products_dir. DIRECTORY_SEPARATOR;
    if ( file_exists($full_directory_path . urldecode($filename) . '.' . $config_images_ext) ) {
      $Res= @unlink( $full_directory_path . urldecode($filename) . '.' . $config_images_ext );
        if ( strtolower($id) != "new") { //no need to delete files that haven't been pushed up yet
          $fileArray[] = urldecode($filename) . '.' . $config_images_ext;
        }
    }

    $thumbs_sizes_list= $config['thumbs_sizes_list'];
    foreach( $thumbs_sizes_list as $thumbs_size_title => $thumbs_size ) {
        $DeletedFile= $full_directory_path . urldecode($filename) . getThumbnailExt( $thumbs_size['width'], $thumbs_size['height'] ) . '.' . $config_images_ext;
        if ( file_exists($DeletedFile) ) {
          $Res= @unlink( $DeletedFile );
            if ( strtolower($id) != "new") {
                $fileArray[] = urldecode($filename) . getThumbnailExt( $thumbs_size['width'], $thumbs_size['height'] ) . '.' . $config_images_ext;
            }
        }
    }
    echo (json_encode($fileArray));
    if ( strtolower($id) != "new") {
        $FtpRes= DeleteFileFromFTPDir( $id, $fileArray);
    }
    echo $FtpRes;
    header('Content-type: application/json');
    echo json_encode(array('ErrorMessage' => '', 'ErrorCode' => 0 ) );
}

if($_POST['type'] == 'update_product'){
    $thumbs_sizes_list= $config['thumbs_sizes_list'];
    $DocumentRoot= $config['DocumentRoot'];
    $config_images_ext= $config['images_ext'];
    $session_id= $_POST['data']['session_id'];
    $fileArray = array();

    $current_is_insert = false;
    if ( !empty($_POST['data']['current_is_insert']) && (int)$_POST['data']['current_is_insert'] == 1 ) {
        $current_is_insert = true;
    }

    $id= $_POST['data']['invid'];

    unset( $_POST['data']['current_is_insert'] );
    unset( $_POST['data']['session_id'] );

    $product_images_array= array();
    if ( !empty($_POST['newproduct_images_list']) ) {
        $_POST['data']['img']= json_encode( $_POST['newproduct_images_list'] );
        $product_images_array= $_POST['newproduct_images_list'];
        unset($_SESSION['newproduct_images_list']);
    }

    $Res= UpdateProduct( $id, $_POST['data'], $current_is_insert );
    unset( $_POST['data']['invid'] );
    $filesMovedCount= 0;

    $save_newproduct_images_list = $_POST['save_newproduct_images_list'];

    if ( $current_is_insert && !empty($save_newproduct_images_list) && is_array( $save_newproduct_images_list) && $Res  ) { // move all images from "/temp_products" to "/products"
        $id= $Res;

        $temp_src_config_uploads_products_dir = '/images/product/temp/product-' . $session_id;
        $dst_config_uploads_products_dir= '/images/product/' . $Res;

        if ( !file_exists( $DocumentRoot . '/images/product' ) ) {
            mkdir( $DocumentRoot . '/images/product' );
        }
        if ( !file_exists( $DocumentRoot . $dst_config_uploads_products_dir ) ) {
            mkdir( $DocumentRoot . $dst_config_uploads_products_dir );
        }

        foreach( $save_newproduct_images_list as $product_image ) {

            $product_image_path= urldecode($product_image);
            $srcFilename = $DocumentRoot . $temp_src_config_uploads_products_dir . DIRECTORY_SEPARATOR . $product_image_path . '.png';
            if ( !file_exists( $srcFilename ) ) {
                $srcFilename = $DocumentRoot . $temp_src_config_uploads_products_dir . DIRECTORY_SEPARATOR . $product_image_path . '.' . $config_images_ext;
            }
            $dstFilename= $DocumentRoot . $dst_config_uploads_products_dir . DIRECTORY_SEPARATOR . $product_image_path . '.' . $config_images_ext;
            $renameRes= rename( $srcFilename,	$dstFilename );
            $filesMovedCount++;

            $fileArray[] = $product_image_path.".jpg";

            foreach( $thumbs_sizes_list as $thumbs_size_title => $thumbs_size ) {
                $srcThumbnailFilename= $DocumentRoot . $temp_src_config_uploads_products_dir . DIRECTORY_SEPARATOR . $product_image_path . getThumbnailExt( $thumbs_size['width'], $thumbs_size['height'] ) . '.' . $config_images_ext;
                $dstThumbnailFilename= $DocumentRoot . $dst_config_uploads_products_dir . DIRECTORY_SEPARATOR . $product_image_path . getThumbnailExt( $thumbs_size['width'], $thumbs_size['height'] ) . '.' . $config_images_ext;

                if ( file_exists($srcThumbnailFilename) ) {
                    $ResThumbnail= rename( $srcThumbnailFilename, $dstThumbnailFilename );
                    $filesMovedCount++;
                    $fileArray[] = $product_image_path . getThumbnailExt( $thumbs_size['width'], $thumbs_size['height'] ) . '.' . $config_images_ext;
                }
            }
        } 
        $FtpRes= CopyFileToFTPDir( $id, $fileArray);
        echo ($FtpRes);
        DeleteDirectory( $DocumentRoot . $temp_src_config_uploads_products_dir );

        // to put here
        $old_invid = $_POST['old_invid'];
        // $product_images_array= getProductById($old_invid);
        $save_newproduct_images_list = $_POST['save_newproduct_images_list'];

        $new_product_images_array = array();
        foreach($save_newproduct_images_list as $key => $val) {
            $NewImage = new stdClass;
            $NewImage->path= $val;
            $pattern = '/-/';
            $NewImage->alt= preg_replace($pattern, ' ', $val);
            $new_product_images_array[]= $NewImage;
        }
        // $product_images_array= getProductById($old_invid);//save_newproduct_images_list
        ImageUpdateProduct( $Res, json_encode( $new_product_images_array ) );//invid, data, current_is_insert

    } // current_is_insert

    // $prodCache = file_get_contents("http://www.mjtrends.com/modules/product.php?cat=".$_POST['data']['cat']."&type=".$_POST['data']['type']."&color=".$_POST['data']['color']);
    header('Content-type: application/json');
    echo json_encode(array('ErrorMessage' => '', 'ErrorCode' => 0, 'files'=> $fileArray, 'Res'=> $Res, 'current_is_insert'=> $current_is_insert, 'filesMovedCount'=> $filesMovedCount, '$_POST'=> print_r($_POST['data'],true) ) );

}

if($_POST['type'] == 'save_purch2'){
    $id= $_POST['data']['invid'];
    $Res= save_purch2( $id, $_POST['data'] );
    unset( $_POST['data']['invid'] );

    header('Content-type: application/json');
    echo json_encode(array('ErrorMessage' => '', 'ErrorCode' => 0, 'id'=> $Res, 'Res'=> $Res, '$_POST'=> print_r($_POST['data'],true) ) );
}

if($_POST['type'] == 'save_uploaded_image_path'){
    set_time_limit(500);
    $id = $_POST['current_id'];
    $new_path_value = $_POST['file_name'];
    $session_id = $_POST['session_id'];

    $A = preg_split('/\./',$new_path_value);

    if ( count($A)> 0 ) {
        $Res= '';
        for( $I= 0; $I< count($A) - 1; $I++ ) {
            $Res.= $A[$I] . ( $I< count($A) - 2 ? "." : "" ) ;
        }
        $new_path_value= $Res;
    }

    if( $id == "new" ) {
        $product_images_array= isset($_POST['newproduct_images_list']) ? $_POST['newproduct_images_list'] : array();
        $pattern = '/-/';
        $product_images_array[]= array( 'path'=> $new_path_value,	'alt'=> preg_replace($pattern, ' ', $new_path_value) );
        $_SESSION['newproduct_images_list']= $product_images_array;
    } else {
        $product_images_array= getProductById($id);
        $NewImage = new stdClass;
        $NewImage->path= $new_path_value;
        $pattern = '/-/';
        $NewImage->alt= preg_replace($pattern, ' ', $new_path_value);
        $product_images_array[]= $NewImage;
        $Res= ImageUpdateProduct( $id, json_encode(  $product_images_array ) );//invid, data, current_is_insert
        echo (json_encode(  $product_images_array ));
    }
    header('Content-type: application/json');
    echo json_encode(array('ErrorMessage' => '', 'ErrorCode' => 0 /*, '_SESSION'=>print_r($_SESSION,true) */ ) );
}

if($_POST['type'] == 'create_images_thumbnails'){
    set_time_limit(500);
    $id = $_POST['current_id'];
    $create_images_thumbnails = $_POST['uploaded_filesList'];
    $session_id = $_POST['session_id'];
    $fileArray = array();

    $create_images_thumbnails_array= json_decode( urldecode($create_images_thumbnails) );
    $thumbs_sizes_list= $config['thumbs_sizes_list'];
    $DocumentRoot= $config['DocumentRoot'];

    if ( strtolower($id) == "new") {
        $config_uploads_products_dir= 'images/product/temp/product-' . $session_id;
    } else {
        $config_uploads_products_dir= 'images/product/' . $id;
    }

    $full_directory_path= $DocumentRoot . DIRECTORY_SEPARATOR . $config_uploads_products_dir. DIRECTORY_SEPARATOR;
    // $full_directory_path = SITE_URL . $config_uploads_products_dir. DIRECTORY_SEPARATOR;

    $CreatedthumbnailsList= array();
    foreach( $create_images_thumbnails_array as $images_src_filename ) {
        $fileArray[] = $images_src_filename;//raw file
        foreach( $thumbs_sizes_list as $thumbs_size_title => $thumbs_size ) {
            $nameArray = pathinfo($images_src_filename);
            $base_filename = $nameArray['filename'];
            $fileArray[] = $base_filename."_".$thumbs_size['width']."x".$thumbs_size['height'].'.jpg';

            $CreateThumbnail= CreateThumbnail( $id, $full_directory_path, $images_src_filename, $thumbs_size['width'], $thumbs_size['height'], $config_uploads_products_dir );
            if ( !empty($CreateThumbnail) ) $CreatedthumbnailsList[]= $CreateThumbnail;
        }
    }
    
    if ( strtolower($id) != "new") { // Do not copy temporary files to ftp
        $FtpRes= CopyFileToFTPDir( $id, $fileArray);
        // echo $FtpRes;
    }
    
    header('Content-type: application/json');
    echo json_encode(array('ErrorMessage' => '', 'ErrorCode' => 0,'id' => $id,'filearray' => $fileArray, 'CreatedthumbnailsListLength'=> count($CreatedthumbnailsList) ) );
}

function CreateThumbnail ( $id, $full_directory_path, $images_src_filename, $thumbs_width, $thumbs_height, $config_uploads_products_dir ) { // http://www.php.net/manual/en/imagick.thumbnailimage.php
    set_time_limit(500);
    include('../../config/config.php');

    $nameArray = pathinfo($images_src_filename);
    $base_filename = $nameArray['filename'];

    $dest_filename= $base_filename . getThumbnailExt($thumbs_width, $thumbs_height) . '.jpg';
    if (!file_exists($full_directory_path . $images_src_filename)) return false;

    list($width, $height, $type) = getimagesize($full_directory_path . $images_src_filename); 

    $target_ratio  = $thumbs_width / $thumbs_height;
    $img_ratio = $width / $height;

    if ($target_ratio > $img_ratio) {
        $new_height = $thumbs_height;
        $new_width = $img_ratio * $thumbs_height;
    } else {
        $new_height = $thumbs_width / $img_ratio;
        $new_width = $thumbs_width;
    }

    if ($new_height > $thumbs_height) {
        $new_height = $thumbs_height;
    }
    if ($new_width > $thumbs_width) {
        $new_height = $thumbs_width;
    }

    $new_img = Imagecreatetruecolor($thumbs_width, $thumbs_height);

    imagefilledrectangle($new_img, 0, 0, $thumbs_width, $thumbs_height, 16777215);	// Fill the image white

    if($type == 2){
        $image = imagecreatefromjpeg($full_directory_path . $images_src_filename); 
    } elseif($type == 3){
        $image = imagecreatefrompng($full_directory_path . $images_src_filename);
    }
    
    imagecopyresampled($new_img, $image, ($thumbs_width-$new_width)/2, ($thumbs_height-$new_height)/2, 0, 0, $new_width, $new_height, $width, $height);		

    imagejpeg($new_img, $full_directory_path . $dest_filename, 80); 
    return $full_directory_path . $dest_filename;
}

function save_purch2($invid, $DataArray){
    include('../../common/database.php');
    $aa = "";
    $bb = "";
    foreach($DataArray as $key=>$value){
        $aa .= $key." ,";
        $bb .= $value."','";
        }
    $aa = substr($aa, 0, -1);
    $bb = substr($bb, 0, -3);
    $sql = "INSERT INTO  inven_purch2(".$aa.")
                VALUES ('".$bb."')";
    if($result = $mysqli->query($sql)){};
}

function UpdateProduct($invid, $DataArray, $current_is_insert) {

    include('../../common/database.php');
    if(isset($DataArray['features'])){
        $features = explode('<br>', $DataArray['features']);

        foreach ($features as $row) {
            if($row != ""){
                $b = explode(':', $row);
                $featureArray[$b[0]] = trim($b[1]);
            }
        }

        $DataArray['features'] = json_encode($featureArray);
    }

    $aa = "";
    $bb = "";
    $cc = "";

    foreach($DataArray as $key=>$value){
        $aa .= $key.",";
        $bb .= $value.",";
        $cc .= $key." = "."'".$value."'".", ";
    }
    $cc = substr($cc, 0, -2);

    if ($current_is_insert == 1) {

        $invid = $DataArray['invid'];
        $cat = $DataArray['cat'];
        $type = $DataArray['type'];
        $color = $DataArray['color'];
        $retail = $DataArray['retail'];
        $saleprice = $DataArray['saleprice'];
        $invamount = $DataArray['invamount'];
        $weight = $DataArray['weight'];
        $volume = $DataArray['volume'];
        $wholesale = $DataArray['wholesale'];
        $purch = $DataArray['purch'];
        $title = $DataArray['title'];
        $features = $DataArray['features'];
        $video = $DataArray['video'];
        $tutorials = $DataArray['tutorials'];
        $descr = $DataArray['descr'];
        $minwidth = $DataArray['minwidth'];
        $minheight = $DataArray['minheight'];
        $minlength = $DataArray['minlength'];
        $sort_order = $DataArray['sort_order'] ? number_format($DataArray['sort_order'], 3, '.', '') : '0.000';
        $active = intval($DataArray['active']);
        $meta_key = $DataArray['meta_key'];
        $meta_desc = $DataArray['meta_desc'];

        // $Res = $this->db->insert('inven_mj', $DataArray);
        // $sql = "INSERT INTO  inven_mj(".$aa.") VALUES ('".$bb."')";
        $sql = "INSERT INTO `inven_mj`(`invid`, `cat`, `type`, `color`, `retail`, `saleprice`, `invamount`, `weight`, `volume`, `wholesale`, `purch`, `title`, `features`, `video`, `tutorials`, `descr`, `minwidth`, `minheight`, `minlength`, `sort_order`, `active`, `meta_key`, `meta_desc`) 
            VALUES (('$invid'),('$cat'),('$type'),('$color'),('$retail'),('$saleprice'),('$invamount'),('$weight'),('$volume'),('$wholesale'),('$purch'),('$title'),('$features'),('$video'),('$tutorials'),('$descr'),('$minwidth'),('$minheight'),('$minlength'),('$sort_order'),('$active'),('$meta_key'),('$meta_desc'))";

        $result = $mysqli->query($sql);
    } else {
        $sql = "UPDATE inven_mj SET ".$cc." WHERE invid = $invid";
		$result = $mysqli->query($sql);
        // $Res = $this->db->update('inven_mj', $DataArray, array('invid' => $invid));
    }
    return $invid;
}

function ImageUpdateProduct($invid, $imageString) {
    include('../../common/database.php');
    $sql = "UPDATE inven_mj SET img = ('$imageString') WHERE invid = $invid";
    $result = $mysqli->query($sql);
    var_dump($result);
    return $invid;
}

function getThumbnailExt( $thumbs_size_width, $thumbs_size_height ) {
    return '_'.$thumbs_size_width . 'x' . $thumbs_size_height;
}

function getProductById($id){
    include('../../common/database.php');
    $res = array();
    $sql = "SELECT * FROM inven_mj where invid = $id";
    $result = $mysqli->query($sql);    
    while($row = $result->fetch_assoc() ){
        $res[] = $row;
    }
    return json_decode($res[0]['img']);
}

function CopyFileToFTPDir( $id, $fileArray) {
    include('../../config/config.php');
    $ftp_server= $config['ftp_site'];
    $ftp_user_name= $config['ftp_user'];
    $ftp_user_pass= $config['ftp_pwd'];

    $conn_id = ftp_connect($ftp_server); // set up basic connection
    $login_result = @ftp_login($conn_id, $ftp_user_name, $ftp_user_pass);
    ftp_pasv($conn_id, true);
    $i = 0;

    $aaa = array();
    $get_current_path_to_front = str_replace('\\', '/', realpath(dirname(__FILE__))) . '/';
    $set_new_path_to_front = str_replace('\\', '/', realpath($get_current_path_to_front . '../')) . '/';

    foreach($fileArray as $src_filename){

        $src = FCPATH.'../../images/product/' . $id . '/' . $src_filename;
        // return json_encode($src);
        if ( !file_exists( $src ) ) {
            return "file doesn't exist";
        }

        $aaa[] = ftp_put($conn_id, '/mjtrends/images/product/' . $id . '/' . $src_filename, $src, FTP_BINARY);
        $i++;
    }
    ftp_close($conn_id);
    
    return json_encode($aaa);
    return $res !== FALSE;
}

function RenameFileToFTPDir( $id, $oldArray, $newArray ) {
    $delRes = DeleteFileFromFTPDir( $id, $oldArray);
    $upRes = CopyFileToFTPDir( $id, $newArray);
    return json_encode(["delRes"=>$delRes, "upRes"=>$upRes]);  // close the connection
}

function DeleteFileFromFTPDir( $id, $fileArray) {
    include('../../config/config.php');
    $ftp_server= $config['ftp_site'];
    $ftp_user_name= $config['ftp_user'];
    $ftp_user_pass= $config['ftp_pwd'];

    $conn_id = ftp_connect($ftp_server); // set up basic connection
    $login_result = ftp_login($conn_id, $ftp_user_name, $ftp_user_pass); // login with username and password
    $res = array();
    $urls = array();
    foreach($fileArray as $filename){
        // if( is_array($dirs_list) and in_array( $filename, $dirs_list ) ) {
            $res[] = ftp_delete($conn_id, '/mjtrends/images/product/' . $id . '/' . $filename);
            // $urls[] = 'images/product/' . $id . '/' . $filename;
        // }
    }
    ftp_close($conn_id);
    return json_encode($urls);

}

function DeleteDirectory($DirectoryName) {
	$H = OpenDir($DirectoryName);
	while ($NextFile = readdir($H)) { // All files in dir
		if ($NextFile == "." or $NextFile == "..")
			continue;
		//AppUtils::deb($DirectoryName . DIRECTORY_SEPARATOR . $NextFile, '$DirectoryName . DIRECTORY_SEPARATOR . $NextFile::');
		unlink($DirectoryName . DIRECTORY_SEPARATOR . $NextFile);
	}
	closedir($H);
	rmdir($DirectoryName);
}

?>