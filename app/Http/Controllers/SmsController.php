<?php
/**
 * Copyright (C) Console iT
 * This file is part of MUNPANEL System.
 *
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential
 *
 * Developed by Adam Yi <xuan@yiad.am>
 */

// To-Do: own SMS API

namespace App\Http\Controllers;

use Config;
use Twilio;
use Illuminate\Http\Request;

class SmsController extends Controller
{
    /**
     * Send an SMS message to one/multiple mobile number(s).
     *
     * @param array $mobileList a list of mobile numbers to send message to
     * @param string $message the content of the SMS
     * @return void
     */
    static public function send($mobileList, $message) {
	//To-Do: store all messages sent
        if (count($mobileList) == 0)
            return false;
        else if (count($mobileList) == 1) { // single send
            $mobile = $mobileList[0];
            if (strlen($mobile) < 6)
                return false;
            if (substr($mobile, 0, 3) == '+86')
                $mobile = substr($mobile, 3);
            if ($mobile[0] == '+')
            {
                //twillio International
                try {
                    Twilio::message($mobile, $message);
                } catch (\Exception $e) {
                    return false;
                }

            }
            else
            {
                //luosimao Chinese
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, "http://sms-api.luosimao.com/v1/send.json");

                curl_setopt($ch, CURLOPT_HTTP_VERSION  , CURL_HTTP_VERSION_1_0 );
                curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 8);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
                curl_setopt($ch, CURLOPT_HEADER, FALSE);

                curl_setopt($ch, CURLOPT_HTTPAUTH , CURLAUTH_BASIC);
                curl_setopt($ch, CURLOPT_USERPWD  , 'api:key-'.Config::get('luosimao.key_sms'));

                curl_setopt($ch, CURLOPT_POST, TRUE);
                curl_setopt($ch, CURLOPT_POSTFIELDS, array('mobile' => $mobile, 'message' => $message .'【MUNPANEL】'));

                $res = curl_exec( $ch );
                curl_close( $ch );
                $result = json_decode($res);
                if ($result->error == 0)
                    return true;
                return false;
            }
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
        return true;
    }

    /**
     * Call a user to give a verification code (4-6 digits).
     *
     * @param string $mobile the number to call
     * @param int $code the verification code
     * @return void
     */

    static public function call($mobile, $code) {
        if (strlen($mobile) < 6)
            return false;
        if (substr($mobile, 0, 3) == '+86')
            $mobile = substr($mobile, 3);
        if ($mobile[0] == '+')
        {
            try {
                Twilio::call($mobile, function($message) {
                    $message->say("Welcome to mengpanle. Your code is ".implode(' ',str_split(session("code"))));
                    $message->pause(["length" => "1"]);
                    $message->say("Welcome to mengpanle. Your code is ".implode(' ',str_split(session("code"))));
                    $message->say("Thank you.");
                });
            } catch (\Exception $e) {
                return false;
            }
        }
        else
        {
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
            $result = json_decode($res);
            if ($result->error == 0)
                return true;
            return false;
        }
        return true;
    }
}
