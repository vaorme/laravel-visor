<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function removeSpecialAccents($string){
        $unwanted_letters = array(
            'Š'=>'S',
            'š'=>'s',
            'Ž'=>'Z',
            'ž'=>'z',
            'À'=>'A',
            'Á'=>'A',
            'Â'=>'A',
            'Ã'=>'A',
            'Ä'=>'A',
            'Å'=>'A',
            'Æ'=>'A',
            'Ç'=>'C',
            'È'=>'E',
            'É'=>'E',
            'Ê'=>'E',
            'Ë'=>'E',
            'Ì'=>'I',
            'Í'=>'I',
            'Î'=>'I',
            'Ï'=>'I',
            'Ñ'=>'N',
            'Ò'=>'O',
            'Ó'=>'O',
            'Ô'=>'O',
            'Õ'=>'O',
            'Ö'=>'O',
            'Ø'=>'O',
            'Ù'=>'U',
            'Ú'=>'U',
            'Û'=>'U',
            'Ü'=>'U',
            'Ý'=>'Y',
            'Þ'=>'B',
            'ß'=>'Ss',
            'à'=>'a',
            'á'=>'a',
            'â'=>'a',
            'ã'=>'a',
            'ä'=>'a',
            'å'=>'a',
            'æ'=>'a',
            'ç'=>'c',
            'è'=>'e',
            'é'=>'e',
            'ê'=>'e',
            'ë'=>'e',
            'ì'=>'i',
            'í'=>'i',
            'î'=>'i',
            'ï'=>'i',
            'ð'=>'o',
            'ñ'=>'n',
            'ò'=>'o',
            'ó'=>'o',
            'ô'=>'o',
            'õ'=>'o',
            'ö'=>'o',
            'ø'=>'o',
            'ù'=>'u',
            'ú'=>'u',
            'û'=>'u',
            'ý'=>'y',
            'þ'=>'b',
            'ÿ'=>'y'
        );
        return strtr( $string, $unwanted_letters );
    }
    public function sanitizeText($string){
        $slug = preg_replace('/[^A-Za-z0-9. -]/', '', $string);
        $slug = str_replace(' ', '-', trim($slug));

        return $slug;
    }
}
