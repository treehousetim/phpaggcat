<?php

require_once 'config.php';

IntuitAggCatHelpers::GetOAuthTokens( $oauth_token, $oauth_token_secret);

$signatures = array( 'consumer_key'     => OAUTH_CONSUMER_KEY,
                     'shared_secret'    => OAUTH_SHARED_SECRET,
                     'oauth_token'      => $oauth_token,
                     'oauth_secret'     => $oauth_token_secret);

$url = FINANCIAL_FEED_URL .'v1/institutions/100000';
$action = 'GET';

$oauthObject = new OAuthSimple();
$oauthObject->setAction( $action );
$oauthObject->reset();

$result = $oauthObject->sign(
array
(
    'path'      => $url,
    'parameters'=>
    array
    (
        'oauth_signature_method'    => 'HMAC-SHA1', 
        'Host'                      => FINANCIAL_FEED_HOST
    ),
    'signatures'=> $signatures
)
);

$options = array();

$options[CURLOPT_CUSTOMREQUEST] = $action;
$options[CURLOPT_URL] = $result['signed_url'];
$options[CURLOPT_HEADER] = 1;
$options[CURLOPT_VERBOSE] = 1;
$options[CURLOPT_RETURNTRANSFER] = 1;
$options[CURLOPT_SSL_VERIFYPEER] = true;
$options[CURLOPT_HTTPHEADER] = array
(
    'Accept:application/json',
    'Content-Type:application/json',
    'Host:'. FINANCIAL_FEED_HOST,
); 

include 'example-exec.php';