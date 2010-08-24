<?php

namespace Regitra\Util;

class Http
{
    /**
     * Get ip
     *
     * @return string
     */
    public static function getIp()
    {
        return \trim(\file_get_contents('http://whatismyip.org/'));
    }
}