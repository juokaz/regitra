<?php

namespace Regitra\Register;

use Regitra\Scrapper;

class Register
{
    /**
     * Get current user slot
     *
     * @param Scrapper\DataObject $data
     * @return Registration
     */
    public function getRegistrationInfo(Scrapper\DataObject $data)
    {
        if (\strlen($data) == 0) {
            throw new \Regitra\Exception ('Empty document');
        }

        $html = $data->getDomDocument();

        $xpath = new \DOMXPath($html);

        \preg_match("/PAKEISTIPR\('(\w{1})','(\w{1})',document.forms\[0\]\.PRASYMO_NR_P\.value,'(\w{2})'/", (string) $data, $params);

        if (count($params) != 4)
        {
            $data = $xpath->query('//tr[contains(td/text(),"Egzamino vieta:")]/td[position()=2]/text()');
            $city = trim($data->item(0)->nodeValue);

            $data = $xpath->query('//tr[contains(td/text(),"Kategorija")]/td[position()=2]/text()');
            $category = trim($data->item(0)->nodeValue);

            $data = $xpath->query('//tr[contains(td/text(),"Pavar")]/td[position()=2]/text()');
            $gears = strpos(trim($data->item(0)->nodeValue), 'mechanin') === 0 ? \Regitra\Runner\Params::GEARS_MANUAL : \Regitra\Runner\Params::GEARS_AUTOMATIC;
        }
        else
        {
            $city = $params[3];
            $category = $params[2];
            $gears = $params[1];
        }

        $data = $xpath->query('//tr[contains(td/text(),"Egzamino data, laikas:")]/td[position()=2]');
        \preg_match('/(\d{4})-(\d{2})-(\d{2}) (\d{2}):(\d{2})/', $data->item(0)->nodeValue, $matches);

        $slot = new Slot(null, $matches[1], $matches[2], $matches[3], $matches[4], $matches[5]);

        return new Registration($slot, $city, $category, $gears);
    }

    /**
     * Get slots
     *
     * @param Scrapper\DataObject $data
     * @param Registration $registration
     * @return array
     */
    public function extractSlots(Scrapper\DataObject $data, Registration $registration = null)
    {
        $result = array();

        if (\strlen($data) == 0)
            return $result;

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

            $a = $xpath->query('./a', $item_);
            $href = $a->item(0)->getAttribute('href');

            \preg_match('/PAKEISTI\(\'(\d+)\'/', $href, $matches);

            $result[] = new Slot($matches[1], $year, $month, $day, $hour);
        }

        if (isset($registration))
        {
            $data = $xpath->query('//input[@name="ip"]/@value');
            $ip = $data->item(0)->nodeValue;

            $registration->setIp($ip);
        }

        return $result;
    }
}