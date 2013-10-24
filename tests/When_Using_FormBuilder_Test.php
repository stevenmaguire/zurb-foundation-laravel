<?php

use Illuminate\Config\Repository;
use Illuminate\Support\MessageBag;
use Stevenmaguire\Foundation\FormBuilder as FormBuilder;
use Mockery as m;

class When_Using_FormBuilder_Test extends PHPUnit_Framework_TestCase {

    protected $html;
    protected $url;
    protected $csrfToken;
    protected $translator;    
    protected $errors;

    public function setUp()
    {
        $this->html = m::mock('Illuminate\Html\HtmlBuilder');
        $this->url = m::mock('Illuminate\Routing\UrlGenerator');
        $this->csrfToken = '';
        $this->translator = m::mock('Illuminate\Translation\Translator');
        $this->errors = m::mock('Illuminate\Support\MessageBag');

        $this->form = m::mock(new FormBuilder($this->html,$this->url,$this->csrfToken,$this->translator,$this->errors));
    }
    public function test_Return_False_When_Checking_For_Error_Without_Name() 
    {
        $this->errors->shouldReceive('has')->once();
        $this->assertTrue($this->form->hasError() == false);
    }    

    public function test_Return_False_When_Checking_For_Error_Without_Matching_Name() 
    {
        $this->errors->shouldReceive('has')->once();
        $this->assertTrue($this->form->hasError('tim') == false);
    }    

    public function test_Return_True_When_Checking_For_Error_With_Matching_Name() 
    {
        $this->errors->shouldReceive('has')->once()->andReturn(true);
        $this->form = m::mock(new FormBuilder($this->html,$this->url,$this->csrfToken,$this->translator,$this->errors));        
        $this->assertTrue($this->form->hasError('tim') == true);
    }    

    public function test_Return_False_When_Getting_Error_Without_Name() 
    {
        $this->errors->shouldReceive('get')->once();
        $this->assertTrue($this->form->getError() == false);
    }

    public function test_Return_False_When_Getting_Error_Without_Matching_Name() 
    {
        $this->errors->shouldReceive('get')->once()->andReturn(false);
        $this->form = m::mock(new FormBuilder($this->html,$this->url,$this->csrfToken,$this->translator,$this->errors));        
        $this->assertTrue($this->form->getError('tim') == false);
    }

    public function test_Return_String_When_Getting_Error_With_Matching_Name() 
    {
        $result = 'eric';
        $this->errors->shouldReceive('get')->once()->andReturn($result);
        $this->form = m::mock(new FormBuilder($this->html,$this->url,$this->csrfToken,$this->translator,$this->errors));        
        $this->assertTrue($this->form->getError('tim') == $result);
    }

    public function tearDown()
    {
        m::close();
    }
}
