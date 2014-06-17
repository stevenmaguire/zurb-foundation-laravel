<?php

use Illuminate\Config\Repository;
use Illuminate\Support\MessageBag;
use Stevenmaguire\Foundation\FormBuilder as FormBuilder;
use Mockery as m;

class When_Building_Email_Test extends PHPUnit_Framework_TestCase {

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

    public function test_Return_Textbox_Input_Tag_With_Matching_Name() 
    {
        $name = 'tim';
        $this->errors->shouldReceive('has')->with($name)->andReturn(false);
        
        $result = $this->form->email($name);

        /**
         * Expected Output:
         *  <input name="$name" type="email">
         */
        $expected_tag = [
          'tag' => 'input',
          'attributes' => ['type' => 'email']
        ];
        $this->assertTag( $expected_tag, $result );
    }

    public function test_Return_Textbox_Input_Tag_With_Matching_Name_With_Label() 
    {
        $name = 'tim';
        $label = 'Email';
        $this->errors->shouldReceive('has')->with($name)->andReturn(false);
        
        $result = $this->form->withLabel($name, $label)->email($name);
        
        /**
         * Expected Output:
         * <label for="$name">
         *  $label
         *  <input name="$name" type="email" id="$name">
         * </label>
         */
        $expected_tag = [
          'tag' => 'label',
          'attributes' => [ 'for' => $name ],
          'content' => $label,
          'child' => [
            'id' => $name,
            'tag' => 'input',
            'attributes' => ['type' => 'email']
          ],
        ];
        $this->assertTag( $expected_tag, $result );
    }       

    public function test_Return_Textbox_Text_Tag_With_Matching_Name_And_Error_Class_While_Errors() 
    {
        $name = 'tim';
        $errors = array('Error message');
        $this->errors->shouldReceive('has')->with($name)->andReturn(true);
        $this->errors->shouldReceive('get')->with($name)->andReturn($errors);
        
        $result = $this->form->email($name);

        /**
         * Expected Output:
         *  <input name="$name" type="email" id="$name">
         *  <span class="error">$errors[0]</span>
         */
        $expected_input = [
          'tag' => 'input',
          'attributes' => ['type' => 'email']
        ];
        $this->assertTag( $expected_input, $result, 'Email input field (with error) not returned correctly.' );
        $expected_err = [
          'tag' => 'span',
          'content' => $errors[0],
          'attributes' => ['class' => 'error']
        ];
        $this->assertTag( $expected_err, $result, 'Error span is not present or does not have the right content and classes.' );
    }
    
    public function test_Return_Textbox_Text_Tag_With_Matching_Name_And_Error_Class_With_Label_While_Errors() 
    {
        $name = 'tim';
        $label = 'Email';
        $errors = array('Error message');
        $this->errors->shouldReceive('has')->with($name)->andReturn(true);
        $this->errors->shouldReceive('get')->with($name)->andReturn($errors);
        
        $result = $this->form->withLabel($name, $label)->email($name);

        /**
         * Expected Output:
         *  <label for="$name" class="error">
         *   $label
         *   <input name="$name" type="email" id="$name">
         *   <span class="error">$errors[0]</span>
         *  </label>
         */
        $expected_tag = [
          'tag' => 'label',
          'attributes' => [
            'for' => $name,
            'class' => 'error',
          ],
          'content' => $label,
          'child' => [
            'id' => $name,
            'tag' => 'input',
            'attributes' => ['type' => 'email']
          ],
        ];
        $this->assertTag( $expected_tag, $result, 'Email input+label field (with error) not built correctly. Does not contain the input field' );
        
        $expected_tag = [
          'tag' => 'label',
          'child' => [
            'tag' => 'span',
            'content' => $errors[0],
            'attributes' => ['class' => 'error']
          ],
        ];
        $this->assertTag( $expected_tag, $result, 'Error span is not present or does not have the right content and classes.' );
    } 

    public function tearDown()
    {
        m::close();
    }
}
