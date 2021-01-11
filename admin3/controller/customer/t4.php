<?php   
//run from https
// header('Content-type: text/plain');
// ini_set('max_execution_time', 600);

session_start();

$key = "ajocsi6rfi22tzv7kx5g8iop";
$secret = "rgvd7ju59k";

$oauth = new OAuth($key, $secret);
$oauth->disableSSLChecks();
$tokenFile = fopen("token.txt", "r") or die(getToken($oauth, $key, $secret));

function getToken($oauth, $key, $secret){

    $req_token = $oauth->getRequestToken("https://openapi.etsy.com/v2/oauth/request_token", "https://fabric-bolt.com/admin3/controller/customer/t4.php", "GET");
    $tokenFile = fopen("token.txt", "w") or die("Unable to open file!");
    fwrite($tokenFile, $req_token['oauth_token'] . "\n");
    fwrite($tokenFile, $req_token['oauth_token_secret'] . "\n");

    $login_url = sprintf(
        "%s?oauth_consumer_key=%s&oauth_token=%s",
        $req_token['login_url'],
        $req_token['oauth_consumer_key'],
        $req_token['oauth_token']
    );

    header("Location: " . $login_url);
}

if(empty($_GET))
{
    getToken($oauth, $key, $secret);
}
else
{
    $tokenFile = fopen("token.txt", "r") or die("Unable to open file!");
    $token = trim(fgets($tokenFile),"\n");
    $tokenSecret = trim(fgets($tokenFile),"\n");
    fclose($tokenFile);

    $oauth1 = new OAuth($key, $secret);
    $oauth1->disableSSLChecks();
    $oauth1->setToken($token, $tokenSecret);

    try {
        // set the verifier and request Etsy's token credentials url
        $acc_token = $oauth1->getAccessToken("https://openapi.etsy.com/v2/oauth/access_token", null, $_GET["oauth_verifier"], "GET");
        echo "good";
        print_r($acc_token);
        $token = $acc_token['oauth_token'];
        $token_secret = $acc_token['oauth_token_secret'];

        echo "token is: ".$token;
        echo "token_secret is: ".$token_secret;

        $_SESSION['OAUTH_ACCESS_TOKEN']['https://openapi.etsy.com/v2/oauth/access_token'] = array("value"=>$token, "secret"=>$token_secret);

    } catch (OAuthException $e) {
        print_r($e->getMessage());
        echo "bad";
    }
}