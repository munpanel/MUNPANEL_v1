<?php
/**
 * Copyright (C) MUNPANEL
 * This file is part of MUNPANEL System.
 *
 * Open-sourced under AGPL v3 License.
 */

namespace App\Http\Controllers;

use App\Delegate;
use App\Dais;
use App\Card;
use App\Volunteer;
use Illuminate\Http\Request;

class CardController extends Controller
{
    /**
     * Generate cards for all delegates.
     *
     * @return void
     */
    public function generateCardsDelegates()
    {
        $delegates = Delegate::where('status', 'paid')->where('conference_id', 3)->get();
        foreach ($delegates as $delegate)
        {
            if ($delegate->cards()->count() == 0)
            {
                $card = new Card;
                //$card->id = uniqid();
                $card->id = generateID();
                $card->reg_id = $delegate->reg_id;
                $card->template = 'Delegate';
                $card->name = $delegate->reg->user->name;
                $card->school = is_object($delegate->school)?$delegate->school->name:$delegate->reg->getInfo('personinfo.school');
                if (!is_object($delegate->nation))
                {
                    echo $delegate->reg_id.' '.$delegate->committee->name.'<br>';
                    continue;
                }
                $card->role = $delegate->nation->name;
                $card->title = $delegate->committee->name;
                $card->save();
            }
        }
    }

    /**
     * Generate cards for all dais.
     *
     * @return void
     */
    public function generateCardsDais()
    {
        $dais = Dais::where('status', 'success')->where('conference_id', 3)->get();
        foreach ($dais as $d)
        {
            $card = new Card;
            $card->id = generateID();
            $card->reg_id = $d->reg_id;
            $card->template = 'Dais';
            $card->name = $d->reg->user->name;
            $card->school = $d->reg->getInfo('personinfo.school');
            $card->role = $d->position; //$d->committee->language == 'English' ? 'Dais':'主席';
            $card->title = $d->committee->name;
            $card->save();
        }
    }

    /**
     * Generate cards for all volunteers.
     *
     * @return void
     */
    public function generateCardsVolunteers()
    {
        $volunteers = Volunteer::where('status', 'paid')->where('conference_id', 3)->get();
        foreach ($volunteers as $d)
        {
            $card = new Card;
            $card->id = generateID();
            $card->reg_id = $d->reg_id;
            $card->template = 'Volunteer';
            $card->name = $d->reg->user->name;
            $card->school = is_object($d->school)?$d->school->name:$d->reg->getInfo('personinfo.school');
            $card->role = '志愿者';
            $card->title = 'Volunteer';
            $card->save();
        }
    }

    /**
     * Generate badges images for all cards.
     *
     * @return void
     */
    public function generateCardBadges()
    {
        set_time_limit(0);
        $cards = Card::all();
        foreach($cards as $card)
        {
            ImageController::generateBadge($card->template, $card->name, $card->school, $card->role, $card->title, 'CMYK', $card->template.'.'.$card->title.'.'.$card->id.'.'.$card->user_id.'.'.$card->name.'.jpg', $card->id, $card->blank);
        }
    }

    /**
     * Regenerate the badge image for one card.
     *
     * @param int $id the ID of the card whose badge is being regenerated
     * @return void
     */
    public function regenerateCardBadge($id)
    {
        $card = Card::findOrFail($id);
        ImageController::generateBadge($card->template, $card->name, $card->school, $card->role, $card->title, 'CMYK', $card->template.'.'.$card->title.'.'.$card->id.'.'.$card->user_id.'.'.$card->name.'.jpg', $card->id, $card->blank);
    }

    /**
     * Create new Card instance.
     *
     * @param string $template the template of the card
     * @param int $uid the ID of ther user to whom the card belongs
     * @param string $name the name displayed in the card
     * @param string $school the school name displayed in the card
     * @param string $role the role name displayed in the card
     * @param string $title the title displayed in the card
     * @return Card the Card instance created
     */
    public function newCard($template, $uid, $name, $school, $role, $title)
    {
        $card = new Card;
        $card->id = generateID();
        $card->user_id = $uid;
        $card->template = $template;
        $card->name = $name;
        $card->school = $school;
        $card->role = $role;
        $card->title = $title;
        $card->save();
	return $card;
    }
    
    /**
     * Import cards from csv to database.
     *
     * @return string import result
     */
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
                $card->id = generateID();
                //$card->reg_id = $data[0];
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
