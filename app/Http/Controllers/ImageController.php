<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Card;
use User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use \Imagick;
use \ImagickDraw;


class ImageController extends Controller
{
    private function addText($draw, $x, $y, $str, $size, $color, $fontCN, $fontEN)
    {
        if (preg_match("/[\x7f-\xff]/", $str)) // if contains Chinese
        {
            $draw->setFont(storage_path('app/images/templates/fonts/' . $fontCN));
        }
        else
        {
            $draw->setFont(storage_path('app/images/templates/fonts/' . $fontEN));
        }
        $draw->setFillColor($color);
        $draw->setFontSize($size * 25 / 6); // for 300 ppi
        $draw->annotation($x, $y, $str);
    }

    public function generateBadge($name, $school, $role, $title)
    {
        $img = new Imagick();
        $draw = new ImagickDraw();
        $img->readImage(storage_path('app/images/templates/17c_badge_template_Delegate.jpg'));
        $draw->setTextAlignment(Imagick::ALIGN_CENTER);
        //$draw->setStrokeWidth(5);
        ImageController::addText($draw, 500, 570, "BJMUNC 2017\n" . $title, 12, '#000000', 'PingHeiLight.ttf', 'DINPRORegular.otf');
        ImageController::addText($draw, 500, 880, $role, 24, '#000000', 'PingHeiBold.ttf', 'MyriadSetProSemibold.ttf');
        ImageController::addText($draw, 500, 1070, $name, 21, '#FFFFFF', 'PingHeiSemibold.ttf', 'MyriadSetProSemibold.ttf');
        ImageController::addText($draw, 500, 1140, $school, 12, '#FFFFFF', 'PingHeiLight.ttf', 'MyriadProLight.otf');
        $img->drawImage($draw);
        header("Content-Type: image/png");
        return response($img)->header('Content-Type', 'image/jpg');
        $img->writeImage('test.jpg');
    }
}
