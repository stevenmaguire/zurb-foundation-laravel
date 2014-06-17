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
        $options = [
          'one' => 'First',
          'two' => 'Second',
          'last' => 'Another',
        ];
        
        $this->errors->shouldReceive('has')->with($name)->andReturn(false);
        
        $result = $this->form->select($name, $options);
        
        /**
         * Expected Output:
         * <select name="$name">
         *   <option value="array_keys($options)[0]">current($options)</option>
         *    ...
         * </select>
         */
        $expected_tag = [
          'tag' => 'select',
          'attributes' => [ 'name' => $name ],
          'children' => [
            'count' => count($options),
            'only' => [ 'tag' => 'option' ],
          ],
        ];
        $this->assertTag( $expected_tag, $result, 'Select does not contain all the values!');
        
        $expected_tag = [
          'tag' => 'option',
          'content' => current($options),
          'attributes' => [ 'value' => current(array_keys($options)) ],
        ];
        $this->assertTag( $expected_tag, $result, 'Select does not contain the expected elements!');
    }

    public function test_Return_Select_Tag_With_Matching_Name_With_Label() 
    {
        $name = 'tim';
        $label = 'Email';
        $options = [
          'one' => 'First',
          'two' => 'Second',
          'last' => 'Another',
        ];
        
        $this->errors->shouldReceive('has')->with($name)->andReturn(false);
        
        $result = $this->form->withLabel($name, $label)->select($name, $options);

        /**
         * Expected Output:
         * <label for="$name">
         *   <select name="$name" id="$name">
         *    <option value="array_keys($options)[0]">current($options)</option>
         *     ...
         *   </select>
         * </label>
         */
        $expected_tag = [
          'tag' => 'label',
          'content' => $label,
          'attributes' => [ 'for' => $name ],
          'children' => [
            'count' => 1,
            'only' => [
              'tag' => 'select',
              'id' => $name,
            ],
          ],
        ];
        $this->assertTag( $expected_tag, $result, 'Select\'s Label does not countain expected data!');
        
        $expected_tag = [
          'tag' => 'select',
          'children' => [
            'count' => count($options),
            'only' => [ 'tag' => 'option' ],
          ],
        ];
        $this->assertTag( $expected_tag, $result, 'Select does not contain all the values!');
        
        $expected_tag = [
          'tag' => 'option',
          'attributes' => [ 'value' => current(array_keys($options)) ],
        ];
        $this->assertTag( $expected_tag, $result, 'Select does not contain the expected elements!');
    }

    public function test_Return_Select_Tag_With_Matching_Name_And_Selected_Value_In_Numeric_Array() 
    {
        $name = 'tim';
        $selectedValue = 2;
        $selectedLabel = 'Great Job';
        $options = [ 'one', 'two', $selectedLabel ];
        $this->errors->shouldReceive('has')->with($name)->andReturn(false);
        
        $result = $this->form->select($name,$options,$selectedValue);

        /**
         * Expected Output:
         *  <select name="$name" id="$name">
         *   <option value="array_keys($options)[0]">current($options)</option>
         *    ...
         *   <option selected="selected" value="$selectedValue">$selectedLabel</option>
         *  </select>
         */
        $expected_tag = [
          'tag' => 'select',
          'children' => [
            'count' => count($options),
            'only' => [ 'tag' => 'option' ],
          ],
        ];
        $this->assertTag( $expected_tag, $result, 'Select does not contain all the values!');
        
        $expected_tag = [
          'tag' => 'option',
          'attributes' => [
            'selected' => 'selected',
            'value' => $selectedValue,
          ],
        ];
        $this->assertTag( $expected_tag, $result, 'Selected element does not match expected one!');
    }

    public function test_Return_Select_Tag_With_Matching_Name_And_Selected_Value_In_Associative_Array() 
    {
        $name = 'tim';
        $selectedValue = 'gjob';
        $selectedLabel = 'Great Job';
        $options = [
          'one' => 'First',
          'two' => 'Second',
          $selectedValue => $selectedLabel,
        ];
        
        $this->errors->shouldReceive('has')->with($name)->andReturn(false);
        
        $result = $this->form->select($name,$options,$selectedValue);

        /**
         * Expected Output:
         *  <select name="$name" id="$name">
         *   <option value="array_keys($options)[0]">current($options)</option>
         *    ...
         *   <option selected="selected" value="$selectedValue">$selectedLabel</option>
         *  </select>
         */
        $expected_tag = [
          'tag' => 'select',
          'attributes' => [ 'name' => $name ],
          'children' => [
            'count' => count($options),
            'only' => [ 'tag' => 'option' ],
          ],
        ];
        $this->assertTag( $expected_tag, $result, 'Select does not contain all the values!');
        
        $expected_tag = [
          'tag' => 'option',
          'attributes' => [
            'selected' => 'selected',
            'value' => $selectedValue,
          ],
        ];
        $this->assertTag( $expected_tag, $result, 'Selected element does not match expected one!');
    }

    public function test_Return_Select_Tag_With_Matching_Name_And_Error_Class_While_Errors() 
    {
        $name = 'tim';
        $options = [
          'one' => 'First',
          'two' => 'Second',
        ];
        $errors = array('Error message');
        $this->errors->shouldReceive('has')->with($name)->andReturn(true);
        $this->errors->shouldReceive('get')->with($name)->andReturn($errors);
        
        $result = $this->form->select($name, $options);

        /**
         * Expected Output:
         *  <select name="$name" id="$name">
         *   <option value="array_keys($options)[0]">current($options)</option>
         *    ...
         *  </select>
         *   <span class="error">$errors[0]</span>
         */
        $expected_tag = [
          'tag' => 'select',
          'attributes' => [
            'name' => $name,
            'class' => 'error', 
          ],
          'children' => [
            'count' => count($options),
            'only' => [ 'tag' => 'option' ],
          ],
        ];
        $this->assertTag( $expected_tag, $result, 'Select does not contain all the values!');

        $expected_tag = [
          'tag' => 'span',
          'content' => $errors[0],
          'attributes' => ['class' => 'error']
        ];
        $this->assertTag( $expected_tag, $result, 'Error span is not present or does not have the right content and classes.' );
    }

    public function test_Return_Select_Tag_With_Matching_Name_And_Error_Class_With_Label_While_Errors() 
    {
        $name = 'tim';
        $label = 'Email';
        $options = [
          'one' => 'First',
          'two' => 'Second',
          'last' => 'Another',
        ];
        $errors = array('Error message');
        $this->errors->shouldReceive('has')->with($name)->andReturn(true);
        $this->errors->shouldReceive('get')->with($name)->andReturn($errors);
        
        $result = $this->form->withLabel($name, $label)->select($name, $options);

        /**
         * Expected Output:
         * <label for="$name" class="error">
         *   <select name="$name" id="$name" class="error">
         *    <option value="array_keys($options)[0]">current($options)</option>
         *     ...
         *   </select>
         *   <span class="error">$errors[0]</span>
         * </label>
         */
        $expected_tag = [
          'tag' => 'label',
          'content' => $label,
          'attributes' => [
            'for' => $name,
            'class' => 'error',
          ],
        ];
        $this->assertTag( $expected_tag, $result, 'Select\'s Label (with error) does not countain expected data!');
        
        $expected_tag = [
          'tag' => 'select',
          'attributes' => [ 'class' => 'error' ],
          'children' => [
            'count' => count($options),
            'only' => [ 'tag' => 'option' ],
          ],
        ];
        $this->assertTag( $expected_tag, $result, 'Select does not contain all the values!');
        
        $expected_tag = [
          'tag' => 'option',
          'attributes' => [ 'value' => current(array_keys($options)) ],
        ];
        $this->assertTag( $expected_tag, $result, 'Select does not contain the expected elements!');

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
