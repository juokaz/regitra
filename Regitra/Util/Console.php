<?php

namespace Regitra\Util;

class Console
{
    static function getBeep ($int_beeps = 1)
    {
        $string_beeps = '';
        
        for ($i = 0; $i < $int_beeps; $i++)
        {
            $string_beeps .= "\x07";
        }

        return $string_beeps;
    }
}