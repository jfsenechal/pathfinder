<?php

namespace AfmLibre\Pathfinder\Helper;

class StringHelper
{
    public static function clean(?string $value): ?string
    {
        if ($value === null) {
            return null;
        }
        /*$patterns = [
            '#\(inoffensif\) \(voir description\) ;#',
            '#\(inoffensif\) ;#',
            '#inoffensif;#',
        ];*/

        $pattern = '#(?:\(inoffensif\) \(voir description\)|\(inoffensif\))(?: ;)?|inoffensif;#';

        return preg_replace($pattern, '', $value);

    }
}
