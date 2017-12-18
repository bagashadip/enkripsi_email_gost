<?php

class Gost {

    function Enkrip($body, $subject) {
        //merubah key menjadi biner
        $c = "";
        for ($h = 0; $h < strlen($subject); $h++) {
            $c.=sprintf("%08s", decbin(ord($subject[$h])));
        }
        $hslkey = $c;
        $keyascii = strrev($c);

        //SBox
        $SBox[0] = array(4, 10, 9, 2, 13, 8, 0, 14, 6, 11, 1, 12, 7, 15, 5, 3);
        $SBox[1] = array(14, 11, 4, 12, 6, 13, 15, 10, 2, 3, 8, 1, 0, 7, 5, 9);
        $SBox[2] = array(5, 8, 1, 13, 10, 3, 4, 2, 14, 15, 12, 7, 6, 0, 9, 11);
        $SBox[3] = array(7, 13, 10, 1, 0, 8, 9, 15, 14, 4, 6, 12, 11, 2, 5, 3);
        $SBox[4] = array(6, 12, 7, 1, 5, 15, 13, 8, 4, 10, 9, 14, 0, 3, 11, 2);
        $SBox[5] = array(4, 11, 10, 0, 7, 2, 1, 13, 3, 6, 8, 5, 9, 12, 15, 14);
        $SBox[6] = array(13, 11, 4, 1, 3, 15, 5, 9, 0, 10, 14, 7, 6, 8, 2, 12);
        $SBox[7] = array(1, 15, 13, 0, 5, 7, 10, 4, 9, 2, 3, 14, 6, 11, 8, 12);

        //Pembagian Kunci dlm bentuk biner
        $K = array(substr($keyascii, -32, 32), substr($keyascii, -64, 32), substr($keyascii, -96, 32),
            substr($keyascii, -128, 32), substr($keyascii, -160, 32), substr($keyascii, -192, 32),
            substr($keyascii, -224, 32), substr($keyascii, 0, 32));

        //penjadwalan kunci enkripsi
        $JKe = array(bindec($K[0]), bindec($K[1]), bindec($K[2]), bindec($K[3]), bindec($K[4]), bindec($K[5]),
            bindec($K[6]), bindec($K[7]),
            bindec($K[0]), bindec($K[1]), bindec($K[2]), bindec($K[3]), bindec($K[4]), bindec($K[5]),
            bindec($K[6]), bindec($K[7]),
            bindec($K[0]), bindec($K[1]), bindec($K[2]), bindec($K[3]), bindec($K[4]), bindec($K[5]),
            bindec($K[6]), bindec($K[7]),
            bindec($K[7]), bindec($K[6]), bindec($K[5]), bindec($K[4]), bindec($K[3]), bindec($K[2]),
            bindec($K[1]), bindec($K[0]));

        $ja = strlen($body);
        $hA = "";

        //merubah plainteks menjadi biner
        for ($j = 0; $j < $ja; $j++) {
            $hA.=sprintf("%08s", decbin(ord($body[$j])));
        }
        $temphA = $hA;
        $arrkA = array();

        // fungsi padding angka 0 pada array indeks tertentu
        $l = 0;
        for ($o = 0; $o < strlen($temphA); $o+=64) { //fungsi untuk membagi plainteks menjadi beberapa blok
            $arrkA[$l] = substr($temphA, $o, 64);
            if (strlen($arrkA[$l]) < 64) {

                //hasil padding dimasukkan ke array arrkA
                $arrkA[$l] = str_pad($arrkA[$l], 64, "0", STR_PAD_LEFT);
            }
            $l++;
        }

        $varkA = "";
        $varR = array();
        $varL = array();
        $hasblock = array();
        $tempL = "";
        $blockCounter = count($arrkA);

        $arrBC = array();
        $nilkA = array();

        $v = 0;
        for ($p = 0; $p < $blockCounter; $p++) {
            $varkA = $arrkA[$p];
            $kL = strrev(substr($varkA, -32, 32));
            $kR = strrev(substr($varkA, 0, 32));

            //proses enkripsi 32 putaran
            for ($i = 0; $i < 32; $i++) {
                $varL = $kL;
                $varRsb = $kR;
                $varR = bindec($kR);
                $pang = pow(2, 32);
                $Total = fmod($varR + $JKe[$i], $pang);
                $tempT = decbin($Total);
                $pT = strlen($tempT);

                if ($pT < 32) {
                    $sRK = str_pad($tempT, 32, "0", STR_PAD_LEFT);
                } else {
                    $sRK = $tempT;
                }

                //pecah hasil Ri+Ki 
                $pRK[0] = bindec(substr($sRK, 0, 4));
                $pRK[1] = bindec(substr($sRK, 4, 4));
                $pRK[2] = bindec(substr($sRK, 8, 4));
                $pRK[3] = bindec(substr($sRK, 12, 4));
                $pRK[4] = bindec(substr($sRK, 16, 4));
                $pRK[5] = bindec(substr($sRK, 20, 4));
                $pRK[6] = bindec(substr($sRK, 24, 4));
                $pRK[7] = bindec(substr($sRK, 28, 4));

                //masukkan ke sbox
                for ($e = 0; $e < 8; $e++) {
                    $hSB[$e] = $SBox[$e][($pRK[$e])];
                }
                $btemp = array_merge($hSB);
                $arrtemp = "";

                for ($a = 0; $a < count($btemp); $a++) {
                    $arrtemp.=sprintf("%04s", decbin($btemp[$a]));
                }

                $xa = substr($arrtemp, 0, 11);
                $xn = substr($arrtemp, 11);
                $ND = $xn . $xa;

                $tempL = $varL;
                $XR = array();
                for ($r = 0; $r < 32; $r++) {
                    if ($i == 31) {
                        $XR[$r] = intval($ND[$r]) ^ intval($kL[$r]);
                    } else {
                        $XR[$r] = intval($ND[$r]) ^ intval($tempL[$r]);
                    }
                }

                $kL = $kR;
                $kR = implode($XR);

                $hasRL = "";
                $hasRL = strrev($kL) . strrev($kR);
            }

            $hasblock[$v][$p] = $hasRL;
            $arrBC = array_merge($hasblock);
            $v++;
        }
        //merubah biner menjadi hexa
        $varBlock = "";
        $hsBC = "";
        $t = 0;
        for ($m = 0; $m < count($arrBC); $m++) {
            $varBlock = implode($arrBC[$m]);
            for ($b = 0; $b < strlen($varBlock); $b++) {
                $tempBC[$b] = base_convert(substr($varBlock, $b * 4, 4), 2, 16);
                $hsBC = implode($tempBC);
            }
            $arrvarBlock[$t] = explode("\n", $hsBC);
            $t++;
        }

        $z = 0;
        $has2 = "";

        for ($a = 0; $a < count($arrvarBlock); $a++) {
            $has2.=substr($arrvarBlock[$a][$z], 0, 16);
        }

        return $has2;
    }

    function Dekrip($text, $key) {

        $H = array();
        $sH = "";
        for ($j = 0; $j < strlen($text); $j++) {
            $H[$j] = str_pad(base_convert($text[$j], 16, 2), 4, "0", STR_PAD_LEFT);
        }

        $c = "";
        for ($h = 0; $h < strlen($key); $h++) {
            $c.=sprintf("%08s", decbin(ord($key[$h])));
        }
        $hslkey = $c;
        $keyascii = strrev($c);

        //SBox
        $SBox[0] = array(4, 10, 9, 2, 13, 8, 0, 14, 6, 11, 1, 12, 7, 15, 5, 3);
        $SBox[1] = array(14, 11, 4, 12, 6, 13, 15, 10, 2, 3, 8, 1, 0, 7, 5, 9);
        $SBox[2] = array(5, 8, 1, 13, 10, 3, 4, 2, 14, 15, 12, 7, 6, 0, 9, 11);
        $SBox[3] = array(7, 13, 10, 1, 0, 8, 9, 15, 14, 4, 6, 12, 11, 2, 5, 3);
        $SBox[4] = array(6, 12, 7, 1, 5, 15, 13, 8, 4, 10, 9, 14, 0, 3, 11, 2);
        $SBox[5] = array(4, 11, 10, 0, 7, 2, 1, 13, 3, 6, 8, 5, 9, 12, 15, 14);
        $SBox[6] = array(13, 11, 4, 1, 3, 15, 5, 9, 0, 10, 14, 7, 6, 8, 2, 12);
        $SBox[7] = array(1, 15, 13, 0, 5, 7, 10, 4, 9, 2, 3, 14, 6, 11, 8, 12);

        //Pembagian Kunci dlm bentuk biner
        $K = array(substr($keyascii, -32, 32), substr($keyascii, -64, 32), substr($keyascii, -96, 32),
            substr($keyascii, -128, 32), substr($keyascii, -160, 32), substr($keyascii, -192, 32),
            substr($keyascii, -224, 32), substr($keyascii, 0, 32));

        //penjadwalan kunci dekripsi
        $JKd = array(bindec($K[0]), bindec($K[1]), bindec($K[2]), bindec($K[3]), bindec($K[4]), bindec($K[5]),
            bindec($K[6]), bindec($K[7]),
            bindec($K[7]), bindec($K[6]), bindec($K[5]), bindec($K[4]), bindec($K[3]), bindec($K[2]),
            bindec($K[1]), bindec($K[0]),
            bindec($K[7]), bindec($K[6]), bindec($K[5]), bindec($K[4]), bindec($K[3]), bindec($K[2]),
            bindec($K[1]), bindec($K[0]),
            bindec($K[7]), bindec($K[6]), bindec($K[5]), bindec($K[4]), bindec($K[3]), bindec($K[2]),
            bindec($K[1]), bindec($K[0]));

        $sL = "";
        $sR = "";
        $sH = implode("", $H);  //$sH adl variabel penampung $H
        $hasH = array();
        $l = 0;
        for ($o = 0; $o < strlen($sH); $o+=64) {
            $hasH[$l] = substr($sH, $o, 64);
            $l++;
        }
        $varkA = "";
        $varR = array();
        $varL = array();
        $hasblock = array();
        $tempL = "";

        $blockCounter = count($hasH);

        $arrBC = array();
        $nilkA = array();
        $v = 0;
        for ($p = 0; $p < $blockCounter; $p++) {
            $varkA = $hasH[$p];
            $sL = strrev(substr($varkA, -32, 32));
            $sR = strrev(substr($varkA, 0, 32));

            //proses dekripsi 32 putaran
            for ($i = 0; $i < 32; $i++) {
                $varL = $sL;
                $varRsb = $sR;
                $varR = bindec($sR);
                $pang = pow(2, 32);
                $Total = fmod($varR + $JKd[$i], $pang);
                $tempT = decbin($Total);

                $pT = strlen($tempT);
                //print_r ($pT);

                if ($pT < 32) {
                    $sRK = str_pad($tempT, 32, " ", STR_PAD_LEFT);
                } else {
                    $sRK = $tempT;
                }

                //pecah hasil Ri+Ki 
                $pRK[0] = bindec(substr($sRK, 0, 4));
                $pRK[1] = bindec(substr($sRK, 4, 4));
                $pRK[2] = bindec(substr($sRK, 8, 4));
                $pRK[3] = bindec(substr($sRK, 12, 4));
                $pRK[4] = bindec(substr($sRK, 16, 4));
                $pRK[5] = bindec(substr($sRK, 20, 4));
                $pRK[6] = bindec(substr($sRK, 24, 4));
                $pRK[7] = bindec(substr($sRK, 28, 4));

                //masukkan ke sbox
                for ($e = 0; $e < 8; $e++) {
                    $hSB[$e] = $SBox[$e][($pRK[$e])];
                }
                $btemp = array_merge($hSB);
                $arrtemp = "";

                for ($a = 0; $a < count($btemp); $a++) {
                    $arrtemp.=sprintf("%04s", decbin($btemp[$a]));
                }

                $xa = substr($arrtemp, 0, 11);
                $xn = substr($arrtemp, 11);
                $ND = $xn . $xa;

                $tempL = array();
                $tempL = $varL;
                $XR = array();
                for ($r = 0; $r < 32; $r++) {
                    if ($i == 31) {
                        $XR[$r] = intval($ND[$r]) ^ intval($sL[$r]);
                    } else {
                        $XR[$r] = intval($ND[$r]) ^ intval($tempL[$r]);
                    }
                }

                $sL = $sR;
                $sR = implode($XR);

                $hasRL = "";
                $hasRL = strrev($sL) . strrev($sR);
            }
            $hasblock[$v][$p] = $hasRL;
            $arrBC = array_merge($hasblock);
        }

        //merubah biner ke ascii
        $hsBC = "";
        for ($m = 0; $m < count($arrBC); $m++) {
            $varBCBlock = "";
            $varBCBlock = implode($arrBC[$m]);
            for ($b = 0; $b < strlen($varBCBlock); $b++) {
                $tempBC[$b] = chr(bindec(substr($varBCBlock, $b * 8, 8)));
                $hsBC = implode($tempBC);
            }
        }
        $asBC = "";
        $asBC = $hsBC;

        return $asBC;
    }

}

?>