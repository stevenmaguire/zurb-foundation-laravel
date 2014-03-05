<?php

use Illuminate\Config\Repository;
use Illuminate\Support\MessageBag;
use Stevenmaguire\Foundation\FormBuilder as FormBuilder;
use Mockery as m;

class When_Building_Select_Test extends PHPUnit_Framework_TestCase {

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
    public function test_Return_Select_Tag_With_Matching_Name() 
    {
        $name = 'tim';
        $this->errors->shouldReceive('has')->with($name)->twice()->andReturn(false);
        
        $result = $this->form->select($name);

        $this->assertTrue(strpos($result,'name="'.$name.'"') !== false
            && strpos($result,'<select') !== false);
    }    

    public function test_Return_Select_Tag_With_Matching_Name_And_Selected_Value_In_Numeric_Array() 
    {
        $name = 'tim';
        $selectedValue = 2;
        $selectedLabel = 'Great Job';
        $values = array('one','two',$selectedLabel);
        $this->errors->shouldReceive('has')->with($name)->twice()->andReturn(false);
        
        $result = $this->form->select($name,$values,$selectedValue);

        $this->assertTrue(strpos($result,'name="'.$name.'"') !== false
            && strpos($result,'<select') !== false
            && strpos($result,'selected="selected">'.$selectedLabel.'</option>') !== false);                    
    }  

    public function test_Return_Select_Tag_With_Matching_Name_And_Selected_Value_In_Associative_Array() 
    {
        $name = 'tim';
        $selectedValue = '3';
        $selectedLabel = 'Great Job';
        $values = array('1' => 'one','2' => 'two','3' => $selectedLabel);
        $this->errors->shouldReceive('has')->with($name)->twice()->andReturn(false);
        
        $result = $this->form->select($name,$values,$selectedValue);

        $this->assertTrue(strpos($result,'name="'.$name.'"') !== false
            && strpos($result,'<select') !== false
            && strpos($result,'selected="selected">'.$selectedLabel.'</option>') !== false);                    
    }       

    public function test_Return_Select_Tag_With_Matching_Name_And_Error_Class_While_Errors() 
    {
        $name = 'tim';
        $errors = array('Error message');
        $this->errors->shouldReceive('has')->with($name)->twice()->andReturn(true);
        $this->errors->shouldReceive('get')->with($name)->once()->andReturn($errors);
        
        $result = $this->form->select($name);

        $this->assertTrue(strpos($result,'name="'.$name.'"') !== false
            && strpos($result,'<select') !== false
            && strpos($result,'class="error"') !== false
            && strpos($result,'<small class="error">'.implode(' ',$errors).'</small>') !== false);
    } 

    public function tearDown()
    {
        m::close();
    }
}
