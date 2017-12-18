<?php

require_once 'proses_Login1.php';
// Include Autoloader
require_once 'vendor/autoload.php';
require_once 'helpers/helpers.php';
require_once 'gost1.php';

$message_id = $_GET['message_id'];
$filename = time() . '_' . $_GET['filename'];
$att = $_GET['att'];
$key = $_GET['key'];

$attachment = $service->users_messages_attachments->get('me', $message_id, $att);

$data = strtr($attachment->data, array('-' => '+', '_' => '/'));

$dirfile = $filename;
$fh = fopen('download/' . $dirfile, "wb");
//$cipher = base64_decode($data);
//$x = new Gost();
//$cipher = $x->Dekrip(base64_decode($data), $key);

if (strlen($key) < 32) {
    $k = 32 - strlen($key);
    $subject = $key;
    for ($i = 1; $i <= $k; $i++) {
        $subject = $subject . "#";
    }
} else {
    $subject = $_POST['subject'];
}

ini_set('max_execution_time', -1);
ini_set('memory_limit', -1);

$cp = base64_decode($data);
$x = new Gost();

$cp = $x->Dekrip(base64_decode($data), $subject);
fwrite($fh, $cp);
fclose($fh);

header("Content-type: application/force-download");
header("Content-Disposition: attachment; filename=" . $dirfile);
echo $cp;


exit();
exit(var_dump($attachment));

exit(var_dump($_GET));