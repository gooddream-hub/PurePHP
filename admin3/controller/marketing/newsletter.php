<?php
include('../../common/auth.php');
include('../../config/config.php');

$auth = new Auth;
$auth->get_auth();
$auth->get_warehouse_access();

if(isset($_POST['imgBase64']) && $_POST['imgBase64'] != '') {
    $img = $_POST['imgBase64'];
    $img = str_replace('data:image/jpeg;base64,', '', $img);
    $img = str_replace(' ', '+', $img);
    $fileData = base64_decode($img);
    //saving
    $fileName = 'featured_'.date("YmdHis").'.jpg';
    file_put_contents(NEWSLETTER_IMG_PATH.$fileName, $fileData);
    echo NEWSLETTER_IMG_GET.$fileName;
}
function create_newsletter(){
	$mysqli = mysqliConn();
	$data = array();

    $data['clearance'] = get_clearance();
    $data['count'] = get_clearance_count();
    $data['sale'] = get_sale();
    $data['sale_count'] = get_sale_count();
    $data['blog'] = get_blog();
    $data['blog_image'] = get_blog_images();
    return $data;
}
function get_clearance(){
    $mysqli = mysqliConn();
    $clearance_data = array();
    $sql = "SELECT invid, cat, type, color, retail, saleprice, img FROM inven_mj WHERE cat = 'Clearance' and invamount > 0 ORDER BY RAND() LIMIT 9";
    if($result = $mysqli->query($sql)){
        while($row = $result->fetch_assoc() ){
            $clearance_data[] = array_map('utf8_encode', $row);
        } 
    }	
    return $clearance_data;
}

function get_clearance_count(){
    $mysqli = mysqliConn();
    $clearance_count = array();
    $sql = "SELECT count(*) as count FROM inven_mj WHERE cat = 'Clearance' and invamount > 0";
    if($result = $mysqli->query($sql)){
        while($row = $result->fetch_assoc() ){
            $clearance_count[] = array_map('utf8_encode', $row);
        } 
    }	
    return $clearance_count;
}
function get_sale(){
    $mysqli = mysqliConn();
    $sale_data = array();
    $sql = "SELECT invid, cat, type, color, retail, saleprice, img FROM inven_mj WHERE invamount > 0 AND saleprice > 0 ORDER BY RAND() LIMIT 6";
    if($result = $mysqli->query($sql)){
        while($row = $result->fetch_assoc() ){
            $sale_data[] = array_map('utf8_encode', $row);
        } 
    }	
    return $sale_data;
}

function get_sale_count(){
    $mysqli = mysqliConn();
    $sale_count = array();
    $sql = "SELECT count(*) as sale_count FROM inven_mj WHERE invamount > 0 AND saleprice > 0";
    if($result = $mysqli->query($sql)){
        while($row = $result->fetch_assoc() ){
            $sale_count[] = array_map('utf8_encode', $row);
        } 
    }	
    return $sale_count;
}
function get_blog(){
    $mysqli = mysqliConn();
    $blog_data = array();
    $sql = "SELECT ID, post_title, post_content, guid FROM wp_posts WHERE post_status = 'publish' AND (post_type = 'revision' OR post_type = 'post') ORDER BY ID DESC LIMIT 4";
    
    if($result = $mysqli->query($sql)){
        while($row = $result->fetch_assoc() ){
            $blog_data[] = array_map('utf8_encode', $row);
        } 
    }	
    
    $blog_updated_data = set_blog_content($blog_data);
    return $blog_updated_data;
    //return $result->fetch_assoc();

}
function set_blog_content($blog){
    $updated = array();
    $str2 = "";

    foreach($blog as $row){
        
        $str = strip_tags($row['post_content']);
        $str = wordwrap($str,115,"\n");
        $str = explode("\n", $str);
        
        foreach($str as $sent){
            if($sent != ""){
                $str2 = $sent;
                break;
            }
        }

        $updated['post_content'][] = $str2;
        $updated['ID'][] = $row['ID'];
        $updated['post_title'][] = $row['post_title'];
        $updated['guid'][] = $row['guid'];
    }
   // print_r($updated);die;
    return $updated;
}
function get_blog_images(){
    $mysqli = mysqliConn();
    $image_data = array();
    $sql = "SELECT ID, post_title, guid, post_parent FROM wp_postmeta INNER JOIN wp_posts ON wp_postmeta.meta_value = wp_posts.ID WHERE meta_key = '_thumbnail_id' ORDER BY post_id DESC, meta_value ASC LIMIT 4";
    if($result = $mysqli->query($sql)){
        while($row = $result->fetch_assoc() ){
            $image_data[] = array_map('utf8_encode', $row);
        } 
    }	
    return $image_data;
}
/* function create_newsletter(){
	$mysqli = mysqliConn();
	$newsletter = array();
    if(isset($_POST['submit'])) {
        $title = $_POST['title'];
        $newsletter_img = $_FILES['newsletter_img'];
        $date = date('Y-m-d h:i:s');
        if(isset($newsletter_img)){
            $errors= array();
            $file_name = $_FILES['newsletter_img']['name'];
            $file_size =$_FILES['newsletter_img']['size'];
            $file_tmp =$_FILES['newsletter_img']['tmp_name'];
            $file_ext= explode('.',$_FILES['newsletter_img']['name']);
            $file_path = NEWSLETTER_IMG_PATH.$file_name;
            $extensions= array("jpeg","jpg","png","gif");
            
            if(in_array(end($file_ext),$extensions)=== false){
            $errors['error']=1;
            $errors['msg']="extension not allowed, please choose a JPEG or PNG file.";
            }
            
            if($file_size > 2097152){
                $errors['error']=1;
                $errors['msg']='File size must be excately 2 MB';
            }
            
            if(empty($errors)==true){
                if(move_uploaded_file($file_tmp,$file_path)) {
                    CopyFileToFTPDir(array($file_name));
                    $sql = "INSERT INTO email_stats (subject, file_path, delivery_date) VALUES ('".$title."', '".$file_name."', '".$date."');";
                    $result = $mysqli->query($sql);
                    $errors['error']=0;
                    $errors['msg']='Newsletter created successfull';
                } else {
                    $errors['error']=1;
                    $errors['msg']='Something went wrong';
                }
                return $errors;
            }else{
            return $errors;
            }
        } 
    }
} */
/* function CopyFileToFTPDir($fileArray) {
    include('../../config/config.php');
    $ftp_server = $config['ftp_site'];
    $ftp_user_name = $config['ftp_user'];
    $ftp_user_pass = $config['ftp_pwd'];

    $conn_id = ftp_connect($ftp_server); // set up basic connection
    $login_result = ftp_login($conn_id, $ftp_user_name, $ftp_user_pass);

    // turn passive mode on
    ftp_pasv($conn_id, true);

    $i = 0;

    foreach($fileArray as $src_filename){
        $src = NEWSLETTER_IMG_PATH.$src_filename;
        if ( !file_exists( $src ) ) {
            return false;
        }

        // @ftp_mkdir($conn_id, 'www/images');
        // @ftp_mkdir($conn_id, 'www/images/newsletter_images');
        // @ftp_mkdir($conn_id, 'www/images/newsletter_images/uploads');

        $res = ftp_put($conn_id, 'www/images/newsletter_images/uploads/' . $src_filename, $src, FTP_BINARY);
        $i++;
    }

    ftp_close($conn_id);
    
    return $res !== FALSE;
} */

?>


