<?php

require_once 'gost1.php';
//require_once 'proses_Login1.php';
// Include Autoloader
require_once 'vendor/autoload.php';
require_once 'helpers/helpers.php';

//$message_id = $_GET['message_id'];
//$filename = time().'_'.$_GET['filename'];
//$att = $_GET['att'];


//$attachment = $service->users_messages_attachments->get('me', $message_id, $att);

$data = strtr($attachment->data, array('-' => '+', '_' => '/'));

$x = new Gost();

ini_set('max_execution_time', -1);
ini_set('memory_limit', -1);

$file_tmpname = $_FILES['file']['tmp_name'];
$file_name = $_FILES['file']['name'];
$file_size = $_FILES['file']['size'];
$file_type = $_FILES['file']['type'];
$info = pathinfo($file_name);
$key = $_POST['key'];

$file_source = fopen($file_tmpname, 'rb');
$file_output = fopen('upload/' . $file_name, 'wb');

move_uploaded_file($file_tmpname, "upload/" . $file_name);

$cipher = $x->Dekrip(base64_decode($data), $key);
fwrite($file_output, $cipher);
