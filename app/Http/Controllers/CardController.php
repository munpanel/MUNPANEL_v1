<?php

namespace App\Http\Controllers;

use App\Delegate;
use App\Card;
use Illuminate\Http\Request;

class CardController extends Controller
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
            $token .= $codeAlphabet[CardController::crypto_rand_secure(0,strlen($codeAlphabet))];
        }
        return $token;
    }
    public function generateCardsDelegates()
    {
        $delegates = Delegate::where('status', 'paid')->get();
        foreach ($delegates as $delegate)
        {
            if ($delegate->committee->is_allocated)
            {
                $card = new Card;
                //$card->id = uniqid();
                $card->id = CardController::generateID();
                $card->user_id = $delegate->user_id;
                $card->template = 'Delegate';
                $card->name = $delegate->user->name;
                $card->school = $delegate->school->name;
                $card->role = $delegate->nation->name;
                $card->title = $delegate->committee->name;
                $card->save();
            }
        }
    }

    public function generateCardBadges()
    {
        $cards = Card::all();
        foreach($cards as $card)
        {
            ImageController::generateBadge($card->template, $card->name, $card->school, $card->role, $card->title, 'CMYK', $card->template.'.'.$card->title.'.'.$card->id.'.'.$card->user_id.'.'.$card->name.'.jpg', $card->id);
        }
    }

}
