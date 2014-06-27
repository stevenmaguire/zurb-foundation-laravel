<?php

class When_Building_Password_Test extends Form_Builder_Test_Case
{

    public function test_It_Can_Return_Password_Input_Tag_With_Matching_Name()
    {
        $name = 'tim';
        $this->errors->shouldReceive('has')->with($name)->twice()->andReturn(false);
        $expected_input_tag = [
            'tag' => 'input',
            'attributes' => ['type' => 'password', 'name' => $name]
        ];

        $result = $this->form->password($name);

        $this->assertTag($expected_input_tag, $result);
    }

    public function test_It_Can_Return_Password_Text_Tag_With_Matching_Name_And_Error_Class_While_Errors()
    {
        $name = 'tim';
        $errors = ['Error message'];
        $this->errors->shouldReceive('has')->with($name)->twice()->andReturn(true);
        $this->errors->shouldReceive('get')->with($name)->once()->andReturn($errors);
        $expected_input_tag = [
            'tag' => 'input',
            'attributes' => ['type' => 'password', 'name' => $name, 'class' => 'error']
        ];
        $expected_message_tag = [
            'tag' => 'small',
            'attributes' => ['class' => 'error'],
            'content' => implode(' ',$errors)
        ];

        $result = $this->form->password($name);

        $this->assertTag($expected_input_tag, $result);
        $this->assertTag($expected_message_tag, $result);
    }

}
