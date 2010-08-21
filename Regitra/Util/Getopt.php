<?php

namespace Regitra\Util;

class Getopt
{
    public function process($args)
    {
        if (empty($args)) {
            return array();
        }

        $opts = array();

        if (isset($args[0][0]) && $args[0][0] != '-') {
            array_shift($args);
        }
        
        reset($args);

        array_map('trim', $args);

        while (list($i, $arg) = each($args))
        {
            if ($arg == '') {
                continue;
            }

            if (\strpos($arg, '--') !== false && current($args) != '') {
                $opts[\str_replace('--', '', $arg)] = current($args);
                next($args);
            }
        }

        return $opts;
    }
}