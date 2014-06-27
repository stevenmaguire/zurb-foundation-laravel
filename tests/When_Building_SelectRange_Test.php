<?php

class When_Building_SelectRange_Test extends Form_Builder_Test_Case
{

    public function test_It_Can_Return_Select_Tag_With_Matching_Name()
    {
        $name = 'tim';
        $start = 1;
        $end = 4;
        $this->errors->shouldReceive('has')->with($name)->times(4)->andReturn(false);
        $expected_select_tag = [
            'tag' => 'select',
            'attributes' => ['name' => $name]
        ];

        $result = $this->form->selectRange($name, $start, $end);

        $this->assertTag($expected_select_tag, $result);
    }

    public function test_It_Can_Return_Select_Tag_With_Matching_Name_And_Selected_Value_In_Numeric_Array()
    {
        $name = 'tim';
        $selectedValue = 2;
        $start = 1;
        $end = 4;
        $this->errors->shouldReceive('has')->with($name)->times(4)->andReturn(false);
        $expected_select_tag = [
            'tag' => 'select',
            'attributes' => ['name' => $name],
            'child' => [
                'tag' => 'option',
                'content' => (string) $selectedValue,
                'attributes' => ['value' => $selectedValue, 'selected' => 'selected']
            ]
        ];

        $result = $this->form->selectRange($name, $start, $end, $selectedValue);

        $this->assertTag($expected_select_tag, $result);
    }

    public function test_It_Can_Return_Select_Tag_With_Matching_Name_And_Error_Class_While_Errors()
    {
        $name = 'tim';
        $start = 1;
        $end = 4;
        $errors = array('Error message');
        $this->errors->shouldReceive('has')->with($name)->times(4)->andReturn(true);
        $this->errors->shouldReceive('get')->with($name)->twice()->andReturn($errors);
        $expected_select_tag = [
            'tag' => 'select',
            'attributes' => ['name' => $name],
        ];
        $expected_message_tag = [
            'tag' => 'small',
            'attributes' => ['class' => 'error'],
            'content' => implode(' ',$errors)
        ];

        $result = $this->form->selectRange($name, $start, $end);

        $this->assertTag($expected_select_tag, $result);
        $this->assertTag($expected_message_tag, $result);
    }

}
