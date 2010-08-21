<?php

namespace Regitra\Register;

use Regitra\Scrapper;

class Register
{
    /**
     * Get current user slot
     *
     * @param Scrapper\DataObject $data
     * @return Slot
     */
    public function getCurrentSlot(Scrapper\DataObject $data)
    {
        if (\strlen($data) == 0) {
            throw new \Regitra\Exception ('Empty document');
        }

        \preg_match("/PAKEISTIPR\('(\w{1})','(\w{1})',document.forms\[0\]\.PRASYMO_NR_P\.value,'(\w{2})'/", (string) $data, $params);

        $html = $data->getDomDocument();

        $xpath = new \DOMXPath($html);

        $data = $xpath->query('//tr[contains(td/text(),"Egzamino data, laikas:")]/td[position()=2]');
        \preg_match('/(\d{4})-(\d{2})-(\d{2}) (\d{2}):(\d{2})/', $data->item(0)->nodeValue, $matches);

        $slot = new Slot($params[3], $params[2], $params[1], $matches[1], $matches[2], $matches[3], $matches[4], $matches[5]);

        return $slot;
    }

    /**
     * Get slot id
     *
     * @param \Regitra\Processor\Slot $slot
     * @param Scrapper\DataObject $data
     * @return string
     */
    public function getSlotId(\Regitra\Processor\Slot $slot, Scrapper\DataObject $data)
    {
        if (\strlen($data) == 0)
        {
            throw new \Regitra\Exception('Empty document');
        }

        $html = $data->getDomDocument();

        $xpath = new \DOMXPath($html);
        $data = $xpath->query("//table/tr[position()=1]/th[@class='lent_yra']");

        $slots = array();

        foreach ($data as $item)
        {
            $value = $item->nodeValue;

            if ($value == 'Diena')
                continue;

            $slots[] = $value;
        }

        $xpath = new \DOMXPath($html);
        $data = $xpath->query("//tr[position()>1]/th[@class='lent_yra']");

        foreach ($data as $item)
        {
            $item_ = $item;
            $date = $item->parentNode->firstChild->nodeValue;

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

            $slot_ = new \Regitra\Processor\Slot(/* dummy places */ 1, $year, $month, $day, $hour, $minute);

            if ($slot->getRawDate() == $slot_->getRawDate())
            {
                $a = $xpath->query('./a', $item_);
                $href = $a->item(0)->getAttribute('href');

                \preg_match('/PAKEISTI\(\'(\d+)\'\)/', $href, $matches);

                return $matches[1];
            }
        }

        throw new \Regitra\Exception('Slot cannot be found');
    }
}