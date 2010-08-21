<?php

namespace Regitra\Tests\Scrapper;

use Regitra\Scrapper;

require_once __DIR__ . '/../TestInit.php';

class DataObjectTest extends \Regitra\Tests\RegitraTestCase
{
    public function testStoresData()
    {
        $obj = new Scrapper\DataObject('string');
        $this->assertEquals('string', $obj->getData());
    }

    public function testCanBeCastedToString()
    {
        $obj = new Scrapper\DataObject('string');
        $this->assertEquals('string', (string) $obj);
    }

    public function testCanGetDomDocument()
    {
        $obj = new Scrapper\DataObject('<html></html>');
        $this->assertType('DOMDocument', $obj->getDomDocument());
    }

    /**
     * @expectedException \Regitra\Exception
     */
    public function testOnlyAcceptsString()
    {
        $obj = new Scrapper\DataObject(new \stdClass());
    }
}