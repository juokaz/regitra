<?php

namespace Regitra\Processor;

class Slot extends \Regitra\Slot
{
    /**
     * Places
     *
     * @var int
     */
    protected $_places = 0;

    /**
     * Slot object
     *
     * @param int $places
     * @param int $year
     * @param int $month
     * @param int $day
     * @param int $hour
     * @param int $minute
     */
    public function __construct($places, $year, $month, $day, $hour, $minute = 0)
    {
        $this->_places = $places;
        parent::__construct($year, $month, $day, $hour, $minute);
    }

    /**
     * Get places
     *
     * @return int
     */
    public function getPlaces()
    {
        return $this->_places;
    }
}