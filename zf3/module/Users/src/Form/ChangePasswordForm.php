<?php
namespace Users\Form;

use Zend\Form\Form;
use Zend\InputFilter\InputFilter;
use Zend\Validator\NotEmpty;
use Zend\Validator\StringLength;
use Zend\Validator\EmailAddress;
use Zend\Validator\Identical;

class ChangePasswordForm extends Form{
    private $action;
    public function __construct($action = 'changePw'){
        $this->action = $action;
        parent::__construct();

        $this->setAttributes([
            'name'=>'change-pw',
            'class'=>'form-horizontal'
        ]);

        $this->addElements();
        $this->addValidator();
    }

    private function addElements(){
        //old password
        if ($this->action == 'changePw'){
            $this->add([
                'type'=>'password',
                'name'=>'old_pw',
                'options'=>[
                    'label'=>'Old Password',
                    'label_attributes'=>[
                        'for'=>'old_pw',
                        'class'=>'control-label'
                    ]
                ],
                'attributes'=>[
                    'class'=>'form-control',
                    'placeholder'=>'Input Old Password',
                ]
            ]);
        }
        

        //new password
        $this->add([
            'type'=>'password',
            'name'=>'new_pw',
            'options'=>[
                'label'=>'New Password',
                'label_attributes'=>[
                    'for'=>'new_pw',
                    'class'=>'control-label'
                ]
            ],
            'attributes'=>[
                'class'=>'form-control',
                'placeholder'=>'Input New Password',
            ]
        ]);

        //confirm new password
        $this->add([
            'type'=>'password',
            'name'=>'confirm_new_pw',
            'options'=>[
                'label'=>'Confirm New Password',
                'label_attributes'=>[
                    'for'=>'confirm_new_pw',
                    'class'=>'control-label'
                ]
            ],
            'attributes'=>[
                'class'=>'form-control',
                'placeholder'=>'Input Confirm Password',
            ]
        ]);

        //csrf
        $this->add([
            'type'=>'csrf',
            'name'=>'csrf',
            'options'=>[
                'csrf_options'=>[
                    'timeout'=>300 // 5minute
                ]
            ]
        ]);

        //button
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

    private function addValidator(){

        $inputFilter = new InputFilter();
        $this->setInputFilter($inputFilter);

        //old_pw
        if ($this->action == 'changePw'){
            $inputFilter->add([
                'name'=>'old_pw',
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
        }

        //new_pw
        $inputFilter->add([
            'name'=>'new_pw',
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

        //confirm_new_pw
        $inputFilter->add([
            'name'=>'confirm_new_pw',
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
                        'token'=>'new_pw',
                    ]
                ],
            ]
        ]);
    }
}
?>