<?php

class When_Building_Label_Test extends Form_Builder_Test_Case
{

    public function test_It_Can_Return_Label_Tag_With_Matching_Name()
    {
        $name = 'tim';
        $this->errors->shouldReceive('has')->with($name)->once()->andReturn(false);
        $expected_label_tag = [
            'tag' => 'label',
            'attributes' => ['for' => $name]
        ];

        $result = $this->form->label($name);

        $this->assertTag($expected_label_tag, $result);
    }

    public function test_It_Can_Return_Label_Tag_With_Matching_Name_And_Error_Class_While_Errors()
    {
        $name = 'tim';
        $this->errors->shouldReceive('has')->with($name)->once()->andReturn(true);
        $expected_label_tag = [
            'tag' => 'label',
            'attributes' => ['for' => $name, 'class' => 'error']
        ];

        $result = $this->form->label($name);

        $this->assertTag($expected_label_tag, $result);
    }

    public function test_It_Can_Return_Label_Tag_With_Matching_Name_And_Value()
    {
        $name = 'tim';
        $value = 'Eric';
        $this->errors->shouldReceive('has')->with($name)->once()->andReturn(false);
        $expected_label_tag = [
            'tag' => 'label',
            'attributes' => ['for' => $name],
            'content' => $value
        ];

        $result = $this->form->label($name, $value);

        $this->assertTag($expected_label_tag, $result);
    }

}
