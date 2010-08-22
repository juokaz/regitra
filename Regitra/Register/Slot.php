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
     * Id
     *
     * @var int
     */
    protected $_id;

    /**
     * Slot object
     *
     * @param int $id
     * @param string $city
     * @param string $category
     * @param string $gears
     * @param int $year
     * @param int $month
     * @param int $day
     * @param int $hour
     * @param int $minute
     */
    public function __construct($id, $city, $category, $gears, $year, $month, $day, $hour, $minute = 0)
    {
        $this->_id = $id;
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

    /**
     * Id
     *
     * @return int
     */
    public function getId()
    {
        return $this->_id;
    }
}