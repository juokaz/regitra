<?php

namespace Regitra\Register;

class Slot extends \Regitra\Slot
{
    /**
     * Category
     *
     * @var string
     */
    protected $_category;

    /**
     * City
     *
     * @var string
     */
    protected $_city;

    /**
     * Gears
     *
     * @var string
     */
    protected $_gears;

    /**
     * Slot object
     *
     * @param string $city
     * @param string $category
     * @param string $gears
     * @param int $year
     * @param int $month
     * @param int $day
     * @param int $hour
     * @param int $minute
     */
    public function __construct($city, $category, $gears, $year, $month, $day, $hour, $minute = 0)
    {
        $this->_city = $city;
        $this->_category = $category;
        $this->_gears = $gears;
        parent::__construct($year, $month, $day, $hour, $minute);
    }

    /**
     * Get city
     *
     * @return string
     */
    public function getCity()
    {
        return $this->_city;
    }

    /**
     * Get category
     *
     * @return string
     */
    public function getCategory()
    {
        return $this->_category;
    }

    /**
     * Gears
     *
     * @return string
     */
    public function getGears()
    {
        return $this->_gears;
    }
}