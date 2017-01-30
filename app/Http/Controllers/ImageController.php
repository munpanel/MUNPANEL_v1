<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Card;
use App\User;
use App\Committee;
use App\Delegate;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use \Imagick;
use \ImagickDraw;
use Metzli\Encoder\Encoder;
use Metzli\Encoder\AztecCode;
//use Metzli\Renderer\PngRenderer;


class ImageController extends Controller
{
    private static function addText($draw, $x, $y, $str, $size, $color, $fontCN, $fontEN)
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

    private static function render(AztecCode $code, $sizeX, $sizeY) // Rewritten according to Metzli\Renderer\PngRenderer, by Adam Yi
    {
        $matrix = $code->getMatrix();
        $factorX = $sizeX / $matrix->getWidth();
        $factorY = $sizeY / $matrix->getHeight();
        $img = new Imagick();
        $img->newImage($sizeX, $sizeY, 'transparent');
        $img->setImageFormat("png");
        //$img->setImageColorspace (imagick::COLORSPACE_CMYK);
        $draw = new ImagickDraw();
        $draw->setFillColor('#000000');
        for ($x = 0; $x < $matrix->getWidth(); $x++) {
            for ($y = 0; $y < $matrix->getHeight(); $y++) {
                if ($matrix->get($x, $y)) {
                    $draw->rectangle($x * $factorX, $y * $factorY, (($x + 1) * $factorX ), (($y + 1) * $factorY ));
                    // We don't know why Metzli minuses one here, but it seems great if we don't.
                    //imagefilledrectangle($im, $x * $f, $y * $f, (($x + 1) * $f - 1), (($y + 1) * $f - 1), $fg);
                }
            }
        }
        $img->drawImage($draw);
        return $img;
    }

    public static function generateBadge($template = 'Delegate', $name, $school, $role, $title, $mode = 'RGB', $filename = 'output', $cardid = 'INVALID')
    {
        $img = new Imagick();
        $draw = new ImagickDraw();
        $img->readImage(storage_path('app/images/templates/17c_badge_template_' . $template . '.jpg'));
        $draw->setTextAlignment(Imagick::ALIGN_CENTER);
        //$draw->setStrokeWidth(5);
        //$img->setImageColorspace (imagick::COLORSPACE_CMYK);
        $w = $img->getImageWidth() / 2;
        ImageController::addText($draw, $w, 605, "BJMUNC 2017\n" . $title, 12, '#FFFFFF', 'PingHeiLight.ttf', 'DINPRORegular.otf');
        ImageController::addText($draw, $w, 953, $role, 24, '#FFFFFF', 'PingHeiBold.ttf', 'MyriadSetProSemibold.ttf');
        ImageController::addText($draw, $w, 1105, $name, 21, '#000000', 'PingHeiSemibold.ttf', 'MyriadSetProSemibold.ttf');
        ImageController::addText($draw, $w, 1175, $school, 12, '#000000', 'PingHeiLight.ttf', 'MyriadProLight.otf');
        //$code = Encoder::encode(uniqid());
        $code = Encoder::encode($cardid);
        //$renderer = new PngRenderer();
        //$aztec = new Imagick();
        //$aztec->readImageBlob($renderer->render($code));
        $codeSize = 45 * 25 /6 ;
        $aztec = ImageController::render($code, $codeSize, $codeSize);
        //return response($aztec->getImageBlob())->header('Content-Type', 'image/png');
        //return response($renderer->render($code))->header('Content-Type', 'image/png');
        //$aztec->setImageColorspace (imagick::COLORSPACE_CMYK); 
        $img->drawImage($draw);
        $img->compositeImage($aztec, Imagick::COMPOSITE_MATHEMATICS, $w - $codeSize / 2, 1315 - $codeSize / 2);
        if ($mode == 'CMYK')
            $aztec->setImageColorspace (imagick::COLORSPACE_CMYK);
        if ($filename == 'output')
            return response($img)->header('Content-Type', 'image/jpg');
        $img->writeImage(storage_path('app/images/badges/'.$filename));
    }

    public function committeeBadge($cid)
    {
        //$committee = Committee::findOrFail($cid);
        //$delegates = $committee->delegates->count();//->where('status', 'paid')->count();
        //return $delegates;
        $delegates = Delegate::where('status', 'paid')->get();
        $return = '';   
        foreach($delegates as $delegate)
        {
            if ($delegate->committee->is_allocated)
            {
                ImageController::generateBadge('Delegate', $delegate->user->name, $delegate->school->name, $delegate->nation->name, $delegate->committee->name, 'CMYK', $delegate->committee->name.'_'.$delegate->user_id.'_'.$delegate->user->name.'.jpg');
                $return .= $delegate->committee->name.'_'.$delegate->user_id.'_'.$delegate->user->name.'.jpg<br>';
            }
        }
        return $return;
    }
}
