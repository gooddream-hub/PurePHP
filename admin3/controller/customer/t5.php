<?php

$key = "ajocsi6rfi22tzv7kx5g8iop";
$secret = "rgvd7ju59k";

$oauth = new OAuth($key, $secret);
$oauth->disableSSLChecks();

$access_token = '3957d385437cf317fb5fa343697b63';// get from db
$access_token_secret = '8d74d92f6e';// get from db

//$oauth = new OAuth($key, $secret);
$oauth->setToken($access_token, $access_token_secret);

try {
    $data = $oauth->fetch("https://openapi.etsy.com/v2/users/__SELF__", null, OAUTH_HTTP_METHOD_GET);
    $json = $oauth->getLastResponse();
    print_r(json_decode($json, true));
    
} catch (OAuthException $e) {
    error_log($e->getMessage());
    error_log(print_r($oauth->getLastResponse(), true));
    error_log(print_r($oauth->getLastResponseInfo(), true));
    exit;
}