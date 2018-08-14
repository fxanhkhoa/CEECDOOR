<?php
namespace Users\Form;
use Zend\Form\Form;
use Zend\InputFilter\InputFilter;
use Zend\Validator\NotEmpty;
use Zend\Validator\StringLength;
use Zend\Validator\EmailAddress;
use Zend\Validator\Identical;

class UserForm extends Form{

    private $action;
    public function __construct($action = "add"){
        parent::__construct();
        $this->setAttributes([
            'name'=>'user-form',
            'class'=>'form-horizontal',
            'action'=>'#',
        ]);
        $this->action = $action;
        $this->addElements();
        $this->validator();
    }

    private function addElements(){
        $this->add([
            'type'=>'text',
            'name'=>'username',
            'attributes'=>[
                'class'=>'form-control',
                'placeholder'=>'Input Username',
                'id'=>'username',
            ],
            'options'=>[
                'label'=>'Username:',
                'label_attributes'=>[
                    'for'=>'username',
                    'class'=>' control-label',
                ]
            ],
        ]);

        //password
        $this->add([
            'type'=>'password',
            'name'=>'password',
            'attributes'=>[
                'class'=>'form-control',
                'placeholder'=>'Input Password',
                'id'=>'password',
            ],
            'options'=>[
                'label'=>'Password:',
                'label_attributes'=>[
                    'for'=>'password',
                    'class'=>' control-label',
                ]
            ],
        ]);

        //Confirm_password
        $this->add([
            'type'=>'password',
            'name'=>'confirm_password',
            'attributes'=>[
                'class'=>'form-control',
                'placeholder'=>'Input Password again',
                'id'=>'confirm_password',
            ],
            'options'=>[
                'label'=>'Repassword',
                'label_attributes'=>[
                    'for'=>'confirm_password',
                    'class'=>' control-label',
                ]
            ],
        ]);

        //fullname
        $this->add([
            'type'=>'text',
            'name'=>'fullname',
            'attributes'=>[
                'class'=>'form-control',
                'placeholder'=>'Input fullname',
                'id'=>'fullname',
            ],
            'options'=>[
                'label'=>'fullname',
                'label_attributes'=>[
                    'for'=>'fullname',
                    'class'=>' control-label',
                ]
            ],
        ]);

        //birthday
        $this->add([
            'type'=>'Date',
            'name'=>'birthday',
            'attributes'=>[
                'class'=>'form-control',
                'placeholder'=>'Input birthday',
                'id'=>'birthday',
            ],
            'options'=>[
                'label'=>'Birthday:',
                'label_attributes'=>[
                    'for'=>'birthday',
                    'class'=>' control-label',
                ]
            ],
        ]);

        //gender
        $this->add([
            'type'=>'Radio',
            'name'=>'gender',
            'attributes'=>[
                'class'=>'radio-inline',
                'id'=>'gender',
                'value'=>'male',
                'style'=>'margin-left:20px'
            ],          
            'options'=>[
                'value_options'=>[
                    'female'=>'Female',
                    'male'=>'Male',
                    'other'=>'Other',
                ],
                'label'=>'Sex:',
                'label_attributes'=>[
                    'for'=>'gender',
                    'class'=>'control-label',
                ],
            ],
        ]);

        //address
        $this->add([
            'type'=>'text',
            'name'=>'address',
            'attributes'=>[
                'class'=>'form-control',
                'placeholder'=>'Input address',
                'id'=>'address',
            ],
            'options'=>[
                'label'=>'address:',
                'label_attributes'=>[
                    'for'=>'address',
                    'class'=>' control-label',
                ]
            ],
        ]);

        //email
        $this->add([
            'type'=>'email',
            'name'=>'email',
            'attributes'=>[
                'class'=>'form-control',
                'placeholder'=>'Input email',
                'id'=>'email',
                'required'=>true,
            ],
            'options'=>[
                'label'=>'email:',
                'label_attributes'=>[
                    'for'=>'email',
                    'class'=>' control-label',
                ]
            ],
        ]);

        //phone
        $this->add([
            'type'=>'text',
            'name'=>'phone',
            'attributes'=>[
                'class'=>'form-control',
                'placeholder'=>'Input phone',
                'id'=>'phone',
            ],
            'options'=>[
                'label'=>'phone:',
                'label_attributes'=>[
                    'for'=>'phone',
                    'class'=>' control-label',
                ]
            ],
        ]);

        //role
        $this->add([
            'type'=>'select',
            'name'=>'role',
            'attributes'=>[
                'class'=>'form-control',
                'placeholder'=>'Input role',
                'id'=>'role',
            ],
            'options'=>[
                'label'=>'role(s):',
                'label_attributes'=>[
                    'for'=>'role',
                    'class'=>' control-label',
                ],
                'value_options'=>[
                    'admin'=>'Admin',
                    'guest'=>'Guest',
                    'staff'=>'Staff',
                    'editor'=>"Editor",
                ],
            ],
        ]);

        //btn
        $this->add([
            'type'=>'submit',
            'name'=>'btnSubmit',
            'attributes'=>[
                'class'=>'btn btn-success',
                'value'=>'Save',
            ],
        ]);
    }

    private function validator(){
        $inputFilter = new InputFilter();
        $this->setInputFilter($inputFilter);

        $inputFilter->add([
            'name'=>'username',
            'required'=>true,
            'filters'=>[
                ['name'=>'StringTrim'],
                ['name'=>'StripTags'],
                ['name'=>'StripNewlines'],
            ],
            'validators'=>[
                [
                    'name'=>'NotEmpty',
                    'options'=>[
                        'break_chain_on_failure'=>true,
                        'messages'=>[
                            NotEmpty::IS_EMPTY=>'Username can not be empty'
                        ]
                    ]
                ],
                [
                    'name'=>'StringLength',
                    'options'=>[
                        'min'=>8,
                        'max'=>50,
                        'messages'=>[
                            StringLength::TOO_SHORT=>'Min is %min%',
                            StringLength::TOO_LONG=>'Max is %max%',
                        ]
                    ]
                ]
            ]
        ]);

        $inputFilter->add([
            'name'=>'password',
            'required'=>true,
            'filters'=>[
                ['name'=>'StringTrim'],
                ['name'=>'StripTags'],
                ['name'=>'StripNewlines'],
            ],
            'validators'=>[
                [
                    'name'=>'NotEmpty',
                    'options'=>[
                        'break_chain_on_failure'=>true,
                        'messages'=>[
                            NotEmpty::IS_EMPTY=>'Password can not be empty'
                        ]
                    ]
                ],
                [
                    'name'=>'StringLength',
                    'options'=>[
                        'min'=>8,
                        'max'=>20
                    ]
                ]
            ]
        ]);

        $inputFilter->add([
            'name'=>'confirm_password',
            'required'=>true,
            'filters'=>[
                ['name'=>'StringTrim'],
                ['name'=>'StripTags'],
                ['name'=>'StripNewlines'],
            ],
            'validators'=>[
                [
                    'name'=>'NotEmpty',
                ],
                [
                    'name'=>'Identical',
                    'options'=>[
                        'token'=>'password',
                    ]
                ],
            ]
        ]);

        $inputFilter->add([
            'name'=>'fullname',
            'required'=>true,
            'filters'=>[
                ['name'=>'StringTrim'],
                ['name'=>'StripTags'],
                ['name'=>'StripNewlines'],
            ],
            'validators'=>[
                [
                    'name'=>'NotEmpty',
                ],
                [
                    'name'=>'StringLength',
                    'options'=>[
                        'max'=>100
                    ]
                ]
            ]
        ]);

        $inputFilter->add([
            'name'=>'email',
            'required'=>true,
            'filters'=>[
                ['name'=>'StringTrim'],
                ['name'=>'StripTags'],
                ['name'=>'StripNewlines'],
            ],
            'validators'=>[
                [
                    'name'=>'NotEmpty',
                ],
                [
                    'name'=>'StringLength',
                    'options'=>[
                        'min'=>10,
                        'max'=>50
                    ]
                ],
                [
                    'name'=>'EmailAddress'
                ]
            ]
        ]);
    }
}

?>