<?php

session_start();

/*==================================================================
*email utk login ke app harus sama dgn email "login my gmail"
* jika tidak maka akan tampil pop-up "email tidak syncron"
================================================================== */

// Include Autoloader
require 'vendor/autoload.php';
require 'helpers/helpers.php';

require "control/fungsi_query1.php";
$mySql = new mySql;
$mySql->openKoneksi();


// Get API Credentials
$config = parse_ini_file('helpers/config.ini');
$notice = '';
$authException = false;
$mime = new Mail_mime();
// Setup Google API Client
$client = new Google_Client();
$client->setClientId($config['client_id']);
$client->setClientSecret($config['client_secret']);
$client->setRedirectUri($config['redirect_url']);
$client->addScope('https://mail.google.com/');
$client->setScopes(array
    ('https://www.googleapis.com/auth/userinfo.email', 'https://mail.google.com/'));

// Create GMail Service
$service = new Google_Service_Gmail($client);

// Check if user is logged out
if (isset($_REQUEST['logout'])) {
    unset($_SESSION['access_token']);

    header("location: index.php");
}

// Check if we have an authorization code
if (isset($_GET['code'])) {
    $code = $_GET['code'];
    $client->authenticate($code);
    $_SESSION['access_token'] = $client->getAccessToken();
    $url = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'];
    header('Location: ' . filter_var($url, FILTER_VALIDATE_URL));
}

// Check if we have an access token in the session
if (isset($_SESSION['access_token'])) {
    $client->setAccessToken($_SESSION['access_token']);
    
    // $json1 = file_get_contents($token);
    $userInfoArray = json_decode($token);
    $tokengetting = $userInfoArray->access_token;

    $q = 'https://www.googleapis.com/oauth2/v1/userinfo?access_token='.$tokengetting;
    $json = file_get_contents($q);
    $userInfoArray = json_decode($json,true);
    $googleEmail = $userInfoArray['email'];
    echo "<script>alert {$q}</script>";

    $query = $mySql->execute("select email from user where email='".$_SESSION['email']."'");
    // echo $_SESSION['email'];
    // echo $googleEmail;
    $ambil_data=$mySql->getArray();
        if($ambil_data[0] == $googleEmail)
        {
            $client->setAccessToken($_SESSION['access_token']);
        }else{                    
            unset($_SESSION['access_token']);
            header('Location: home.php?page=home&syncron=failsyncron');
        }

    // $infouser = $oauth2->userinfo;print_r($infouser->get());
    
} else {
    $loginUrl = $client->createAuthUrl();
}
?>