<?php

use Illuminate\Config\Repository;
use Illuminate\Support\MessageBag;
use Stevenmaguire\Foundation\FormBuilder as FormBuilder;
use Mockery as m;

class When_Using_FormBuilder_Test extends PHPUnit_Framework_TestCase {

    protected $form;

    public function setUp()
    {
        $html = m::mock('Illuminate\Html\HtmlBuilder');
        $url = m::mock('Illuminate\Routing\UrlGenerator');
        $csrfToken = '';
        $translator = m::mock('Illuminate\Translation\Translator');
        
        $this->form = m::mock(new FormBuilder($html,$url,$csrfToken,$translator));
    }
    public function test_Return_False_When_Checking_Session_For_Error_By_Name() 
    {

        $this->assertTrue($this->form->hasError() == false);
    }    

    public function test_Return_Empty_String_When_Checking_Session_For_Error_By_Name() 
    {

        $this->assertTrue($this->form->getError() == '');
    }  

    public function tearDown()
    {
        m::close();
    }
}
