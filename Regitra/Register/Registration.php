<?php

namespace Regitra\Register;

class Registration
{
    /**
     * Slot
     *
     * @var Slot
     */
    private $slot;

    /**
     * Ip address
     *
     * @var string
     */
    private $ip;

    /**
     * Category
     *
     * @var string
     */
    private $category;

    /**
     * City
     *
     * @var string
     */
    private $city;

    /**
     * Gears
     *
     * @var string
     */
    private $gears;

    /**
     * Registration
     *
     * @param Slot $slot
     * @param string $city
     * @param string $category
     * @param string $gears
     */
    public function __construct(Slot $slot, $city, $category, $gears)
    {
        $this->slot = $slot;
        $this->city = $city;
        $this->category = $category;
        $this->gears = $gears;
    }

    /**
     * Get slot
     *
     * @return string
     */
    public function getSlot()
    {
        return $this->slot;
    }

    /**
     * Set ip
     *
     * @param string $ip
     */
    public function setIp($ip)
    {
        $this->ip = $ip;
    }

    /**
     * Get ip
     *
     * @return string
     */
    public function getIp()
    {
        return $this->ip;
    }

    /**
     * Get city
     *
     * @return string
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * Get category
     *
     * @return string
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * Gears
     *
     * @return string
     */
    public function getGears()
    {
        return $this->gears;
    }
}