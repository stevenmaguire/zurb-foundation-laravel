<?php

use Stevenmaguire\Foundation\FormBuilder;

use Mockery as m;

class When_Using_FormBuilder_Test extends PHPUnit_Framework_TestCase {

    public function tearDown()
    {
        m::close();
    }
}
