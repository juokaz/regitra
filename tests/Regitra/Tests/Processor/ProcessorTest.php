<?php

namespace Regitra\Tests\Processor;

use Regitra\Processor;

require_once __DIR__ . '/../TestInit.php';

class ProcessorTest extends \Regitra\Tests\RegitraTestCase
{
    public function testReturnType()
    {
        $obj = new Processor\Processor($this->_getDataObject(''));
        $this->assertType('array', $obj->extractSlots());
    }

    /**
     * @dataProvider numberOfExtractedSlots
     */
    public function testCorrectNumberOfExtractedSlots($file, $number)
    {
        $obj = new Processor\Processor($this->_getDataResult($file));
        $this->assertEquals($number, count($obj->extractSlots()));
    }

    public function testCorrectDates()
    {
        $obj = new Processor\Processor($this->_getDataResult(0));
        $slots = $obj->extractSlots();

        $this->assertEquals(1, $slots[0]->getPlaces());
        $this->assertEquals('2010-08-13 13:00', $slots[0]->getDate('Y-m-d H:i'));
        $this->assertEquals(2, $slots[1]->getPlaces());
        $this->assertEquals('2010-08-20 13:00', $slots[1]->getDate('Y-m-d H:i'));
        $this->assertEquals(2, $slots[2]->getPlaces());
        $this->assertEquals('2010-08-20 14:00', $slots[2]->getDate('Y-m-d H:i'));
    }

    public function numberOfExtractedSlots()
    {
        return array(
          array(0, 3),
          array(1, 63),
          array(2, 76),
        );
    }

    /**
     * Get data result
     *
     * @param int $index
     * @return \Regitra\Scrapper\DataObject
     */
    protected function _getDataResult($index = 1)
    {
        $filename = __DIR__ . '/../../../data/result' . $index . '.html';

        if (!\file_exists($filename))
        {
            throw new \Exception('Result file not found');
        }

        $data = \file_get_contents($filename);

        return $this->_getDataObject($data);
    }

    /**
     * Get data object
     *
     * @param string $string
     * @return \Regitra\Scrapper\DataObject
     */
    protected function _getDataObject($string = '')
    {
        return new \Regitra\Scrapper\DataObject($string);
    }
}