<?php

use Illuminate\Config\Repository;
use Illuminate\Support\MessageBag;
use Stevenmaguire\Foundation\FormBuilder as FormBuilder;
use Mockery as m;

class When_Building_Label_Test extends PHPUnit_Framework_TestCase {

    protected $html;
    protected $url;
    protected $csrfToken;
    protected $translator;    
    protected $errors;

    public function setUp()
    {
        $this->html = new Illuminate\Html\HtmlBuilder;
        $this->url = m::mock('Illuminate\Routing\UrlGenerator');
        $this->csrfToken = '';
        $this->translator = m::mock('Illuminate\Translation\Translator');
        $this->errors = m::mock('Illuminate\Support\MessageBag');

        $this->form = new FormBuilder($this->html,$this->url,$this->csrfToken,$this->translator,$this->errors);
    }

    public function test_Return_Label_Tag_With_Matching_Name() 
    {
        $name = 'tim';
        $this->errors->shouldReceive('has')->with($name)->once()->andReturn(false);
        
        $result = $this->form->label($name);

        $this->assertTrue(strpos($result,'for="'.$name.'"') !== false
            && strpos($result,'<label') !== false);
    }    

    public function test_Return_Label_Tag_With_Matching_Name_And_Error_Class_While_Errors() 
    {
        $name = 'tim';
        $this->errors->shouldReceive('has')->with($name)->once()->andReturn(true);
        
        $result = $this->form->label($name);

        $this->assertTrue(strpos($result,'for="'.$name.'"') !== false
            && strpos($result,'<label') !== false
            && strpos($result,'class="error"') !== false);
    } 

    public function test_Return_Label_Tag_With_Matching_Name_And_Value() 
    {
        $name = 'tim';
        $value = 'Eric';
        $this->errors->shouldReceive('has')->with($name)->once()->andReturn(false);
        
        $result = $this->form->label($name,$value);

        $this->assertTrue(strpos($result,'for="'.$name.'"') !== false
            && strpos($result,'<label') !== false
            && strpos($result,'>'.$value.'</label') !== false);
    }    


    public function tearDown()
    {
        m::close();
    }
}
