<?php

namespace AfmLibre\Pathfinder\Helper;

class NumberHelper
{
    public static function numberSign(int $value): string
    {
        if ($value > 0) {
            return '+'.$value;
        }

        return (string)$value;

    }
}