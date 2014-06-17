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
        $name = 'tim_tom';
        $label_text = ucwords(str_ireplace('_', ' ', $name));
        
        $this->errors->shouldReceive('has')->with($name)->andReturn(false);
        
        $result = $this->form->label($name);

        /**
         * Expected Output:
         *  <label for="$name">$label_text</label>
         */
        $expected_tag = [
          'tag' => 'label',
          'content' => $label_text,
          'attributes' => ['for' => $name]
        ];
        $this->assertTag( $expected_tag, $result, 'Label does not match the parameters.' );
    }    

    public function test_Return_Label_Tag_With_Matching_Name_And_Error_Class_While_Errors() 
    {
        $name = 'tim';
        $label_text = ucwords(str_ireplace('_', ' ', $name));

        $this->errors->shouldReceive('has')->with($name)->andReturn(true);
        
        $result = $this->form->label($name);

        /**
         * Expected Output:
         *  <label for="$name" class="error">$label_text</label>
         */
        $expected_tag = [
          'tag' => 'label',
          'content' => $label_text,
          'attributes' => [
            'for' => $name,
            'class' => 'error',
          ],
        ];
        $this->assertTag( $expected_tag, $result, 'Label with error does not match the parameters.' );
    } 

    public function test_Return_Label_Tag_With_Matching_Name_And_Value() 
    {
        $name = 'tim';
        $value = 'Eric';
        $this->errors->shouldReceive('has')->with($name)->andReturn(false);
        
        $result = $this->form->label($name,$value);

        /**
         * Expected Output:
         *  <label for="$name" class="error">$value</label>
         */
        $expected_tag = [
          'tag' => 'label',
          'content' => $value,
          'attributes' => [ 'for' => $name ],
        ];
        $this->assertTag( $expected_tag, $result, 'Label with error does not match the parameters.' );
    }    


    public function tearDown()
    {
        m::close();
    }
}
