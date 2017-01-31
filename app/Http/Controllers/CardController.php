<?php

namespace App\Http\Controllers;

use App\Delegate;
use App\Dais;
use App\Card;
use App\Volunteer;
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
            if ($delegate->committee->is_allocated && $delegate->cards()->count() == 0)
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

    public function generateCardsDais()
    {
        $dais = Dais::all();
        foreach ($dais as $d)
        {
            $card = new Card;
            $card->id = CardController::generateID();
            $card->user_id = $d->user_id;
            $card->template = 'Dais';
            $card->name = $d->user->name;
            $card->school = $d->school->name;
            $card->role = $d->committee->language == 'English' ? 'Dais':'主席';
            $card->title = $d->committee->name;
            $card->save();
        }
    }

    public function generateCardsVolunteers()
    {
        $volunteers = Volunteer::where('status', 'paid')->get();
        foreach ($volunteers as $d)
        {
            $card = new Card;
            $card->id = CardController::generateID();
            $card->user_id = $d->user_id;
            $card->template = 'Volunteer';
            $card->name = $d->user->name;
            $card->school = $d->school->name;
            $card->role = '志愿者';
            $card->title = 'Volunteer';
            $card->save();
        }
    }

    public function generateCardBadges()
    {
        set_time_limit(0);
        $cards = Card::all();
        foreach($cards as $card)
        {
            ImageController::generateBadge($card->template, $card->name, $card->school, $card->role, $card->title, 'CMYK', $card->template.'.'.$card->title.'.'.$card->id.'.'.$card->user_id.'.'.$card->name.'.jpg', $card->id, $card->blank);
        }
    }

    public function newCard($template, $uid, $name, $school, $role, $title)
    {
        $card = new Card;
        $card->id = CardController::generateID();
        $card->user_id = $uid;
        $card->template = $template;
        $card->name = $name;
        $card->school = $school;
        $card->role = $role;
        $card->title = $title;
        $card->save();
    }
    
    public function importCards()
    {
        if (($handle = fopen("/var/www/munpanel/test.csv", "r")) !== FALSE) {
            $resp = "";
            while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
                $num = count($data);
                for ($c=0; $c < $num; $c++) {
                    $resp = $resp. $data[$c] . "<br />\n";
                }
                $card = new Card;
                $card->id = CardController::generateID();
                $card->user_id = $data[0];
                $card->template = $data[1];
                $card->title = $data[2];
                $card->name = $data[3];
                $card->school = $data[4];
                $card->role = $data[5];
                $card->save();
                $resp = $resp. response()->json($card) . "<br />\n";
            }
            fclose($handle);
            return $resp;
        }
    }


}
