<?php
/**
 * Copyright (C) MUNPANEL
 * This file is part of MUNPANEL System.
 *
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential
 *
 * Developed by Adam Yi <xuan@yiad.am>
 */

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
    /**
     * Add text to an ImagickDraw in order to draw text on an Imagick graph with
     * fonts options for both Chinese and English fonts as well as automatic 
     * spacing options for generating badge. Auto wrap is enabled in this function,
     * so if text is too long it will automatically be wrapped from below to top.
     * From top to below wrapping mode will be developed in the future.
     *
     * @param Imagick $image the image to be drawed on (It will not draw on that image, but on $draw instead)
     * @param ImagickDraw $draw the instance will the annotation will be applied
     * @param int $x the X position of the center of the text
     * @param int $y the Y position of the center of the text
     * @param string $str the text to be annotated
     * @param int $size the size of the text to be annotated (in pt)
     * @param string $fontCN the name of the font used for Chinese characters
     * @param string $fontEN the name of the font used for English characters
     * @param bool $space whether to automatically add space between characters
     * @return ImagickDraw the instance with text annotated
     */
    private static function addText($image, $draw, $x, $y, $str, $size, $color, $fontCN, $fontEN, $space = false)
    {
        if (preg_match("/[\x7f-\xff]/", $str)) // if contains Chinese
        {
            $draw->setFont(storage_path('app/images/templates/fonts/' . $fontCN));
            $words = preg_split('/(?<!^)(?!$)/u', $str);
            if ($space) {
                switch(count($words))
                {
                    // Excel Version by Jiazhao Xu:
                    //=IF(LEN(A8)=2,MID(A8,1,1)&"      "&MID(A8,2,1),IF(LEN(A8)=3,MID(A8,1,1)&"  "&MID(A8,2,1)&"  "&MID(A8,3,1),IF(LEN(A8)=4,MID(A8,1,1)&" "&MID(A8,2,1)&" "&MID(A8,3,1)&" "&MID(A8,4,1),IF(LEN(A8)>4,A8))))
                    case 2: $space = "      "; break;
                    case 3: $space = " "; break;
                    case 4: $space = " "; break;
                    default: $space= "";
                }
            }
            else $space = '';
        }
        else
        {
            $draw->setFont(storage_path('app/images/templates/fonts/' . $fontEN));
            $words = preg_split('% %', $str);
            $space = ' ';
        }
        $draw->setFillColor($color);
        $draw->setFontSize($size * 25 / 6); // for 300 ppi

        $font_size = $size * 25 / 6;
        $max_height = 99999;

        $max_width = $image->getImageWidth() / 131 * 125;
        
        // Holds calculated height of lines with given font, font size
        $total_height = 0;

        // Run until we find a font size that doesn't exceed $max_height in pixels
        while ( 0 == $total_height || $total_height > $max_height ) {
            if ( $total_height > 0 ) $font_size--; // we're still over height, decrease font size and try again

            $draw->setFontSize($font_size);

            // Calculate number of lines / line height
            // Props users Sarke / BMiner: http://stackoverflow.com/questions/5746537/how-can-i-wrap-text-using-imagick-in-php-so-that-it-is-drawn-as-multiline-text
            //$words = preg_split('%\s%', $str);//, PREG_SPLIT_NO_EMPTY);
            $lines = array();
            $l = count($words);
            $i = $l;
            $line_height_ratio = 1;
            
            $line_height = 0;

            while ( $l > 0 ) { 
                $metrics = $image->queryFontMetrics( $draw, implode($space, array_slice($words, --$i - 1) ) );
                $line_height = max( $metrics['textHeight'], $line_height );
                if ( $metrics['textWidth'] > $max_width || $i < 1 ) {
                    $lines[] = implode($space, array_slice($words, ++$i - 1) );
                    if ($i == 1)
                        break;
                    $words = array_slice( $words, 0, --$i);
                    $l = $i ;
                }
            }

            $total_height = count($lines) * $line_height * $line_height_ratio;



            if ( $total_height === 0 ) return false; // don't run endlessly if something goes wrong
        }

        // Writes text to image
        for( $i = 0; $i < count($lines); $i++ ) {
            $draw->annotation($x, $y - ($i * $line_height * $line_height_ratio), $lines[$i] );
        }
//        $draw->annotation($x, $y, $str);
        return $draw;
    }

    /**
     * Render an aztec code to a image
     * Rewritten according to Metzli\Renderer\PngRenderer, by Adam Yi
     *
     * @param AztecCode $code the aztec code to be rendered
     * @param int $sizeX the X size of the generated image
     * @param int $sizeY the Y size of the generated image
     * @return Imagick the image generated
     */
    private static function render(AztecCode $code, $sizeX, $sizeY)
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

    /**
     * Generate a badge image.
     *
     * @param string $template the name of the template of the badge used
     * @param string $name the name of the person printed in the badge
     * @param string $school the name of the school printed in the badge
     * @param string $role the name of the role printed in the badge
     * @param string $title the title text printed in the badge
     * @param string $mode the output mode of the image (RGB/CMYK)
     * @param string $filename the output filename of the image (or 'output' for displaying without saving)
     * @param string $cardid the ID of the card used to generate the Aztec Code in the badge
     * @param boolean $blank whether this is a blank badge
     * @return \Illuminate\Http\Response|string the image to display or "success"
     */
    public static function generateBadge($template = 'Delegate', $name, $school, $role, $title, $mode = 'RGB', $filename = 'output', $cardid = 'INVALID', $blank = false)
    {
        $img = new Imagick();
        $draw = new ImagickDraw();
        $img->readImage(storage_path('app/images/templates/17c_badge_template_' . $template . '.jpg'));
        $draw->setTextAlignment(Imagick::ALIGN_CENTER);
        //$draw->setStrokeWidth(5);
        //$img->setImageColorspace (imagick::COLORSPACE_CMYK);
        $w = $img->getImageWidth() / 2;
        if (!$blank)
        {
            ImageController::addText($img, $draw, $w, 605, "BJMUNC 2017\n" . $title, 12, '#FFFFFF', 'PingHeiLight.ttf', 'DINPRORegular.otf');
            ImageController::addText($img, $draw, $w, 953, $role, 24, '#FFFFFF', 'PingFang Heavy.ttf', 'MyriadSetProSemibold.ttf', true);
            ImageController::addText($img, $draw, $w, 1105, $name, 21, '#000000', 'PingFang Bold.ttf', 'MyriadSetProSemibold.ttf');
            ImageController::addText($img, $draw, $w, 1175, $school, 12, '#000000', 'PingFang Regular.ttf', 'MyriadProLight.otf');
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
        }
        //Color is reversed to transform to CMYK, will try other methods
        //if ($mode == 'CMYK')
        //    $img->setImageColorspace (imagick::COLORSPACE_CMYK);
        if ($filename == 'output')
            return response($img)->header('Content-Type', 'image/jpg');
        $img->writeImage(storage_path('app/images/badges/'.$filename));
	return 'success';
    }

    /**
     * (Deprecated) Generate the badge images for a committee
     * Please note that this method does not generate valid cards
     * since it does not use the Card class and all card IDs are
     * "INVALID".
     * Do not use this method. It will be removed in future versions.
     *
     * @param int $cid the ID of the committee of which delegates' badge images are generated
     * @return string generating results
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
