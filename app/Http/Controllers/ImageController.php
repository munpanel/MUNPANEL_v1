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
    public function generateBadge($name, $school, $role)
    {
        $img = new Imagick();
        $draw = new ImagickDraw();
        $img->readImage(storage_path('app/images/templates/17c_badges_Delegate.jpg'));
        $draw->setTextAlignment(Imagick::ALIGN_CENTER);
        //$draw->setStrokeWidth(5);
        $draw->setFontSize(8);
        $draw->setFont('/usr/share/fonts/MUN/AGaramondPro-Bold.otf');
        $draw->setFillColor('#ffff00');
        $draw->annotation(500, 555, 'BJMUNC 2017');
        $img->drawImage($draw);
        header("Content-Type: image/png");
        return response($img)->header('Content-Type', 'image/jpg');
        $img->writeImage('test.jpg');
    }
}
