<?php

use Illuminate\Config\Repository;
use Illuminate\Support\MessageBag;
use Stevenmaguire\Foundation\FormBuilder as FormBuilder;
use Mockery as m;

class When_Building_TextArea_Test extends PHPUnit_Framework_TestCase {

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
    
    public function test_Return_Textarea_Tag_With_Matching_Name() 
    {
        $name = 'tim';
        $this->errors->shouldReceive('has')->andReturn(false);
        
        $result = $this->form->textarea($name);
        
        /**
        * Expected Output:
        * <textarea name="$name"></textarea>
        */
        $expected_tag = [
          'tag' => 'textarea',
          'attributes' => [ 'name' => $name ]
        ];
        $this->assertTag( $expected_tag, $result, 'Textarea field not correclty generated!');

    }

    public function test_Return_Textarea_Tag_With_Matching_Name_And_Value() 
    {
        $name = 'tim';
        $value = 'Great Job';
        $this->errors->shouldReceive('has')->with($name)->andReturn(false);
        
        $result = $this->form->textarea($name,$value);

        /**
        * Expected Output:
        * <textarea name="$name"></textarea>
        */
        $expected_tag = [
          'tag' => 'textarea',
          'content' => $value,
          'attributes' => [ 'name' => $name ]
        ];
        $this->assertTag( $expected_tag, $result, 'Textarea field not correclty generated!');
    }

    public function test_Return_Textarea_Tag_With_Matching_Name_And_Value_With_Label() 
    {
        $name = 'tim';
        $value = 'Great Job';
        $labelValue = "Great Things";
        $this->errors->shouldReceive('has')->with($name)->andReturn(false);
        
        $result = $this->form->withLabel($name, $labelValue)->textarea($name,$value);

        /**
        * Expected Output:
        * <label for="$name">$labelValue
        *   <textarea name="$name">$value</textarea>
        * </label>
        */
        $expected_tag = [
          'tag' => 'label',
          'attributes' => [ 'for' => $name ],
          'content' => $labelValue, 
          'child' => [
            'tag' => 'textarea',
            'content' => $value,
            'attributes' => [ 'name' => $name ]
          ]
        ];
        $this->assertTag( $expected_tag, $result, 'Textarea field + label not correclty generated!');
    }

    public function test_Return_Textarea_ag_With_Matching_Name_And_Error_Class_While_Errors() 
    {
        $name = 'tim';
        $errors = array('Error message');
        $this->errors->shouldReceive('has')->with($name)->andReturn(true);
        $this->errors->shouldReceive('get')->with($name)->andReturn($errors);
        
        $result = $this->form->textarea($name);

        /**
        * Expected Output:
        * <textarea name="tim"></textarea>
        * <span class="error">$errors[0]</span>
        */
        $expected_tag = [
          'tag' => 'textarea',
          'attributes' => [
            'name' => $name,
            'class' => 'error',
          ],
        ];
        $this->assertTag( $expected_tag, $result, 'Textarea (with error) field not correclty generated!');
        $expected_tag = [
          'tag' => 'span',
          'content' => $errors[0],
          'attributes' => ['class' => 'error']
        ];
        $this->assertTag( $expected_tag, $result, 'Error span is not present or does not have the right content and classes.' );
        
    } 

    public function test_Return_Textarea_ag_With_Matching_Name_And_Error_Class_With_Label_While_Errors() 
    {
        $name = 'tim';
        $labelValue = "Great Things";
        $errors = array('Error message');
        $this->errors->shouldReceive('has')->with($name)->andReturn(true);
        $this->errors->shouldReceive('get')->with($name)->andReturn($errors);
        
        $result = $this->form->withLabel($name, $labelValue)->textarea($name);

        /**
        * Expected Output:
        * <label for="$name" class="error">$labelValue
        *   <textarea name="$name" class="error"></textarea>
        *   <span class="error">$errors[0]</span>
        * </label>
        */
        $expected_tag = [
          'tag' => 'label',
          'attributes' => [
            'for' => $name,
            'class' => 'error',
          ],
          'content' => $labelValue, 
          'child' => [
            'tag' => 'textarea',
            'attributes' => [ 'name' => $name ]
          ]
        ];
        $this->assertTag( $expected_tag, $result, 'Textarea + Label (with error) field not correclty generated!');
        $expected_tag = [
          'tag' => 'span',
          'content' => $errors[0],
          'attributes' => ['class' => 'error']
        ];
        $this->assertTag( $expected_tag, $result, 'Error span is not present or does not have the right content and classes.' );
        
    } 

    public function tearDown()
    {
        m::close();
    }
}
