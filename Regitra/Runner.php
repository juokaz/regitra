<?php

namespace Regitra;

use Regitra\Scrapper;
use Regitra\Processor;
use Regitra\Register;
use Regitra\Runner;
use Regitra\License;

class Runner
{
    /**
     * Current user registration slot
     *
     * @var Regitra\Register\Registration
     */
    private $registrationInfo = null;

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

        $scrapper = $this->getScrapper();

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
        $register = new Register\Register();

        $scrapper = $this->getScrapper();

        if (!isset($this->registrationInfo))
        {
            $data = $scrapper->getData('https://www.eregitra.lt/viesa/interv/INT_Header_Inf.php', array(
                'Action' => 'IESKOTI',
                'Asm_kodas' => $person->getPersonCode()
            ));

            $this->registrationInfo = $register->getRegistrationInfo($data);
        }

        $params = Runner\Params::validate($this->registrationInfo->getCity(), $this->registrationInfo->getCategory(), $this->registrationInfo->getGears());

        $data = $scrapper->getData('https://www.eregitra.lt/viesa/interv/INT_Header.php', array(
            'Action' => 'PAKEISTI_LAIKA',
            'Asm_kodas' => $person->getPersonCode(),
            'EGZ_TIPAS' => 'P',
            'IDi' => '',
            'Kategorija' => $params['category'],
            'PRASYMO_NR' => $person->getExamId(),
            'PRASYMO_NR_P' => $person->getExamId(),
            'P_deze' => $params['gears'],
            'Padal' => $params['city'],
            'Padalinys_P' => '',
            'TPadalinys' => '',
            'nrows' => '1'));

        $slots = $register->extractSlots($data, $this->registrationInfo);

        if (count($slots) == 0)
        {
            throw new Exception('No slots available');
        }

        // get first slot
        $slot = $slots[0];

        if ($this->registrationInfo->getSlot()->getRawDate() > $slot->getRawDate())
        {
            $url = sprintf(
                'https://www.eregitra.lt/viesa/interv/INT_App.php?Vardas=&Pavarde=&Asm_kodas=%s&Padalinys=%s' .
                '&SYSDATE=%s&Prasymo_nr=&Kategorija=%s&P_deze=%s&Fraze=&ip=%s&PRASYMO_NR=%s&Action=Issaugoti_Pakeisti' .
                '&GrafikoID=%s&Prakt_Date=%s&Prakt_Time=%s',

                $person->getPersonCode(), $params['city'], date('Y-m-d\AH:i:s'), $params['category'], $params['gears'],
                $this->registrationInfo->getIp(), $person->getExamId(), $slot->getId(), $slot->getDate('Y-m-d H:i'), null
            );

            $scrapper->getData($url);

            // reset current registration info
            $this->registrationInfo = null;

            return $slot;
        }

        return false;
    }

    /**
     * Is driving license ready
     *
     * @param Register\Person $person
     * @return boolean
     */
    public function isDrivingLicenseReady(Register\Person $person)
    {
        $scrapper = $this->getScrapper();

        $data = $scrapper->getData('https://www.eregitra.lt/viesa/vp_paiesk/vp_search.php', array(
            'ASM_KODAS' => $person->getPersonCode()), false);

        $license = new License\License();

        return $license->isReady($data);
    }

    /**
     * Get scrapper
     *
     * @return \Regitra\Scrapper\Scrapper
     */
    private function getScrapper()
    {
        static $scrapper;

        if (!$scrapper)
        {
            $scrapper = new \Regitra\Scrapper\Scrapper();
            $scrapper->setInitUrl('https://www.eregitra.lt/viesa/interv/Index.php');
            $scrapper->setCookiesPath(tempnam(null, 'Regitra'));
        }

        return $scrapper;
    }
}