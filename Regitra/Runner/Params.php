<?php

namespace Regitra\Runner;

class Params
{
    const GEARS_AUTOMATIC = 'A';
    const GEARS_MANUAL = 'M';

    /**
     * Available cities
     *
     * @var array
     */
    protected static $_cities = array(
        'AL' => 'Alytus',
        'KN' => 'Kaunas',
        'KL' => 'Klaipėda',
        'MR' => 'Marijampolė',
        'PN' => 'Panevėžys',
        'SL' => 'Šiauliai',
        'TR' => 'Tauragė',
        'TL' => 'Telšiai',
        'UT' => 'Utena',
        'VL' => 'Vilnius',
        'MZ' => 'Mažeikiai',
        'BR' => 'Biržai',
        'RK' => 'Rokiškis',
        'KD' => 'Kėdainiai',
    );

    /**
     * Available categories
     *
     * @var array
     */
    protected static $_categories = array(
        'A' => 'A',
        'A1' => 'A1',
        'Aapr' => 'Aapr',
        'B' => 'B',
        'B1' => 'B1',
        'C' => 'C',
        'C1' => 'C',
    );

    public static function validate($city, $category, $gears = self::GEARS_MANUAL)
    {
        if (!\array_key_exists($city, self::$_cities))
        {
            if (!\in_array($city, self::$_cities))
            {
                throw new \Regitra\Exception('Invalid city "' . $city . '"');
            }
            else
            {
                $reversed = \array_flip(self::$_cities);
                $city = $reversed[$city];
            }
        }

        if (!\array_key_exists($category, self::$_categories))
        {
            if (!\in_array($category, self::$_categories))
            {
                throw new \Regitra\Exception('Invalid category "' . $category . '"');
            }
            else
            {
                $reversed = \array_flip(self::$_categories);
                $category = $reversed[$category];
            }
        }

        if ($gears != self::GEARS_AUTOMATIC && $gears != self::GEARS_MANUAL)
        {
            if ($gears != '')
            {
                throw new \Regitra\Exception('Invalid gears "' . $gears . '"');
            }
            else
            {
                $gears = self::GEARS_MANUAL;
            }
        }

        return array('city' => $city, 'category' => $category, 'gears' => $gears);
    }
}
