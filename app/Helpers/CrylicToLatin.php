<?php

namespace App\Helpers;

use Illuminate\Support\Str;

class CrylicToLatin 
{
    public static function convert(string $string): string
    {
        $map = [
            'а' => 'a',   
            'А' => 'A',   
            'б' => 'b',   
            'Б' => 'B',   
            'в' => 'v',
            'В' => 'V',
            'г' => 'g',
            'Г' => 'G',
            'д' => 'd',
            'Д' => 'D',
            'е' => 'e',
            'Е' => 'E',
            'ё' => 'yo',
            'Ё' => 'Yo',
            'ж' => 'j',
            'Ж' => 'J',
            'з' => 'z',
            'З' => 'Z',
            'и' => 'i',
            'И' => 'I',
            'й' => 'y',
            'Й' => 'Y',
            'к' => 'k',
            'К' => 'K',
            'л' => 'l',
            'Л' => 'L',
            'м' => 'm',
            'М' => 'M',
            'н' => 'n',
            'Н' => 'N',
            'о' => 'o',
            'О' => 'O',
            'п' => 'p',
            'П' => 'P',
            'р' => 'r',
            'Р' => 'R',
            'с' => 's',
            'С' => 'S',
            'т' => 't',
            'Т' => 'T',
            'у' => 'u',
            'У' => 'U',
            'ф' => 'f',
            'Ф' => 'F',
            'х' => 'x',
            'Х' => 'X',
            'ц' => 'ts',
            'Ц' => 'Ts',
            'ч' => 'ch',
            'Ч' => 'Ch',
            'ш' => 'sh',
            'Ш' => 'Sh',
            'щ' => 'shch',
            'Щ' => 'Shch',
            'ъ' => '\'',
            'Ъ' => '\'',
            'ы' => 'y',
            'Ы' => 'Y',
            'ь' => '',
            'Ь' => '',
            'э' => 'e',
            'Э' => 'E',
            'ю' => 'yu',
            'Ю' => 'Yu',
            'я' => 'ya',
            'Я' => 'Ya',
            'ў' => 'o\'',
            'Ў' => 'O\'',
            'қ' => 'q',
            'Қ' => 'Q',
            'ғ' => 'g\'',
            'Ғ' => 'G\'',
            'ҳ' => 'h',
            'Ҳ' => 'H',
        ];
        return Str::replace(array_keys($map), array_values($map), $string);
    }

}