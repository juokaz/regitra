<?php

namespace Regitra\License;

use Regitra\Scrapper;

class License
{
    /**
     * Is driving license ready
     *
     * @param Scrapper\DataObject $data
     * @return boolean
     */
    public function isReady(Scrapper\DataObject $data)
    {
        return \strpos($data, 'negalima') === false;
    }
}