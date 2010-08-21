<?php

namespace Regitra\Processor;

use Regitra\Scrapper;

class Processor
{
    /**
     * Data to be processed
     *
     * @var Scrapper\DataObject 
     */
    protected $_data = null;

    /**
     * Processor object
     *
     * @param Scrapper\DataObject $data
     */
    public function __construct(Scrapper\DataObject $data)
    {
        $this->_data = $data;
    }

    /**
     * Extract slots
     *
     * @return array
     */
    public function extractSlots()
    {
        $result = array();

        if (\strlen($this->_data) == 0)
            return $result;

        $html = $this->_data->getDomDocument();

        $xpath = new \DOMXPath($html);
        $data = $xpath->query("//table/th[@class='lent_yra']");

        $slots = array();

        foreach ($data as $item)
        {
            $value = $item->nodeValue;

            if ($value == 'Diena')
                continue;

            $slots[] = $value;
        }

        $xpath = new \DOMXPath($html);
        $data = $xpath->query("//tr/th[@class='lent_yra']");

        foreach ($data as $item)
        {
            $date = $item->parentNode->firstChild->nodeValue;
            $value = (int) $item->nodeValue;

            $i = 0;
            while (isset($item->previousSibling))
            {
                $item = $item->previousSibling;
                $i++;
            }

            // date column
            if ($i == 0)
            {
                continue;
            }

            list($year, $month, $day) = explode('.', $date);
            list($hour, $minute) = explode(':', $slots[$i - 2]);

            $slot = new Slot($value, $year, $month, $day, $hour, $minute);

            $result[] = $slot;
        }

        return $result;
    }
}