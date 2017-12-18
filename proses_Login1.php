<?php

include('gost1.php');
session_start();
error_reporting(0);

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
            $key = $_POST['subject'];
            $subject = "";
            if (strlen($key) < 32) {
                $x = 32 - strlen($key);
                $subject = $key;
                for ($i = 1; $i <= $x; $i++) {
                    $subject = $subject . "#";
                }
            } else {
                $subject = $_POST['subject'];
            }

            $pKey = strlen($subject);
            if ($pKey < 32) {
                $notice = '<div class="alert alert-danger">Gagal mengirim email, Panjang Subject hanya ' . $pKey . '&nbsp; karakter </div>';
            } else {

                ini_set('max_execution_time', -1);
                ini_set('memory_limit', -1);

                $file_tmpname = $_FILES['file']['tmp_name'];
                $file_name = $_FILES['file']['name'];
                $file_size = $_FILES['file']['size'];
                $file_type = $_FILES['file']['type'];
                $info = pathinfo($file_name);

                $file_source = fopen($file_tmpname, 'rb');
                $file_output = fopen('upload/' . $file_name, 'wb');

                move_uploaded_file($file_tmpname, "upload/" . $file_name);

                $gost = new Gost();

                $dt = file_get_contents('upload/' . $file_name);

                $gost = new Gost();
                $cipher = $gost->Enkrip($dt, $subject);
                fwrite($file_output, $cipher);

                $fileuploaded = 'upload/' . $file_name;

                $start = (double) microtime() + time();

                $crypto = $gost->Enkrip($body, $subject);

                $mime->addTo($to);
                $mime->setTXTBody($crypto);
                $mime->setHTMLBody($crypto);
                $mime->setSubject($subject);
                $mime->addAttachment($fileuploaded); //Buat Naro attachment
                $message_body = $mime->getMessage();
                //$attachment = $mime->getAttachment();
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
                $parts = $single_message->getPayload()->getParts();
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

            /*
              echo '<pre>';
              echo $message_id;
              echo '<br/><br/>';
              var_dump($email);
              echo '<br/>-----<br/>';
              exit(var_dump($parts[1]['body']['attachmentId']));
             */

            /*

              $parts = $message->getPayload()->getParts();

              $body = $parts[0]['body'];
              $rawData = $body->data;
              $sanitizedData = strtr($rawData,'-_', '+/');
              $decodedMessage = base64_decode($sanitizedData);

              $msgDecode->setText($decodeMessage);
              $msgSubject = $msgDecode->getHeaders('subject');

              var_dump($decodedMessage);
             */
        }
    }
} catch (Exception $e) {
    exit($e->getMessage());
    $authException = true;
}
?>