<?php

class When_Building_Select_Test extends Form_Builder_Test_Case
{

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
        $expected_select_tag = [
            'tag' => 'select',
            'attributes' => ['name' => $name],
            'child' => [
                'tag' => 'option',
                'content' => $selectedLabel,
                'attributes' => ['selected' => 'selected']
            ]
        ];

        $result = $this->form->select($name,$values,$selectedValue);

        $this->assertTag($expected_select_tag, $result);
    }

    public function test_Return_Select_Tag_With_Matching_Name_And_Selected_Value_In_Associative_Array()
    {
        $name = 'tim';
        $selectedValue = '3';
        $selectedLabel = 'Great Job';
        $values = array('1' => 'one','2' => 'two','3' => $selectedLabel);
        $this->errors->shouldReceive('has')->with($name)->twice()->andReturn(false);
        $expected_select_tag = [
            'tag' => 'select',
            'attributes' => ['name' => $name],
            'child' => [
                'tag' => 'option',
                'content' => $selectedLabel,
                'attributes' => ['selected' => 'selected']
            ]
        ];

        $result = $this->form->select($name,$values,$selectedValue);

        $this->assertTag($expected_select_tag, $result);
    }

    public function test_Return_Select_Tag_With_Matching_Name_And_Error_Class_While_Errors()
    {
        $name = 'tim';
        $errors = array('Error message');
        $this->errors->shouldReceive('has')->with($name)->twice()->andReturn(true);
        $this->errors->shouldReceive('get')->with($name)->once()->andReturn($errors);
        $expected_select_tag = [
            'tag' => 'select',
            'attributes' => ['name' => $name],
        ];
        $expected_message_tag = [
            'tag' => 'small',
            'attributes' => ['class' => 'error'],
            'content' => implode(' ',$errors)
        ];

        $result = $this->form->select($name);

        $this->assertTag($expected_select_tag, $result);
        $this->assertTag($expected_message_tag, $result);
    }

}
