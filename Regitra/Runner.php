<?php

namespace Regitra;

use Regitra\Scrapper;
use Regitra\Processor;
use Regitra\Register;
use Regitra\Runner;

class Runner
{
    /**
     * Current user registration slot
     *
     * @var Regitra\Register\Slot
     */
    private $_current_slot = null;

    /**
     * Get slots
     *
     * @param string $city
     * @param string $category
     * @param string $gears
     * @return array
     */
    public function getSlots($city, $category, $gears = self::GEARS_MANUAL)
    {
        $params = Runner\Params::validate($city, $category, $gears);

        $scrapper = $this->_getScrapper();

        $document = $scrapper->getData(sprintf('https://212.59.5.68/nveis/INTERV/INT_GRAFIKAS_VIEW.php?Padalinys=%s&Kategorija=%s&P_deze=%s&Action=',
                $params['city'], $params['category'], $params['gears']
        ));

        $processor = new Processor\Processor($document);

        return $processor->extractSlots();
    }

    /**
     * Get first available slot
     *
     * @param string $city
     * @param string $category
     * @param string $gears
     * @return array
     */
    public function getFirstAvailableSlot($city, $category, $gears = self::GEARS_MANUAL)
    {
        $slots = $this->getSlots($city, $category, $gears);

        if (count($slots) == 0)
        {
            throw new Exception('No slots available');
        }

        return $slots[0];
    }

    /**
     * Register person to best available slot
     *
     * @param Register\Person $person
     * @return boolean
     */
    public function registerPerson(Register\Person $person)
    {
        if (!isset($this->_current_slot))
        {
            $register = new Register\Register();

            $scrapper = $this->_getScrapper();

            $data = $scrapper->getData('https://212.59.5.68/nveis/INTERV/INT_Header_Inf.php', array('Asm_kodas' => $person->getPersonCode()));

            $this->_current_slot = $register->getCurrentSlot($data);
        }

        $params = Runner\Params::validate($this->_current_slot->getCity(), $this->_current_slot->getCategory(), $this->_current_slot->getGears());

        $slot = $this->getFirstAvailableSlot($params['city'], $params['category'], $params['gears']);

        if ($this->_current_slot->getRawDate() > $slot->getRawDate())
        {
            $data = $scrapper->getData('https://212.59.5.68/nveis/INTERV/INT_Header.php', array(
                'Action' => 'PAKEISTI_LAIKA',
                'Asm_kodas' => $person->getPersonCode(),
                'EGZ_TIPAS' => 'P',
                'IDi' => '',
                'Kategorija' => $params['category'],
                'PRASYMO_NR' => $person->getTheoryExamId(),
                'PRASYMO_NR_P' => $person->getTheoryExamId(),
                'P_deze' => $params['gears'],
                'Padal' => $params['city'],
                'Padalinys_P' => '',
                'TPadalinys' => '',
                'nrows' => '1'));

            $slotid = $register->getSlotId($slot, $data);

            $url = sprintf(
                'https://212.59.5.68/nveis/INTERV/INT_App.php?Vardas=&Pavarde=&Asm_kodas=%s&Padalinys=%s' .
                '&SYSDATE=%s&Prasymo_nr=&Kategorija=%s&P_deze=%s&Fraze=&ip=%s&PRASYMO_NR=%s&Action=Issaugoti_Pakeisti' .
                '&GrafikoID=%s&Prakt_Date=%s&Prakt_Time=%s',

                $person->getPersonCode(), $params['city'], date('Y-m-d\AH:i:s'), $params['category'], $params['gears'],
                $this->_getIp(), $person->getTheoryExamId(), $slotid, $slot->getDate('Y-m-d'), $slot->getDate('H:i')
            );

            $scrapper->getData($url);

            // reset current slot
            $this->_current_slot = null;

            return $slot;
        }

        return false;
    }

    /**
     * Get ip
     *
     * @return string
     */
    public function _getIp()
    {
        // @todo
        return '78.60.231.20';
    }

    /**
     * Get scrapper
     *
     * @return \Regitra\Scrapper\Scrapper
     */
    private function _getScrapper()
    {
        static $scrapper;

        if (!$scrapper)
        {
            $scrapper = new \Regitra\Scrapper\Scrapper();
            $scrapper->setInitUrl('https://212.59.5.68/nveis/INTERV/Index.php');
            $scrapper->setCookiesPath(tempnam(null, 'Regitra'));
        }

        return $scrapper;
    }
}