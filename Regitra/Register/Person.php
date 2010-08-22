<?php

namespace Regitra\Register;

class Person
{
    protected $_personCode = null;

    protected $_name = null;

    protected $_surname = null;

    protected $_theoryExamId = null;

    /**
     * Set person code
     *
     * @param string $code
     */
    public function setPersonCode($code)
    {
        $this->_personCode = $code;
    }

    /**
     * Get person code
     *
     * @return string
     */
    public function getPersonCode()
    {
        return $this->_personCode;
    }

    /**
     * Set name
     *
     * @param string $name
     * @param string $surname
     */
    public function setName($name, $surname)
    {
        $this->_name = $name;
        $this->_surname = $surname;
    }

    /**
     * Get first name
     *
     * @return string
     */
    public function getFirstName()
    {
        return $this->_name;
    }

    /**
     * Get last name
     *
     * @return string
     */
    public function getLastName()
    {
        return $this->_surname;
    }

    /**
     * Set theory exam id
     *
     * @param int $id
     */
    public function setTheoryExamId($id)
    {
        $this->_theoryExamId = $id;
    }

    /**
     * Get exam id
     *
     * @return id
     */
    public function getTheoryExamId()
    {
        return $this->_theoryExamId;
    }
}