<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HelperController extends Controller
{
    private static function crypto_rand_secure($min, $max) {
        $range = $max - $min;
        if ($range < 0) return $min; // not so random...
        $log = log($range, 2);
        $bytes = (int) ($log / 8) + 1; // length in bytes
        $bits = (int) $log + 1; // length in bits
        $filter = (int) (1 << $bits) - 1; // set all lower bits to 1
        do {
            $rnd = hexdec(bin2hex(openssl_random_pseudo_bytes($bytes)));
            $rnd = $rnd & $filter; // discard irrelevant bits
        } while ($rnd >= $range);
        return $min + $rnd;
    }

    public static function generateID($length=16){
        $token = "";
        $codeAlphabet = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
        $codeAlphabet.= "abcdefghijklmnopqrstuvwxyz";
        $codeAlphabet.= "0123456789";
        for($i=0;$i<$length;$i++){
            $token .= $codeAlphabet[HelperController::crypto_rand_secure(0,strlen($codeAlphabet))];
        }
        return $token;
    }

    /**
     * 时间格式化
     * @link http://php.net/manual/en/function.time.php
     * @param string $date 时间
     * @param boolean $concise 是否仅显示几天前而不显示具体时间
     * @return string 格式化后的时间（如几天前）
     */
    function nicetime($date, $concise = false)
    {
            if(empty($date))
            {
                    return false;
            }

            $periods = array('秒钟', '分钟', '小时', '天', '周', '月', '年');
            $lengths = array("60", "60", "24", "7", "4.35", "12");

            $now = time();
            $unix_date = strtotime($date);

            if($now > $unix_date)
            {
                    $difference = $now - $unix_date;
                    $tense = '前';
            }
            else
            {
                    $difference = $unix_date - $now;
                    $tense = '后';
            }

            for($j = 0; $difference >= $lengths[$j] && $j < count($lengths) - 1; $j++)
            {
                    $difference /= $lengths[$j];
            }

            $difference = round($difference);

            if($concise)
                return "$difference$periods[$j]{$tense}"
            return "$date（$difference$periods[$j]{$tense}）";
    }

}
