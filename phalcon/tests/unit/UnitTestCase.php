<?php

namespace Test;

use Phalcon\Di;
use Phalcon\Test\UnitTestCase as PhalconTestCase;

abstract class UnitTestCase extends PhalconTestCase
{    
    protected $_http;

    public function setUp()
    {        
        parent::setUp();        
        $di = Di::getDefault();
        $this->setDi($di);
    }
}