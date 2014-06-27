<?php

class When_Building_TextArea_Test extends Form_Builder_Test_Case
{

    public function test_It_Can_Return_Textarea_Tag_With_Matching_Name()
    {
        $name = 'tim';
        $this->errors->shouldReceive('has')->with($name)->twice()->andReturn(false);
        $expected_textarea_tag = [
            'tag' => 'textarea',
            'attributes' => ['name' => $name]
        ];

        $result = $this->form->textarea($name);

        $this->assertTag($expected_textarea_tag, $result);
    }

    public function test_It_Can_Return_Textarea_Tag_With_Matching_Name_And_Value()
    {
        $name = 'tim';
        $value = 'Great Job';
        $this->errors->shouldReceive('has')->with($name)->twice()->andReturn(false);
        $expected_textarea_tag = [
            'tag' => 'textarea',
            'attributes' => ['name' => $name],
            'content' => $value
        ];

        $result = $this->form->textarea($name,$value);

        $this->assertTag($expected_textarea_tag, $result);
    }

    public function test_It_Can_Return_Textarea_ag_With_Matching_Name_And_Error_Class_While_Errors()
    {
        $name = 'tim';
        $errors = array('Error message');
        $this->errors->shouldReceive('has')->with($name)->twice()->andReturn(true);
        $this->errors->shouldReceive('get')->with($name)->once()->andReturn($errors);
        $expected_textarea_tag = [
            'tag' => 'textarea',
            'attributes' => ['name' => $name]
        ];
        $expected_message_tag = [
            'tag' => 'small',
            'attributes' => ['class' => 'error'],
            'content' => implode(' ',$errors)
        ];

        $result = $this->form->textarea($name);

        $this->assertTag($expected_textarea_tag, $result);
        $this->assertTag($expected_message_tag, $result);
    }

}
