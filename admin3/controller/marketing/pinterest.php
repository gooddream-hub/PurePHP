<?php
include('../../common/database.php');
include('../../config/config.php');
include('../../libraries/Pinterestnew/autoload.php');

ini_set('max_execution_time', 300);



use DirkGroenen\Pinterest\Pinterest as PinterestAPI;

$appid = $config['pin']['appid'];
$secret = $config['pin']['secret'];

$pinterest = new PinterestAPI($appid, $secret);
		
$pin_access_token_path = '../../cache/pin_access_token.json';

// $cache = readCache($pin_access_token_path);

// if ($cache) {
//     $token = json_decode($cache);
// }

// if(isset($_GET["code"])){
//     $token = $pinterest->auth->getOAuthToken($_GET["code"]);
//     $pinterest->auth->setOAuthToken($token->access_token);
    

//     // writeCache(json_encode(['access_token' => $token->access_token]), $pin_access_token_path);
// }

// $auth_token = isset($token) && $token && isset($token->access_token) && $token->access_token
//     ? $token->access_token
//     : $config['pin_auth_token'];


// if (!$auth_token) {
//     $callback = 'https://secure51.webhostinghub.com/~mjtren5/admin/beta/marketing/pinterest/callback';
//     $login_url = $pinterest->auth->getLoginUrl($callback, array('read_public,write_public'));
// 	// redirect( $login_url );
// 	header('Location: '.$login_url);
// }

// $pinterest->auth->setOAuthToken($token->access_token);

function create_thumb($img_path, $thumb_path, $new_width)
{
	$parts = pathinfo($img_path);
	$thumb_name = $parts['filename'].'_x_'.$new_width.'.'.$parts['extension'];

	if (!file_exists($img_path)) return false;

	list($width, $height, $type) = getimagesize($img_path);
	
	if (!$height) return false;

	$img_ratio = $width / $height;
	$new_height = $new_width / $img_ratio;
	
	$img = new Imagick($img_path);
	$img->thumbnailImage($new_width , $new_height , TRUE);
	$img->writeImage($thumb_path . '/' . $thumb_name);

	return $thumb_name;
}

function CopyFileToFTPDir($fileArray, $config_key_path)
{
    include('../../config/config.php');
	restore_error_handler(); 

	$ftp_server= $config['ftp_site'];
	$ftp_user_name= $config['ftp_user'];
	$ftp_user_pass= $config['ftp_pwd'];

	$conn_id = ftp_connect($ftp_server); // set up basic connection
	$login_result = ftp_login($conn_id, $ftp_user_name, $ftp_user_pass);
	ftp_pasv($conn_id, true);

	$i = 0;
	foreach($fileArray as $src_filename){
		$src = $config[$config_key_path]['img_path'] . '/' . $src_filename;
		if ( !file_exists( $src ) ) {
			return false;
		}

		$res = ftp_put($conn_id, $config[$config_key_path]['img_cdn'] . $src_filename, $src, FTP_BINARY);
		$i++;
	}

	ftp_close($conn_id);

	return $res !== FALSE;
}

function delay_pinterest_posting($data) {
    include('../../models/pins_for_posting_model.php');
    pins_for_posting_model_insert_pin($data);
}

function publish_to_social($img, $message, $social){
    include('../../models/pins_for_posting_model.php');
    $data = array(
        'type' => $social,
        'img' => htmlentities($img),
        'message' => htmlentities($message)
    );
    pins_for_posting_model_insert_pin($data);
}
?>