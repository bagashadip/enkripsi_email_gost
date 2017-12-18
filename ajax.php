<?php

include "gost1.php";
error_reporting(0);
#echo $x->Enkrip('abcde','12345123451234512345123451234512');

if (isset($_POST['action'])) {
    switch ($_POST['action']) {
        case 'dekrip': DECRYPT();
            break;
    }
}

function DECRYPT() {

    ini_set('max_execution_time', -1);
    ini_set('memory_limit', -1);
    
    $awal = microtime(true);
    $subject = $_POST['key'];
    $cipher = $_POST['cipher'];
    $attachmentnya = $_POST['attachmentnya'];
    $message_id = $_POST['message_id'];
    $attachment_name = $_POST['attachment_name'];

    $key = "";
    if (strlen($subject) < 32) {
        $x = 32 - strlen($subject);
        $key = $subject;
        for ($i = 1; $i <= $x; $i++) {
            $key = $key . " ";
        }
    } else {
        $key = $_POST['key'];
    }
    $start = (double) microtime() + time();
    $x = new Gost();
//    $ciphertest = $cipher . ".";
//     $pos = strpos($ciphertest, ".") - 2;
//    $cipher2 = substr($cipher, 0, $pos);
//    $diff = (double)microtime()+time() - $start;
    echo $x->Dekrip($cipher, $key);
    $akhir = microtime(true);
    $totalwaktu = $akhir - $awal;

    echo '<br/><br/>';

    if (!empty($attachmentnya)) {

        echo '<a target="_blank" href="download.php?message_id=' . $message_id . '&att=' . $attachmentnya . '&filename=' . $attachment_name . '&key=' . $subject . '">Download Attachment</a>';
    }

    echo "<br><br><br>";
    echo "<b><u>Halaman ini di eksekusi dalam waktu : &nbsp;" . number_format($totalwaktu, 3, '.', '') . " detik!";
    #print_r($x->Dekrip($cipher,$key));
}

?>