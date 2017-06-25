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
use Illuminate\Support\Facades\Log;

class SmsController extends Controller
{
    function autoReplySMS(Request $request)
    {
        SmsController::send([$request->mobile], '尊敬的用户，感谢您的回信与对 MUNPANEL 的大力支持。然而，此短信并不被我们监听，如您有任何关于会议的问题，请询问会议组委；如您关于系统有任何问题，请联系 support@munpanel.com');
        return;
    }

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
                    Log::info('Successfully sent an SMS to '.$mobile. ' using Twillio, message of which is: '. $message);
                    Twilio::message($mobile, $message);
                } catch (\Exception $e) {
                    Log::info('Error sending an SMS to '.$mobile. ' using Twillio, message of which is: '. $message);
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
                if (is_null($result))
                {
                    Log::info('Error sending an SMS to '.$mobile. ' using luosimao, message of which is: '. $message);
                    return false;
                }
                if ($result->error == 0)
                {
                    Log::info('Successfully sent an SMS to '.$mobile. ' using luosimao, message of which is: '. $message);
                    return true;
                }
                Log::info('Error sending an SMS to '.$mobile. ' using luosimao, message of which is: '. $message);
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
                Log::info('Successfully called '.$mobile. ' using Twilio, code of which is: '. $code);
            } catch (\Exception $e) {
                Log::info('Error calling '.$mobile. ' using Twilio, code of which is: '. $code);
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
            if (is_null($result))
            {
                Log::info('Error calling '.$mobile. ' using luosimao, code of which is: '. $code);
                return false;
            }
            if ($result->error == 0)
            {
                Log::info('Successfully called '.$mobile. ' using luosimao, code of which is: '. $code);
                return true;
            }
            Log::info('Error calling '.$mobile. ' using luosimao, code of which is: '. $code);
            return false;
        }
        return true;
    }
}
