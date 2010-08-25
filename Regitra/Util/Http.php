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
        static $ipAddress;

        try
        {
            if (false === ($ip = @\file_get_contents('http://whatismyip.org/')))
            {
                throw new \Regitra\Exception('IP address cannot be retrieved');
            }

            $ipAddress = $ip;
        }
        catch (\Regitra\Exception $e)
        {
            $ip = $ipAddress;
        }

        return \trim($ip);
    }
}