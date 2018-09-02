<?php
session_start();


require "twitteroauth/autoload.php";

use Abraham\TwitterOAuth\TwitterOAuth;

$config['consumer_key']      = 'i71F7AZVIrRgJ9ERUj0Me3hv3';
$config['consumer_secret']   = 'YRa63bG1RYJWWrqt7b7v4HkZwSFJtfTxPiVLKDeCMlW8L314e6';
$config['url_login']         = 'http://www.laquinieladelgordo.com';
$config['url_callback']      = 'http://www.laquinieladelgordo.com/tw-callback.php';

// create TwitterOAuth object
$twitteroauth = new TwitterOAuth($config['consumer_key'], $config['consumer_secret']);
 
// request token of application
$request_token = $twitteroauth->oauth(
    'oauth/request_token', [
        'oauth_callback' => $config['url_callback']
    ]
);

// throw exception if something gone wrong
if($twitteroauth->getLastHttpCode() != 200) {
    throw new \Exception('There was a problem performing this request');
}
 
// save token of application to session
$_SESSION['oauth_token'] = $request_token['oauth_token'];
$_SESSION['oauth_token_secret'] = $request_token['oauth_token_secret'];
 
// generate the URL to make request to authorize our application
$url = $twitteroauth->url(
    'oauth/authenticate', [
        'oauth_token' => $request_token['oauth_token']
    ]
);
 
// and redirect
header('Location: '. $url);
?>
