<?php
include('gost1.php');
session_start();

// Include Autoloader
require 'vendor/autoload.php';
require 'helpers/helpers.php';

//GAUSAH KARENA GA PAKE DBASE
//require "control/fungsi_query1.php"
//$mySql = new mySql;
//$mySql->openKoneksi();
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
} else {
    $loginUrl = $client->createAuthUrl();
}

#-------------------------------------------INI ADALAH PROSES COMPOSE/ENKRIP-------------------------------------------#
// Check if we have an access token ready for API calls
try {
    if (isset($_SESSION['access_token']) && $client->getAccessToken()) {
        // Make API Calls
        if (isset($_POST['send'])) {
            $to = $_POST['to'];
            $body = $_POST['message'];
            $subject = $_POST['subject'];
            //$key = $_POST['key'];

            $pKey = strlen($subject);
            if ($pKey < 32) {
                $notice = '<div class="alert alert-danger">Gagal mengirim email, Panjang Subject hanya ' . $pKey . '&nbsp; karakter </div>';
            } else {
                $start = (double) microtime() + time();
                $gost = new Gost();
                $crypto = $gost->Enkrip($body, $subject);

                $mime->addTo($to);
                $mime->setTXTBody($crypto);
                $mime->setHTMLBody($crypto);
                $mime->setSubject($subject);
                $message_body = $mime->getMessage();

                $encoded_message = base64url_encode($message_body);

                // Gmail Message Body
                $message = new Google_Service_Gmail_Message();
                $message->setRaw($encoded_message);

                // Send the Email
                $email = $service->users_messages->send('me', $message);
                $diff = (double) microtime() + time() - $start;
                if ($email->getId()) {
                    $notice = '<div class="alert alert-success"><strong>Email berhasil dikirim</strong></div>' . '<div class="alert alert-info"><strong>Waktu Eksekusi : &nbsp' . $diff . '</strong></div>';
                } else {
                    $notice = '<div class="alert alert-danger">Gagal mengirim email, coba lagi !</div>';
                }
            }

            $service = new Google_Service_Gmail($client);

            $inbox = [];
            $optParams = [];
            $optParams['maxResults'] = 5; // Return Only 5 Messages
            //$optParams['q'] = 'in:inbox category:penting';
            // category:primary
            $optParams['labelIds'] = 'INBOX'; // Only show messages in Inbox
            $messages = $service->users_messages->listUsersMessages('me', $optParams);
            $list = $messages->getMessages();
            //$messageId = $list->id; // Grab first Message

            foreach ($list as $msglist) {
                //$optParamsGet = [];
                $optParamsGet['format'] = 'full'; // Display message in payload
                $single_message = $service->users_messages->get('me', $msglist->id, $optParamsGet);
                $message_id = $msglist->id;
                $messagePayload = $single_message->getPayload();
                $headers = $single_message->getPayload()->getHeaders();
                $snippet = $single_message->getSnippet();

                foreach ($headers as $single) {
                    if ($single->getName() == 'Subject') {
                        $message_subject = $single->getValue();
                    } else if ($single->getName() == 'Date') {
                        $message_date = $single->getValue();
                        $message_date = date('M jS Y h:i A', strtotime($message_date));
                    } else if ($single->getName() == 'From') {

                        $message_sender = $single->getValue();
                        $message_sender = str_replace('"', '', $message_sender);
                    }
                }

                $inbox[] = [
                    'messageId' => $message_id,
                    //'messageSnippet' => $snippet,
                    'messageSubject' => $message_subject,
                    'messageDate' => $message_date,
                    'messageSender' => $message_sender
                ];
            }

            /* $parts = $message->getPayload()->getParts();

              $body = $parts[0]['body'];
              $rawData = $body->data;
              $sanitizedData = strtr($rawData,'-_', '+/');
              $decodedMessage = base64_decode($sanitizedData);

              $msgDecode->setText($decodeMessage);
              $msgSubject = $msgDecode->getHeaders('subject');

              var_dump($decodedMessage); */
        }
    }
} catch (Google_Auth_Exception $e) {
    $authException = true;
}
?>