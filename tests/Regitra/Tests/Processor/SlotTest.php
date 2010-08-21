<?php

namespace Regitra\Tests\Processor;

use Regitra\Processor;

require_once __DIR__ . '/../TestInit.php';

class SlotTest extends \Regitra\Tests\RegitraTestCase
{
    public function testCanStoreDate()
    {
        $obj = new Processor\Slot(1, 2005, 5, 8, 10, 00);
        $this->assertEquals('2005-05-08 10:00:00', $obj->getDate('Y-m-d H:i:s'));
    }

    public function testCanStorePlaces()
    {
        $obj = new Processor\Slot(1, 2005, 5, 8, 10, 00);
        $this->assertEquals(1, $obj->getPlaces());
    }
}