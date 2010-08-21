<?php

namespace Regitra;

class Slot
{
    /**
     * Date
     *
     * @var int
     */
    protected $_date = 0;

    /**
     * Slot object
     *
     * @param int $year
     * @param int $month
     * @param int $day
     * @param int $hour
     * @param int $minute
     */
    public function __construct($year, $month, $day, $hour, $minute = 0)
    {
        $this->_date = \mktime($hour, $minute, 0, $month, $day, $year);
    }

    /**
     * Get date
     *
     * @param string $format
     * @return string
     */
    public function getDate($format = 'Y-m-d H:i')
    {
        return \date($format, $this->_date);
    }

    /**
     * Get raw date (unix microtime)
     *
     * @return int
     */
    public function getRawDate()
    {
        return $this->_date;
    }

    public function __toString()
    {
        return $this->getDate();
    }
}