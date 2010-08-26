<?php

namespace Regitra\Register;

class Slot extends \Regitra\Slot
{
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
     * @param int $year
     * @param int $month
     * @param int $day
     * @param int $hour
     * @param int $minute
     */
    public function __construct($id, $year, $month, $day, $hour, $minute = 0)
    {
        $this->_id = $id;
        parent::__construct($year, $month, $day, $hour, $minute);
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