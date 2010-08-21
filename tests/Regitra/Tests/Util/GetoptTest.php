<?php

namespace Regitra\Tests\Util;

use Regitra\Util;

require_once __DIR__ . '/../TestInit.php';

class GetoptTest extends \Regitra\Tests\RegitraTestCase
{
    protected $_util = null;

    protected function setup()
    {
        parent::setUp();

        $this->_util = new Util\Getopt();
    }

    public function testCanProcess()
    {
        $this->assertEquals(array('test' => 'value'), $this->_util->process(array('--test', 'value')));
    }

    public function testCanProcessMultipleParams()
    {
        $this->assertEquals(array('test' => 'value', 'test2' => 'value2'), $this->_util->process(array('--test', 'value', '--test2', 'value2')));
    }

    public function testCanProcessMissingValues()
    {
        $this->assertEquals(array('test' => 'value', 'test2' => 'value2'), $this->_util->process(array('--test', 'value', '--test2', 'value2', '--test3')));
    }

    public function testCanProcessInvalidParams()
    {
        $this->assertEquals(array('test' => 'value', 'test2' => 'value2'), $this->_util->process(array('--test', 'value', 'something', '--test2', 'value2', 'option')));
    }
}