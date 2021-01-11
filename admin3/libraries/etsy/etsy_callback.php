<?php
$key = "ajocsi6rfi22tzv7kx5g8iop";
$secret = "rgvd7ju59k";

$tokenFile = fopen("/var/www/mjtrends/admin3/libraries/etsy/token.txt", "r") or die("Unable to open file!");
$token = trim(fgets($tokenFile),"\n");
$tokenSecret = trim(fgets($tokenFile),"\n");
fclose($tokenFile);

$oauth = new OAuth($key, $secret);
$oauth->disableSSLChecks();
$oauth->setToken($token, $tokenSecret);

try {
    // set the verifier and request Etsy's token credentials url
    $acc_token = $oauth->getAccessToken("https://openapi.etsy.com/v2/oauth/access_token", null, $_GET["oauth_verifier"], "GET");
    $token = $acc_token['oauth_token'];
    $token_secret = $acc_token['oauth_token_secret'];

    $_SESSION['OAUTH_ACCESS_TOKEN']['https://openapi.etsy.com/v2/oauth/access_token'] = array("value"=>$token, "secret"=>$token_secret);
    
    $access_file = '/var/www/mjtrends/admin3/libraries/etsy/access_token.json';
    $val = array("value"=>$token, "secret"=>$token_secret);
	file_put_contents($access_file, json_encode($val));

} catch (OAuthException $e) {
    print_r($e->getMessage());
    echo "bad";
}

//header("Location: ".$redirect);