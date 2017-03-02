<?php

namespace App\Http\Controllers;

use Config;
use Illuminate\Http\Request;

class SmsController extends Controller
{
    static public function send($mobileList, $message) {
        if (count($mobileList) == 0)
            return false;
        else if (count($mobileList) == 1) { // single send
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, "http://sms-api.luosimao.com/v1/send.json");

            curl_setopt($ch, CURLOPT_HTTP_VERSION  , CURL_HTTP_VERSION_1_0 );
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 8);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
            curl_setopt($ch, CURLOPT_HEADER, FALSE);

            curl_setopt($ch, CURLOPT_HTTPAUTH , CURLAUTH_BASIC);
            curl_setopt($ch, CURLOPT_USERPWD  , 'api:key-'.Config::get('luosimao.key_sms'));

            curl_setopt($ch, CURLOPT_POST, TRUE);
            curl_setopt($ch, CURLOPT_POSTFIELDS, array('mobile' => $mobileList[0], 'message' => $message .'【MUNPANEL】'));

            $res = curl_exec( $ch );
            curl_close( $ch );
        } else { // batch send
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, "http://sms-api.luosimao.com/v1/send_batch.json");

            curl_setopt($ch, CURLOPT_HTTP_VERSION  , CURL_HTTP_VERSION_1_0 );
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 8);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
            curl_setopt($ch, CURLOPT_HEADER, FALSE);

            curl_setopt($ch, CURLOPT_HTTPAUTH , CURLAUTH_BASIC);
            curl_setopt($ch, CURLOPT_USERPWD  , 'api:key-'.Config::get('luosimao.key_sms'));

            curl_setopt($ch, CURLOPT_POST, TRUE);
            curl_setopt($ch, CURLOPT_POSTFIELDS, array('mobilei_list' => implode(',', $mobileList), 'message' => $message .'【MUNPANEL】'));

            $res = curl_exec( $ch );
            curl_close( $ch );
        }
    }

    static public function call($mobile, $code) {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "http://voice-api.luosimao.com/v1/verify.json");

        curl_setopt($ch, CURLOPT_HTTP_VERSION  , CURL_HTTP_VERSION_1_0 );
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_HEADER, FALSE);

        curl_setopt($ch, CURLOPT_HTTPAUTH , CURLAUTH_BASIC);
        curl_setopt($ch, CURLOPT_USERPWD  , 'api:key-'.Config::get('luosimao.key_call'));

        curl_setopt($ch, CURLOPT_POST, TRUE);
        curl_setopt($ch, CURLOPT_POSTFIELDS, array('mobile' => $mobile,'code' => $code));

        $res = curl_exec( $ch );
        curl_close( $ch );
    }
}
