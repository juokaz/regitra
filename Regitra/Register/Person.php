<?php

namespace Regitra\Register;

class Person
{
    protected $_person_code = null;

    protected $_name = null;

    protected $_surname = null;

    protected $_theory_exam_id = null;

    public function setPersonCode($code)
    {
        $this->_person_code = $code;
    }

    public function getPersonCode()
    {
        return $this->_person_code;
    }

    public function setName($name, $surname)
    {
        $this->_name = $name;
        $this->_surname = $surname;
    }

    public function getFirstName()
    {
        return $this->_name;
    }

    public function getLastName()
    {
        return $this->_surname;
    }

    public function setTheoryExamId($id)
    {
        $this->_theory_exam_id = $id;
    }

    public function getTheoryExamId()
    {
        return $this->_theory_exam_id;
    }
}